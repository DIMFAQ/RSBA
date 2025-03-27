<?php

namespace App\Livewire\Piutang;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Piutang')]
#[Lazy]
class Index extends Component
{
    public function render()
    {
        return view('livewire.piutang.index');
    }
}
