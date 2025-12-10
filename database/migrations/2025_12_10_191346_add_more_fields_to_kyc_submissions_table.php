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
        Schema::table('kyc_submissions', function (Blueprint $table) {
            // STEP 1 - Informasi Dasar
            $table->enum('account_type', ['individu', 'organisasi'])->nullable();
            $table->string('entity_name')->nullable();
            $table->string('entity_email')->nullable();
            $table->text('entity_address')->nullable();

            //Step 2 - 

            // STEP 3 - Identitas pemegang akun
            $table->string('holder_phone', 20)->nullable();
            $table->string('holder_ktp', 30)->nullable();
            $table->string('profile_photo_path')->nullable();

            // STEP 4 - Informasi pencairan dana
            $table->string('bank_name', 100)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->string('book_photo_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kyc_submissions', function (Blueprint $table) {
            // STEP 1 - Informasi Dasar
            $table->enum('account_type', ['individu', 'organisasi'])->nullable();
            $table->string('entity_name')->nullable();
            $table->string('entity_email')->nullable();
            $table->text('entity_address')->nullable();

            // STEP 3 - Identitas pemegang akun
            $table->string('holder_phone', 20)->nullable();
            $table->string('holder_ktp', 30)->nullable();
            $table->string('profile_photo_path')->nullable();

            // STEP 4 - Informasi pencairan dana
            $table->string('bank_name', 100)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->string('book_photo_path')->nullable();
        });
    }
};
