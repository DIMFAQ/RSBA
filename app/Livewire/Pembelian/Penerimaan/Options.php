<?php

namespace App\Livewire\Pembelian\Penerimaan;

use App\Models\Gudang\Pembelian;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Options extends Component
{
    // public $sumber;

    public $search;

    public ?Pembelian $pembelian;

    public function updatedSearch($value)
    {
        $this->pembelian = Pembelian::where('no', $value)->firstOr(function () {
            return null;
        });;
    }

    public function render()
    {
        return view('livewire.pembelian.penerimaan.options');
    }
}
