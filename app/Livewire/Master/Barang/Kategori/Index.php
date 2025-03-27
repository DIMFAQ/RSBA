<?php

namespace App\Livewire\Master\Barang\Kategori;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Kategori Barang')]
#[Lazy]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.barang.kategori.index');
    }
}
