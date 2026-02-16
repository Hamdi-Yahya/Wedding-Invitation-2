<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Membuat tabel wishes untuk menyimpan ucapan dari tamu.
     */
    public function up(): void
    {
        Schema::create('wishes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('guests')->onDelete('cascade');
            $table->text('message');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Menghapus tabel wishes.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishes');
    }
};
