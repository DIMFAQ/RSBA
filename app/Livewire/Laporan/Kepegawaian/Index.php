<?php

namespace App\Livewire\Laporan\Kepegawaian;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Laporan')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.laporan.kepegawaian.index');
    }
}
