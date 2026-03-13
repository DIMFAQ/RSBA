<?php

namespace App\Livewire\Gudang;

use App\Traits\AuthorizesFromRoute;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Gudang')]
#[Lazy]
class Index extends Component
{
    use AuthorizesFromRoute;

    public bool $stats = false;

    public function render()
    {
        $this->authorizeFromRoute();
        return view('livewire.gudang.index');
    }
}
