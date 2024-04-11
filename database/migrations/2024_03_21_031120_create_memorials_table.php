<?php

use App\Models\Memorial;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('memorials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->string('first_name');
            $table->string('middle_name')->default('');
            $table->string('last_name');
            $table->enum('gender', [
                Memorial::GENDER_MALE,
                Memorial::GENDER_FEMALE,
                Memorial::GENDER_OTHER,
            ])->default(Memorial::GENDER_OTHER);
            $table->string('place_of_birth');
            $table->string('place_of_death');
            $table->date('day_of_birth');
            $table->date('day_of_death');
            $table->string('text');
            $table->string('avatar')->nullable()->default('');
            $table->boolean('default')->default(false);
            $table->enum('status', [
                Memorial::STATUS_PUBLISHED,
                Memorial::STATUS_ARCHIVED,
                Memorial::STATUS_DELETED,
            ])->default(Memorial::STATUS_PUBLISHED);
            $table->timestamps();

            $table->unique(['id', 'default']);
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade')
            ;
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memorials');
    }
};
