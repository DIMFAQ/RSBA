<?php

namespace App\Livewire\Laporan\Umum;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Laporan Umum')]
#[Lazy]
class Index extends Component
{
    public $tab;

    public function render()
    {
        return view('livewire.laporan.umum.index');
    }
}
