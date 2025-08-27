<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $primaryKey = 'book_id';

    protected $fillable = [
        'title',
        'published',
        'price',
        'quantity',
        'details',
        'cover_image_url',
        'rating',
    ];

    protected $casts = [
        'published' => 'date',
        'price' => 'integer',
        'quantity' => 'integer',
        'rating' => 'float',
    ];

    /**
     * Get the categories for the book.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_categories', 'book_id', 'category_id');
    }

    /**
     * Get the genres for the book.
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genres', 'book_id', 'genre_id');
    }

    /**
     * Get the writers for the book.
     */
    public function writers()
    {
        return $this->belongsToMany(Writer::class, 'book_writers', 'book_id', 'writer_id');
    }

    /**
     * Get the cart items for the book.
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class, 'book_id');
    }

    /**
     * Get the order items for the book.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'book_id');
    }

    /**
     * Check if book is in stock.
     */
    public function isInStock()
    {
        return $this->quantity > 0;
    }

    /**
     * Get the first writer name.
     */
    public function getFirstWriterAttribute()
    {
        return $this->writers->first()?->name ?? 'Unknown Author';
    }

    /**
     * Get the first genre name.
     */
    public function getFirstGenreAttribute()
    {
        return $this->genres->first()?->name ?? 'Uncategorized';
    }
}
