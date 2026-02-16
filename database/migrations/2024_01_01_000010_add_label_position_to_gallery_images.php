<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Menambah kolom label dan object_position pada tabel gallery_images.
     * label: untuk identifikasi foto (misal "Mempelai Pria", "Mempelai Wanita").
     * object_position: untuk mengatur posisi crop foto (misal "center", "top").
     */
    public function up(): void
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->string('label')->nullable()->after('alt_text');
            $table->string('object_position')->default('center')->after('label');
        });
    }

    /**
     * Menghapus kolom label dan object_position.
     */
    public function down(): void
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropColumn(['label', 'object_position']);
        });
    }
};
