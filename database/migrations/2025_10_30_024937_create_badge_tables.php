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
        Schema::create('badge_tables', function (Blueprint $table) {
            $table->id('id_badge');
            $table->string('nama_badge', 64)->nullable();
            $table->text('deskripsi')->nullable();
            $table->double('jumlah_minimal', 15, 2)->nullable();
            $table->longBlob('foto_icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badge_tables');
    }
};
