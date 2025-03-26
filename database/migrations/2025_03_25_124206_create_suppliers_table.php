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
        Schema::create('um_supplier', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->string('alamat', 225);
            $table->string('telp', 25);
            $table->string('email', 50)->nullable();
            $table->string('npwp', 25)->nullable();
            $table->string('bank', 50)->nullable();
            $table->string('norek', 50)->nullable();
            $table->string('an', 50)->nullable();
            $table->string('status', 5)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('um_supplier');
    }
};
