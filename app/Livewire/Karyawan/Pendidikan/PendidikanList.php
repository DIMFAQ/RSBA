<?php

namespace App\Livewire\Karyawan\Pendidikan;

use Livewire\Component;
use App\Models\Sdm\Karyawan;
use Livewire\Attributes\Lazy;
use Illuminate\Support\Facades\DB;
use TallStackUi\Traits\Interactions;
use App\Models\Sdm\KaryawanPendidikan;

#[Lazy]
class PendidikanList extends Component
{
    use Interactions;

    public $pendidikans;
    public ?Karyawan $karyawan;

    public function mount($id)
    {
        $this->karyawan = Karyawan::findOrFail($id);

        $this->pendidikans = KaryawanPendidikan::where('karyawan_id', $id)
            ->orderBy('tahun_lulus', 'DESC')
            ->get();
    }

    function delete($id): void
    {
        $this->dialog()
            ->question('Hapus Pendidikan')
            ->confirm('Ya', 'confirmedDelete', $id)
            ->cancel('Batal', 'canceledDelete')
            ->send();
    }

    public function confirmedDelete($id): void
    {
        $pendidikan  = KaryawanPendidikan::findOrFail($id);

        DB::beginTransaction();
        try {
            $pendidikan->delete();
            DB::commit();

            $this->dispatch('deleted-pendidikan-karyawan');

            $this->toast()
                ->success('Sukses', 'Data pendidikan dihapus.')
                ->send();
        } catch (\Throwable $e) {
            DB::rollback();
            $this->toast()
                ->error('Failed', 'Error : ' . $e->getMessage())
                ->send();
        }
    }

    public function canceledDelete(): void
    {
        $this->toast()
            ->info('Dibatalkan', 'Batal hapus data pendidikan.')
            ->send();
        $this->dispatch('batal-deleted-karyawan');
    }

    public function placeholder()
    {
        return view('components.skeleton', ['paragraf' => 2, 'footer' => 0]);
    }

    public function render()
    {
        return view('livewire.karyawan.pendidikan.pendidikan-list');
    }
}
