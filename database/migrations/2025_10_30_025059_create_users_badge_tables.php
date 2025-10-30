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
            $table->foreignId('id_user')->constrained('users_donasikuy', 'id_user')->onDelete('cascade');
            $table->foreignId('id_badge')->constrained('badge', 'id_badge')->onDelete('cascade');
            $table->date('tanggal_diraih')->nullable();
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
