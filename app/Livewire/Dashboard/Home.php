<?php

namespace App\Livewire\Dashboard;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Sdm\Karyawan;
use App\Models\User;
use App\Models\Master\Supplier;
use App\Models\Master\Barang;
use App\Models\Gudang\Pembelian;
use App\Models\Surat\SuratCuti;
use App\Models\Surat\SuratSp3;
use App\Models\Maintenance\Jadwal;
use App\Models\Assets\AssetBarang;
use App\Enums\StatusApproval;
use Illuminate\Support\Facades\Auth;

#[Title('Dashboard')]
#[Lazy]
class Home extends Component
{
    public array $stats = [];
    public array $recentCuti = [];
    public array $recentPurchases = [];
    public array $recentMaintenance = [];
    public array $recentSp3 = [];
    public string $userRoleName = 'Guest';
    public string $userName = '';

    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->userName = optional($user->karyawan)->nama ?? $user->email;
            $this->userRoleName = $user->roles->first()?->name ?? 'Guest';

            try {
                if ($user->hasRole('Super-Admin')) {
                    $this->loadSuperAdminData();
                } elseif ($user->hasRole('Staff-SDM')) {
                    $this->loadSdmData();
                } elseif ($user->hasRole('Bagian-Umum')) {
                    $this->loadUmumData();
                } elseif ($user->hasRole('Keuangan')) {
                    $this->loadKeuanganData();
                } else {
                    $this->loadGuestData();
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }

    private function loadSuperAdminData()
    {
        $this->stats = [
            'karyawan_count' => Karyawan::count(),
            'user_count' => User::count(),
            'barang_count' => Barang::count(),
            'supplier_count' => Supplier::count(),
            'pending_cuti' => SuratCuti::whereIn('status', [StatusApproval::PENDING, StatusApproval::WAITING])->count(),
            'maintenance_count' => Jadwal::count(),
            'asset_count' => AssetBarang::count(),
            'total_hutang' => Pembelian::whereIn('status_pembayaran', ['tempo', ''])->orWhereNull('status_pembayaran')->sum('total'),
        ];

        $this->recentCuti = SuratCuti::with('karyawan')->latest()->take(5)->get()->toArray();
        $this->recentPurchases = Pembelian::with('supplier')->latest()->take(5)->get()->toArray();
        $this->recentMaintenance = Jadwal::with('asset')->latest()->take(5)->get()->toArray();
    }

    private function loadSdmData()
    {
        $this->stats = [
            'karyawan_count' => Karyawan::count(),
            'pending_cuti' => SuratCuti::whereIn('status', [StatusApproval::PENDING, StatusApproval::WAITING])->count(),
            'sp3_count' => SuratSp3::count(),
        ];

        $this->recentCuti = SuratCuti::with('karyawan')->latest()->take(5)->get()->toArray();
        $this->recentSp3 = SuratSp3::with('penyetuju')->latest()->take(5)->get()->toArray();
    }

    private function loadUmumData()
    {
        $this->stats = [
            'barang_count' => Barang::count(),
            'supplier_count' => Supplier::count(),
            'asset_count' => AssetBarang::count(),
            'maintenance_count' => Jadwal::count(),
        ];

        $this->recentPurchases = Pembelian::with('supplier')->latest()->take(5)->get()->toArray();
        $this->recentMaintenance = Jadwal::with('asset')->latest()->take(5)->get()->toArray();
    }

    private function loadKeuanganData()
    {
        $this->stats = [
            'total_hutang' => Pembelian::whereIn('status_pembayaran', ['tempo', ''])->orWhereNull('status_pembayaran')->sum('total'),
            'total_pembayaran' => Pembelian::where('status_pembayaran', 'lunas')->sum('total'),
            'pembelian_count' => Pembelian::count(),
        ];

        $this->recentPurchases = Pembelian::with('supplier')->latest()->take(5)->get()->toArray();
    }

    private function loadGuestData()
    {
        $this->stats = [
            'karyawan_count' => Karyawan::count(),
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.home');
    }
}
