<?php

namespace App\Livewire\Pembelian;

use App\Models\Gudang\Pembelian;
use App\Models\Gudang\PembelianRequest;
use App\Traits\BlocksTransactionDuringOpname;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

#[Title('Pembelian')]
#[Lazy]
class Index extends Component
{
    use WithPagination;
    use BlocksTransactionDuringOpname;

    public $state;

    public $tab;
    public bool $stats = false;
    public $search = '';

    public ?Pembelian $pembelian;

    #[On('close-cari-pembelian')]
    function updatedSearch($value)
    {
        $this->search = $value;
        $this->pembelian = Pembelian::where('no', $value)->firstOr(function () {
            return null;
        });
    }

    #[Computed]
    public function getRequestPembelianProperty()
    {
        // return PembelianRequest::where('status', 'pending')->count();
        return rand(1, 100); // Simulating a random count for requests
    }

    public function render()
    {

        if (!$this->blockIfOpnameActive()) {
            return view('components.opname-block');
        }

        $this->authorize('view-pembelian');
        return view('livewire.pembelian.index');
    }
}
