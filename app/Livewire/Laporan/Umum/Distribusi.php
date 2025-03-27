<?php

namespace App\Livewire\Laporan\Umum;

use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Distribusi extends Component
{
    public function render()
    {
        return view('livewire.laporan.umum.distribusi');
    }
}
