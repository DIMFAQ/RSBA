<?php

namespace App\Livewire\Distribusi;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Attributes\Locked;
use App\Models\Gudang\Distribusi;

#[Title('Distribusi')]
#[Lazy]
class Index extends Component
{
    #[Locked]
    public ?Distribusi $distribusi;

    public $search = '';

    function updatedSearch($value)
    {
        $this->distribusi = Distribusi::where('id', $value)->firstOr(
            function () {
                return null;
            }
        );
    }

    public function render()
    {
        return view('livewire.distribusi.index');
    }
}
