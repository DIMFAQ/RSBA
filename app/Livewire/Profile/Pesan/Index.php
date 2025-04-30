<?php

namespace App\Livewire\Profile\Pesan;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Attributes\Isolate;

#[Lazy]
#[Isolate]
#[Title('Pesan')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.profile.pesan.index');
    }
}
