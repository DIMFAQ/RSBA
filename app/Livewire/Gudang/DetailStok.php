<?php

namespace App\Livewire\Gudang;

use App\Models\Master\Barang;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Lazy]
class DetailStok extends Component
{
    use WithPagination, WithoutUrlPagination;

    // public ?Barang $barang;
    // #[Locked]
    public ?Barang $barang;
    public $stok;
    public $stokId = null;

    public function mount(Barang $barang, $stok = false)
    {
        $this->barang = $barang;
        $this->stok = $stok;
    }


    private function getStoks()
    {
        return $this->barang->stoks()
            ->when($this->stok, function ($query) {
                // $query->with(['distribusiDetails' => function ($query) {
                //     $query->orderBy('id', 'desc');
                // }])
                $query->with('distribusiDetails')
                    ->where('stok', '>', 0);
            })
            ->with('penerimaanDet.penerimaan')
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.gudang.detail-stok', [
            'stoks' => $this->getStoks()
        ]);
    }
}
