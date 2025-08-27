<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Writer extends Model
{
    use HasFactory;

    protected $primaryKey = 'writer_id';

    protected $fillable = [
        'name',
        'bio',
        'image_url',
    ];

    /**
     * Get the books for the writer.
     */
    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_writers', 'writer_id', 'book_id');
    }
}
