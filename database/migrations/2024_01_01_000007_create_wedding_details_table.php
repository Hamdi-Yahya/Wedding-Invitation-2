<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Membuat tabel wedding_details (singleton ID=1) untuk detail akad, resepsi, dan dress code.
     */
    public function up(): void
    {
        Schema::create('wedding_details', function (Blueprint $table) {
            $table->id();
            $table->string('ceremony_title')->default('The Ceremony');
            $table->string('ceremony_time', 20)->nullable();
            $table->string('ceremony_venue')->nullable();
            $table->string('ceremony_address')->nullable();
            $table->string('reception_title')->default('The Reception');
            $table->string('reception_time', 20)->nullable();
            $table->string('reception_venue')->nullable();
            $table->string('reception_note')->nullable();
            $table->string('dress_code_title')->default('Dress Code');
            $table->string('dress_code_note')->nullable();
            $table->string('dress_code_style1')->default('Formal Attire');
            $table->string('dress_code_style2')->default('Smart Casual');
            $table->timestamps();
        });
    }

    /**
     * Menghapus tabel wedding_details.
     */
    public function down(): void
    {
        Schema::dropIfExists('wedding_details');
    }
};
