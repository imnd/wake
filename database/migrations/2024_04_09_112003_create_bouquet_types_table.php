<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bouquet_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name');
            $table->double('price');
            $table->string('image');
            $table->smallInteger('priority')->default(0);
            $table->softDeletes();
        });
        Schema::table('bouquets', function (Blueprint $table) {
            $table->unsignedSmallInteger('type_id')
                ->nullable()
                ->default(null)
                ->after('id');
            $table
                ->foreign('type_id')
                ->references('id')
                ->on('bouquet_types')
                ->noActionOnDelete()
                ->noActionOnUpdate()
            ;
        });
    }

    public function down(): void
    {
        Schema::table('bouquets', function (Blueprint $table) {
            $table->dropColumn('bouquet_type');
        });
        Schema::dropIfExists('bouquet_types');
    }
};
