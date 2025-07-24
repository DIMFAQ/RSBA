<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TemplateImportJasa implements FromArray, WithHeadings
{

    protected $template;

    function __construct($template)
    {
        $this->template = $template;
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $data = [];

        switch ($this->template) {
            case 'txt':
                $data = [
                    [
                        'Kelas Rawat (KELAS_RAWAT)',
                        'Tgl Checkin (ADMISSION_DATE) YYYY-MM-DD',
                        'tgl_checkout (DISCHARGE_DATE) YYYY-MM-DD',
                        'Diaglist',
                        'Proclist',
                        'Deskripsi Inacbg (DESKRIPSI_INACBG)',
                        'Klaim (TOTAL_TARIF)',
                        'Total Tarif RS (TARIF_RS)',
                        'Nama Pasien',
                        'No Rekmedis',
                        'Nama Dokter DPJP',
                        'No Sep',
                        'Layanan (ranap/rajal)',
                        'Cara Bayar (bpjs/jkmd/tunai)'
                    ]
                ];
                break;
            case 'disetujui':
                $data = [
                    [
                        'No. Sep',
                        'Nominal Disetujui',
                        'Diisi Dg Pembayaran Ke : 1/2/3'
                    ]
                ];
                break;

            case 'visit';
                $data = [
                    [
                        'No Rekmedis',
                        'Tgl Checkout (YYYY-MM-DD)',
                        'Nama Dokter Umum 1',
                        'Jumlah Visit Dokter Umum 1',
                        'Nama Dokter Umum 2',
                        'Jumlah Visit Dokter Umum 2 (jika ada nama dokter umum lainnya, silahkan insert dengan header umum dan jumlah_umum lagi)',
                        'Nama Dokter Spesialis 1',
                        'Jumlah Visit Dokter Spesialis 1',
                        'Nama Dokter Spesialis 2',
                        'Jumlah Visit Dokter Spesialis 2 (jika ada nama dokter spesialis lainnya, silahkan insert dengan header spesialis dan jumlah_spesialis lagi)',
                        'Nama Dokter Operator',
                        'Nama Dokter Anastesi'
                    ]
                ];
                break;

            case 'rincian':
                # code...
                break;
            case 'rincian_inacbg':
                $data = [
                    [
                        'Tgl Checkout',
                        'No Rekmedis',
                        'SEP',
                        'Totaal Chosaring',
                        'Total Prosedur Non Bedah',
                        'Total Prosedur Bedah',
                        'Total Konsultasi',
                        'Total Tenaga Ahli',
                        'Total Keperawatan',
                        'Total Penunjang',
                        'Total Radiologi',
                        'Total Laboratorium',
                        'Total Pelayanan Darah',
                        'Total Rehabilitasi',
                        'Total Kamar akomodasi',
                        'Total Kamar Intensif',
                        'Total Obat',
                        'Total Alkes',
                        'Total Bmhp',
                        'Total Sewa Alat',
                        'Total Obat Kronis',
                        'Total Obat Kemo',
                    ]
                ];
                break;

            case 'ranap':
                $data =  [
                    [
                        'No Sep',
                        'Kelompok Jasa (ri_no/ri_op/ri_mata/ri_partus/ri_sc/ri_curet/ri_hd)',
                        'Nama Dokter Sppdkgh',
                        'Nama Dokter Umum Sertifikat',
                        'Nama Dokter DPJP HD'
                    ]
                ];
                break;

            case 'rajal':
                $data = [
                    [
                        'No Sep',
                        'Kelompok Jasa (rj_sp/rj_um/rj_mata/rj_hd)',
                        'Nama Dokter sppdkgh',
                        'Nama Dokter Umum Sertifikat'
                    ]
                ];
                break;

            default:
                # code...
                break;
        }

        return $data;
    }


    public function headings(): array
    {

        switch ($this->template) {
            case 'txt':
                return [
                    'kelas_rawat',
                    'tgl_checkin',
                    'tgl_checkout',
                    'diaglist',
                    'proclist',
                    'deskripsi_inacbg',
                    'klaim',
                    'tarif_rs',
                    'nama_pasien',
                    'no_rekmedis',
                    'dpjp',
                    'sep',
                    'layanan',
                    'cabar'
                ];
                break;
            case 'disetujui':
                return [
                    'sep',
                    'disetujui',
                    'batch'
                ];
                break;

            case 'visit';
                return [
                    'no_rekmedis',
                    'tgl_checkout',
                    'umum',
                    'jumlah_umum',
                    'umum',
                    'jumlah_umum',
                    'spesialis',
                    'jumlah_spesialis',
                    'spesialis',
                    'jumlah_spesialis',
                    'operator',
                    'span'
                ];
                break;

            case 'rincian':
                return [];
                break;

            case 'rincian_inacbg':
                return [
                    'tgl_checkout',
                    'no_rekmedis',
                    'sep',
                    'chosaring',
                    'prosedur_non_bedah',
                    'prosedur_bedah',
                    'konsultasi',
                    'tenaga_ahli',
                    'keperawatan',
                    'penunjang',
                    'radiologi',
                    'laboratorium',
                    'pelayanan_darah',
                    'rehabilitasi',
                    'kamar_akomodasi',
                    'rawat_intensif',
                    'obat',
                    'alkes',
                    'bmhp',
                    'sewa_alat',
                    'obat_kronis',
                    'obat_kemo',
                ];
                break;

            case 'ranap':
                return [
                    'sep',
                    'kelompok',
                    'sppdkgh',
                    'umum_sertifikat',
                    'dpjp_hd'
                ];
                break;

            case 'rajal':
                return [
                    'sep',
                    'kelompok',
                    'sppdkgh',
                    'umum_sertifikat'
                ];
                break;

            default:
                return [];
                break;
        }
    }
}
