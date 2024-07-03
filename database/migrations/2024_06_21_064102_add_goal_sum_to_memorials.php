<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memorials', function (Blueprint $table) {
            $table->float('goal_sum')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('memorials', function (Blueprint $table) {
            $table->dropColumn('goal_sum');
        });
    }
};
