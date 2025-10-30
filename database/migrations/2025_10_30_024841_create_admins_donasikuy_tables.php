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
        Schema::create('admins_donasikuy_tables', function (Blueprint $table) {
            $table->id('id_admin');
            $table->string('nama_admin', 128);
            $table->string('email_admin', 255);
            $table->string('password_admin', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins_donasikuy_tables');
    }
};
