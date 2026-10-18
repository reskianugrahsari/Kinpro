<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->string('bulan');
            $table->integer('tahun');
            $table->integer('nilai_kedisiplinan')->default(0);
            $table->integer('kinerja')->default(0);
            $table->integer('sikap')->default(0);
            $table->integer('kepemimpinan')->default(0);
            $table->integer('loyalitas')->default(0);
            $table->integer('it')->default(0);
            $table->text('masukan_atasan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
