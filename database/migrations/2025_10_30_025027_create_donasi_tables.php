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
            $table->foreignId('id_user')->constrained('users_donasikuy', 'id_user')->onDelete('cascade');
            $table->foreignId('id_kampanye')->constrained('kampanye', 'id_kampanye')->onDelete('cascade');
            $table->double('jumlah_donasi', 15, 2)->default(0);
            $table->string('metode_pembayaran', 255)->nullable();
            $table->date('tanggal_donasi')->nullable();
            $table->enum('status_donasi', ['terkirim', 'pending'])->default('pending');
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
