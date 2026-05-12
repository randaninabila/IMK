<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->string('jabatan', 100)->nullable()->after('cabang_id');
            $table->text('deskripsi')->nullable()->after('jabatan');
            $table->string('foto', 255)->nullable()->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropColumn(['jabatan', 'deskripsi', 'foto']);
        });
    }
};