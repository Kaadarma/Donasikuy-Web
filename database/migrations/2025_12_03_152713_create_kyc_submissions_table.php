<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kyc_submissions', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')
                ->constrained('users', 'id')
                ->cascadeOnDelete();

            $table->string('full_name');
            $table->string('nik', 20);
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();

            // path file upload
            $table->string('id_card_path')->nullable();   // foto KTP
            $table->string('selfie_path')->nullable();    // selfie dengan KTP

            // status pengajuan
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending');

            $table->text('note')->nullable(); // catatan admin (opsional)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_submissions');
    }
};
