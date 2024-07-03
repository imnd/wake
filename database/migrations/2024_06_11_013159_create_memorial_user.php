<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memorial_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('memorial_id');
            $table->timestamps();
            $table->primary(['user_id', 'memorial_id']);
            $table->comment('Memorials that a user reaches via a deep link.');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memorial_user');
    }
};
