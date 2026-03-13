<?php

namespace App\Livewire\Laporan\Kepegawaian;

use App\Traits\AuthorizesFromRoute;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Laporan')]
class Index extends Component
{
    use AuthorizesFromRoute;

    public function render()
    {
        $this->authorizeFromRoute();
        return view('livewire.laporan.kepegawaian.index');
    }
}
