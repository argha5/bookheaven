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
        // Book Categories pivot table
        Schema::create('book_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books', 'book_id')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        });

        // Book Genres pivot table
        Schema::create('book_genres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books', 'book_id')->onDelete('cascade');
            $table->foreignId('genre_id')->constrained('genres', 'genre_id')->onDelete('cascade');
        });

        // Book Writers pivot table
        Schema::create('book_writers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books', 'book_id')->onDelete('cascade');
            $table->foreignId('writer_id')->constrained('writers', 'writer_id')->onDelete('cascade');
        });

        // Cart table
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books', 'book_id')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->timestamp('added_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
        Schema::dropIfExists('book_writers');
        Schema::dropIfExists('book_genres');
        Schema::dropIfExists('book_categories');
    }
};
