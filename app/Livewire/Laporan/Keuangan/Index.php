<?php

namespace App\Livewire\Laporan\Keuangan;

use App\Traits\AuthorizesFromRoute;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesFromRoute;

    public function render()
    {
        $this->authorizeFromRoute();
        return view('livewire.laporan.keuangan.index');
    }
}
