<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'book_id',
        'quantity',
        'added_at',
    ];

    protected $casts = [
        'added_at' => 'datetime',
        'quantity' => 'integer',
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book for the cart item.
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }

    /**
     * Get the total price for this cart item.
     */
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->book->price;
    }
}
