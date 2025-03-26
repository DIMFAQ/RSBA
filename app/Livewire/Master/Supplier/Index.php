<?php

namespace App\Livewire\Master\Supplier;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Data Supplier')]
#[Lazy]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.supplier.index');
    }
}
