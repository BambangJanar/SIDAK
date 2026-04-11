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
        Schema::create('surat_stakeholders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surat')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->enum('role_type', ['pembuat', 'penerima_utama', 'penerima_delegasi'])->default('penerima_utama');

            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('assigned_at')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stakeholder_surats');
    }
};
