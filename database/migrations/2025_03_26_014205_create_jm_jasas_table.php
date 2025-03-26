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
        Schema::create('jm_jasa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jm_prosentase_id');
            $table->string('dokter', 50);
            $table->string('status', 50);
            $table->double('jasa')->default(0);
            $table->timestamps();

            $table->foreign('jm_prosentase_id')->references('id')->on('jm_prosentase')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jm_jasa');
    }
};
