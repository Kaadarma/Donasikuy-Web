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
        Schema::create('users_badge_tables', function (Blueprint $table) {
            $table->id('id_user_badge');

            // Relasi ke tabel users (bawaan Laravel)
            $table->foreignId('id_user')
                ->nullable()
                ->constrained('users', 'id')          // BUKAN users_donasikuy
                ->cascadeOnDelete();

            // Relasi ke tabel badge_tables
            $table->foreignId('id_badge')
                ->nullable()
                ->constrained('badge_tables', 'id_badge')
                ->cascadeOnDelete();

            // Opsional: kapan badge didapat
            // $table->timestamp('tanggal_didapat')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_badge_tables');
    }
};
