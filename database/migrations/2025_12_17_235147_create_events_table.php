<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('events', function (Blueprint $table) {
        $table->id();

        // kalau event dibuat oleh user, pakai ini (opsional)
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

        $table->string('title');
        $table->string('slug')->unique();
        $table->string('category')->nullable();

        $table->string('short_description')->nullable();
        $table->text('description')->nullable();

        $table->string('image')->nullable();
        $table->string('banner')->nullable();

        $table->unsignedBigInteger('target')->default(0);
        $table->unsignedBigInteger('raised')->default(0);

        $table->date('deadline')->nullable();

        $table->enum('status', ['draft','pending','approved','rejected','closed'])->default('draft');

        $table->timestamp('approved_at')->nullable();
        $table->unsignedBigInteger('approved_by')->nullable(); // admin id kalau mau

        $table->timestamps();
    });
}

};
