<?php

namespace App\Livewire\Settings\Menu;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Lazy]
#[Title('Setting Menu')]
class Index extends Component
{
    public function render()
    {
        $this->authorize('view-menus');
        return view('livewire.settings.menu.index');
    }
}
