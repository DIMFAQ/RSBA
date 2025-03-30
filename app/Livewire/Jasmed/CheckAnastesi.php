<?php

namespace App\Livewire\Jasmed;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\JmDokter;
use App\Models\JmPasien;
use Livewire\Attributes\Lazy;
use Illuminate\Support\Facades\DB;
use TallStackUi\Traits\Interactions;

#[Lazy(isolate: false)]
class CheckAnastesi extends Component
{
    use Interactions;

    public $anastesi = [];

    public function updatedanastesi($value, $key): void
    {
        $data = [
            'jm_pasien_id' => $key,
            'dokter' => $value,
            'jumlah' => 1,
            'status' => 'an'
        ];

        DB::beginTransaction();
        try {
            JmDokter::create($data);
            DB::commit();

            $this->toast()
                ->success('Behasil', 'Dokter anastesi berhasil diupdate.')
                ->send();
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->toast()
                ->error('Gagal', 'Error : ' . $e->getMessage())
                ->send();
        }
    }

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
            ->whereBetween('tgl_checkout', [Carbon::parse($lastData['tgl_checkout'])->subMonths(3), $lastData['tgl_checkout']])
            ->whereIn('kelompok', ['ri_sc', 'ri_op', 'ri_mata'])
            ->orderBy('tgl_checkout', 'desc')
            ->get();

        return view('livewire.jasmed.check-anastesi', ['pasien' => $pasien]);
    }
}
