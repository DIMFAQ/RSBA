<?php

namespace App\Livewire\Jasmed;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\JmPasien;
use Livewire\Attributes\Lazy;

#[Lazy(isolate: false)]
class CheckVisit extends Component
{
    public function render()
    {
        $lastData = JmPasien::select('tgl_checkout')
            ->where('layanan', 'ranap')
            ->where('cabar', 'bpjs')
            ->where('disetujui', '>', 0)
            ->orderBy('tgl_checkout', 'desc')
            ->first();

        $pasien = JmPasien::select([
            'jm_pasien.id',
            'jm_pasien.nama_pasien',
            'jm_pasien.no_rekmedis',
            'jm_pasien.tgl_checkin',
            'jm_pasien.tgl_checkout',
            'jm_pasien.dpjp',
            'jm_pasien.sep',
            'jm_pasien.kelompok'
        ])
            ->leftJoin('jm_dokter', 'jm_pasien.id', '=', 'jm_dokter.jm_pasien_id')
            ->whereNull('jm_dokter.id')
            ->where('jm_pasien.layanan', 'ranap')
            ->where('jm_pasien.cabar', 'bpjs')
            ->where('jm_pasien.disetujui', '>', 0)
            ->whereBetween('jm_pasien.tgl_checkout', [
                Carbon::parse($lastData['tgl_checkout'])->startOfMonth(),
                Carbon::parse($lastData['tgl_checkout'])->endOfMonth()
            ])
            ->get();

        return view('livewire.jasmed.check-visit', ['pasien' => $pasien]);
    }
}
