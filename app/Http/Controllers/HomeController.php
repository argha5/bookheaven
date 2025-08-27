<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Writer;
use App\Models\Genre;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Get all books (limit 20)
        $allBooks = Book::with(['writers', 'genres', 'categories'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Get popular books (most ordered, limit 20)
        $popularBooks = Book::with(['writers'])
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->limit(20)
            ->get();

        // Get top rated books (limit 20)
        $topRatedBooks = Book::with(['writers'])
            ->whereNotNull('rating')
            ->orderBy('rating', 'desc')
            ->limit(20)
            ->get();

        // Get recently added books (limit 20)
        $recentBooks = Book::with(['writers'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Get books on sale (quantity > 0, limit 20)
        $saleBooks = Book::with(['writers'])
            ->where('quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Get writers (limit 15)
        $writers = Writer::orderBy('name')
            ->limit(15)
            ->get();

        // Get genres (limit 15)
        $genres = Genre::orderBy('name')
            ->limit(15)
            ->get();

        return view('home', compact(
            'allBooks',
            'popularBooks',
            'topRatedBooks',
            'recentBooks',
            'saleBooks',
            'writers',
            'genres'
        ));
    }

    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('info_message', 'Please login to add book to your cart!');
        }

        $request->validate([
            'book_id' => 'required|exists:books,book_id'
        ]);

        $userId = Auth::id();
        $bookId = $request->book_id;

        // Check if book is already in cart
        $existingCartItem = Cart::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->first();

        if ($existingCartItem) {
            return redirect()->back()
                ->with('info_message', 'This book is already in your cart');
        }

        // Add to cart
        Cart::create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'quantity' => 1,
            'added_at' => now()
        ]);

        return redirect()->back()
            ->with('success_message', 'Book added to cart successfully!');
    }
}
