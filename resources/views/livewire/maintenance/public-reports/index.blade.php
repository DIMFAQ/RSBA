<div class="space-y-4">

    {{-- Header --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Laporan Pengaduan Publik</h2>
            <p class="text-sm text-gray-500">Laporan kerusakan dari pengunjung / pasien tanpa login.</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col gap-3 sm:flex-row">
        <div class="flex-1">
            <x-ts:input
                wire:model.live.debounce.400ms="search"
                placeholder="Cari ruangan, kode tiket, deskripsi, pelapor..."
                prefix-icon="tabler-search"
            />
        </div>
        <div>
            <x-ts:select.styled
                wire:model.live="filterJenis"
                placeholder="Semua Jenis"
                :options="[
                    ['value' => 'umum', 'label' => '🔧 Umum'],
                    ['value' => 'it',   'label' => '💻 IT'],
                ]"
                select="label:label|value:value"
            />
        </div>
        <div>
            <x-ts:select.styled
                wire:model.live="filterStatus"
                placeholder="Semua Status"
                :options="[
                    ['value' => 'pending', 'label' => 'Pending'],
                    ['value' => 'proses',  'label' => 'Diproses'],
                    ['value' => 'selesai', 'label' => 'Selesai'],
                ]"
                select="label:label|value:value"
            />
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">#</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Kode Tiket</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Ruangan</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Jenis</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Deskripsi</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Ditangani</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Waktu</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($reports as $report)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-gray-500">{{ $report->id }}</td>
                        <td class="px-4 py-3">
                            @if ($report->tracking_code)
                                <span class="inline-flex items-center gap-1 font-mono text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-md">
                                    {{ $report->tracking_code }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-medium text-gray-800">{{ $report->ruangan?->nama ?? '-' }}</span>
                            @if ($report->pelapor_nama)
                                <span class="block text-xs text-gray-500">Oleh: {{ $report->pelapor_nama }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($report->jenis === 'it')
                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700">
                                    💻 IT
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-medium text-orange-700">
                                    🔧 Umum
                                </span>
                            @endif
                        </td>
                        <td class="max-w-xs px-4 py-3 text-gray-700">
                            <p class="line-clamp-2">{{ $report->deskripsi }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $colors = ['pending' => 'bg-red-100 text-red-700', 'proses' => 'bg-yellow-100 text-yellow-700', 'selesai' => 'bg-green-100 text-green-700'];
                                $labels = ['pending' => 'Pending', 'proses' => 'Diproses', 'selesai' => 'Selesai'];
                            @endphp
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $colors[$report->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $labels[$report->status] ?? $report->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $report->handler?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $report->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                @if ($report->status === 'pending')
                                    <x-ts:button
                                        wire:click="proses({{ $report->id }})"
                                        size="xs"
                                        color="warning"
                                    >
                                        Proses
                                    </x-ts:button>
                                @endif
                                @if (in_array($report->status, ['pending', 'proses']))
                                    <x-ts:button
                                        wire:click="markAsSelesai({{ $report->id }})"
                                        size="xs"
                                        color="success"
                                    >
                                        Selesai
                                    </x-ts:button>
                                @endif
                                @if ($report->status === 'selesai')
                                    <span class="text-xs text-gray-400 italic">Selesai</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <x-icon name="tabler-inbox" class="size-10 opacity-40" />
                                <span>Belum ada laporan pengaduan.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div>{{ $reports->links() }}</div>

    {{-- Modal Selesai --}}
    <x-ts:modal wire="modalSelesai" title="Tandai Selesai" blur>
        <div class="space-y-4">
            <p class="text-sm text-gray-600">Masukkan catatan penyelesaian sebelum menutup laporan ini.</p>
            <x-ts:textarea
                wire:model="catatanHandler"
                label="Catatan Penyelesaian"
                placeholder="Tuliskan tindakan yang sudah dilakukan..."
                rows="3"
            />
        </div>
        <x-slot:footer>
            <x-ts:button x-on:click="$tsui.close.modal('modalSelesai')" color="secondary">Batal</x-ts:button>
            <x-ts:button wire:click="selesai" loading="selesai" color="success">Simpan & Selesai</x-ts:button>
        </x-slot:footer>
    </x-ts:modal>

</div>
