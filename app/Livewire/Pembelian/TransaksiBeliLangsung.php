<?php

namespace App\Livewire\Pembelian;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Gudang\Stok;
use Livewire\Attributes\On;
use App\Models\Master\Barang;
use Livewire\Attributes\Lazy;
use App\Models\Gudang\Pembelian;
use App\Models\Gudang\Penerimaan;
use Illuminate\Support\Facades\DB;
use TallStackUi\Traits\Interactions;
use Illuminate\Support\Facades\Cache;
use App\Models\Gudang\PembelianDetail;
use App\Models\Gudang\PenerimaanDetail;
use App\Models\Gudang\PembelianRequestDetails;
use App\Traits\BlocksTransactionDuringOpname;

#[Lazy]
class TransaksiBeliLangsung extends Component
{
    use BlocksTransactionDuringOpname;
    use Interactions;

    public $createTerm = '';
    public $cartItems = [];

    public $tgl_pembelian, $tgl_pembayaran;
    public int $supplier;
    public string $no_faktur, $keterangan, $status_pembayaran = 'lunas';
    public array $cabarOptions = [
        ['value' => 'lunas', 'nama' => 'Tunai / Lunas'],
        ['value' => 'tempo', 'nama' => 'Tempo'],
    ];

    protected $rules = [
        'supplier' => 'required',
        'tgl_pembelian' => 'required|date',
        'no_faktur' => 'required',
        'tgl_pembayaran' => 'required',
        'cartItems' => 'required|array|min:1',
        // 'cartItems.*.jumlah' => 'required|numeric|min:1',
    ];


    public function messages()
    {
        return [
            'cartItems.required' => 'Minimal ada satu item pembelian.',
            'cartItems.min' => 'Minimal ada satu item pembelian.'
        ];
    }

    public function mount()
    {
        $this->tgl_pembelian = date('Y-m-d');


        // Check data dari pengajuan
        $cacheKey = session()->get('cart_pengajuan_cache_key');
        $selectedIds = Cache::get($cacheKey);
        if ($selectedIds) {
            $this->loadProducts($selectedIds);
        }
    }

    public function loadProducts($selectedIds)
    {
        $pengajuan = PembelianRequestDetails::with(['barang', 'barang.satuan'])
            ->whereIn('id', $selectedIds)
            ->selectRaw('barang_id, SUM(jml_disetujui) as total_jml_disetujui') 
            ->groupBy('barang_id')
            ->get()
            ->map(function ($item): array {
                return [
                    'id' => $item->barang_id,
                    'bhp' => $item->barang->bhp,
                    'sku' => $item->barang->sku,
                    'nama' => $item->barang->nama,
                    'satuan' => $item->barang->satuan->nama,
                    'jumlah' => $item->total_jml_disetujui,
                    'harga' => 0,
                    'batch' => '',
                    'waranty_date' => '',
                    'subTotal' => 0
                ];
            })->toArray();

        $this->cartItems = $pengajuan;
    }

    public function getBarang($id): ?object
    {
        $barang = Barang::with('satuan')
            ->where('id', $id)
            ->orWhere('sku', $id)
            ->first();

        if ($barang) {
            $items = (object) [
                'id' => $barang->id,
                'bhp' => $barang->bhp == 1 ? true : false,
                'sku' => $barang->sku,
                'nama' => $barang->nama,
                'satuan' => $barang->satuan->nama,
            ];
            return $items;
        }
        return null;
    }

    // function updateItemQuantity($index, $quantity)
    // {
    //     if ($quantity > 0) {
    //         $this->cartItems[$index]->jumlah = $quantity;
    //     }
    // }

    // function deleteItemCart($index): void
    // {
    //     unset($this->cartItems[$index]);
    //     $this->cartItems = array_values($this->cartItems);
    // }

    function submit()
    {
        /**
         * No 
         * {PO}{0001}{1224}
         * PO = Pre Order
         * 0001 = number [reset setiap tahun], max nomor setiap tahun 9999
         * 1224 = bulantahun
         */


        $this->validate();

        DB::beginTransaction();
        try {
            // calc total
            $totalBeli = collect($this->cartItems)
                ->sum(fn($cart) => $cart['jumlah'] * $cart['harga']);

            // mapping data pembelian
            $pembelian = Pembelian::create([
                'no' => $this->generateNumberPembelian(),
                'tgl' => $this->tgl_pembelian,
                'supplier_id' => $this->supplier,
                'jenis' => 'langsung',
                'status_pembayaran' => $this->status_pembayaran,
                'tgl_pembayaran' => $this->tgl_pembayaran,
                'total' => $totalBeli,
                'status' => 'selesai'
            ]);

            // mapping data penerimaan
            $penerimaan = Penerimaan::create([
                'tanggal' => $this->tgl_pembelian,
                'no_faktur' => $this->no_faktur,
                'keterangan' => $this->keterangan ?? '-',
                'penerima' => auth()->user()->id
            ]);


            // detil pembelian
            $pembelianDetails = collect($this->cartItems)
                ->map(
                    function ($item) use ($pembelian) {
                        return [
                            'pembelian_id' => $pembelian->id,
                            'barang_id' => $item['id'],
                            'jumlah' => $item['jumlah'] ?? 0,
                            'batch' => $item['batch'],
                            'harga_satuan' => $item['harga'],
                        ];
                    }
                )->toArray();
            PembelianDetail::insert($pembelianDetails);


            // penerimaan Detail
            $penerimaanDetails = collect($pembelianDetails)
                ->map(
                    function ($item) use ($penerimaan, $pembelian) {
                        return [
                            'penerimaan_id' => $penerimaan->id,
                            'pembelian_det_id' => PembelianDetail::where('pembelian_id', $pembelian->id)
                                ->where('barang_id', $item['barang_id'])
                                ->first()->id,
                            'jumlah' => $item['jumlah'],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                )->toArray();
            PenerimaanDetail::insert($penerimaanDetails);


            // STOK IN
            $pembelianDets = PembelianDetail::with('terimas')->where('pembelian_id', $pembelian->id)->get();
            // dd($pembelianDets);

            $stokData = collect($pembelianDets)
                ->map(
                    function ($detail) {
                        $terima = $detail->terimas?->first();
                        return  [
                            'penerimaan_det_id' => $terima->id,
                            'barang_id' => $detail->barang_id,
                            'stok' => $detail->jumlah,
                            'batch' => $detail->batch,
                            'harga_satuan' => $detail->harga_satuan,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                )->toArray();
            Stok::insert($stokData);


            // TODO Mutasi Stok Pemebelian Langsung

            DB::commit();

            $this->dispatch('new-transaksi-langsung-created');

            // Clear Cache
            $cacheKey = session()->get('current_pengajuan_cache_key');
            Cache::forget($cacheKey);
            session()->forget('current_pengajuan_cache_key');

            $this->toast()
                ->success('Berhasil', 'Pembelian berhasil disimpan.')
                ->send();
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->toast()
                ->error('Failed', 'Error:' . $e->getMessage())
                ->send();
        }
    }


    // generate nomor
    // urutan nomor berganti setiap tahun
    private function generateNumberPembelian(): string
    {
        $tglPembelian = $this->tgl_pembelian;
        $bulantahun = Carbon::parse($tglPembelian)->format('my');
        $tahun = Carbon::parse($tglPembelian)->format('Y');;

        $last = Pembelian::select('id', 'no')
            ->whereYear('tgl', $tahun)
            ->where('jenis', 'langsung')
            ->orderBy('id', 'desc')
            ->first();

        $no = 1;
        if ($last) {
            $no = (int)substr($last->no, 2, 4) + 1;
        }
        $no = str_pad($no, 4, '0', STR_PAD_LEFT);

        return "PD{$no}{$bulantahun}";
    }

    public function render()
    {
        if (!$this->blockIfOpnameActive()) {
            return view('components.opname-block');
        }

        return view('livewire.pembelian.transaksi-beli-langsung');
    }
}
