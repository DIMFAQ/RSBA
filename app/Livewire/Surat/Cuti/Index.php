<?php

namespace App\Livewire\Surat\Cuti;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Surat Cuti')]
#[Lazy]
class Index extends Component
{
    public function render()
    {
        return view('livewire.surat.cuti.index');
    }
}
