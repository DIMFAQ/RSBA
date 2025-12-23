<?php

namespace App\Livewire\Asset;

use App\Livewire\Forms\Asset\AssetBarangForm;
use Livewire\Component;
use Livewire\Attributes\Lazy;
use App\Models\Assets\AssetBarang;
use Illuminate\Support\Facades\DB;
use TallStackUi\Traits\Interactions;

#[Lazy]
class Catat extends Component
{
    use Interactions;

    public AssetBarangForm $form;

    public ?AssetBarang $assetBarang;

    public $statusOptions = [
        'baik' => 'Baik',
        'diperbaiki' => 'Perbaikan',
        'rusak' => 'Rusak',
        'hilang' => 'Hilang',
    ];

    public function mount($id)
    {
        $this->assetBarang = AssetBarang::findOrFail($id);
    }

    public function submit()
    {

        $this->form->validate(
            [
                'tgl_catat' => 'required',
                'status' => 'required'
            ]
        );

        try {
            $this->form->catatAsset($this->assetBarang);

            $this->dispatch('new-asset-created');

            $this->toast()
                ->success('Berhasil', 'Barang berhasil dilakukan pencatatan sebagai asset.')
                ->send();
        } catch (\Throwable $e) {
            $this->toast()
                ->error("Tidak Berhasil", "<i>{$e->getMessage()}</i> <br> Silahkan coba lagi.")
                ->send();
        }
    }

    public function render()
    {

        // $mains = AssetBarang::all();
        return view('livewire.asset.catat');
    }
}
