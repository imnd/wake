<?php

use App\Models\Bouquet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bouquets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('memorial_id');
            $table->text('condolences');
            $table->string('from');
            $table->enum('status', [Bouquet::STATUS_UNPAID, Bouquet::STATUS_PAID])->default(Bouquet::STATUS_UNPAID);
            $table->softDeletes();
            $table->timestamps();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('memorial_id')
                ->references('id')
                ->on('memorials')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bouquets');
    }
};
