<?php

namespace App\Livewire\Master\Barang\Satuan;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Satuan Barang')]
#[Lazy]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.barang.satuan.index');
    }
}
