<?php

namespace App\Livewire\Dashboard;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
#[Lazy]
class Home extends Component
{
    public function render()
    {
        return view('livewire.dashboard.home');
    }
}
