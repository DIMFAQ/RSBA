<?php

namespace App\Livewire\Surat\Cuti;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Approval Cuti')]
#[Lazy]
class Approval extends Component
{
    public function render()
    {
        return view('livewire.surat.cuti.approval');
    }
}
