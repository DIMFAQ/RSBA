<?php

namespace App\Livewire\Akreditasi\Ep;

use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class ListEp extends Component
{
    public ?int $akre_bab_id;

    public function mount($babId)
    {
        $this->akre_bab_id = $babId;
    }

    public function render()
    {
        return view('livewire.akreditasi.ep.list-ep');
    }
}
