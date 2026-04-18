<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration {
    public function up(): void {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->decimal('jam_lembur', 5, 2)->default(0)->comment('Jam lembur dalam angka');
            $table->text('keterangan')->nullable();
            $table->timestamps();
 
            $table->unique(['karyawan_id', 'tanggal']);
        });
    }
    public function down(): void { Schema::dropIfExists('absensis'); }
};