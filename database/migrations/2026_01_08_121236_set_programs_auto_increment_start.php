<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration {
    public function up(): void
    {
        DB::statement('ALTER TABLE programs AUTO_INCREMENT = 7');
    }

    public function down(): void
    {
        // optional: balikin ke default (1)
        DB::statement('ALTER TABLE programs AUTO_INCREMENT = 1');
    }
};

