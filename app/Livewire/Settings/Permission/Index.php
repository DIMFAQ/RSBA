<?php

namespace App\Livewire\Settings\Permission;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;

#[Title('Permission')]
#[Lazy(isolate: false)]
class Index extends Component
{
    public function render()
    {
        return view('livewire.settings.permission.index');
    }
}
