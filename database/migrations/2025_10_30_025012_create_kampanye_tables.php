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
        Schema::create('kampanye_tables', function (Blueprint $table) {
            $table->id('id_kampanye');
            $table->foreignId('id_user')->nullable()->constrained('users_donasikuy', 'id_user')->onDelete('set null');
            $table->foreignId('id_kategori')->nullable()->constrained('kategori_kampanye', 'id_kategori')->onDelete('set null');
            $table->foreignId('id_admin')->nullable()->constrained('admins_donasikuy', 'id_admin')->onDelete('set null');
            $table->string('judul_kampanye', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('jumlah_target')->nullable();
            $table->double('jumlah_terkumpul', 15, 2)->default(0);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status_kampanye', ['open', 'closed'])->default('open');
            $table->longBlob('gambar_kampanye')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kampanye_tables');
    }
};
