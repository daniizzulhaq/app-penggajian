<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration {
    public function up(): void {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('hari_kerja')->default(0);
            $table->integer('hari_hadir')->default(0);
            $table->integer('hari_izin')->default(0);
            $table->integer('hari_sakit')->default(0);
            $table->integer('hari_alpha')->default(0);
            $table->decimal('total_jam_lembur', 8, 2)->default(0);
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('total_tunjangan', 15, 2)->default(0);
            $table->decimal('uang_lembur', 15, 2)->default(0);
            $table->decimal('total_potongan', 15, 2)->default(0);
            $table->decimal('potongan_alpha', 15, 2)->default(0);
            $table->decimal('gaji_bersih', 15, 2)->default(0);
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('diapprove_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
 
            $table->unique(['karyawan_id', 'bulan', 'tahun']);
        });
    }
    public function down(): void { Schema::dropIfExists('payrolls'); }
};
 