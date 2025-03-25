<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\Sdm\Karyawan;
use Livewire\Attributes\Lazy;

#[Lazy]
class HistoryJabatan extends Component
{
    private ?Karyawan $karyawan;

    public function mount($karyawanId)
    {
        $this->karyawan = Karyawan::find($karyawanId);
    }

    public function render()
    {
        return view('livewire.karyawan.history-jabatan', ['karyawan' => $this->karyawan]);
    }
}
