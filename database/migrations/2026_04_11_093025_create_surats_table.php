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
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat')->onUpdate('cascade');
            $table->string('nomor_surat', 100)->nullable();
            $table->string('nomor_agenda', 50)->nullable();
            $table->date('tanggal_diterima')->nullable();
            $table->string('dari_instansi', 200)->nullable();
            $table->string('ke_instansi', 200)->nullable();
            $table->text('alamat_surat')->nullable();
            $table->text('perihal');
            $table->string('lampiran_file')->nullable();

            $table->enum('status_surat', ['baru', 'proses', 'disetujui', 'ditolak', 'arsip'])->default('baru');
            $table->text('alasan_penolakan')->nullable();
            $table->string('status_sebelum_arsip', 20)->nullable();

            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
