<?php

namespace App\Livewire\Dashboard\DisplayMonitor;

use App\Exports\ServerRoomTelemetryExport;
use App\Services\DmsMiddlewareClient;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class DisplayTabServerRoom extends Component
{
    public ?array $device = null;
    public ?array $latestReading = null;
    public array $readings = [];
    
    // Period selection: 'latest_10' | '1_day' | '1_month' | '1_year' | 'custom'
    public string $timeRange = 'latest_10';
    public ?string $startDate = null;
    public ?string $endDate = null;
    public ?string $customStartDate = null;
    public ?string $customEndDate = null;

    // Pagination
    public int $currentPage = 1;
    public int $perPage = 10;

    public string $errorMessage = '';
    public string $successMessage = '';

    public function mount(DmsMiddlewareClient $client): void
    {
        $this->loadMonitoringData($client);
    }

    public function setTimeRange(string $range, DmsMiddlewareClient $client): void
    {
        $this->timeRange = $range;
        $this->currentPage = 1;

        if ($range === 'latest_10') {
            $this->startDate = null;
            $this->endDate = null;
        } elseif ($range === '1_day') {
            $this->startDate = now()->subHours(24)->toDateTimeString();
            $this->endDate = now()->toDateTimeString();
        } elseif ($range === '1_month') {
            $this->startDate = now()->subDays(30)->toDateTimeString();
            $this->endDate = now()->toDateTimeString();
        } elseif ($range === '1_year') {
            $this->startDate = now()->subDays(365)->toDateTimeString();
            $this->endDate = now()->toDateTimeString();
        }

        $this->loadMonitoringData($client);
    }

    public function applyCustomFilter(DmsMiddlewareClient $client): void
    {
        $this->timeRange = 'custom';
        $this->currentPage = 1;
        $this->startDate = $this->customStartDate ? $this->customStartDate . ' 00:00:00' : null;
        $this->endDate = $this->customEndDate ? $this->customEndDate . ' 23:59:59' : null;
        $this->loadMonitoringData($client);
    }

    public function resetFilter(DmsMiddlewareClient $client): void
    {
        $this->customStartDate = null;
        $this->customEndDate = null;
        $this->setTimeRange('latest_10', $client);
    }

    public function loadMonitoringData(DmsMiddlewareClient $client): void
    {
        try {
            $limit = match ($this->timeRange) {
                'latest_10' => 10,
                '1_day'     => 300,
                '1_month'   => 1500,
                '1_year'    => 5000,
                default     => 500,
            };

            $data = $client->getServerRoomStatus($this->startDate, $this->endDate, $limit);

            $this->device        = $data['device'] ?? null;
            $this->latestReading = $data['latest_reading'] ?? null;
            $this->readings      = $data['readings'] ?? [];
            $this->errorMessage  = '';

            // Ensure current page is valid after data refresh
            if ($this->currentPage > $this->totalPages) {
                $this->currentPage = max(1, $this->totalPages);
            }
        } catch (\Exception $e) {
            $this->errorMessage = 'Gagal terhubung ke DMS Middleware API: ' . $e->getMessage();
        }
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function nextPage(): void
    {
        if ($this->currentPage < $this->totalPages) {
            $this->currentPage++;
        }
    }

    public function gotoPage(int $page): void
    {
        $this->currentPage = max(1, min($page, $this->totalPages));
    }

    public function getPaginatedReadingsProperty(): array
    {
        $offset = ($this->currentPage - 1) * $this->perPage;
        return array_slice($this->readings, $offset, $this->perPage);
    }

    public function getTotalPagesProperty(): int
    {
        return max(1, (int) ceil(count($this->readings) / $this->perPage));
    }

    public function exportAuditLog()
    {
        if (empty($this->readings)) {
            $this->errorMessage = 'Tidak ada data telemetry untuk diekspor pada rentang waktu ini.';
            return null;
        }

        $filename = 'Audit_Telemetry_Ruang_Server_' . date('Ymd_His') . '.xlsx';
        
        return Excel::download(
            new ServerRoomTelemetryExport($this->readings, $this->device ?? []),
            $filename
        );
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="space-y-6 animate-pulse">
            {{-- Header Skeleton --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <div class="h-12 w-12 rounded-xl bg-gray-200 dark:bg-gray-700"></div>
                    <div class="space-y-2">
                        <div class="h-5 w-48 bg-gray-200 dark:bg-gray-700 rounded-md"></div>
                        <div class="h-3 w-32 bg-gray-100 dark:bg-gray-600 rounded-md"></div>
                    </div>
                </div>
                <div class="h-10 w-64 bg-gray-200 dark:bg-gray-700 rounded-xl"></div>
            </div>

            {{-- Cards Skeleton --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="h-44 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/50 p-6"></div>
                <div class="h-44 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/50 p-6"></div>
                <div class="h-44 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/50 p-6"></div>
            </div>

            {{-- Table Skeleton --}}
            <div class="h-64 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/50 p-6"></div>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.dashboard.display-monitor.display-tab-server-room');
    }
}
