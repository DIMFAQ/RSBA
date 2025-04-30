<?php

namespace App\Livewire\Pembelian;

use App\Models\Gudang\Pembelian;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

#[Title('Pembelian')]
#[Lazy]
class Index extends Component
{
    use WithPagination;

    public $tab;
    public bool $stats = false;
    public $search = '';

    public ?Pembelian $pembelian;

    #[On('close-cari-pembelian')]
    function updatedSearch($value)
    {
        $this->search = $value;
        $this->pembelian = Pembelian::where('no', $value)->firstOr(function () {
            return null;
        });
    }

    public function render()
    {

        return view('livewire.pembelian.index');
    }
}
