<?php

namespace App\Livewire\Surat\Cuti;

use App\Models\Surat\SuratCuti;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Lazy]
class Approval extends Component
{
    public $suratCuti;

    public string $status = '', $keterangan;

    public $optionsApproval = [
        ['value' => 'approved', 'label' => 'Setujui', 'color' => 'indigo'],
        ['value' => 'rejected', 'label' => 'Tolak', 'color' => 'red']
    ];

    public function mount(?SuratCuti $suratCuti)
    {
        $this->suratCuti = $suratCuti;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|string',
            'keterangan' => $this->status === 'rejected' ? 'required|string' : 'nullable|string'
        ];
    }

    public function submit()
    {
        $this->validate();

        $data = [
            'surat_cuti_id' => $this->suratCuti->id,
            'disetujui' => auth()->user()->id,
            'status' => $this->status,
            'keterangan' => $this->keterangan ?? null,
            'approved_at' => now()->toIso8601String(),
        ];

        // Simpan data approval ke database
        SuratCuti::find($this->suratCuti->id)->update($data);
    }

    public function render()
    {
        return view('livewire.surat.cuti.approval');
    }
}
