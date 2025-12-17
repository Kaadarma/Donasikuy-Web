<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE programs 
            MODIFY status ENUM(
                'draft','pending','approved','running','completed','expired','rejected','cancelled'
            ) NOT NULL DEFAULT 'draft'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE programs 
            MODIFY status ENUM(
                'draft','pending','approved','running','completed','expired','rejected'
            ) NOT NULL DEFAULT 'draft'");
    }

};
