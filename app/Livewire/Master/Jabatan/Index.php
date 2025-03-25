<?php

namespace App\Livewire\Master\Jabatan;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Lazy]
#[Title('Data Jabatan')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.jabatan.index');
    }
}
