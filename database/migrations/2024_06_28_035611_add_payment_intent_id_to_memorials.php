<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memorials', function (Blueprint $table) {
            $table->string('payment_intent_id')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('memorials', function (Blueprint $table) {
            $table->dropColumn('payment_intent_id');
        });
    }
};
