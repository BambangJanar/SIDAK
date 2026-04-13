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
        Schema::table('pengaturan', function (Blueprint $table) {
            $table->string('kop_instansi', 255)->nullable()->after('instansi_logo');
            $table->string('kop_divisi', 255)->nullable()->after('kop_instansi');
            $table->text('kop_alamat')->nullable()->after('kop_divisi');
            $table->string('kop_kontak', 255)->nullable()->after('kop_alamat');
            $table->string('logo_instansi', 255)->nullable()->after('kop_kontak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan', function (Blueprint $table) {
            //
        });
    }
};
