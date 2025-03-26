<?php

namespace App\Livewire\Surat\Cuti;

use Livewire\Component;
use App\Models\Sdm\Jabatan;
use App\Models\Sdm\Karyawan;
use Livewire\Attributes\Lazy;
use TallStackUi\Traits\Interactions;
use App\Livewire\Forms\SuratCutiForm;

#[Lazy]
class Add extends Component
{
    use Interactions;
    public SuratCutiForm $form;
    public ?Karyawan $karyawan;

    public function mount()
    {
        $this->form->options_atasan = Jabatan::pluck('nama', 'id');
    }

    public function updateKaryawan($value)
    {
        $this->karyawan = Karyawan::findOrFail($value);
        $this->form->sisa_cuti = $this->karyawan?->cuti;
        $this->form->jenis_cuti = '';
    }

    public function updateJenisCuti($value)
    {
        $this->form->tgl_cuti = []; //ketika ganti jenis, tanggal pengajuan cuti di reset terlebih dahulu 

        $this->form->sisa_cuti = $this->karyawan?->cuti;
        if ($value === 'bersalin') {
            $this->form->sisa_cuti = 90;
        }
    }

    // simpan data
    function submit()
    {
        $this->validate();

        $submitting = $this->form->submiting(karyawan: $this->karyawan);

        if ($submitting['status'] === 'sukses') {
            $this->dispatch('created-cuti');

            $this->toast()
                ->success('Sukses', 'Surat cuti berhasil dibuat.')
                ->send();
        } else {
            $this->toast()
                ->error('Failed', $submitting['message'])
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.surat.cuti.add');
    }
}
