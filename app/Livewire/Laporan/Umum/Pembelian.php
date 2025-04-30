<?php

namespace App\Livewire\Laporan\Umum;

use App\Models\Gudang\PenerimaanDetail;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Isolate;

#[Isolate]
#[Lazy]
class Pembelian extends Component
{
    public bool $init = true;

    #[Locked]
    public $headers = [
        ['index' => 'no_faktur', 'label' => 'No. Faktur'],
        ['index' => 'nama_barang', 'label' => 'Barang'],
        ['index' => 'tanggal', 'label' => 'Tanggal'],
        ['index' => 'supplier', 'label' => 'Supplier'],
        ['index' => 'jumlah', 'label' => 'Jumlah'],
        ['index' => 'harga', 'label' => 'Harga Satuan'],
        ['index' => 'total', 'label' => 'Total'],
    ];

    #[Locked]
    public $rows = [];

    #[Locked]
    public $total = 0;

    #[On('cariPembelian')]
    public function cariDataBeli($periode, $vendor, $jenis)
    {
        $this->init = false;
        $this->getDataBeli($periode, $vendor, $jenis);
    }

    #[Computed]
    public function getDataBeli($periode, $vendor, $jenis)
    {
        // periode to string $periode_awal and $periode_akhir
        [$periode_awal, $periode_akhir] = $periode;

        $data = PenerimaanDetail::with('penerimaan', 'pembelianDet', 'pembelianDet.pembelian', 'pembelianDet.barang', 'stoks')
            ->whereHas(
                'penerimaan',
                function ($query) use ($periode_awal, $periode_akhir) {
                    $query->whereBetween(
                        'tanggal',
                        [$periode_awal, $periode_akhir]
                    );
                }
            )
            ->when(
                $vendor,
                function ($query, $vendor) {
                    $query->whereHas(
                        'pembelianDet.pembelian',
                        function ($q) use ($vendor) {
                            $q->where(
                                'supplier_id',
                                $vendor
                            );
                        }
                    );
                }
            )
            ->get();

        $this->rows = $data->map(function ($data) {
            return [
                'nama_barang' => $data->stoks->barang->nama,
                'no_faktur' => $data->penerimaan->no_faktur,
                'tanggal' => $data->penerimaan->tanggal,
                'supplier' => $data->pembelianDet->pembelian->supplier->nama,
                'jumlah' => $data->jumlah,
                'harga' => formatRupiah($data->stoks->harga_satuan, false, false),
                'total' => formatRupiah(($data->stoks->stok * $data->stoks->harga_satuan), false, false)
            ];
        })->toArray();

        // Total Table
        $this->total = $data->map(function ($data) {
            return $data->stoks->stok * $data->stoks->harga_satuan;
        })->sum();
    }

    public function render()
    {
        return view('livewire.laporan.umum.pembelian');
    }
}
