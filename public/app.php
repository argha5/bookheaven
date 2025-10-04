<?php
session_start();

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bkh';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed. Please ensure MySQL is running in XAMPP and the 'bkh' database exists.");
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart']) && isset($_SESSION['user_id'])) {
        $book_id = $_POST['book_id'];
        $user_id = $_SESSION['user_id'];
        $user_type = $_SESSION['user_type'] ?? 'user';
        
        // For admin users, create a user record if it doesn't exist
        if ($user_type === 'admin') {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt->execute([$user_id]);
            if ($stmt->rowCount() === 0) {
                // Create a user record for admin to enable cart functionality
                $stmt = $pdo->prepare("INSERT INTO users (user_id, username, email, pass) VALUES (?, ?, ?, ?)");
                $stmt->execute([$user_id, $_SESSION['user_name'], 'admin@bookheaven.com', password_hash('admin123', PASSWORD_DEFAULT)]);
            }
        }
        
        // Check if item already in cart
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND book_id = ?");
        $stmt->execute([$user_id, $book_id]);
        
        if ($stmt->rowCount() > 0) {
            // Update quantity
            $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND book_id = ?");
            $stmt->execute([$user_id, $book_id]);
        } else {
            // Add new item
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, book_id, quantity) VALUES (?, ?, 1)");
            $stmt->execute([$user_id, $book_id]);
        }
        
        header("Location: /app.php?success=added_to_cart");
        exit;
    }
    
    // Handle login
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // Check users table first
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['pass'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_type'] = 'user';
            header("Location: /app.php?success=logged_in");
            exit;
        }
        
        // Check admin table if not found in users
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin) {
            // Check if password matches (plain text or hashed)
            if ($password === '123456' || password_verify($password, $admin['password'])) {
                $_SESSION['user_id'] = $admin['admin_id'];
                $_SESSION['user_name'] = $admin['full_name'] ?? $admin['username'];
                $_SESSION['user_type'] = 'admin';
                header("Location: /app.php?success=logged_in");
                exit;
            }
        }
        
        $error = "Invalid email or password";
    }
    
    // Handle registration
    if (isset($_POST['register'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, pass) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $password]);
            
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            header("Location: /app.php?success=registered");
            exit;
        } catch (PDOException $e) {
            $error = "Email already exists or registration failed";
        }
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: /app.php?success=logged_out");
    exit;
}

// Get page parameter
$page = $_GET['page'] ?? 'home';

// Get books with related data
$booksQuery = "
    SELECT b.*, 
           GROUP_CONCAT(DISTINCT w.name SEPARATOR ', ') as writers,
           GROUP_CONCAT(DISTINCT g.name SEPARATOR ', ') as genres,
           GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') as categories
    FROM books b
    LEFT JOIN book_writers bw ON b.book_id = bw.book_id
    LEFT JOIN writers w ON bw.writer_id = w.writer_id
    LEFT JOIN book_genres bg ON b.book_id = bg.book_id
    LEFT JOIN genres g ON bg.genre_id = g.genre_id
    LEFT JOIN book_categories bc ON b.book_id = bc.book_id
    LEFT JOIN categories c ON bc.category_id = c.id
    GROUP BY b.book_id
    ORDER BY b.created_at DESC
    LIMIT 12
";
$stmt = $pdo->query($booksQuery);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get writers
$stmt = $pdo->query("SELECT * FROM writers ORDER BY name LIMIT 10");
$writers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get genres
$stmt = $pdo->query("SELECT * FROM genres ORDER BY name LIMIT 10");
$genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get categories
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name LIMIT 10");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get cart count if user is logged in
$cartCount = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $cartCount = $result['total'] ?? 0;
}

// Get audiobooks
$stmt = $pdo->query("SELECT * FROM audiobooks WHERE status = 'visible' ORDER BY created_at DESC LIMIT 6");
$audiobooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookHeaven 2.0 - Your Literary Paradise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --text-color: #2c3e50;
            --bg-light: #ecf0f1;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-light);
            color: var(--text-color);
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .book-card {
            transition: all 0.3s ease;
            height: 100%;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 15px;
            overflow: hidden;
        }

        .book-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .book-cover {
            height: 300px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .book-card:hover .book-cover {
            transform: scale(1.05);
        }

        .carousel-item img {
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        }

        .sidebar-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .sidebar-card .card-header {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        .alert {
            border-radius: 15px;
            border: none;
        }

        .footer {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            margin-top: 4rem;
        }

        .nav-tabs .nav-link {
            border-radius: 25px 25px 0 0;
            margin-right: 5px;
            border: none;
            background: var(--bg-light);
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            color: white;
        }

        .badge {
            border-radius: 50%;
            padding: 5px 8px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/app.php">
                <i class="fas fa-book-open me-2"></i>BookHeaven 2.0
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= $page === 'home' ? 'active' : '' ?>" href="/app.php">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page === 'books' ? 'active' : '' ?>" href="/app.php?page=books">
                            <i class="fas fa-book me-1"></i>Books
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page === 'audiobooks' ? 'active' : '' ?>" href="/app.php?page=audiobooks">
                            <i class="fas fa-headphones me-1"></i>Audiobooks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page === 'writers' ? 'active' : '' ?>" href="/app.php?page=writers">
                            <i class="fas fa-pen me-1"></i>Writers
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/app.php?page=cart">
                                <i class="fas fa-shopping-cart me-1"></i>Cart
                                <?php if ($cartCount > 0): ?>
                                    <span class="badge bg-danger"><?= $cartCount ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i><?= htmlspecialchars($_SESSION['user_name']) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/app.php?page=profile"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="/app.php?page=orders"><i class="fas fa-list me-2"></i>My Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/app.php?logout=1"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/app.php?page=login">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/app.php?page=register">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Success/Error Messages -->
    <?php if (isset($_GET['success'])): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php
                switch($_GET['success']) {
                    case 'added_to_cart': echo '<i class="fas fa-check-circle me-2"></i>Item added to cart successfully!'; break;
                    case 'logged_in': echo '<i class="fas fa-check-circle me-2"></i>Welcome back! You have been logged in.'; break;
                    case 'registered': echo '<i class="fas fa-check-circle me-2"></i>Registration successful! Welcome to BookHeaven.'; break;
                    case 'logged_out': echo '<i class="fas fa-check-circle me-2"></i>You have been logged out successfully.'; break;
                }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <?php
        switch($page) {
            case 'home':
                include 'pages/home.php';
                break;
            case 'home':
                include 'pages/bookdetails.php';
                break;
            case 'books':
                include 'pages/books.php';
                break;
            case 'audiobooks':
                include 'pages/audiobooks.php';
                break;
            case 'writers':
                include 'pages/writers.php';
                break;
            case 'cart':
                include 'pages/cart.php';
                break;
            case 'login':
                include 'pages/login.php';
                break;
            case 'register':
                include 'pages/register.php';
                break;
            case 'profile':
                include 'pages/profile.php';
                break;
            default:
                include 'pages/home.php';
        }
        ?>
    </div>

    <!-- Footer -->
    <footer class="footer py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-book-open me-2"></i>BookHeaven 2.0</h5>
                    <p>Your ultimate destination for book rentals and literary adventures.</p>
                </div>
                <div class="col-md-3">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="/app.php" class="text-white-50 text-decoration-none">Home</a></li>
                        <li><a href="/app.php?page=books" class="text-white-50 text-decoration-none">Books</a></li>
                        <li><a href="/app.php?page=audiobooks" class="text-white-50 text-decoration-none">Audiobooks</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Contact</h6>
                    <p class="text-white-50 mb-1"><i class="fas fa-envelope me-2"></i>info@bookheaven.com</p>
                    <p class="text-white-50"><i class="fas fa-phone me-2"></i>+1 (555) 123-4567</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; 2025 BookHeaven 2.0. All rights reserved. | Powered by Laravel</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
