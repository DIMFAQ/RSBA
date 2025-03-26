<?php

namespace App\Livewire\Surat\Sp3;

use App\Models\Surat\SuratSp3;
use App\Models\Surat\SuratSp3Detail;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Lazy]
class Details extends Component
{
    #[Locked]
    public ?SuratSp3 $suratSp3;

    public $headers = [
        ['index' => 'keterangan', 'label' => 'Keterangan'],
        ['index' => 'nominal', 'label' => 'Nominal'],
    ];

    public $rows = [];

    public function mount($suratSp3)
    {

        $this->suratSp3 = $suratSp3;
        $this->rows = $suratSp3->load('details')->details->map(function ($detail) {
            return $detail;
        });
    }

    public function render()
    {
        return view('livewire.surat.sp3.details');
    }
}
