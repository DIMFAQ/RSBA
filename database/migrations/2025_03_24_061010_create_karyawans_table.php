<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sdm_karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 25)->unique();
            $table->string('nik', 16);
            $table->string('nama', 50);
            $table->string('jk', 1)->default('L')->nullable();
            $table->string('tempat_lahir', 30)->nullable();
            $table->date('tgl_lahir');
            $table->string('hp', 15);
            $table->string('hp2', 15)->nullable();
            $table->string('status_pernikahan', 15)->default('belum_menikah');
            $table->string('gelar_depan', 10)->nullable();
            $table->string('gelar_belakang', 10)->nullable();
            $table->string('prov', 50);
            $table->string('kab', 50);
            $table->string('kec', 50);
            $table->string('desa', 50);
            $table->string('alamat', 225);
            $table->string('dom_prov', 50)->nullable();
            $table->string('dom_kab', 50)->nullable();
            $table->string('dom_kec', 50)->nullable();
            $table->string('dom_desa', 50)->nullable();
            $table->string('dom_alamat', 255)->nullable();
            $table->enum('agama', ['islam', 'kristen', 'katolik', 'hindu', 'budha', 'khonghucu']);
            $table->string('suku', 25)->nullable();
            $table->enum('status', ['kontrak', 'tetap', 'mitra', 'bantuan', 'magang'])->default('kontrak');
            $table->date('tgl_masuk');
            $table->string('resign')->nullable();
            $table->date('resign_at')->nullable();
            $table->string('no_sip', 60)->nullable();
            $table->date('sip_berakhir')->nullable();
            $table->string('npwp', 50)->nullable();
            $table->integer('cuti')->default(0);
            $table->string('foto', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdm_karyawan');
    }
};
