<?php

namespace App\Livewire\Karyawan\Document;

use App\Models\Sdm\KaryawanDocument;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class View extends Component
{
    public $document;
    public function mount(?KaryawanDocument $documentsSelected)
    {
        $this->document = $documentsSelected;
    }

    public function render()
    {
        return view('livewire.karyawan.document.view');
    }
}
