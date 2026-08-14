<div wire:poll.10s="loadMonitoringData" class="space-y-6">
    
    {{-- Top Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700/50 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">Monitoring Ruang Server</h2>
                    @if ($device && ($device['is_online'] ?? false))
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-800/50">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            ONLINE
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-400 dark:border-rose-800/50">
                            <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                            OFFLINE
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Audit Export Button --}}
        <div>
            <button wire:click="exportAuditLog" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all transform active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Excel ({{ count($readings) }} Data)
            </button>
        </div>
    </div>

    {{-- Error Banner --}}
    @if ($errorMessage)
        <div class="p-4 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800/40 rounded-xl text-xs text-rose-700 dark:text-rose-400 flex items-center justify-between">
            <span class="font-medium">{{ $errorMessage }}</span>
            <button wire:click="$set('errorMessage', '')" class="text-rose-500 hover:text-rose-800 dark:hover:text-rose-200 font-bold">&times;</button>
        </div>
    @endif

    {{-- Temperature Warning Threshold Banner --}}
    @if ($latestReading && (($latestReading['temperature'] ?? 0) > 28.0 || ($latestReading['humidity'] ?? 0) > 70.0))
        <div class="p-4 bg-amber-50 dark:bg-amber-950/30 border border-amber-300 dark:border-amber-700/50 rounded-xl text-amber-900 dark:text-amber-200 flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-amber-800 dark:text-amber-300">PERINGATAN SUHU / KELEMBAPAN RUANG SERVER</h4>
                <p class="text-xs mt-0.5 text-amber-700 dark:text-amber-400">Kondisi lingkungan melebihi batas aman (Suhu Max: 28°C, Kelembapan Max: 70%). Mohon periksa AC Pendingin Ruang Server.</p>
            </div>
        </div>
    @endif

    {{-- Realtime Telemetry Metric Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Temperature Widget --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700/50 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Suhu Ruangan</span>
                <span class="p-2 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-xl border border-rose-100 dark:border-rose-800/40">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </span>
            </div>
            <div class="my-4 flex items-baseline gap-2">
                <span class="text-4xl font-extrabold text-gray-800 dark:text-white tracking-tight">
                    {{ $latestReading ? number_format($latestReading['temperature'], 1) : '--.-' }}
                </span>
                <span class="text-lg font-bold text-gray-400">°C</span>
            </div>
            <div class="flex items-center justify-between text-xs text-gray-400 dark:text-gray-500 border-t border-gray-100 dark:border-gray-700/50 pt-3">
                <span>Batas Ideal: 18.0 - 24.0 °C</span>
                <span class="font-semibold {{ ($latestReading['temperature'] ?? 0) <= 24.0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                    {{ ($latestReading['temperature'] ?? 0) <= 24.0 ? 'NORMAL' : 'TINGGI' }}
                </span>
            </div>
        </div>

        {{-- Humidity Widget --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700/50 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Kelembapan Udara</span>
                <span class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl border border-blue-100 dark:border-blue-800/40">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.12a2 2 0 00-1.022.547l-1.018 1.018a2 2 0 00.547 3.428l2.387.477a6 6 0 003.86-.517l.318-.158a6 6 0 013.86-.517l2.387.477a2 2 0 002.433-2.433l-1.018-1.018z"></path>
                    </svg>
                </span>
            </div>
            <div class="my-4 flex items-baseline gap-2">
                <span class="text-4xl font-extrabold text-gray-800 dark:text-white tracking-tight">
                    {{ $latestReading ? number_format($latestReading['humidity'], 1) : '--.-' }}
                </span>
                <span class="text-lg font-bold text-gray-400">% RH</span>
            </div>
            <div class="flex items-center justify-between text-xs text-gray-400 dark:text-gray-500 border-t border-gray-100 dark:border-gray-700/50 pt-3">
                <span>Batas Ideal: 40.0 - 60.0 %</span>
                <span class="font-semibold {{ ($latestReading['humidity'] ?? 0) <= 60.0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                    {{ ($latestReading['humidity'] ?? 0) <= 60.0 ? 'OPTIMAL' : 'LEMBAP' }}
                </span>
            </div>
        </div>

        {{-- Network Health Widget --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700/50 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Sinyal & Koneksi LAN</span>
                <span class="p-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl border border-indigo-100 dark:border-indigo-800/40">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071a10 10 0 0114.142 0M1.414 9.414a15 15 0 0121.172 0"></path>
                    </svg>
                </span>
            </div>
            <div class="my-3 space-y-1.5">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-400 dark:text-gray-500">Wi-Fi RSSI:</span>
                    <span class="font-bold text-gray-700 dark:text-gray-200">
                        {{ $latestReading['rssi'] ?? '--' }} dBm
                        @php
                            $rssi = $latestReading['rssi'] ?? -99;
                        @endphp
                        @if ($rssi >= -60) <span class="text-emerald-600 dark:text-emerald-400 font-bold">(Excellent)</span>
                        @elseif ($rssi >= -70) <span class="text-blue-600 dark:text-blue-400 font-bold">(Good)</span>
                        @elseif ($rssi >= -80) <span class="text-amber-600 dark:text-amber-400 font-bold">(Fair)</span>
                        @else <span class="text-rose-600 dark:text-rose-400 font-bold">(Weak)</span>
                        @endif
                    </span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-400 dark:text-gray-500">IP Address:</span>
                    <span class="font-mono font-medium text-gray-700 dark:text-gray-200">{{ $device['ip_address'] ?? '192.168.1.120' }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-400 dark:text-gray-500">HTTP Latency:</span>
                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $latestReading['latency_ms'] ?? '--' }} ms</span>
                </div>
            </div>
            <div class="flex items-center justify-between text-[11px] text-gray-400 dark:text-gray-500 border-t border-gray-100 dark:border-gray-700/50 pt-2.5">
                <span>Last Seen: {{ !empty($device['last_seen_at']) ? date('H:i:s (d M)', strtotime($device['last_seen_at'])) : '-' }}</span>
                <span>Interval: 5m</span>
            </div>
        </div>

    </div>

    {{-- Telemetry History & Time Range Sub-Tabs Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/50 shadow-sm overflow-hidden">
        
        {{-- Sub-Tabs Header --}}
        <div class="p-5 border-b border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/30 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-gray-800 dark:text-white">Riwayat Telemetry & Log Sensor</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    @if ($timeRange === 'latest_10')
                        Menampilkan 10 data pembacaan sensor terbaru secara realtime.
                    @elseif ($timeRange === '1_day')
                        Menampilkan riwayat telemetri dalam 1 hari terakhir (24 jam).
                    @elseif ($timeRange === '1_month')
                        Menampilkan riwayat telemetri dalam 1 bulan terakhir (30 hari).
                    @elseif ($timeRange === '1_year')
                        Menampilkan riwayat telemetri dalam 1 tahun terakhir.
                    @else
                        Menampilkan riwayat telemetri rentang kustom ({{ $startDate ? date('d/m/Y', strtotime($startDate)) : '' }} - {{ $endDate ? date('d/m/Y', strtotime($endDate)) : '' }}).
                    @endif
                </p>
            </div>

            {{-- Period Filter Pills --}}
            <div class="flex flex-wrap items-center gap-1.5 bg-gray-200/60 dark:bg-gray-900/80 p-1.5 rounded-xl text-xs font-semibold">
                <button 
                    wire:click="setTimeRange('latest_10')" 
                    class="px-3 py-1.5 rounded-lg transition-all {{ $timeRange === 'latest_10' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-sm font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200' }}"
                >
                    10 Terbaru
                </button>
                <button 
                    wire:click="setTimeRange('1_day')" 
                    class="px-3 py-1.5 rounded-lg transition-all {{ $timeRange === '1_day' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-sm font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200' }}"
                >
                    1 Hari Terakhir
                </button>
                <button 
                    wire:click="setTimeRange('1_month')" 
                    class="px-3 py-1.5 rounded-lg transition-all {{ $timeRange === '1_month' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-sm font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200' }}"
                >
                    1 Bulan Terakhir
                </button>
                <button 
                    wire:click="setTimeRange('1_year')" 
                    class="px-3 py-1.5 rounded-lg transition-all {{ $timeRange === '1_year' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-sm font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200' }}"
                >
                    1 Tahun Terakhir
                </button>
                <button 
                    wire:click="$set('timeRange', 'custom')" 
                    class="px-3 py-1.5 rounded-lg transition-all {{ $timeRange === 'custom' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-sm font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200' }}"
                >
                    Kustom
                </button>
            </div>
        </div>

        {{-- Custom Date Form (Appears when 'custom' is active) --}}
        @if ($timeRange === 'custom')
            <div class="px-5 py-3.5 bg-indigo-50/50 dark:bg-indigo-950/20 border-b border-indigo-100/70 dark:border-indigo-900/30 flex flex-wrap items-center gap-3 text-xs">
                <span class="font-medium text-gray-700 dark:text-gray-300">Pilih Rentang Tanggal:</span>
                <input type="date" wire:model="customStartDate" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-2.5 py-1.5 text-gray-700 dark:text-gray-200 focus:ring-1 focus:ring-indigo-500 outline-none" title="Tanggal Mulai">
                <span class="text-gray-400 font-medium">s/d</span>
                <input type="date" wire:model="customEndDate" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-2.5 py-1.5 text-gray-700 dark:text-gray-200 focus:ring-1 focus:ring-indigo-500 outline-none" title="Tanggal Selesai">
                <button wire:click="applyCustomFilter" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    Terapkan
                </button>
                <button wire:click="resetFilter" class="px-3 py-1.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 font-medium rounded-lg transition-colors">
                    Reset
                </button>
            </div>
        @endif

        {{-- Table Content --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700/50 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="py-3 px-5">Waktu Recorded</th>
                        <th class="py-3 px-5">Suhu (°C)</th>
                        <th class="py-3 px-5">Kelembapan (%)</th>
                        <th class="py-3 px-5">RSSI Sinyal</th>
                        <th class="py-3 px-5">Latency (ms)</th>
                        <th class="py-3 px-5">Status Device</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/30 text-xs text-gray-700 dark:text-gray-300">
                    @forelse ($this->paginatedReadings as $row)
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/20 transition-colors">
                            <td class="py-3 px-5 font-mono text-gray-600 dark:text-gray-300">
                                {{ date('d/m/Y H:i:s', strtotime($row['recorded_at'])) }}
                            </td>
                            <td class="py-3 px-5 font-bold {{ $row['temperature'] > 28.0 ? 'text-rose-600 dark:text-rose-400' : 'text-gray-800 dark:text-white' }}">
                                {{ number_format($row['temperature'], 2) }} °C
                            </td>
                            <td class="py-3 px-5 font-bold {{ $row['humidity'] > 70.0 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-800 dark:text-white' }}">
                                {{ number_format($row['humidity'], 2) }} %
                            </td>
                            <td class="py-3 px-5">
                                <span class="font-mono">{{ $row['rssi'] }} dBm</span>
                            </td>
                            <td class="py-3 px-5 font-mono text-indigo-600 dark:text-indigo-400">
                                {{ $row['latency_ms'] ?? '-' }} ms
                            </td>
                            <td class="py-3 px-5">
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> ONLINE
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 dark:text-gray-500 text-xs">
                                Belum ada data telemetry yang tercatat pada rentang waktu ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Pagination / Footer --}}
        <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/30 dark:bg-gray-900/20 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <div class="text-gray-500 dark:text-gray-400">
                @if (count($readings) > 0)
                    Menampilkan <span class="font-bold text-gray-700 dark:text-gray-200">{{ (($currentPage - 1) * $perPage) + 1 }}</span> - <span class="font-bold text-gray-700 dark:text-gray-200">{{ min($currentPage * $perPage, count($readings)) }}</span> dari total <span class="font-bold text-gray-700 dark:text-gray-200">{{ count($readings) }}</span> data tercatat
                @else
                    Total 0 data
                @endif
            </div>

            @if ($this->totalPages > 1)
                <div class="flex items-center gap-2">
                    <button 
                        wire:click="previousPage" 
                        @if ($currentPage <= 1) disabled @endif
                        class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-40 disabled:cursor-not-allowed font-medium transition-colors"
                    >
                        &laquo; Sebelumnya
                    </button>
                    
                    <span class="px-3 py-1 font-semibold text-gray-700 dark:text-gray-300">
                        Halaman {{ $currentPage }} / {{ $this->totalPages }}
                    </span>

                    <button 
                        wire:click="nextPage" 
                        @if ($currentPage >= $this->totalPages) disabled @endif
                        class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-40 disabled:cursor-not-allowed font-medium transition-colors"
                    >
                        Selanjutnya &raquo;
                    </button>
                </div>
            @endif
        </div>

    </div>

</div>
