<?php

namespace App\Livewire\Kepegawaian;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use App\Traits\AuthorizesFromRoute;

#[Title('Konfigurasi Jadwal')]
class KonfigurasiJadwal extends Component
{
    use AuthorizesFromRoute;

    #[Url]
    public $tab = 'aturan-jadwal';

    public function mount()
    {
        $user = auth()->user();
        if ($this->tab === 'koordinator' && !$user?->hasRole(['Super-Admin', 'Staff-SDM', 'Wakil-Direktur', 'Wadir-SDM-Umum'])) {
            $this->tab = 'aturan-jadwal';
        }
    }

    public function updatedTab($value)
    {
        $user = auth()->user();
        if ($value === 'koordinator' && !$user?->hasRole(['Super-Admin', 'Staff-SDM', 'Wakil-Direktur', 'Wadir-SDM-Umum'])) {
            $this->tab = 'aturan-jadwal';
        }
    }

    public function render()
    {
        $user = auth()->user();
        $canAccess = $user?->hasRole(['Super-Admin', 'Staff-SDM', 'Wakil-Direktur', 'Wadir-SDM-Umum'])
            || $user?->isKoordinator()
            || $user?->can('view-kepegawaian-konfigurasi-jadwal')
            || $user?->can('view-kepegawaian-jadwal-kerja');

        abort_unless(
            $canAccess,
            403,
            "Anda tidak memiliki hak akses ke halaman Konfigurasi Jadwal."
        );

        return view('livewire.kepegawaian.konfigurasi-jadwal');
    }
}

