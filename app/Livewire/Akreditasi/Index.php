<?php

namespace App\Livewire\Akreditasi;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Akreditasi')]
#[Lazy]
class Index extends Component
{
    public function render()
    {
        return view('livewire.akreditasi.index');
    }
}
