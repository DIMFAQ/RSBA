<?php

namespace App\Livewire\Jasmed;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\JmPasien;
use Livewire\Attributes\Lazy;

#[Lazy(isolate: false)]
class CheckAnastesi extends Component
{
    // TODO: Action simpan dokter anastesi

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
            'tgl_checkout',
            'dpjp',
            'sep',
            'kelompok'
        ])
            ->leftJoin('jm_dokter', function ($join) {
                $join->on('jm_pasien.id', '=', 'jm_dokter.jm_pasien_id')
                    ->where('jm_dokter.status', '=', 'an');
            })
            ->whereNull('jm_dokter.id')
            ->where('layanan', 'ranap')
            ->where('cabar', 'bpjs')
            ->whereBetween('tgl_checkout', [Carbon::parse($lastData['tgl_checkout'])->subDays(90), $lastData['tgl_checkout']])
            ->whereIn('kelompok', ['ri_sc', 'ri_op', 'ri_mata'])
            ->orderBy('tgl_checkout', 'desc')
            ->get();

        return view('livewire.jasmed.check-anastesi', ['pasien' => $pasien]);
    }
}
