<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('disbursement_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // user isi ini:
            $table->unsignedBigInteger('amount');
            $table->text('note')->nullable();

            // admin isi nanti (bukan tugas kamu, tapi kolomnya disiapin):
            $table->string('status', 30)->default('requested'); // requested|approved|rejected|paid
            $table->date('paid_at')->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_name', 150)->nullable();
            $table->string('account_number', 50)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disbursement_requests');
    }
};
