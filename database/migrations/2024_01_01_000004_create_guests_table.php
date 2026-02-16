<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Membuat tabel guests untuk menyimpan data tamu undangan.
     */
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_number', 20)->nullable();
            $table->enum('category', ['VIP', 'Regular'])->default('Regular');
            $table->string('slug')->unique();
            $table->string('qr_code_string', 10)->unique();
            $table->enum('rsvp_status', ['Pending', 'Coming', 'Not Coming'])->default('Pending');
            $table->integer('guest_count')->default(1);
            $table->timestamp('check_in_time')->nullable();
            $table->enum('gift_type', ['Fisik', 'Amplop', 'None'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Menghapus tabel guests.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
