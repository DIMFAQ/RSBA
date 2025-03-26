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
            'nama_pasien',
            'no_rekmedis',
            'tgl_checkin',
            'tgl_checkout',
            'dpjp',
            'sep',
            'kelompok'
        ])
            ->leftJoin('jm_dokter', 'jm_pasien.id', '=', 'jm_dokter.jm_pasien_id')
            ->whereNull('jm_dokter.id')
            ->where('layanan', 'ranap')
            ->where('cabar', 'bpjs')
            ->where('disetujui', '>', 0)
            ->whereBetween('tgl_checkout', [
                Carbon::parse($lastData['tgl_checkout'])->startOfMonth(),
                Carbon::parse($lastData['tgl_checkout'])->endOfMonth()
            ])
            ->get();

        return view('livewire.jasmed.check-visit', ['pasien' => $pasien]);
    }
}
