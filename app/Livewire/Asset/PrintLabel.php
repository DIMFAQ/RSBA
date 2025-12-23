<?php

namespace App\Livewire\Asset;

use Livewire\Component;
use Milon\Barcode\DNS2D;
use Livewire\Attributes\Computed;
use App\Models\Assets\AssetBarang;


class PrintLabel extends Component
{
    public ?AssetBarang $assetBarang;
    public $logo;

    public function mount($id)
    {
        // if ($id) {
        $this->assetBarang = AssetBarang::with(['barang', 'ruangan'])->find($id);
        // }

        // if (!$this->assetBarang) {
        // abort(404, 'Asset not found');
        // }
    }

    #[Computed]
    public function generateBarcode()
    {
        $barcode = new DNS2D();
        return $barcode->getBarcodePNG($this->assetBarang?->kode, 'QRCODE');
    }

    public function render()
    {
        return view('livewire.asset.print-label');
    }
}
