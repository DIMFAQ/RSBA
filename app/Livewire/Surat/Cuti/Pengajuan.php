<?php

namespace App\Livewire\Surat\Cuti;

use App\Livewire\Forms\SuratCutiForm;
use App\Models\Surat\CutiJenis;
use Livewire\Component;
use App\Models\Sdm\Karyawan;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Locked;
use TallStackUi\Traits\Interactions;

#[Lazy]
class Pengajuan extends Component
{
    use Interactions;

    public SuratCutiForm $form;

    #[Locked]
    public ?Karyawan $karyawan;

    // public $options_urgensi;

    public function mount($id)
    {
        $this->karyawan = Karyawan::findOrFail($id);

        if ($this->karyawan?->sisa_cuti < 0) {
            $this->toast()
                ->warning('Belum terpenuhi', 'Masa kerja kurang dari 1 tahun')
                ->send();
            return;
        }
        $this->form->sisa_cuti = $this->karyawan?->sisa_cuti;
        $this->form->initOptionsUrgensi();
    }

    public function updatedFormJenisCuti($value)
    {
        $this->form->tgl_cuti = [];
        $cutiDiambil = $this->karyawan?->cuti;

        $jenis  = CutiJenis::findOrFail($value);

        $this->form->sisa_cuti = $jenis->lama;
        if ($jenis->periode) {
            $this->form->sisa_cuti = $jenis->lama - $cutiDiambil;
        }
    }

    function submit()
    {
        $this->validate();
        // submit data menggunakan SuratCutiForm
        $submiting = $this->form->submiting(karyawan: $this->karyawan);

        if ($submiting['success']) {
            $this->dispatch('created-cuti');

            $this->toast()
                ->success('Sukses', 'Cuti berhasil diajukan.')
                ->send();
        } else {
            $this->toast()
                ->error('Failed', $submiting['message'])
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.surat.cuti.pengajuan');
    }
}
