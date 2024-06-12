<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->text('infotext')->nullable();
            $table->boolean('public');
            $table->boolean('candidates_exist');
            $table->boolean('all_votes_counted');
        });

        DB::statement('ALTER TABLE elections ALTER COLUMN infotext TYPE text[] USING ARRAY[infotext]');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};
