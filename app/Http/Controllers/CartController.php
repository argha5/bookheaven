<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cartItems = Cart::with('book.writers')
            ->where('user_id', Auth::id())
            ->get();

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->quantity * $item->book->price;
        });

        return view('cart.index', compact('cartItems', 'totalAmount'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,book_id',
            'quantity' => 'integer|min:1|max:10'
        ]);

        $userId = Auth::id();
        $bookId = $request->book_id;
        $quantity = $request->quantity ?? 1;

        // Check if book is already in cart
        $existingCartItem = Cart::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->first();

        if ($existingCartItem) {
            $existingCartItem->update([
                'quantity' => $existingCartItem->quantity + $quantity
            ]);
            return response()->json(['message' => 'Cart updated successfully!']);
        }

        // Add new item to cart
        Cart::create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'quantity' => $quantity,
            'added_at' => now()
        ]);

        return response()->json(['message' => 'Book added to cart successfully!']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $cartItem = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return response()->json(['message' => 'Cart updated successfully!']);
    }

    public function remove($id)
    {
        $cartItem = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cartItem->delete();

        return redirect()->back()
            ->with('success_message', 'Item removed from cart successfully!');
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->back()
            ->with('success_message', 'Cart cleared successfully!');
    }
}
