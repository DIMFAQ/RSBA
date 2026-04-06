<?php

namespace App\Livewire\Akuntansi\Jurnal;

use App\Traits\AuthorizesFromRoute;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Jurnal Umum')]
class Index extends Component
{
    use AuthorizesFromRoute;

    public function render()
    {
        $this->authorizeFromRoute();
        return view('livewire.akuntansi.jurnal.index');
    }
}
