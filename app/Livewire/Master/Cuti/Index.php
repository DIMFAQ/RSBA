<?php

namespace App\Livewire\Master\Cuti;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Lazy]
#[Title('Pengaturan Cuti')]
class Index extends Component
{
    public function render()
    {
        $this->authorize('pengaturan-cuti');
        return view('livewire.master.cuti.index');
    }
}
