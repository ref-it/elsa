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
        Schema::create('lists', function (Blueprint $table) {
            $table->id();
            $table->integer('election');
            $table->integer('committee');
            $table->json('name');
            $table->json('description')->nullable();
            $table->integer('seats')->nullable();
            $table->integer('seats_deputy')->nullable();
        });
        
        DB::statement('ALTER TABLE lists ALTER COLUMN description TYPE text[] USING ARRAY[description]');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lists');
    }
};
