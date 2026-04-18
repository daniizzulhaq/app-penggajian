<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration {
    public function up(): void {
        Schema::create('potongans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
            $table->string('nama_potongan');
            $table->decimal('nominal', 15, 2)->default(0);
            $table->enum('jenis', ['bpjs_kes', 'bpjs_tk', 'pph21', 'lainnya'])->default('lainnya');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('potongans'); }
};