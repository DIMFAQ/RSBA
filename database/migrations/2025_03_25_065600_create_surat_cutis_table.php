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
        Schema::create('surat_cuti', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karyawan_id');
            $table->string('no_surat', 10)->unique();
            $table->date('tgl_surat');
            $table->date('tgl_mulai');
            $table->date('tgl_akhir');
            $table->text('tgl_cuti');
            $table->integer('lama_cuti');
            $table->enum('urgensi', ['tahunan', 'besar', 'sakit', 'bersalin', 'penting', 'lain']);
            $table->text('keterangan')->nullable();
            $table->text('alamat')->nullable();
            $table->enum('status', ['proses', 'disetujui', 'ditolak'])->default('proses');
            $table->text('acc')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('karyawan_id')->references('id')->on('sdm_karyawan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_cuti');
    }
};
