<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AudiobookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/add-to-cart', [HomeController::class, 'addToCart'])->name('add-to-cart');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Book Routes
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
Route::get('/search', [BookController::class, 'search'])->name('books.search');
Route::get('/genre/{id}', [BookController::class, 'byGenre'])->name('books.by-genre');
Route::get('/writer/{id}', [BookController::class, 'byWriter'])->name('books.by-writer');

// Cart Routes (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
});

// Audiobook Routes
Route::get('/audiobooks', [AudiobookController::class, 'index'])->name('audiobooks.index');
Route::get('/audiobooks/{id}', [AudiobookController::class, 'show'])->name('audiobooks.show');
Route::get('/audiobooks/{id}/play', [AudiobookController::class, 'play'])->name('audiobooks.play');

// User Profile Routes (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/subscriptions', [ProfileController::class, 'subscriptions'])->name('profile.subscriptions');
});

// Subscription Routes
Route::get('/subscription-plans', [SubscriptionController::class, 'index'])->name('subscriptions.index');
Route::middleware('auth')->group(function () {
    Route::post('/subscribe/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');
});

// Static Pages
Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/privacy', 'pages.privacy')->name('privacy');

// Admin Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/books', [AdminController::class, 'books'])->name('books');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});
