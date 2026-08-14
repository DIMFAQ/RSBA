<?php

namespace App\Livewire\Dashboard;

use App\Exports\ServerRoomTelemetryExport;
use App\Services\DmsMiddlewareClient;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('components.layouts.app')]
class ServerRoomMonitoring extends Component
{
    public ?array $device = null;
    public ?array $latestReading = null;
    public array $readings = [];
    
    // Date Range Filter for Audit Export
    public ?string $startDate = null;
    public ?string $endDate = null;

    public string $errorMessage = '';
    public string $successMessage = '';

    public function mount(DmsMiddlewareClient $client): void
    {
        $this->loadMonitoringData($client);
    }

    public function loadMonitoringData(DmsMiddlewareClient $client): void
    {
        try {
            $data = $client->getServerRoomStatus($this->startDate, $this->endDate);

            $this->device        = $data['device'] ?? null;
            $this->latestReading = $data['latest_reading'] ?? null;
            $this->readings      = $data['readings'] ?? [];
            $this->errorMessage  = '';
        } catch (\Exception $e) {
            $this->errorMessage = 'Gagal terhubung ke DMS Middleware API: ' . $e->getMessage();
        }
    }

    public function filterData(DmsMiddlewareClient $client): void
    {
        $this->loadMonitoringData($client);
    }

    public function resetFilter(DmsMiddlewareClient $client): void
    {
        $this->startDate = null;
        $this->endDate   = null;
        $this->loadMonitoringData($client);
    }

    public function exportAuditLog()
    {
        if (empty($this->readings)) {
            $this->errorMessage = 'Tidak ada data telemetry untuk diekspor pada rentang tanggal ini.';
            return null;
        }

        $filename = 'Audit_Telemetry_Ruang_Server_' . date('Ymd_His') . '.xlsx';
        
        return Excel::download(
            new ServerRoomTelemetryExport($this->readings, $this->device ?? []),
            $filename
        );
    }

    public function render()
    {
        return view('livewire.dashboard.server-room-monitoring')
            ->title('Pemantauan Ruang Server - RSBA Office');
    }
}
