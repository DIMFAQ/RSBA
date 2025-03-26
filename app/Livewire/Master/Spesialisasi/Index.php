<?php

namespace App\Livewire\Master\Spesialisasi;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Spesialisasi Dokter')]
#[Lazy(isolate: false)]
class Index extends Component
{
    public function render()
    {
        return view('livewire.master.spesialisasi.index');
    }
}
