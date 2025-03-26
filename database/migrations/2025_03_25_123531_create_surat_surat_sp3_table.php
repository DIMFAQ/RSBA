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
        Schema::create('surat_sp3', function (Blueprint $table) {
            $table->id();
            $table->integer('no');
            $table->year('tahun');
            $table->date('tgl');
            $table->string('rekanan', 25);
            $table->enum('bayar', ['tunai', 'trf', 'giro']);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('disetujui');
            $table->string('jabatan');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('disetujui')->references('id')->on('sdm_karyawan')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_sp3');
    }
};
