<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Writer;
use App\Models\Genre;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function show($id)
    {
        $book = Book::with(['writers', 'genres', 'categories'])
            ->findOrFail($id);

        // Get related books (same genre or writer)
        $relatedBooks = Book::with(['writers', 'genres'])
            ->where('book_id', '!=', $id)
            ->where(function ($query) use ($book) {
                $query->whereHas('genres', function ($q) use ($book) {
                    $q->whereIn('genre_id', $book->genres->pluck('genre_id'));
                })->orWhereHas('writers', function ($q) use ($book) {
                    $q->whereIn('writer_id', $book->writers->pluck('writer_id'));
                });
            })
            ->limit(6)
            ->get();

        return view('books.show', compact('book', 'relatedBooks'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $genreId = $request->get('genre');
        $categoryId = $request->get('category');
        $writerId = $request->get('writer');

        $books = Book::with(['writers', 'genres', 'categories']);

        if ($query) {
            $books->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('details', 'LIKE', "%{$query}%")
                  ->orWhereHas('writers', function ($q) use ($query) {
                      $q->where('name', 'LIKE', "%{$query}%");
                  });
        }

        if ($genreId) {
            $books->whereHas('genres', function ($q) use ($genreId) {
                $q->where('genre_id', $genreId);
            });
        }

        if ($categoryId) {
            $books->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        if ($writerId) {
            $books->whereHas('writers', function ($q) use ($writerId) {
                $q->where('writer_id', $writerId);
            });
        }

        $books = $books->paginate(12);
        
        $genres = Genre::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $writers = Writer::orderBy('name')->get();

        return view('books.search', compact('books', 'genres', 'categories', 'writers', 'query'));
    }

    public function byGenre($genreId)
    {
        $genre = Genre::findOrFail($genreId);
        $books = $genre->books()
            ->with(['writers', 'genres'])
            ->paginate(12);

        return view('books.by-genre', compact('books', 'genre'));
    }

    public function byWriter($writerId)
    {
        $writer = Writer::findOrFail($writerId);
        $books = $writer->books()
            ->with(['writers', 'genres'])
            ->paginate(12);

        return view('books.by-writer', compact('books', 'writer'));
    }
}
