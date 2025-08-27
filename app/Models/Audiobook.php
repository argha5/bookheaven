<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audiobook extends Model
{
    use HasFactory;

    protected $primaryKey = 'audiobook_id';

    protected $fillable = [
        'title',
        'writer',
        'genre',
        'category',
        'language',
        'audio_url',
        'poster_url',
        'description',
        'duration',
        'status',
    ];

    protected $casts = [
        'duration' => 'datetime:H:i:s',
    ];

    /**
     * Scope for visible audiobooks.
     */
    public function scopeVisible($query)
    {
        return $query->where('status', 'visible');
    }

    /**
     * Get formatted duration.
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) return 'Unknown';
        
        $duration = \Carbon\Carbon::parse($this->duration);
        $hours = $duration->format('H');
        $minutes = $duration->format('i');
        
        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }
        return $minutes . 'm';
    }
}
