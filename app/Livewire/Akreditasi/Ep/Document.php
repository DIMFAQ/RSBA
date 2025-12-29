<?php

namespace App\Livewire\Akreditasi\Ep;

use App\Models\Akreditasi\AkreFiles;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Document extends Component
{
    public AkreFiles $file;

    public function mount($docSelectedId)
    {
        $this->file = AkreFiles::findOrFail($docSelectedId);
    }

    public function render()
    {
        return view('livewire.akreditasi.ep.document');
    }
}
