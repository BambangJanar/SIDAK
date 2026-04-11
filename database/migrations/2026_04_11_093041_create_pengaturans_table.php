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
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('app_name', 100)->default('Tracking Disposisi');
            $table->string('app_description')->default('Aplikasi Manajemen Surat');
            $table->string('app_logo')->nullable();
            $table->string('app_favicon')->nullable();
            $table->string('theme_color', 20)->default('blue');

            $table->string('instansi_nama', 200)->default('DINAS KOMUNIKASI DAN INFORMATIKA');
            $table->text('instansi_alamat')->nullable();
            $table->string('instansi_telepon', 20)->nullable();
            $table->string('instansi_email', 100)->nullable();
            $table->string('instansi_logo')->nullable();

            $table->string('ttd_nama_penandatangan')->nullable();
            $table->string('ttd_nip', 50)->nullable();
            $table->string('ttd_jabatan', 100)->default('Kepala Dinas');
            $table->string('ttd_kota', 50)->default('Banjarmasin');
            $table->string('ttd_image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};
