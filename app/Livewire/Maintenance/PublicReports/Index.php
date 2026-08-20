<?php

namespace App\Livewire\Maintenance\PublicReports;

use App\Models\Maintenance\PublicReport;
use Livewire\Component;
use Livewire\WithPagination;
use TallStackUi\Traits\Interactions;

class Index extends Component
{
    use WithPagination, Interactions;

    public string $filterStatus = '';
    public string $filterJenis  = '';
    public string $search       = '';

    // Modal konfirmasi selesai
    public bool   $modalSelesai     = false;
    public ?int   $selectedReportId = null;
    public string $catatanHandler   = '';

    public function updatingSearch(): void    { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingFilterJenis(): void  { $this->resetPage(); }

    public function proses(int $id): void
    {
        $report = PublicReport::with('ruangan')->findOrFail($id);
        $ruanganNama = $report->ruangan?->nama ?? 'ruangan';

        $this->dialog()
            ->question('Proses Laporan', "Yakin ingin memproses laporan kerusakan di <b>{$ruanganNama}</b>?")
            ->confirm('Ya, Proses', 'confirmedProses', $id)
            ->cancel('Batal')
            ->send();
    }

    public function confirmedProses(int $id): void
    {
        $report = PublicReport::findOrFail($id);
        $report->update([
            'status'     => 'proses',
            'handled_by' => auth()->id(),
        ]);

        $this->toast()->success('Berhasil', 'Laporan sedang diproses.')->send();
    }

    public function markAsSelesai(int $id): void
    {
        $report = PublicReport::with('ruangan')->findOrFail($id);
        $ruanganNama = $report->ruangan?->nama ?? 'ruangan';

        $this->dialog()
            ->question('Tandai Selesai', "Yakin laporan kerusakan di <b>{$ruanganNama}</b> ini sudah selesai ditangani?")
            ->confirm('Ya, Selesai', 'confirmedSelesai', $id)
            ->cancel('Batal')
            ->send();
    }

    public function confirmedSelesai(int $id): void
    {
        $report = PublicReport::findOrFail($id);
        $report->update([
            'status'     => 'selesai',
            'handled_by' => auth()->id(),
        ]);

        $this->toast()->success('Berhasil', 'Laporan pengaduan telah ditandai Selesai.')->send();
    }

    public function render()
    {
        $reports = PublicReport::with(['ruangan', 'handler'])
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterJenis,  fn($q) => $q->where('jenis', $this->filterJenis))
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->whereHas('ruangan', fn($r) => $r->where('nama', 'like', '%' . $this->search . '%'))
                        ->orWhere('tracking_code', 'like', '%' . $this->search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                        ->orWhere('pelapor_nama', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.maintenance.public-reports.index', compact('reports'));
    }
}
