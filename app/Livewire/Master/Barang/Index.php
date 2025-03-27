<?php

namespace App\Livewire\Master\Barang;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Barang')]
#[Lazy]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.barang.index');
    }
}
