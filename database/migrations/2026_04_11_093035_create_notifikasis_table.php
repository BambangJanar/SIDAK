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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type', 50);
            $table->string('title');
            $table->text('message')->nullable();

            $table->foreignId('surat_id')->nullable()->constrained('surat')->onDelete('cascade');
            $table->foreignId('disposisi_id')->nullable()->constrained('disposisi')->onDelete('cascade');

            $table->string('url')->nullable();
            $table->boolean('is_read')->default(false);
            $table->datetime('read_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
