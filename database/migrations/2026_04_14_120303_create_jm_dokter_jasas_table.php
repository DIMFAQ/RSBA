<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW view_jm_dokter_jasa AS
            SELECT 
                j.jm_prosentase_id,
                j.dokter,
                j.status AS status_jasa,
                j.jasa,
                COALESCE(SUM(d.jumlah), 0) AS jumlah,
                CASE MAX(d.status)
                    WHEN 'sp'      THEN 'Spesialis'
                    WHEN 'um'      THEN 'Umum'
                    WHEN 'an'      THEN 'Anastesi'
                    WHEN 'dpjp'    THEN 'DPJP'
                    WHEN 'um_s'    THEN 'Umum Sertifikat'
                    WHEN 'sppdkgh' THEN 'Sp.PD, KGH'
                    WHEN 'dpjp_hd' THEN 'DPJP HD'
                    ELSE MAX(d.status)
                END AS status_label,
                MAX(d.status) AS status_code
            FROM jm_jasa j
            LEFT JOIN jm_prosentase p
                ON j.jm_prosentase_id = p.id
            LEFT JOIN jm_dokter d
                ON p.jm_pasien_id = d.jm_pasien_id
                AND j.dokter = d.dokter
            GROUP BY
                j.jm_prosentase_id,
                j.dokter,
                j.status,
                j.jasa
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_jm_dokter_jasa");
    }
};
