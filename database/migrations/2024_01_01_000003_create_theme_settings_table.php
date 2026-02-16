<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Membuat tabel theme_settings (singleton ID=1) untuk pengaturan tema visual.
     */
    public function up(): void
    {
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->id();
            $table->string('theme_name')->default('Modern Pink');
            $table->string('primary_color', 7)->default('#E91E8C');
            $table->string('secondary_color', 7)->default('#FFF9F9');
            $table->string('font_family')->default('Parisienne');
            $table->text('background_image_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Menghapus tabel theme_settings.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_settings');
    }
};
