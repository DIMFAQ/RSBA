<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">Sistem Penggajian (Payroll)</h1>
            <p class="text-sm text-slate-500">Kelola dan cetak slip gaji karyawan RSBA berdasarkan status kepegawaian dan jabatan.</p>
        </div>
    </div>

    <!-- Filters Panel -->
    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-2xs">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
            <div>
                <x-ts:input wire:model.live.debounce.300ms="search" placeholder="Cari nama karyawan..." icon="tabler.search" class="w-full" />
            </div>
            <div>
                <select wire:model.live="bagianFilter" class="w-full rounded-lg border-gray-300 text-sm shadow-2xs focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Bagian</option>
                    @foreach($bagians as $bag)
                        <option value="{{ $bag->id }}">{{ $bag->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Salaries Table -->
    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm text-slate-600">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50 text-xs font-semibold uppercase text-slate-400">
                        <th class="px-6 py-4">Nama & NIP</th>
                        <th class="px-6 py-4">Bagian / Jabatan</th>
                        <th class="px-6 py-4">Status Kerja</th>
                        <th class="px-6 py-4">Gaji Pokok</th>
                        <th class="px-6 py-4">Tunjangan</th>
                        <th class="px-6 py-4">Gaji Bersih</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($karyawans as $karyawan)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $karyawan->full_nama }}</div>
                                <div class="text-xs text-slate-400">NIP: {{ $karyawan->nip }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-700">{{ $karyawan->calculated_salary['bagian_nama'] }}</div>
                                <div class="text-xs text-slate-500">{{ $karyawan->calculated_salary['jabatan_nama'] }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-{{ $karyawan->status->color() }}-50 text-{{ $karyawan->status->color() }}-700 border border-{{ $karyawan->status->color() }}-100">
                                    {{ $karyawan->status->nama() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-700">
                                Rp {{ number_format($karyawan->calculated_salary['gaji_pokok'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-700">
                                Rp {{ number_format($karyawan->calculated_salary['tunjangan'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-indigo-600">
                                Rp {{ number_format($karyawan->calculated_salary['gaji_bersih'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <x-ts:button flat color="indigo" class="text-xs font-bold" wire:click="viewSlip({{ $karyawan->id }})">
                                    <x-tabler-file-text class="mr-1 h-4 w-4" />
                                    Lihat Slip Gaji
                                </x-ts:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <x-tabler-database-x class="mx-auto h-12 w-12 text-slate-300 mb-3" />
                                <div class="text-sm font-semibold">Tidak Ada Karyawan Ditemukan</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($karyawans->hasPages())
            <div class="border-t border-slate-100 px-6 py-4 bg-slate-50/50">
                {{ $karyawans->links() }}
            </div>
        @endif
    </div>

    <!-- Salary Slip Modal -->
    <x-ts:modal wire:model="isOpenModal" size="md" class="relative z-50">
        <x-slot:title>
            <span class="flex items-center gap-1.5 font-bold text-slate-800">
                <x-tabler-file-invoice class="h-5 w-5 text-indigo-500" />
                Slip Gaji Karyawan
            </span>
        </x-slot:title>

        @if($selectedSlip)
            <!-- Printable Area -->
            <div id="salary-slip-print" class="p-6 bg-white border border-slate-100 rounded-2xl shadow-sm text-slate-700 text-sm">
                <!-- Header -->
                <div class="text-center border-b-2 border-slate-900 pb-4 mb-4">
                    <h2 class="text-lg font-bold text-slate-900 tracking-wide uppercase">RUMAH SAKIT BAITURRAHIM JAMBI</h2>
                    <p class="text-2xs text-slate-500 mt-0.5">Jl. Prof. M. Yamin No. 99, Jambi | Telp: (0741) 987654</p>
                    <div class="mt-3 inline-block bg-slate-100 px-3 py-1 rounded-full text-xs font-bold text-slate-700 tracking-wider">SLIP GAJI BULANAN</div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-y-2 text-xs border-b border-slate-100 pb-3 mb-4">
                    <div>
                        <span class="text-slate-400 font-medium">Nama Karyawan:</span>
                        <div class="font-bold text-slate-900 mt-0.5">{{ $selectedSlip['nama'] }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium">NIP / Status:</span>
                        <div class="font-bold text-slate-900 mt-0.5">{{ $selectedSlip['nip'] }} ({{ $selectedSlip['status'] }})</div>
                    </div>
                    <div class="mt-2">
                        <span class="text-slate-400 font-medium">Jabatan / Bagian:</span>
                        <div class="font-bold text-slate-900 mt-0.5">{{ $selectedSlip['jabatan'] }} ({{ $selectedSlip['bagian'] }})</div>
                    </div>
                    <div class="mt-2">
                        <span class="text-slate-400 font-medium">Periode Pembayaran:</span>
                        <div class="font-bold text-slate-900 mt-0.5">{{ $selectedSlip['periode'] }}</div>
                    </div>
                </div>

                <!-- Salary Computations -->
                <div class="space-y-4">
                    <!-- Earnings -->
                    <div>
                        <h4 class="text-xs font-bold uppercase text-indigo-600 tracking-wider mb-2">Penghasilan (A)</h4>
                        <div class="space-y-1.5 text-xs">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Gaji Pokok</span>
                                <span class="font-bold text-slate-800">Rp {{ number_format($selectedSlip['gaji_pokok'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Tunjangan Jabatan</span>
                                <span class="font-bold text-slate-800">Rp {{ number_format($selectedSlip['tunjangan'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-t border-slate-100 pt-1.5 font-bold text-slate-900">
                                <span>Total Penerimaan Bruto</span>
                                <span>Rp {{ number_format($selectedSlip['gaji_pokok'] + $selectedSlip['tunjangan'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Deductions -->
                    <div>
                        <h4 class="text-xs font-bold uppercase text-rose-600 tracking-wider mb-2">Potongan (B)</h4>
                        <div class="space-y-1.5 text-xs">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Iuran BPJS Kesehatan</span>
                                <span class="font-semibold text-rose-600">Rp {{ number_format($selectedSlip['bpjs_kes'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Iuran BPJS Ketenagakerjaan</span>
                                <span class="font-semibold text-rose-600">Rp {{ number_format($selectedSlip['bpjs_ket'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Pajak Penghasilan (PPh 21)</span>
                                <span class="font-semibold text-rose-600">Rp {{ number_format($selectedSlip['pajak'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-t border-slate-100 pt-1.5 font-bold text-rose-700">
                                <span>Total Potongan</span>
                                <span>Rp {{ number_format($selectedSlip['bpjs_kes'] + $selectedSlip['bpjs_ket'] + $selectedSlip['pajak'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Net Pay -->
                    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-3.5 mt-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-2xs font-bold text-indigo-700 uppercase tracking-wider">GAJI BERSIH DITERIMA (A - B)</div>
                                <div class="text-4xs text-indigo-400 mt-0.5">*Ditransfer langsung ke rekening payroll terdaftar</div>
                            </div>
                            <span class="text-lg font-black text-indigo-800">
                                Rp {{ number_format($selectedSlip['gaji_bersih'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Signatures -->
                <div class="mt-8 grid grid-cols-2 text-center text-xs text-slate-500">
                    <div>
                        <p class="font-medium">Penerima,</p>
                        <div class="h-12"></div>
                        <p class="font-bold text-slate-900 border-b border-slate-300 pb-0.5 inline-block">{{ $selectedSlip['nama'] }}</p>
                    </div>
                    <div>
                        <p class="font-medium">Jambi, {{ now()->translatedFormat('d F Y') }}</p>
                        <p class="font-medium">Mengetahui, Kabag SDM</p>
                        <div class="h-12"></div>
                        <p class="font-bold text-slate-900 border-b border-slate-300 pb-0.5 inline-block">Staff SDM RSBA</p>
                    </div>
                </div>
            </div>

            <!-- Print Actions -->
            <x-slot:footer>
                <div class="flex justify-end gap-2.5">
                    <x-ts:button flat color="slate" wire:click="closeModal">Tutup</x-ts:button>
                    <x-ts:button color="indigo" class="font-bold" onclick="printSalarySlip()">
                        <x-tabler-printer class="mr-1.5 h-4 w-4" />
                        Cetak Slip Gaji
                    </x-ts:button>
                </div>
            </x-slot:footer>
        @endif
    </x-ts:modal>

    <!-- Custom Print Script -->
    <script>
        function printSalarySlip() {
            var printContents = document.getElementById('salary-slip-print').innerHTML;
            var originalContents = document.body.innerHTML;

            // Open new print window to print only the slip
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Cetak Slip Gaji</title>');
            printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">');
            printWindow.document.write('<style>@media print { body { padding: 20px; } }</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Wait for styles load and print
            setTimeout(function() {
                printWindow.print();
                printWindow.close();
            }, 500);
        }
    </script>
</div>
