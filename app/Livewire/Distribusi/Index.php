<?php

namespace App\Livewire\Distribusi;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Attributes\Locked;
use App\Models\Gudang\Distribusi;
use App\Traits\BlocksTransactionDuringOpname;

#[Title('Distribusi')]
#[Lazy]
class Index extends Component
{
    use BlocksTransactionDuringOpname;

    #[Locked]
    public ?Distribusi $distribusi;

    public bool $stats = false;
    public $search = '';


    public function mount() {}

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
        if (!$this->blockIfOpnameActive()) {
            return view('components.opname-block');
        }


        $this->authorize('view-disitribusi');
        return view('livewire.distribusi.index');
    }
}
