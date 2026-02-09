<?php

namespace App\Livewire\Pembelian\Penerimaan;

use Livewire\Component;
use App\Models\Gudang\Stok;
use Livewire\Attributes\Lazy;
use App\Models\Gudang\Pembelian;
use App\Models\Gudang\Penerimaan;
use App\Models\Gudang\StokMutasi;
use Illuminate\Support\Facades\DB;
use TallStackUi\Traits\Interactions;
use App\Models\Gudang\PenerimaanDetail;

#[Lazy]
class TerimaBarang extends Component
{
    use Interactions;

    public $pembelianId;
    public ?Pembelian $pembelian;
    public $detailPesanan;


    // form
    public $tgl_diterima;
    public string $no_invoice, $keterangan;
    public $terimaBarang;
    public int $totalHargaTerimaBarang = 0;

    public $rules = [
        'tgl_diterima' => 'required',
        'no_invoice' => 'required',
    ];

    function mount(?Pembelian $pembelian)
    {
        $this->pembelianId = $pembelian->id;
        $this->pembelian = $pembelian;

        // ketika pembelian not null, load relation detil pembelian
        $this->detailPesanan = $pembelian
            ? $pembelian->load('pembelians')->pembelians
            : collect();


        $this->terimaBarang = $this->detailPesanan->map(fn($item) => [
            'pembelian_det_id' => $item->id,
            'barang_id' => $item->barang_id,
            'jumlahDiterima' => 0,
            'hargaSatuan' => 0,
            'batch' => null,
            'waranty_date' => null,
            'subtotal' => 0,
            'remainingQuantity' => $item->jumlah - $item->terimas?->sum('jumlah'),
        ])->toArray();
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

            // [02] Init data detilPenerimaan
            $dataDetilPenerimaan = collect($this->terimaBarang)
                ->filter(fn($item) => $item['jumlahDiterima'] > 0)
                ->map(
                    function ($item) use ($penerimaan) {
                        // If item is an object, convert to array
                        $item = is_object($item) ? (array) $item : $item;

                        return [
                            'penerimaan_id' => $penerimaan->id,
                            'pembelian_det_id' => $item['pembelian_det_id'],
                            'jumlah' => $item['jumlahDiterima'],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                );

            // insert into penerimaan detil
            if ($dataDetilPenerimaan->isNotEmpty()) {
                $penerimaanDetails = $dataDetilPenerimaan->map(function ($data): PenerimaanDetail {
                    return PenerimaanDetail::create($data);
                });
            }


            // [03] Insert Into Stok
            $dataStok = collect($this->terimaBarang)
                ->filter(fn($item) => $item['jumlahDiterima'] > 0)
                ->map(
                    function ($item) use ($penerimaan) {
                        // If item is an object, convert to array
                        $item = is_object($item) ? (array) $item : $item;

                        return  [
                            'penerimaan_det_id' => PenerimaanDetail::where('penerimaan_id', $penerimaan->id)
                                ->where('pembelian_det_id', $item['pembelian_det_id'])
                                ->value('id'),
                            'barang_id' => $item['barang_id'],
                            'stok' => $item['jumlahDiterima'],
                            'batch' => $item['batch'],
                            'harga_satuan' => $item['hargaSatuan'],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                );

            if ($dataStok->isNotEmpty()) {
                $stoks = $dataStok->map(function ($data): Stok {
                    return Stok::create($data);
                });

                // TODO create mutasi penerimaan pembelian
                // foreach ($stoks as $stok) {
                //     $this->createMutasi(
                //         stok: $stoks,
                //         jumlah: $stok->stok,
                //         penerimaanDetails: $penerimaanDetails
                //     );
                // }
            }


            // [04] Last, update total pada pembelian
            $pembelian = Pembelian::findOrFail($this->pembelianId);
            $pembelian->total = $pembelian->total +  $this->totalHargaTerimaBarang;
            if ($status == 'selesai') {
                $pembelian->status = 'selesai';
            }
            $pembelian->save();


            DB::commit();

            $this->dispatch('penerimaan-beli-saved');

            $this->toast()
                ->success('Berhasil', 'Data pembelian deterima, stok barang telah diperbaharui.')
                ->send();
        } catch (\Throwable $e) {
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
