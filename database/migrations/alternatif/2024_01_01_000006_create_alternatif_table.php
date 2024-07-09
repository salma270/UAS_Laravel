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
        Schema::create('alternatif', function (Blueprint $table) {
            $table->id('id_alternatif');
            $table->string('kode_alternatif', 4)->unique();
            $table->string('nama_alternatif', 100)->unique();
            $table->time('jam_buka');
            $table->time('jam_tutup');
            $table->string('alamat', 500);
            $table->string('rating', 5);
            $table->string('deskripsi', 500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alternatif');
    }
};
