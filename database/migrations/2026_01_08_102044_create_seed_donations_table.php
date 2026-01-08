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
    Schema::create('seed_donations', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        // identitas program SEED
        $table->string('program_slug');
        $table->string('program_title');
        $table->string('program_image')->nullable();
        $table->string('program_category')->nullable();

        $table->unsignedBigInteger('amount');
        $table->string('status')->default('success');

        $table->boolean('is_anonymous')->default(false);
        $table->string('donor_name')->nullable();
        $table->text('message')->nullable();

        $table->timestamps();

        $table->index(['user_id', 'program_slug']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seed_donations');
    }
};
