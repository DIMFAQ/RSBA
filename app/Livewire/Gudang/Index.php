<?php

namespace App\Livewire\Gudang;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Gudang')]
#[Lazy]
class Index extends Component
{
    public bool $stats = false;

    public function render()
    {
        $this->authorize('view-gudang');
        return view('livewire.gudang.index');
    }
}
