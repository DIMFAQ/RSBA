<?php

namespace App\Livewire\Jasmed;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Jasmed')]
class Index extends Component
{

    public $content;

    function navigateTo($route)
    {
        $this->content = $route;
    }

    public function render()
    {
        $this->authorize('view-jasmed');
        return view('livewire.jasmed.index');
    }
}
