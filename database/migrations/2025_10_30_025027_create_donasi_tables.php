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
        Schema::create('donasi_tables', function (Blueprint $table) {
            $table->id('id_donasi');

            // Relasi ke users (bawaan Laravel)
            $table->foreignId('id_user')
                ->nullable()
                ->constrained('users', 'id')     // BUKAN 'users_donasikuy' lagi
                ->cascadeOnDelete();             // ON DELETE CASCADE

            // Relasi ke kampanye (kalau kamu memang punya kampanye_tables)
            $table->foreignId('id_kampanye')
                ->nullable()
                ->constrained('kampanye_tables', 'id_kampanye')
                ->nullOnDelete();

            // Kolom-kolom donasi
            $table->double('jumlah_donasi', 15, 2);
            $table->string('metode_pembayaran', 100)->nullable();
            $table->enum('status_donasi', ['pending', 'success', 'failed'])->default('pending');
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donasi_tables');
    }
};
