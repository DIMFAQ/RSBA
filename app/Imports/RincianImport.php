<?php

namespace App\Imports;

use App\Models\JmPasien;
use App\Models\JmRincian;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RincianImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {


        if (!empty($row['sep'])) {
            $pasien = JmPasien::where('sep', $row['sep'])->first();
        } else {
            $pasien = JmPasien::where('no_rekmedis', $row['no_rekmedis'])
                ->where('tgl_checkout', $row['tgl_checkout'])
                ->first();
        }

        return
            DB::transaction(function () use ($row, $pasien) {
                JmRincian::updateOrCreate(
                    [
                        'jm_pasien_id' => $pasien->id
                    ],
                    [
                        'chosaring' => $row['chosaring'] ?? 0,
                        'prosedur_non_bedah' => $row['prosedur_non_bedah'],
                        'prosedur_bedah' => $row['prosedur_bedah'],
                        'konsultasi' => $row['konsultasi'],
                        'tenaga_ahli' => $row['tenaga_ahli'],
                        'keperawatan' => $row['keperawatan'],
                        'penunjang' => $row['penunjang'],
                        'radiologi' => $row['radiologi'],
                        'laboratorium' => $row['laboratorium'],
                        'pelayanan_darah' => $row['pelayanan_darah'],
                        'rehabilitasi' => $row['rehabilitasi'],
                        'kamar_akomodasi' => $row['kamar_akomodasi'],
                        'rawat_intensif' => $row['rawat_intensif'],
                        'obat' => $row['obat'],
                        'alkes' => $row['alkes'],
                        'bmhp' => $row['bmhp'],
                        'sewa_alat' => $row['sewa_alat'],
                        'obat_kronis' => $row['obat_kronis'],
                        'obat_kemo' => $row['obat_kemo'],
                    ]
                );
            });

        // return new JmRincian([]);
    }
}
