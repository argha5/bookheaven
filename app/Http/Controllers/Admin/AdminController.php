<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Order;
use App\Models\Audiobook;
use App\Models\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'total_audiobooks' => Audiobook::count(),
            'total_writers' => Writer::count(),
            'total_orders' => Order::count(),
            'total_sales_month' => Order::whereMonth('created_at', now()->month)->sum('total_amount'),
        ];

        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentUsers'));
    }

    public function users()
    {
        $users = User::with('orders')
            ->withCount('orders')
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function books()
    {
        $books = Book::with(['writers', 'genres', 'categories'])
            ->paginate(20);

        return view('admin.books', compact('books'));
    }

    public function orders()
    {
        $orders = Order::with(['user', 'items.book'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.orders', compact('orders'));
    }

    public function reports()
    {
        $monthlyStats = DB::table('orders')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();

        $topBooks = DB::table('order_items')
            ->join('books', 'order_items.book_id', '=', 'books.book_id')
            ->select('books.title', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('books.book_id', 'books.title')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reports', compact('monthlyStats', 'topBooks'));
    }
}
