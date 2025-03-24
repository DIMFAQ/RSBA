<?php

namespace App\Livewire\User;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('User')]
#[Lazy]
class Index extends Component
{
    public function render()
    {
        $this->authorize('view-user');
        return view('livewire.user.index');
    }
}
