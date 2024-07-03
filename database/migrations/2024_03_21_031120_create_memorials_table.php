<?php

use App\Helpers\Statuses;
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
            $table->string('title')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->default('');
            $table->string('last_name');
            $table->enum('gender', [
                Memorial::GENDER_MALE,
                Memorial::GENDER_FEMALE,
                Memorial::GENDER_OTHER,
            ])->default(Memorial::GENDER_OTHER);
            $table->string('place_of_birth');
            $table->string('place_of_death')->nullable();
            $table->date('day_of_birth');
            $table->date('day_of_death');
            $table->text('text')->nullable();
            $table->string('avatar')->nullable()->default('');
            $table->boolean('default')->default(false);
            $table->string('status', 10)->default(Statuses::STATUS_UNPAID);
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
