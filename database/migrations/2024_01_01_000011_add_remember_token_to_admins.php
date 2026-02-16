<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Menambahkan kolom remember_token ke tabel admins.
     * Diperlukan oleh Auth::attempt() saat opsi remember=true.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->rememberToken();
        });
    }

    /**
     * Menghapus kolom remember_token dari tabel admins.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('remember_token');
        });
    }
};
