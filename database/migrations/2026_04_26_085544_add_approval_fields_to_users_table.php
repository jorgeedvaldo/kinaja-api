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
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('approved'); // pending, approved, rejected, suspended
            $table->foreignId('status_updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('status_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['status_updated_by']);
            $table->dropColumn(['status', 'status_updated_by', 'status_updated_at']);
        });
    }
};
