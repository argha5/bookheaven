<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audiobooks', function (Blueprint $table) {
            $table->id('audiobook_id');
            $table->string('title');
            $table->string('writer');
            $table->string('genre', 100);
            $table->string('category', 100);
            $table->string('language', 100)->nullable();
            $table->string('audio_url');
            $table->string('poster_url')->nullable();
            $table->text('description')->nullable();
            $table->time('duration')->nullable();
            $table->enum('status', ['visible', 'hidden', 'pending'])->default('visible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audiobooks');
    }
};
