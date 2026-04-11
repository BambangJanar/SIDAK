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
        // 1. Buat Tabel Peran DULU (Master)
        Schema::create('peran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_peran', 50);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // 2. Buat Tabel Bagian DULU (Master)
        Schema::create('bagian', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bagian', 100);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // 3. BARU Buat Tabel Users (Bisa nge-link karena peran & bagian sudah ada)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap'); // <-- Sudah diubah dari 'name'
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Custom Kolom
            $table->foreignId('peran_id')->default(3)->constrained('peran'); // <-- Ditambah constrained()
            $table->foreignId('bagian_id')->nullable()->constrained('bagian')->nullOnDelete();
            $table->string('nama_bagian_kustom', 100)->nullable();
            $table->enum('status', ['pending', 'active', 'inactive'])->default('pending');
            $table->boolean('status_aktif')->default(false);

            $table->rememberToken();
            $table->timestamps();
        });

        // Bawaan Laravel
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus (Drop) dari yang paling bawah hirarkinya dulu
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('bagian');
        Schema::dropIfExists('peran');
    }
};
