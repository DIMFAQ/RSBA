<?php

namespace App\Livewire\Surat\Cuti;

use App\Models\Surat\SuratCuti;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class DetilTanggalCuti extends Component
{
    public $surat;
    function mount(?SuratCuti $surat)
    {
        $this->surat = $surat;
    }

    public function render()
    {
        return view('livewire.surat.cuti.detil-tanggal-cuti',);
    }
}
