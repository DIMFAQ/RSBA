<?php

namespace App\Livewire\Profile\Pesan;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Isolate;

#[Lazy]
#[Isolate]
class ListPesan extends Component
{
    public function render()
    {
        return view('livewire.profile.pesan.list-pesan');
    }
}
