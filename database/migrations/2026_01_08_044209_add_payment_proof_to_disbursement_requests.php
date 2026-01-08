<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disbursement_requests', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('paid_at');
            $table->text('admin_note')->nullable()->after('payment_proof');
        });
    }

    public function down(): void
    {
        Schema::table('disbursement_requests', function (Blueprint $table) {
            $table->dropColumn(['payment_proof', 'admin_note']);
        });
    }

};
