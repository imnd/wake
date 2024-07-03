<?php

use App\Enums\PaymentMethods;
use App\Helpers\Statuses;
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
            $table->unsignedSmallInteger('type_id')->nullable()->default(null);
            $table->text('condolences');
            $table->string('from');
            $table->string('status', 10)->default(Statuses::STATUS_UNPAID);
            $table->string('payment_intent_id')->nullable()->default(null);
            $table->enum('payment_method', PaymentMethods::values())->nullable();
            $table->float('amount')->nullable(false)->default(0);
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
