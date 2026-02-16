<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Menambahkan kolom nama orang tua mempelai ke tabel event_settings.
     */
    public function up(): void
    {
        Schema::table('event_settings', function (Blueprint $table) {
            $table->string('partner_1_father')->nullable()->after('partner_1_name');
            $table->string('partner_1_mother')->nullable()->after('partner_1_father');
            $table->string('partner_2_father')->nullable()->after('partner_2_name');
            $table->string('partner_2_mother')->nullable()->after('partner_2_father');
        });
    }

    /**
     * Menghapus kolom nama orang tua mempelai.
     */
    public function down(): void
    {
        Schema::table('event_settings', function (Blueprint $table) {
            $table->dropColumn(['partner_1_father', 'partner_1_mother', 'partner_2_father', 'partner_2_mother']);
        });
    }
};
