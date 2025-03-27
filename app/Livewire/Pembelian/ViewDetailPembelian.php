<?php

namespace App\Livewire\Pembelian;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use App\Models\Gudang\Pembelian;

#[Lazy]
class ViewDetailPembelian extends Component
{

    public array $headers, $rows;

    public ?Pembelian $pembelian;
    public $penerimaan;

    function mount($id)
    {
        $this->pembelian = Pembelian::with([
            'supplier',
            'pembelians',
            'pembelians.barang.satuan',
            'pembelians.terimas.stoks',
            'pembelians.terimas.penerimaan.user'
        ])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.pembelian.view-detail-pembelian');
    }
}
