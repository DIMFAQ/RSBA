<?php

namespace App\Livewire\Master\Penyimpanan;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use Illuminate\Support\Facades\DB;
use TallStackUi\Traits\Interactions;
use App\Models\Master\BarangPenyimpanan;

#[Lazy(isolate: false)]
class Add extends Component
{
    use Interactions;

    public string $nama, $deskripsi;

    public $rules = [
        'nama' => 'required|string|unique:um_penyimpanan,nama',
        'deskripsi' => 'required|string'
    ];

    public function submit()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $data = [
                'nama' => $this->nama,
                'deskripsi' => $this->deskripsi
            ];
            BarangPenyimpanan::create($data);

            DB::commit();

            $this->dispatch('new-penyimpanan-created');
            $this->dispatch('close-modal', id: 'modal-new-penyimpanan');

            $this->toast()
                ->success('Berhasil', 'Tempat penyimpanan barang berhasil dibuat.')
                ->send();
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->toast()
                ->error('Failed', 'Error : ' . $e->getMessage())
                ->send();
        }
    }



    public function render()
    {
        return view('livewire.master.penyimpanan.add');
    }
}
