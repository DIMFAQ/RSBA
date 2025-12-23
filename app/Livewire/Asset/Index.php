<?php

namespace App\Livewire\Asset;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Asset')]
#[Lazy]
class Index extends Component
{
    public function render()
    {
        $this->authorize('view-asset');
        return view('livewire.asset.index');
    }
}
