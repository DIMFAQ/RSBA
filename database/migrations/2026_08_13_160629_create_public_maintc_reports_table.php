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
        Schema::create('public_maintc_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ruangan_id')->index();
            $table->enum('jenis', ['umum', 'it'])->default('umum');
            $table->text('deskripsi');
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');
            $table->unsignedBigInteger('handled_by')->nullable();
            $table->text('catatan_handler')->nullable();
            $table->timestamps();

            $table->foreign('ruangan_id')->references('id')->on('ruangan')->onDelete('cascade');
            $table->foreign('handled_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_maintc_reports');
    }
};
