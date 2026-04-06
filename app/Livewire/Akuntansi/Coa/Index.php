<?php

namespace App\Livewire\Akuntansi\Coa;

use App\Traits\AuthorizesFromRoute;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Chart Of Account')]
class Index extends Component
{
    use AuthorizesFromRoute;

    public function render()
    {
        $this->authorizeFromRoute();
        return view('livewire.akuntansi.coa.index');
    }
}
