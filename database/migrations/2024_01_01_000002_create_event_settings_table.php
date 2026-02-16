<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Membuat tabel event_settings (singleton ID=1) untuk menyimpan informasi acara.
     */
    public function up(): void
    {
        Schema::create('event_settings', function (Blueprint $table) {
            $table->id();
            $table->string('partner_1_name');
            $table->string('partner_2_name');
            $table->string('tagline')->nullable();
            $table->date('event_date');
            $table->string('start_time', 10);
            $table->string('end_time', 10);
            $table->string('venue_name');
            $table->text('venue_address');
            $table->text('map_link_url')->nullable();
            $table->text('wa_template_msg')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Menghapus tabel event_settings.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_settings');
    }
};
