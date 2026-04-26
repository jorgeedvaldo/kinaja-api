<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY status VARCHAR(255) DEFAULT 'active'");

        \Illuminate\Support\Facades\DB::table('users')
            ->where('status', 'approved')
            ->update(['status' => 'active']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY status VARCHAR(255) DEFAULT 'approved'");

        \Illuminate\Support\Facades\DB::table('users')
            ->where('status', 'active')
            ->update(['status' => 'approved']);
    }
};
