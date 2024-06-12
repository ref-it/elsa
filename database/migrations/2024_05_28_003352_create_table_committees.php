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
        Schema::create('committees', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->text('description')->nullable();
            $table->text('logo')->nullable();
            $table->integer('seats');
            $table->integer('seats_deputy');
            $table->json('elections');
            $table->boolean('lists');
            $table->boolean('lists_quoted');
            $table->boolean('active');
        });

        DB::statement('ALTER TABLE committees ALTER COLUMN description TYPE text[] USING ARRAY[description]');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committees');
    }
};
