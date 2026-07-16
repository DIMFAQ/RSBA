<?php

namespace App\Livewire\Kepegawaian\Absensi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\Sdm\JadwalKerjaDetail;
use App\Models\Sdm\Karyawan;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use TallStackUi\Traits\Interactions;

#[Title('Rekap Absensi')]
class Rekap extends Component
{
    use Interactions;
    use WithPagination;

    public $bulan;
    public $tahun;
    public $ruangan_id = null;
    public $karyawan_id = null;
    public $tanggal_spesifik = null;
    public $mode = 'bulanan'; // 'bulanan', 'harian'

    // Properties for Manual Correction
    public $editingRecordId = null;
    public $editStatus = '';
    public $editAbsenMasuk = '';
    public $editAbsenKeluar = '';
    public $editCatatan = '';
    public $showEditModal = false;

    // Reset pagination when filter updates
<<<<<<< HEAD
    public function updatedRuanganId() { $this->resetPage('dailyPage'); }
    public function updatedKaryawanId() { $this->resetPage('dailyPage'); }
    public function updatedTanggalSpesifik() { $this->resetPage('dailyPage'); }
    public function updatedMode() { $this->resetPage('dailyPage'); }
    public function updatedBulan() { $this->resetPage('dailyPage'); }
    public function updatedTahun() { $this->resetPage('dailyPage'); }
=======
    public function updatedRuanganId() { $this->resetPage('dailyPage'); $this->resetPage('rekapKaryawanPage'); }
    public function updatedKaryawanId() { $this->resetPage('dailyPage'); $this->resetPage('rekapKaryawanPage'); }
    public function updatedTanggalSpesifik() { $this->resetPage('dailyPage'); $this->resetPage('rekapKaryawanPage'); }
    public function updatedMode() { $this->resetPage('dailyPage'); $this->resetPage('rekapKaryawanPage'); }
    public function updatedBulan() { $this->resetPage('dailyPage'); $this->resetPage('rekapKaryawanPage'); }
    public function updatedTahun() { $this->resetPage('dailyPage'); $this->resetPage('rekapKaryawanPage'); }
    public function updatedStatusFilter() { $this->resetPage('dailyPage'); $this->resetPage('rekapKaryawanPage'); }
>>>>>>> 76d7dfa (feat(absensi): redesign rekap tab grid cards, daily overtime details table, and custom paginators)

    public function editRecord($id)
    {
        $record = JadwalKerjaDetail::findOrFail($id);
        $this->editingRecordId = $id;
        $this->editStatus = $record->status_kehadiran instanceof \App\Enums\StatusKehadiran 
            ? $record->status_kehadiran->value 
            : $record->status_kehadiran;
        
        $this->editAbsenMasuk = $record->absen_masuk_at 
            ? Carbon::parse($record->absen_masuk_at)->format('Y-m-d\TH:i') 
            : '';
        $this->editAbsenKeluar = $record->absen_keluar_at 
            ? Carbon::parse($record->absen_keluar_at)->format('Y-m-d\TH:i') 
            : '';
            
        $this->editCatatan = $record->catatan;
        $this->showEditModal = true;
    }

    public function saveCorrection()
    {
        $record = JadwalKerjaDetail::findOrFail($this->editingRecordId);
        
        $record->update([
            'status_kehadiran' => $this->editStatus ?: 'belum_dicek',
            'absen_masuk_at' => $this->editAbsenMasuk ?: null,
            'absen_keluar_at' => $this->editAbsenKeluar ?: null,
            'catatan' => $this->editCatatan ?: null,
            'updated_by' => auth()->id() ?? 1,
        ]);

        $this->showEditModal = false;
        $this->toast()->success('Berhasil', 'Koreksi absensi berhasil disimpan.')->send();
    }

    public function mount()
    {
        abort_unless(
            auth()->user()?->hasRole(['Super-Admin', 'Staff-SDM']),
            403,
            'Hanya Staff SDM yang dapat mengakses Halaman Rekap Absensi.'
        );
        $this->bulan = (int) date('m');
        $this->tahun = (int) date('Y');
    }

    public function render()
    {
        $ruangans = Ruangan::orderBy('nama')->get();
        $karyawans = Karyawan::orderBy('nama')->get();

        // 1. Build Base Query without relations to avoid N+1 and Memory Leaks during aggregation
        $baseQuery = JadwalKerjaDetail::query()
            ->whereNotNull('status_kehadiran');

        if ($this->mode === 'bulanan') {
            $baseQuery->whereMonth('tanggal', $this->bulan)
                      ->whereYear('tanggal', $this->tahun);
        } else {
            if ($this->tanggal_spesifik) {
                $baseQuery->whereDate('tanggal', $this->tanggal_spesifik);
            } else {
                $baseQuery->whereDate('tanggal', date('Y-m-d'));
            }
        }

        if ($this->ruangan_id) {
            $baseQuery->whereHas('jadwalKerja', function ($q) {
                $q->where('ruangan_id', $this->ruangan_id);
            });
        }

        if ($this->karyawan_id) {
            $baseQuery->where('karyawan_id', $this->karyawan_id);
        }

<<<<<<< HEAD
        // 2. Memory-efficient Overall Summary Aggregation
=======
        // 2. Fetch the paginated Karyawan list
        $karyawanQuery = Karyawan::query();
        if ($this->karyawan_id) {
            $karyawanQuery->where('id', $this->karyawan_id);
        }
        if ($this->ruangan_id) {
            $karyawanQuery->where('ruangan_id', $this->ruangan_id);
        }
        if ($allowedRuanganIds !== null) {
            $karyawanQuery->whereIn('ruangan_id', $allowedRuanganIds);
        }

        $paginatedKaryawans = $karyawanQuery->orderBy('nama')
            ->paginate(15, ['*'], 'rekapKaryawanPage');

        if ($paginatedKaryawans->currentPage() > 1 && $paginatedKaryawans->currentPage() > $paginatedKaryawans->lastPage()) {
            $this->setPage(max(1, $paginatedKaryawans->lastPage()), 'rekapKaryawanPage');
            $paginatedKaryawans = $karyawanQuery->orderBy('nama')
                ->paginate(15, ['*'], 'rekapKaryawanPage');
        }

        $currentPageKaryawanIds = $paginatedKaryawans->pluck('id')->toArray();

        // 3. Overall Summary Calculations (for the top cards)
>>>>>>> 76d7dfa (feat(absensi): redesign rekap tab grid cards, daily overtime details table, and custom paginators)
        $summary = [
            'hadir' => 0,
            'terlambat' => 0,
            'pulang_cepat' => 0,
            'tidak_hadir' => 0,
            'cuti' => 0,
            'izin' => 0,
            'perlu_verifikasi' => 0,
            'total_overtime_menit' => 0,
        ];

<<<<<<< HEAD
        $summaryRaw = (clone $baseQuery)
            ->select('status_kehadiran', DB::raw('count(*) as total'))
            ->groupBy('status_kehadiran')
            ->get();

        foreach ($summaryRaw as $row) {
            $statusVal = $row->status_kehadiran instanceof \App\Enums\StatusKehadiran 
                ? $row->status_kehadiran->value 
                : $row->status_kehadiran;
            if (isset($summary[$statusVal])) {
                $summary[$statusVal] = (int) $row->total;
            }
        }

        // 3. Memory-efficient Per-Employee Summary Aggregation
        $rekapRaw = (clone $baseQuery)
            ->select('karyawan_id', 'status_kehadiran', DB::raw('count(*) as total'))
            ->groupBy('karyawan_id', 'status_kehadiran')
            ->get();

        $rekapKaryawan = [];
        foreach ($rekapRaw as $row) {
            $kId = $row->karyawan_id;
            $statusVal = $row->status_kehadiran instanceof \App\Enums\StatusKehadiran 
                ? $row->status_kehadiran->value 
                : $row->status_kehadiran;

            if (!isset($rekapKaryawan[$kId])) {
                $rekapKaryawan[$kId] = [
                    'hadir' => 0,
                    'terlambat' => 0,
                    'pulang_cepat' => 0,
                    'tidak_hadir' => 0,
                    'cuti' => 0,
                    'izin' => 0,
                    'perlu_verifikasi' => 0,
                ];
=======
        // Overall raw details select (for overall summary counts and overtime sum)
        $overallRaw = (clone $baseQuery)
            ->leftJoin('sdm_jadwal_shift', 'sdm_jadwal_kerja_detail.shift_id', '=', 'sdm_jadwal_shift.id')
            ->select([
                'sdm_jadwal_kerja_detail.status_kehadiran',
                'sdm_jadwal_kerja_detail.catatan',
                'sdm_jadwal_kerja_detail.absen_masuk_at',
                'sdm_jadwal_kerja_detail.absen_keluar_at',
                'sdm_jadwal_kerja_detail.shift_id',
                'sdm_jadwal_kerja_detail.tanggal',
                'sdm_jadwal_shift.jam_masuk as shift_jam_masuk',
                'sdm_jadwal_shift.jam_keluar as shift_jam_keluar',
                'sdm_jadwal_shift.lintas_hari as shift_lintas_hari'
            ])
            ->toBase()
            ->get();

        foreach ($overallRaw as $row) {
            $statusVal = $row->status_kehadiran;
            if (isset($summary[$statusVal])) {
                $summary[$statusVal]++;
            }

            // Parse minutes from catatan
            $menit = 0;
            if ($statusVal === 'terlambat' && $row->catatan) {
                if (preg_match('/Terlambat (-?\d+) menit/i', $row->catatan, $matches)) {
                    $menit = abs((int) $matches[1]);
                    $summary['menit_terlambat'] += $menit;
                }
            } elseif ($statusVal === 'pulang_cepat' && $row->catatan) {
                if (preg_match('/Pulang cepat (-?\d+) menit/i', $row->catatan, $matches)) {
                    $menit = abs((int) $matches[1]);
                    $summary['menit_pulang_cepat'] += $menit;
                }
            }

            // Overtime Calculation
            if ($row->absen_masuk_at && $row->absen_keluar_at) {
                $masuk = Carbon::parse($row->absen_masuk_at);
                $keluar = Carbon::parse($row->absen_keluar_at);

                if ($row->shift_id && $row->shift_jam_keluar) {
                    $jamKeluar = Carbon::parse($row->shift_jam_keluar);
                    $targetCheckout = Carbon::parse(Carbon::parse($row->tanggal)->format('Y-m-d') . ' ' . $jamKeluar->format('H:i:s'));
                    if ($row->shift_lintas_hari || $jamKeluar->lt(Carbon::parse($row->shift_jam_masuk))) {
                        $targetCheckout->addDay();
                    }
                    if ($keluar->gt($targetCheckout)) {
                        $summary['total_overtime_menit'] += abs($keluar->diffInMinutes($targetCheckout));
                    }
                } else {
                    $summary['total_overtime_menit'] += abs($keluar->diffInMinutes($masuk));
                }
            }
        }

        // 4. Detailed summary specifically for current page Karyawans
        $rekapRaw = (clone $baseQuery)
            ->whereIn('karyawan_id', $currentPageKaryawanIds)
            ->leftJoin('sdm_jadwal_shift', 'sdm_jadwal_kerja_detail.shift_id', '=', 'sdm_jadwal_shift.id')
            ->select([
                'sdm_jadwal_kerja_detail.id',
                'sdm_jadwal_kerja_detail.karyawan_id',
                'sdm_jadwal_kerja_detail.status_kehadiran',
                'sdm_jadwal_kerja_detail.catatan',
                'sdm_jadwal_kerja_detail.absen_masuk_at',
                'sdm_jadwal_kerja_detail.absen_keluar_at',
                'sdm_jadwal_kerja_detail.shift_id',
                'sdm_jadwal_kerja_detail.tanggal',
                'sdm_jadwal_shift.jam_masuk as shift_jam_masuk',
                'sdm_jadwal_shift.jam_keluar as shift_jam_keluar',
                'sdm_jadwal_shift.lintas_hari as shift_lintas_hari'
            ])
            ->toBase()
            ->get();

        $rekapKaryawan = [];
        // Pre-initialize rekap array for all paginated employees to keep order
        foreach ($paginatedKaryawans as $kar) {
            $rekapKaryawan[$kar->id] = [
                'karyawan' => $kar,
                'hadir' => 0,
                'terlambat' => 0,
                'menit_terlambat' => 0,
                'pulang_cepat' => 0,
                'menit_pulang_cepat' => 0,
                'tidak_hadir' => 0,
                'cuti' => 0,
                'izin' => 0,
                'perlu_verifikasi' => 0,
                'total_overtime_menit' => 0,
                'overtime_details' => [],
            ];
        }

        foreach ($rekapRaw as $row) {
            $kId = $row->karyawan_id;
            $tanggalObj = Carbon::parse($row->tanggal);
            $statusVal = $row->status_kehadiran;

            if (!isset($rekapKaryawan[$kId])) {
                continue;
>>>>>>> 76d7dfa (feat(absensi): redesign rekap tab grid cards, daily overtime details table, and custom paginators)
            }
            if (isset($rekapKaryawan[$kId][$statusVal])) {
<<<<<<< HEAD
                $rekapKaryawan[$kId][$statusVal] = (int) $row->total;
=======
                $rekapKaryawan[$kId][$statusVal]++;
            }

            // Parse minutes from catatan
            $menit = 0;
            if ($statusVal === 'terlambat' && $row->catatan) {
                if (preg_match('/Terlambat (-?\d+) menit/i', $row->catatan, $matches)) {
                    $menit = abs((int) $matches[1]);
                }
            } elseif ($statusVal === 'pulang_cepat' && $row->catatan) {
                if (preg_match('/Pulang cepat (-?\d+) menit/i', $row->catatan, $matches)) {
                    $menit = abs((int) $matches[1]);
                }
            }

            if ($statusVal === 'terlambat') {
                $rekapKaryawan[$kId]['menit_terlambat'] += $menit;
            } elseif ($statusVal === 'pulang_cepat') {
                $rekapKaryawan[$kId]['menit_pulang_cepat'] += $menit;
>>>>>>> 76d7dfa (feat(absensi): redesign rekap tab grid cards, daily overtime details table, and custom paginators)
            }

            // Overtime Calculation
            $overtimeMenit = 0;
            $overtimeKeterangan = '';
            if ($row->absen_masuk_at && $row->absen_keluar_at) {
                $masuk = Carbon::parse($row->absen_masuk_at);
                $keluar = Carbon::parse($row->absen_keluar_at);

                if ($row->shift_id && $row->shift_jam_keluar) {
                    $jamKeluar = Carbon::parse($row->shift_jam_keluar);
                    $targetCheckout = Carbon::parse($tanggalObj->format('Y-m-d') . ' ' . $jamKeluar->format('H:i:s'));
                    if ($row->shift_lintas_hari || $jamKeluar->lt(Carbon::parse($row->shift_jam_masuk))) {
                        $targetCheckout->addDay();
                    }
                    if ($keluar->gt($targetCheckout)) {
                        $overtimeMenit = abs($keluar->diffInMinutes($targetCheckout));
                        $overtimeKeterangan = "Pulang terlambat";
                    }
                } else {
                    $overtimeMenit = abs($keluar->diffInMinutes($masuk));
                    $overtimeKeterangan = "Tugas hari Libur/OFF";
                }
            }

            if ($overtimeMenit > 0) {
                $rekapKaryawan[$kId]['total_overtime_menit'] += $overtimeMenit;
                $rekapKaryawan[$kId]['overtime_details'][] = [
                    'tanggal' => $tanggalObj->translatedFormat('d M Y'),
                    'menit' => $overtimeMenit,
                    'keterangan' => $overtimeKeterangan
                ];
            }
        }

<<<<<<< HEAD
        // Eager-hydrate Karyawan models in a single query
        $karyawanIds = array_keys($rekapKaryawan);
        $karyawansMap = Karyawan::whereIn('id', $karyawanIds)->get()->keyBy('id');
        foreach ($rekapKaryawan as $kId => &$rk) {
            $rk['karyawan'] = $karyawansMap->get($kId);
        }
        unset($rk);

        // 4. Paginated Daily Records (limited to 15 per page to save memory)
        $records = (clone $baseQuery)
=======
        // 5. Paginated Daily Records (limited to 15 per page to save memory)
        $recordsQuery = clone $baseQuery;
        if ($this->statusFilter) {
            $recordsQuery->where('status_kehadiran', $this->statusFilter);
        }

        $records = $recordsQuery
>>>>>>> 76d7dfa (feat(absensi): redesign rekap tab grid cards, daily overtime details table, and custom paginators)
            ->with(['karyawan', 'shift', 'karyawan.ruangan', 'jadwalKerja', 'jadwalKerja.ruangan'])
            ->orderBy('tanggal', 'desc')
            ->paginate(15, ['*'], 'dailyPage');

        if ($records->currentPage() > 1 && $records->currentPage() > $records->lastPage()) {
            $this->setPage(max(1, $records->lastPage()), 'dailyPage');
            $records = $recordsQuery
                ->with(['karyawan', 'shift', 'karyawan.ruangan', 'jadwalKerja', 'jadwalKerja.ruangan'])
                ->orderBy('tanggal', 'desc')
                ->paginate(15, ['*'], 'dailyPage');
        }

        return view('livewire.kepegawaian.absensi.rekap', [
            'ruangans' => $ruangans,
            'karyawans' => $karyawans,
            'records' => $records,
            'summary' => $summary,
            'rekapKaryawan' => $rekapKaryawan,
            'paginatedKaryawans' => $paginatedKaryawans
        ]);
    }
}
