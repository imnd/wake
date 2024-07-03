<?php

use App\Helpers\Str;
use App\Models\Memorial;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memorials', function (Blueprint $table) {
            $table->uuid('uuid')->nullable();
        });
        foreach (Memorial::get() as $memorial) {
            $memorial->update([
                'uuid' => Str::uuid(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('memorials', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
