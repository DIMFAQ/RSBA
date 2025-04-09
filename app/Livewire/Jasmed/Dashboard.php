<?php

namespace App\Livewire\Jasmed;

use Carbon\Carbon;
use App\Models\JmJasa;
use Livewire\Component;
use App\Models\JmPasien;
use Livewire\Attributes\Lazy;

#[Lazy]
class Dashboard extends Component
{

    public string $periode;
    public $layanan;
    public array $layanan_opt = [
        ['label' => 'Rajal', 'value' => 'rajal'],
        ['label' => 'Ranap', 'value' => 'ranap'],
    ];

    public string $cabar;
    public array $cabar_opt = [
        ['label' => 'BPJS', 'value' => 'bpjs'],
        ['label' => 'Tunai', 'value' => 'tunai'],
        ['label' => 'JKMD', 'value' => 'jkmd']
    ];

    public $pasienDiajukan = 0;
    public $pasienDisetujui = 0;
    public $pasienPending = 0;

    public $klaimDiajukan = 0;
    public $klaimDisetujui = 0;
    public $klaimPending = 0;

    public $prosentaseDisetujui = 0;

    public $total_jasa = 0;

    function mount()
    {
        $this->periode = Carbon::now()->format('Y-m');
    }

    function updated($propertyName)
    {
        $this->updateDashboard();
    }

    function updateDashboard()
    {
        $layanan = $this->layanan;
        $cabar = $this->cabar;
        $periode = $this->periode;


        // Stats Pasien
        $this->getStatsPasien(periode: $periode, layanan: $layanan, cabar: $cabar);


        // Stats Klaim
        $this->getStatsKlaim(periode: $periode, layanan: $layanan, cabar: $cabar);

        // Stats Jasa
        $this->getStatsJasa(periode: $periode, layanan: $layanan, cabar: $cabar);
    }

    private function getStatsPasien($periode, $layanan, $cabar)
    {
        $pasienDiajukan = JmPasien::where('tgl_checkout', 'like', "$periode%")
            ->when(
                $layanan,
                fn($query) => $query->where('layanan', $layanan)
            )
            ->when(
                $cabar,
                fn($query) => $query->where('cabar', $cabar)
            )
            ->count();
        $this->pasienDiajukan = number_format($pasienDiajukan, 0, ',', '.');

        // total pasien klaim
        $pasienDisetujui = JmPasien::where('tgl_checkout', 'like', "$periode%")
            ->when(
                $layanan,
                fn($query) => $query->where('layanan', $layanan)
            )
            ->when(
                $cabar,
                fn($query) => $query->where('cabar', $cabar)
            )
            ->where('disetujui', '>', 0)
            ->count();
        $this->pasienDisetujui = number_format($pasienDisetujui, 0, ',', '.');


        // total Pasien Pending
        $pasienPending = JmPasien::where('tgl_checkout', 'like', "$periode%")
            ->when(
                $layanan,
                fn($query) => $query->where('layanan', $layanan)
            )
            ->when(
                $cabar,
                fn($query) => $query->where('cabar', $cabar)
            )
            ->where('disetujui', 0)
            ->count();
        $this->pasienPending = number_format($pasienPending, 0, ',', '.');

        // persentase pasien disetjui
        $this->prosentaseDisetujui = round(
            ((
                $pasienDisetujui  / ($pasienDiajukan ? $pasienDiajukan : 1)
            ) * 100),
            2
        ) . "%";
    }


    private function getStatsKlaim($periode, $layanan, $cabar)
    {
        // Diajukan
        $klaimDiajukan = JmPasien::where('tgl_checkout', 'like', "$periode%")
            ->when(
                $layanan,
                fn($query) => $query->where('layanan', $layanan)
            )
            ->when(
                $cabar,
                fn($query) => $query->where('cabar', $cabar)
            )
            ->sum('klaim');
        $this->klaimDiajukan = "Rp. " . number_format($klaimDiajukan, 0, ',', '.');


        // klaim
        $klaimDisetujui = JmPasien::where('tgl_checkout', 'like', "$periode%")
            ->when(
                $layanan,
                fn($query) => $query->where('layanan', $layanan)
            )
            ->when(
                $cabar,
                fn($query) => $query->where('cabar', $cabar)
            )
            ->where('disetujui', '>', 0)
            ->sum('disetujui');
        $this->klaimDisetujui = "Rp. " . number_format($klaimDisetujui, 0, ',', '.');


        // Pending Klaim
        $klaimPending = JmPasien::where('tgl_checkout', 'like', "$periode%")
            ->when(
                $layanan,
                fn($query) => $query->where('layanan', $layanan)
            )
            ->when(
                $cabar,
                fn($query) => $query->where('cabar', $cabar)
            )
            ->where('disetujui', 0)
            ->sum('klaim');
        $this->klaimPending = "Rp. " . number_format($klaimPending, 0, ',', '.');
    }

    private function getStatsJasa($periode, $layanan, $cabar)
    {
        // total jasa
        $jasa = JmJasa::whereHas(
            'prosentase.pasien', //relation jmJasa => JmProsentase => JmPasien
            function ($query) use ($periode, $layanan, $cabar) {
                $query //Query to relations JmPasien (as above)
                    ->where('tgl_checkout', 'like', "$periode%")
                    ->when(
                        $layanan,
                        fn($query) => $query->where('layanan', $layanan)
                    )
                    ->when(
                        $cabar,
                        fn($query) => $query->where('cabar', $cabar)
                    )
                ;
            }
        )
            ->sum('jasa');
        $this->total_jasa = "Rp. " . number_format($jasa, 0, ',', '.');
    }

    public function render()
    {
        return view('livewire.jasmed.dashboard');
    }
}
