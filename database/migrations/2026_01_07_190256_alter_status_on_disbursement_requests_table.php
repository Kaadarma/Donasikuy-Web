<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disbursement_requests', function (Blueprint $table) {
            // MySQL: enum butuh raw statement, jadi drop & add ulang kolom status
            $table->dropColumn('status');
        });

        Schema::table('disbursement_requests', function (Blueprint $table) {
            $table->enum('status', ['requested','approved','rejected','paid'])
                ->default('requested')
                ->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('disbursement_requests', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('disbursement_requests', function (Blueprint $table) {
            $table->string('status', 30)->default('requested')->after('note');
        });
    }
};
