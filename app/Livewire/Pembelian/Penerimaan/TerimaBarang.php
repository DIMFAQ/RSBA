<?php

namespace App\Livewire\Pembelian\Penerimaan;

use Exception;
use Throwable;
use Livewire\Component;
use App\Models\Gudang\Stok;
use Livewire\Attributes\Lazy;
use App\Models\Gudang\Pembelian;
use App\Models\Gudang\Penerimaan;
use App\Models\Gudang\StokMutasi;
use Illuminate\Support\Facades\DB;
use TallStackUi\Traits\Interactions;
use App\Models\Gudang\PenerimaanDetail;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;

#[Lazy]
class TerimaBarang extends Component
{
    use Interactions;

    protected $pembelianId;

    #[Locked]
    public ?Pembelian $pembelian;

    #[Locked]
    public ?Penerimaan $penerimaan = null;

    #[Locked]
    public $penerimaanId = null;


    // form
    public $tgl_diterima;
    public string $no_invoice, $keterangan;
    public $terimaBarang;


    public $rules = [
        'tgl_diterima' => 'required',
        'no_invoice' => 'required',
    ];

    function mount(?Pembelian $pembelian)
    {
        $this->pembelianId = $pembelian->id;
        $this->pembelian = Pembelian::with(
            [
                'details:id,pembelian_id,barang_id,jumlah,harga_satuan,diskon,ppn',
                'details.barang:id,nama,bhp,satuan_id',
                'details.barang.satuan:id,nama',
                'details.terimas:id,pembelian_det_id,penerimaan_id,jumlah',
                'details.terimas.penerimaan:id,no_faktur,penerima',
                'details.terimas.penerimaan.user:id,karyawan_id',
                'details.terimas.penerimaan.user.karyawan:id,nama'
            ]
        )->find($this->pembelianId);

        $this->terimaBarang = $this->setTerimaBarang();

        $this->penerimaanId = $this->pembelian->details
            ->firstWhere(fn($item) => $item->terimas && $item->terimas->count() > 0) // cari detail yang sudah ada penerimaan
            ?->terimas->first()->penerimaan_id ?? null;

        $this->penerimaan = $this->getPenerimaan;
        $this->no_invoice = $this->penerimaan?->no_faktur ?? '';
    }


    #[Computed()]
    public function getPenerimaan()
    {
        return Penerimaan::find($this->penerimaanId);
    }

    public function setTerimaBarang(): array
    {
        return $this->pembelian->details->map(function ($item) {
            $receivedQty = $item->terimas?->sum('jumlah') ?? 0;

            return [
                'pembelian_det_id' => $item->id,
                'barang_id' => $item->barang_id,
                'jumlahDiterima' => 0,
                'hargaSatuan' => (int) $item->harga_satuan,
                'diskon' => $item->diskon ?? 0,
                'ppn' => $item->ppn ?? 0,
                'ppnAmount' => 0,
                'batch' => null,
                'waranty_date' => null,
                'subtotal' => 0,
                'remainingQuantity' => $item->jumlah - $receivedQty,
                'is_received' => $receivedQty > 0 ? true : false
            ];
        })->toArray();
    }


    function submit($status)
    {
        $this->validate();

        DB::beginTransaction();
        try {

            //[01] init data penerimaan 
            $dataPenerimaan = [
                'tanggal' => $this->tgl_diterima,
                'no_faktur' => $this->no_invoice,
                'keterangan' => $this->keterangan ?? '-',
                'penerima' => auth()->user()->id
            ];
            // Insert To Penerimaan
            $penerimaan = Penerimaan::create($dataPenerimaan);

            // [02] Init data detilPenerimaan, Stok In , Mutasi, Update Pembelian Details
            $items = collect($this->terimaBarang)
                ->filter(fn($i) => $i['jumlahDiterima'] > 0)
                ->values();

            if ($items->isEmpty()) {
                throw new Exception("Tidak ada item diterima");
            }

            foreach ($items as $item) {
                if ($item['jumlahDiterima'] > $item['remainingQuantity']) {
                    throw new Exception("Jumlah diterima melebihi sisa PO");
                }
            }


            $now = now();

            // Detail Penerimaan
            $detailRows = $items->map(fn($i) => [
                'penerimaan_id' => $penerimaan->id,
                'pembelian_det_id' => $i['pembelian_det_id'],
                'jumlah' => $i['jumlahDiterima'],
                'created_at' => $now,
                'updated_at' => $now,
            ])->toArray();

            PenerimaanDetail::insert($detailRows);


            // Get penerimaan inserted
            $detailMap = PenerimaanDetail::where('penerimaan_id', $penerimaan->id)
                ->pluck('id', 'pembelian_det_id');

            // Stok IN
            $stokRows = $items->map(fn($i) => [
                'penerimaan_det_id' => $detailMap[$i['pembelian_det_id']],
                'barang_id' => $i['barang_id'],
                'stok' => $i['jumlahDiterima'],
                'batch' => $i['batch'],
                'harga_satuan' => $i['hargaSatuan'],
                'created_at' => $now,
                'updated_at' => $now,
            ])->toArray();

            Stok::insert($stokRows);


            // Update Detail Pembelian
            $ids = $items->pluck('pembelian_det_id');
            $sql = "UPDATE um_pembelian_det SET
harga_satuan = CASE id ";
            foreach ($items as $i) {
                $sql .= "WHEN {$i['pembelian_det_id']} THEN {$i['hargaSatuan']} ";
            }
            $sql .= "END,
    diskon = CASE id ";
            foreach ($items as $i) {
                $sql .= "WHEN {$i['pembelian_det_id']} THEN {$i['diskon']} ";
            }
            $sql .= "END,
    ppn = CASE id ";
            foreach ($items as $i) {
                $sql .= "WHEN {$i['pembelian_det_id']} THEN {$i['ppn']} ";
            }
            $sql .= "END
WHERE id IN (" . $ids->implode(',') . ")";

            DB::statement($sql);


            // Update Total Pembelian
            $total = DB::table('um_pembelian_det')
                ->where('pembelian_id', $this->pembelianId)
                ->selectRaw("
        SUM(jumlah * harga_satuan) as subtotal_gross,

        SUM(diskon) as total_diskon,

        SUM(((jumlah * harga_satuan) - diskon) * (ppn/100)) as total_ppn
    ")
                ->first();

            Pembelian::where('id', $this->pembelianId)
                ->update([
                    'subtotal' => $total->subtotal_gross,
                    'total_diskon' => $total->total_diskon,
                    'total_ppn' => $total->total_ppn,
                    'total' => ($total->subtotal_gross - $total->total_diskon) + $total->total_ppn,
                    'status' => $status === 'selesai' ? 'selesai' : DB::raw('status')
                ]);


            DB::commit();

            $this->dispatch('penerimaan-beli-saved');

            $this->toast()
                ->success('Berhasil', 'Data pembelian deterima, stok barang telah diperbaharui.')
                ->send();
        } catch (Throwable $e) {
            DB::rollBack();

            $this->toast()
                ->error('Failed', "Line : {$e->getLine()}; Error : {$e->getMessage()}")
                ->send();
        }
    }


    private function createMutasi(object $stok, int $jumlah, object $penerimaanDetails): ?StokMutasi
    {

        $stokSebelum = 0;
        $stokSesudah = $stokSebelum + $jumlah;
        $keterangan = sprintf(
            "Penerimaan: {id: %s, oleh: %s}\n" .
                "Stok: {id: %d, awal: %s, akhir: %s }\n",
            $penerimaanDetails->id,
            $penerimaanDetails->penerimaan->user->karyawan?->nama,
            $stok->id,
            $stokSebelum,
            $stokSesudah
        );

        $mutasi =  StokMutasi::create([
            'stok_id' => $stok->id,
            'barang_id' => $stok->barang_id,
            'jenis_mutasi' => 'PEMBELIAN',
            'jumlah' => abs($jumlah),
            'multiplier' => 1,
            'stok_sebelum' => $stokSebelum,
            'stok_sesudah' => $stokSesudah,
            'keterangan' => $keterangan,
            'referensi_type' => PenerimaanDetail::class,
            'referensi_id' => $penerimaanDetails->id,
            'created_by' => auth()->user()->id,
            'is_posted' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $mutasi;
    }




    public function render()
    {
        return view('livewire.pembelian.penerimaan.terima-barang');
    }
}
