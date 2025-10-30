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
            $table->foreignId('id_user')->constrained('users_donasikuy', 'id_user')->onDelete('cascade');
            $table->foreignId('id_kampanye')->constrained('kampanye', 'id_kampanye')->onDelete('cascade');
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
