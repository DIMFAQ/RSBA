<?php

namespace App\Livewire\Akreditasi\Chapters;

use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Stat extends Component
{
    public function render()
    {
        return view('livewire.akreditasi.chapters.stat');
    }
}
