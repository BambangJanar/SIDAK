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
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surat')->onDelete('cascade');

            $table->foreignId('dari_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ke_user_id')->constrained('users')->onDelete('cascade');

            $table->enum('status_disposisi', ['dikirim', 'diterima', 'diproses', 'selesai', 'ditolak'])->default('dikirim');
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_disposisi')->useCurrent();
            $table->datetime('tanggal_respon')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisis');
    }
};
