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
        Schema::create('surat_sp3_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sp3_id');
            $table->text('keterangan');
            $table->decimal('nominal', 16, 2)->nullable(0);
            $table->timestamps();

            $table->foreign('sp3_id')->references('id')->on('surat_sp3')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_sp3_details');
    }
};
