<?php
// Get filter parameters
$writerFilter = $_GET['writer'] ?? '';
$genreFilter = $_GET['genre'] ?? '';
$categoryFilter = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

// Build query with filters
$whereConditions = [];
$params = [];

if ($writerFilter) {
    $whereConditions[] = "bw.writer_id = ?";
    $params[] = $writerFilter;
}

if ($genreFilter) {
    $whereConditions[] = "bg.genre_id = ?";
    $params[] = $genreFilter;
}

if ($categoryFilter) {
    $whereConditions[] = "bc.category_id = ?";
    $params[] = $categoryFilter;
}

if ($search) {
    $whereConditions[] = "(b.title LIKE ? OR w.name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$whereClause = !empty($whereConditions) ? "WHERE " . implode(" AND ", $whereConditions) : "";

$booksQuery = "
    SELECT DISTINCT b.*, 
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
    $whereClause
    GROUP BY b.book_id
    ORDER BY b.created_at DESC
";

$stmt = $pdo->prepare($booksQuery);
$stmt->execute($params);
$filteredBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-md-3">
        <!-- Search and Filters -->
        <div class="card sidebar-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-search me-2"></i>Search & Filter</h5>
            </div>
            <div class="card-body">
                <form method="GET">
                    <input type="hidden" name="page" value="books">
                    
                    <!-- Search -->
                    <div class="mb-3">
                        <label for="search" class="form-label">Search Books</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?= htmlspecialchars($search) ?>" placeholder="Title or Author">
                    </div>
                    
                    <!-- Writer Filter -->
                    <div class="mb-3">
                        <label for="writer" class="form-label">Writer</label>
                        <select class="form-select" id="writer" name="writer">
                            <option value="">All Writers</option>
                            <?php foreach($writers as $writer): ?>
                                <option value="<?= $writer['writer_id'] ?>" 
                                        <?= $writerFilter == $writer['writer_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($writer['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Genre Filter -->
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre</label>
                        <select class="form-select" id="genre" name="genre">
                            <option value="">All Genres</option>
                            <?php foreach($genres as $genre): ?>
                                <option value="<?= $genre['genre_id'] ?>" 
                                        <?= $genreFilter == $genre['genre_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($genre['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">All Categories</option>
                            <?php foreach($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" 
                                        <?= $categoryFilter == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Apply Filters
                    </button>
                </form>
                
                <?php if ($writerFilter || $genreFilter || $categoryFilter || $search): ?>
                    <a href="/app.php?page=books" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-book me-2"></i>Books Collection</h2>
            <span class="text-muted"><?= count($filteredBooks) ?> books found</span>
        </div>
        
        <?php if (empty($filteredBooks)): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>No books found</h4>
                    <p class="text-muted">Try adjusting your search criteria</p>
                    <a href="/app.php?page=books" class="btn btn-primary">
                        <i class="fas fa-list me-2"></i>View All Books
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach($filteredBooks as $book): ?>
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card book-card">
                            <img src="<?= htmlspecialchars($book['cover_image_url'] ?? 'https://via.placeholder.com/300x400/f8f9fa/6c757d?text=No+Cover') ?>" 
                                 class="card-img-top book-cover" alt="<?= htmlspecialchars($book['title']) ?>">
                            <div class="card-body">
                                <h6 class="card-title fw-bold"><?= htmlspecialchars($book['title']) ?></h6>
                                <?php if (!empty($book['writers'])): ?>
                                    <p class="text-muted small mb-1">
                                        <i class="fas fa-user me-1"></i><?= htmlspecialchars($book['writers']) ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($book['genres'])): ?>
                                    <p class="text-muted small mb-1">
                                        <i class="fas fa-tags me-1"></i><?= htmlspecialchars($book['genres']) ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($book['categories'])): ?>
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-folder me-1"></i><?= htmlspecialchars($book['categories']) ?>
                                    </p>
                                <?php endif; ?>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-primary fw-bold fs-5">$<?= number_format($book['price'], 2) ?></span>
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
                                            <button type="submit" name="add_to_cart" class="btn btn-primary btn-sm">
                                                <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <a href="/app.php?page=login" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-sign-in-alt me-1"></i>Login
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <?php if ($book['quantity'] > 0): ?>
                                    <small class="text-success">
                                        <i class="fas fa-check-circle me-1"></i><?= $book['quantity'] ?> in stock
                                    </small>
                                <?php else: ?>
                                    <small class="text-danger">
                                        <i class="fas fa-times-circle me-1"></i>Out of stock
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
