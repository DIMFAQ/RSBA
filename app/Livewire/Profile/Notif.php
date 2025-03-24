<?php

namespace App\Livewire\Profile;

use Livewire\Attributes\Isolate;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
#[Isolate]
class Notif extends Component
{
    public function render()
    {
        return view('livewire.profile.notif');
    }
}
