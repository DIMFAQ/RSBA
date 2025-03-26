<?php

namespace App\Livewire\Surat\Sp3;

use App\Models\Surat\SuratSp3;
use Livewire\Component;

class PrintSp3 extends Component
{
    public $suratSp3;
    public function mount(?SuratSp3 $suratSp3)
    {
        $this->suratSp3 = $suratSp3;
    }

    public function render()
    {
        return view('livewire.surat.sp3.print-sp3');
    }
}
