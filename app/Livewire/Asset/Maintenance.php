<?php

namespace App\Livewire\Asset;

use App\Models\Assets\AssetBarang;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Maintenance extends Component
{
    public ?AssetBarang $assetBarang;

    public function mount($id)
    {
        $this->assetBarang = AssetBarang::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.asset.maintenance');
    }
}
