<?php

namespace App\Livewire\Jasmed\Dokter;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\JmPasien;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Locked;
use Livewire\WithPagination;

#[Lazy]
class CheckVisit extends Component
{
    use WithPagination;

    public $tgl_checkout, $cabar, $search_option, $cari;
    public bool $is_no_klaim = false;
    public bool $has_no_dokter = false;

    #[Locked]
    public $selectedId;

    public function mount($tgl_checkout, $cabar, $search_option, $cari, $is_no_klaim, $has_no_dokter)
    {
        $this->tgl_checkout = $tgl_checkout;
        $this->search_option = $search_option;
        $this->cabar = $cabar;
        $this->cari = $cari;
        $this->is_no_klaim = $is_no_klaim;
        $this->has_no_dokter = $has_no_dokter;
    }

    #[Computed]
    public function getRows()
    {
        $query =  JmPasien::select([
            'jm_pasien.id',
            'jm_pasien.nama_pasien',
            'jm_pasien.no_rekmedis',
            'jm_pasien.tgl_checkin',
            'jm_pasien.tgl_checkout',
            'jm_pasien.dpjp',
            'jm_pasien.sep',
            'jm_pasien.disetujui',
            'jm_pasien.kelompok'
        ])
            ->with(['dokter'])
            ->where('jm_pasien.layanan', 'ranap')
            ->where('jm_pasien.cabar', $this->cabar)
            ->where('jm_pasien.disetujui', $this->is_no_klaim ? '=' : '>', 0)
            ->where($this->search_option, 'like', '%' . $this->cari . '%')
            ->whereBetween('jm_pasien.tgl_checkout', [
                Carbon::parse($this->tgl_checkout)->startOfMonth(),
                Carbon::parse($this->tgl_checkout)->endOfMonth()
            ]);

        if ($this->has_no_dokter) {
            $query->whereDoesntHave('dokter');
        }

        return $query->paginate(10);
    }

    public function editDokter($id)
    {
        $this->selectedId = $id;
        $this->dispatch(
            'open-modal',
            id: 'modal-edit-dokter'
        );
    }

    public function render()
    {
        return view('livewire.jasmed.dokter.check-visit');
    }
}
