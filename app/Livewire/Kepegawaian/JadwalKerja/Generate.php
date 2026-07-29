<?php

namespace App\Livewire\Kepegawaian\JadwalKerja;

use App\Models\Sdm\Bagian;
use App\Models\Sdm\JadwalKerja;
use App\Models\Sdm\JadwalKerjaDetail;
use App\Models\Sdm\JadwalShift;
use App\Models\Sdm\Karyawan;
use App\Enums\KategoriKerja;
use App\Enums\StatusKaryawan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;
use TallStackUi\Traits\Interactions;

class Generate extends Component
{
    use Interactions;

    public $ruangan_id;
    public $bulan;
    public $tahun;

    public function rules()
    {
        return [
            'ruangan_id' => 'required|exists:ruangan,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2024|max:2099',
        ];
    }

    public function mount()
    {
        $this->authorize('generate', JadwalKerja::class);
        $this->bulan = date('n');
        $this->tahun = date('Y');
    }

    public function submit()
    {
        $this->validate();

        $this->authorize('generate', JadwalKerja::class);

        $karyawanId = Auth::user()->karyawan_id;

        $user = Auth::user();
        $isKoorDokter = $user?->isKoordinatorDokter() ?? false;
        $isKoorKaryawan = $user?->isKoordinatorKaryawan() ?? false;
        $tipeJadwal = $isKoorDokter ? 'dokter' : 'karyawan';

        // Cek apakah jadwal sudah ada
        $exists = JadwalKerja::where('ruangan_id', $this->ruangan_id)
            ->where('bulan', $this->bulan)
            ->where('tahun', $this->tahun)
            ->where('tipe', $tipeJadwal)
            ->exists();

        if ($exists) {
            $this->toast()->error('Gagal', 'Jadwal kerja untuk ruangan, periode, dan kelompok ini sudah pernah dibuat.')->send();
            return;
        }

        $karyawansQuery = Karyawan::where('ruangan_id', $this->ruangan_id)
            ->whereNull('resign_at');

        if ($isKoorDokter) {
            $karyawansQuery->whereHas('dokterRecord');
        } elseif ($isKoorKaryawan) {
            $karyawansQuery->whereDoesntHave('dokterRecord');
        }

        $karyawans = $karyawansQuery->get();

        $hasReguler = $karyawans->contains(function ($k) {
            return $k->kategori_kerja === KategoriKerja::REGULER;
        });

        $shiftReguler = null;
        if ($hasReguler) {
            // Cek apakah ada shift REGULER
            $shiftReguler = JadwalShift::where('kode', 'REGULER')->where('aktif', true)->first();
            if (!$shiftReguler) {
                $this->toast()->error('Gagal', 'Ruangan ini memiliki pegawai reguler, namun Master Shift dengan kode REGULER belum dibuat atau tidak aktif.')->send();
                return;
            }
        }

        try {
            DB::beginTransaction();

            $jadwalKerja = JadwalKerja::create([
                'ruangan_id' => $this->ruangan_id,
                'bulan' => $this->bulan,
                'tahun' => $this->tahun,
                'tipe' => $tipeJadwal,
                'status' => 'draft',
                'dibuat_oleh' => $karyawanId,
            ]);

            $daysInMonth = Carbon::create($this->tahun, $this->bulan, 1)->daysInMonth;
            
            $details = [];
            foreach ($karyawans as $karyawan) {
                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $date = Carbon::create($this->tahun, $this->bulan, $d);
                    
                    $shiftId = null;
                    if ($karyawan->kategori_kerja === KategoriKerja::REGULER && $shiftReguler) {
                        // Senin - Jumat (1 - 5)
                        if ($date->dayOfWeekIso >= 1 && $date->dayOfWeekIso <= 5) {
                            $shiftId = $shiftReguler->id;
                        }
                    }

                    $details[] = [
                        'jadwal_kerja_id' => $jadwalKerja->id,
                        'karyawan_id' => $karyawan->id,
                        'shift_id' => $shiftId,
                        'tanggal' => $date->format('Y-m-d'),
                        'status_kehadiran' => 'belum_dicek',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Bulk insert
            foreach (array_chunk($details, 500) as $chunk) {
                JadwalKerjaDetail::insert($chunk);
            }

            DB::commit();

            $this->dispatch('jadwal-kerja-generated');
            $this->dispatch('close-modal', id: 'generate-jadwal-kerja');

            $this->toast()->success('Berhasil', 'Draf Jadwal Kerja berhasil di-generate.')->send();
            
            return redirect()->route('kepegawaian.jadwal-kerja.kelola', ['id' => $jadwalKerja->id]);

        } catch (Throwable $e) {
            DB::rollBack();
            $this->toast()->error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage())->send();
        }
    }

    public function render()
    {
        $bulanOptions = collect(range(1, 12))->map(fn($m) => [
            'value' => $m,
            'label' => date('F', mktime(0, 0, 0, $m, 1))
        ])->toArray();

        $tahunOptions = collect(range(date('Y'), date('Y') + 2))->map(fn($y) => [
            'value' => $y,
            'label' => (string) $y
        ])->toArray();

<<<<<<< HEAD
        $ruanganOptions = \App\Models\Ruangan::where('is_active', true)->select('id', 'nama')->get()->map(fn($item) => ['value' => $item->id, 'label' => $item->nama])->toArray();
=======
        $user = Auth::user();
        $ruanganQuery = \App\Models\Ruangan::where('is_active', true);
        
        if ($user && !$user->hasRole(['Super-Admin', 'Staff-SDM'])) {
            $ruanganIds = $user->getRuanganKoordinatorIds() ?? [];
            if ($user->karyawan?->ruangan_id) {
                $ruanganIds[] = $user->karyawan->ruangan_id;
            }
            $ruanganQuery->whereIn('id', array_unique($ruanganIds));
        }

        $ruanganOptions = $ruanganQuery->select('id', 'nama')->get()->map(fn($item) => ['value' => $item->id, 'label' => $item->nama])->toArray();
>>>>>>> 8685ac3 (feat(sdm): pemisahan sdm_jadwal_kerja tipe karyawan dan dokter)

        return view('livewire.kepegawaian.jadwal-kerja.generate', [
            'ruanganOptions' => $ruanganOptions,
            'bulanOptions' => $bulanOptions,
            'tahunOptions' => $tahunOptions,
        ]);
    }
}
