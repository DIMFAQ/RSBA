<?php

namespace App\Livewire\Pembelian;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Master\Barang;
use Livewire\Attributes\Lazy;
use App\Models\Gudang\Pembelian;
use Illuminate\Support\Facades\DB;
use TallStackUi\Traits\Interactions;
use App\Models\Gudang\PembelianDetail;

#[Lazy]
class TransaksiBeliPO extends Component
{
    use Interactions;

    public $createTerm = '';
    public $cartItems = [];

    public $tgl_pembelian;
    public int $supplier;

    protected $rules = [
        'tgl_pembelian' => 'required|date',
        'supplier' => 'required',
        'cartItems' => 'required|array|min:1',
        // 'cartItems.*.jumlah' => 'required|numeric|min:1',
    ];


    public function messages()
    {
        return [
            'cartItems.required' => 'Minimal tambah satu item pembelian.',
            'cartItems.min' => 'Minimal tambah satu item pembelian.'
        ];
    }

    function getBarang($id): ?object
    {
        $barang = Barang::with('satuan')
            ->where('id', $id)
            ->orWhere('sku', $id)
            ->first();

        if ($barang) {
            $items = (object) [
                'id' => $barang->id,
                'sku' => $barang->sku,
                'nama' => $barang->nama,
                'satuan' => $barang->satuan->nama,
            ];
            return $items;
        }
        return null;
    }


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
            $data = [
                'no' => $this->generateNumberPO(),
                'tgl' => $this->tgl_pembelian,
                'supplier_id' => $this->supplier,
                'jenis' => 'pre_order',
            ];

            // dd($data);
            $pembelian = Pembelian::create($data);

            // detil pembelian
            $items = collect($this->cartItems)->map(function ($item) use ($pembelian) {
                // If item is an object, convert to array
                $item = is_object($item) ? (array) $item : $item;

                return [
                    'pembelian_id' => $pembelian->id,
                    'barang_id' => $item['id'],
                    'jumlah' => $item['jumlah'] ?? 0,
                ];
            });

            PembelianDetail::insert($items->toArray());

            DB::commit();

            $this->dispatch('new-transaksi-po-created');

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
    private function generateNumberPO(): string
    {
        $tglPembelian = $this->tgl_pembelian;
        $bulantahun = Carbon::parse($tglPembelian)->format('my');
        $tahun = Carbon::parse($tglPembelian)->format('Y');;

        $last = Pembelian::select('id', 'no')
            ->whereYear('tgl', $tahun)
            ->where('jenis', 'pre_order')
            ->orderBy('id', 'desc')
            ->first();

        $no = 1;
        if ($last) {
            $no = (int)substr($last->no, 2, 4) + 1;
        }
        $no = str_pad($no, 4, '0', STR_PAD_LEFT);

        return "PO{$no}{$bulantahun}";
    }

    public function render()
    {
        return view('livewire.pembelian.transaksi-beli-po');
    }
}
