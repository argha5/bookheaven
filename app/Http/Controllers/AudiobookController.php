<?php

namespace App\Http\Controllers;

use App\Models\Audiobook;
use Illuminate\Http\Request;

class AudiobookController extends Controller
{
    public function index()
    {
        $audiobooks = Audiobook::visible()
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('audiobooks.index', compact('audiobooks'));
    }

    public function show($id)
    {
        $audiobook = Audiobook::findOrFail($id);
        
        $relatedAudiobooks = Audiobook::visible()
            ->where('audiobook_id', '!=', $id)
            ->where(function($query) use ($audiobook) {
                $query->where('genre', $audiobook->genre)
                      ->orWhere('category', $audiobook->category);
            })
            ->limit(6)
            ->get();

        return view('audiobooks.show', compact('audiobook', 'relatedAudiobooks'));
    }

    public function play($id)
    {
        $audiobook = Audiobook::findOrFail($id);
        
        return view('audiobooks.player', compact('audiobook'));
    }
}
