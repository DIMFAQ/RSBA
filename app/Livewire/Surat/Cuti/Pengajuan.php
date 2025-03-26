<?php

namespace App\Livewire\Surat\Cuti;

use App\Livewire\Forms\SuratCutiForm;
use Livewire\Component;
use App\Models\Sdm\Karyawan;
use Livewire\Attributes\Lazy;
use TallStackUi\Traits\Interactions;

#[Lazy]
class Pengajuan extends Component
{
    use Interactions;

    public SuratCutiForm $form;

    public ?Karyawan $karyawan;

    public function mount($id)
    {
        $this->karyawan = Karyawan::findOrFail($id);
        $this->form->sisa_cuti = $this->karyawan?->cuti;
    }

    public function updatedFormJenisCuti($value)
    {
        $this->form->tgl_cuti = []; //ketika ganti jenis, tanggal pengajuan cuti di reset terlebih dahulu 

        $this->form->sisa_cuti = $this->karyawan?->cuti;
        if ($value === 'bersalin') {
            $this->form->sisa_cuti = 90;
        }
    }

    function submit()
    {
        $this->validate();
        // submit data menggunakan SuratCutiForm
        $submiting = $this->form->submiting(karyawan: $this->karyawan);

        if ($submiting['status'] === 'sukses') {
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
