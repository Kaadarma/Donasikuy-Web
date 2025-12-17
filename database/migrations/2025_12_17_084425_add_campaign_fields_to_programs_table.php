<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('programs', function (Blueprint $table) {
        $table->foreignId('user_id')
            ->nullable()
            ->after('id')
            ->constrained()
            ->nullOnDelete();

        $table->enum('status', [
            'draft',
            'pending',
            'approved',
            'rejected',
            'running',
            'completed',
            'expired',
            'suspended',
        ])->default('draft')->after('deadline');

        $table->timestamp('approved_at')
            ->nullable()
            ->after('status');

        $table->foreignId('approved_by')
            ->nullable()
            ->after('approved_at')
            ->constrained('users')
            ->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('programs', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropForeign(['approved_by']);

        $table->dropColumn([
            'user_id',
            'status',
            'approved_at',
            'approved_by',
        ]);
    });
}

};
