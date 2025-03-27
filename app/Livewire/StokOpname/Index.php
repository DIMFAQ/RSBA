<?php

namespace App\Livewire\StokOpname;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Stok Opname')]
#[Lazy]
class Index extends Component
{
    public function render()
    {
        return view('livewire.stok-opname.index');
    }
}
