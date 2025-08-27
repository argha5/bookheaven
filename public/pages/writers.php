<?php
// Get all writers with their book counts
$writersQuery = "
    SELECT w.*, COUNT(DISTINCT bw.book_id) as book_count
    FROM writers w
    LEFT JOIN book_writers bw ON w.writer_id = bw.writer_id
    GROUP BY w.writer_id
    ORDER BY w.name ASC
";
$stmt = $pdo->query($writersQuery);
$allWriters = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get selected writer details if ID is provided
$writerId = $_GET['id'] ?? '';
$selectedWriter = null;
$writerBooks = [];

if ($writerId) {
    $stmt = $pdo->prepare("SELECT * FROM writers WHERE writer_id = ?");
    $stmt->execute([$writerId]);
    $selectedWriter = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($selectedWriter) {
        // Get books by this writer
        $booksQuery = "
            SELECT DISTINCT b.*, 
                   GROUP_CONCAT(DISTINCT g.name SEPARATOR ', ') as genres,
                   GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') as categories
            FROM books b
            JOIN book_writers bw ON b.book_id = bw.book_id
            LEFT JOIN book_genres bg ON b.book_id = bg.book_id
            LEFT JOIN genres g ON bg.genre_id = g.genre_id
            LEFT JOIN book_categories bc ON b.book_id = bc.book_id
            LEFT JOIN categories c ON bc.category_id = c.id
            WHERE bw.writer_id = ?
            GROUP BY b.book_id
            ORDER BY b.created_at DESC
        ";
        $stmt = $pdo->prepare($booksQuery);
        $stmt->execute([$writerId]);
        $writerBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<?php if ($selectedWriter): ?>
    <!-- Writer Profile -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <?php if (!empty($selectedWriter['image_url'])): ?>
                        <img src="<?= htmlspecialchars($selectedWriter['image_url']) ?>" 
                             class="img-fluid rounded-circle mb-3" alt="<?= htmlspecialchars($selectedWriter['name']) ?>"
                             style="width: 200px; height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 200px; height: 200px;">
                            <i class="fas fa-user fa-5x text-white"></i>
                        </div>
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($selectedWriter['name']) ?></h3>
                    <p class="text-muted">
                        <i class="fas fa-book me-2"></i><?= count($writerBooks) ?> Books
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-pen me-2"></i><?= htmlspecialchars($selectedWriter['name']) ?></h2>
                <a href="/app.php?page=writers" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Writers
                </a>
            </div>
            
            <?php if (!empty($selectedWriter['bio'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle me-2"></i>Biography</h5>
                    </div>
                    <div class="card-body">
                        <p><?= nl2br(htmlspecialchars($selectedWriter['bio'])) ?></p>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-books me-2"></i>Books by <?= htmlspecialchars($selectedWriter['name']) ?></h5>
                </div>
                <div class="card-body">
                    <?php if (empty($writerBooks)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <h5>No books found</h5>
                            <p class="text-muted">This writer hasn't published any books yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach($writerBooks as $book): ?>
                                <div class="col-md-4 col-lg-3 mb-4">
                                    <div class="card book-card">
                                        <img src="<?= htmlspecialchars($book['cover_image_url'] ?? 'https://via.placeholder.com/300x400/f8f9fa/6c757d?text=No+Cover') ?>" 
                                             class="card-img-top book-cover" alt="<?= htmlspecialchars($book['title']) ?>">
                                        <div class="card-body">
                                            <h6 class="card-title fw-bold"><?= htmlspecialchars($book['title']) ?></h6>
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
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-primary fw-bold fs-5">$<?= number_format($book['price'], 2) ?></span>
                                                <?php if (isset($_SESSION['user_id'])): ?>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
                                                        <button type="submit" name="add_to_cart" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-cart-plus me-1"></i>Add
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <a href="/app.php?page=login" class="btn btn-outline-primary btn-sm">Login</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Writers List -->
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-users me-2"></i>Our Writers</h2>
            <p class="text-muted mb-4">Discover amazing authors and their literary works</p>
        </div>
    </div>
    
    <div class="row">
        <?php if (empty($allWriters)): ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h4>No writers found</h4>
                        <p class="text-muted">Check back later for new author profiles</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php foreach($allWriters as $writer): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card book-card h-100">
                        <div class="card-body text-center">
                            <?php if (!empty($writer['image_url'])): ?>
                                <img src="<?= htmlspecialchars($writer['image_url']) ?>" 
                                     class="img-fluid rounded-circle mb-3" alt="<?= htmlspecialchars($writer['name']) ?>"
                                     style="width: 120px; height: 120px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width: 120px; height: 120px;">
                                    <i class="fas fa-user fa-3x text-white"></i>
                                </div>
                            <?php endif; ?>
                            
                            <h5 class="card-title"><?= htmlspecialchars($writer['name']) ?></h5>
                            
                            <?php if (!empty($writer['bio'])): ?>
                                <p class="text-muted small mb-3">
                                    <?= htmlspecialchars(substr($writer['bio'], 0, 120)) ?>
                                    <?= strlen($writer['bio']) > 120 ? '...' : '' ?>
                                </p>
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <span class="badge bg-primary">
                                    <i class="fas fa-book me-1"></i><?= $writer['book_count'] ?> Books
                                </span>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="/app.php?page=writers&id=<?= $writer['writer_id'] ?>" class="btn btn-primary">
                                    <i class="fas fa-eye me-2"></i>View Profile
                                </a>
                                <a href="/app.php?page=books&writer=<?= $writer['writer_id'] ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-book me-2"></i>View Books
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
