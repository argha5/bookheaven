<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BookHeaven - Your Literary Paradise')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #57abd2;
            --secondary-color: #f8f5fc;
            --accent-color: rgb(223, 219, 227);
            --text-color: #333;
            --light-purple: #e6d9f2;
            --dark-text: #212529;
            --light-text: #f8f9fa;
            --card-bg: #f8f9fa;
            --aside-bg: #f0f2f5;
            --nav-hover: #e0e0e0;
            --border-color: #dee2e6;
            --promo-card-bg: #ffffff;
            --book-card-bg: #ffffff;
            --writer-section-bg: #ffffff;
            --filter-section-bg: #ffffff;
            --genre-section-bg: #ffffff;
            --header-bg: #ffffff;
            --footer-bg: #f8f9fa;
            --footer-text: #212529;
        }

        .dark-mode {
            --primary-color: #57abd2;
            --secondary-color: #2d3748;
            --accent-color: #4a5568;
            --text-color: #f8f9fa;
            --light-purple: #4a5568;
            --dark-text: #f8f9fa;
            --light-text: #212529;
            --card-bg: #1a202c;
            --aside-bg: #1a202c;
            --nav-hover: #4a5568;
            --border-color: #4a5568;
            --promo-card-bg: #2d3748;
            --book-card-bg: #2d3748;
            --writer-section-bg: #2d3748;
            --filter-section-bg: #2d3748;
            --genre-section-bg: #2d3748;
            --header-bg: #1a202c;
            --footer-bg: #1a202c;
            --footer-text: #f8f9fa;
        }

        body {
            font-family: "Nunito", sans-serif;
            color: var(--dark-text);
            background-color: var(--secondary-color);
            transition: background-color 0.3s, color 0.3s;
        }

        /* Message Modal Styles */
        .message-modal {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 350px;
            width: 100%;
            animation: slideIn 0.5s forwards;
        }

        .message-modal.hide {
            animation: slideOut 0.5s forwards;
        }

        .message-content {
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .message-content.success {
            background-color: #28a745;
        }

        .message-content.error {
            background-color: #dc3545;
        }

        .message-content.info {
            background-color: #17a2b8;
        }

        .message-text {
            flex-grow: 1;
            margin-right: 15px;
        }

        .close-message {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Header Styles */
        .navbar {
            background-color: var(--header-bg);
            border-bottom: 1px solid var(--border-color);
        }

        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: var(--dark-text) !important;
            transition: color 0.3s;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
        }

        /* Footer Styles */
        footer {
            background-color: var(--footer-bg);
            color: var(--footer-text);
            padding: 20px 0;
            transition: background-color 0.3s, color 0.3s;
            margin-top: 50px;
        }

        footer a {
            color: var(--footer-text);
            transition: color 0.3s;
        }

        footer a:hover {
            color: var(--primary-color);
        }

        /* Book Card Styles */
        .book-card {
            background: var(--book-card-bg);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
            border: 1px solid var(--border-color);
            height: 100%;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .book-cover {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .book-info {
            padding: 15px;
            text-align: center;
        }

        .book-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            color: var(--primary-color);
            transition: color 0.3s;
        }

        .book-author {
            color: var(--text-color);
            margin-bottom: 10px;
            font-size: 14px;
            transition: color 0.3s;
        }

        .book-price {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 15px;
            color: var(--accent-color);
            transition: color 0.3s;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-book"></i> BookHeaven
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('books.search') }}">Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('audiobooks.index') }}">Audiobooks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subscriptions.index') }}">Subscriptions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">About</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                <i class="fas fa-shopping-cart"></i> Cart
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.orders') }}">Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success_message') || session('error_message') || session('info_message'))
        <div class="message-modal" id="messageModal">
            <div class="message-content 
                @if(session('success_message')) success @endif
                @if(session('error_message')) error @endif
                @if(session('info_message')) info @endif">
                <div class="message-text">
                    {{ session('success_message') ?? session('error_message') ?? session('info_message') }}
                </div>
                <button class="close-message" onclick="closeMessageModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>BookHeaven</h5>
                    <p>Your literary paradise for books, audiobooks, and reading communities.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                        <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Connect With Us</h5>
                    <div class="social-links">
                        <a href="#" class="me-3"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; {{ date('Y') }} BookHeaven. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function closeMessageModal() {
            const modal = document.getElementById('messageModal');
            if (modal) {
                modal.classList.add('hide');
                setTimeout(() => modal.remove(), 500);
            }
        }

        // Auto-hide message after 5 seconds
        setTimeout(() => {
            closeMessageModal();
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>
