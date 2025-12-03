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
        Schema::create('donasi_bulanan_tables', function (Blueprint $table) {
            $table->id('id_bulanan');

            // Relasi ke tabel users (default Laravel)
            $table->foreignId('id_user')
                ->nullable()
                ->constrained('users', 'id')      // BUKAN users_donasikuy
                ->cascadeOnDelete();

            // Relasi ke tabel kampanye_tables
            $table->foreignId('id_kampanye')
                ->nullable()
                ->constrained('kampanye_tables', 'id_kampanye')  // BUKAN kampanye
                ->cascadeOnDelete();

            $table->double('jumlah', 15, 2)->default(0);
            $table->date('tanggal_mulai')->nullable();
            $table->string('pembayaran_selanjutnya', 255)->nullable();
            $table->string('status_bulanan', 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donasi_bulanan_tables');
    }
};
