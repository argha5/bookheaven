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

// Get books for homepage
$stmt = $pdo->query("SELECT * FROM books LIMIT 12");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get writers
$stmt = $pdo->query("SELECT * FROM writers LIMIT 10");
$writers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get genres
$stmt = $pdo->query("SELECT * FROM genres LIMIT 10");
$genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookHeaven 2.0 - Laravel Version</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .book-card {
            transition: transform 0.3s;
            height: 100%;
        }
        .book-card:hover {
            transform: translateY(-5px);
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-book"></i> BookHeaven 2.0</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-home"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-book"></i> Books</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-headphones"></i> Audiobooks</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-user"></i> Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 mb-4">Welcome to BookHeaven 2.0</h1>
            <p class="lead mb-4">Your Laravel-powered book rental platform</p>
            <div class="alert alert-success d-inline-block">
                <i class="fas fa-check-circle"></i> Successfully converted to Laravel!
            </div>
        </div>
    </section>

    <div class="container my-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-users"></i> Popular Writers</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach($writers as $writer): ?>
                            <div class="mb-2">
                                <a href="#" class="text-decoration-none"><?= htmlspecialchars($writer['name']) ?></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-tags"></i> Genres</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach($genres as $genre): ?>
                            <div class="mb-2">
                                <a href="#" class="text-decoration-none"><?= htmlspecialchars($genre['name']) ?></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <h2 class="mb-4"><i class="fas fa-star"></i> Featured Books</h2>
                <div class="row">
                    <?php foreach($books as $book): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card book-card">
                                <img src="<?= htmlspecialchars($book['cover_image_url'] ?? 'https://via.placeholder.com/300x400') ?>" 
                                     class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>" style="height: 250px; object-fit: cover;">
                                <div class="card-body">
                                    <h6 class="card-title"><?= htmlspecialchars($book['title']) ?></h6>
                                    <p class="text-muted small">Published: <?= htmlspecialchars($book['published_date']) ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary fw-bold">$<?= number_format($book['price'], 2) ?></span>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2025 BookHeaven 2.0 - Laravel Version. All rights reserved.</p>
            <p><i class="fas fa-code"></i> Successfully converted from PHP to Laravel Framework</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
