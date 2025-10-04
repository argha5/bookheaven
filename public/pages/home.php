<!-- Carousel Section -->
<div class="row mb-4">
    <div class="col-12">
        <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="../assets/images/1.png" class="d-block w-100" alt="Book Rental Promotion" style="height: 400px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block">
                        <h2>Welcome to BookHeaven 2.0</h2>
                        <p>Your ultimate book rental destination</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="../assets/images/2.png" class="d-block w-100" alt="Audiobook Collection" style="height: 400px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block">
                        <h2>Audiobook Collection</h2>
                        <p>Listen to your favorite stories</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="../assets/images/3.png" class="d-block w-100" alt="Become a Writer" style="height: 400px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block">
                        <h2>Become a Writer</h2>
                        <p>Share your stories with the world</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sidebar -->
    <div class="col-md-3">
        <!-- Writers Card -->
        <div class="card sidebar-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Popular Writers</h5>
            </div>
            <div class="card-body">
                <?php foreach($writers as $writer): ?>
                    <div class="mb-3 p-2 rounded" style="background: rgba(52, 152, 219, 0.1);">
                        <a href="/app.php?page=books&writer=<?= $writer['writer_id'] ?>" class="text-decoration-none fw-bold">
                            <i class="fas fa-pen me-2 text-primary"></i><?= htmlspecialchars($writer['name']) ?>
                        </a>
                        <?php if (!empty($writer['bio'])): ?>
                            <p class="small text-muted mb-0 mt-1"><?= htmlspecialchars(substr($writer['bio'], 0, 80)) ?>...</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Genres Card -->
        <div class="card sidebar-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Genres</h5>
            </div>
            <div class="card-body">
                <?php foreach($genres as $genre): ?>
                    <div class="mb-2">
                        <a href="/app.php?page=books&genre=<?= $genre['genre_id'] ?>" class="text-decoration-none d-flex align-items-center">
                            <i class="fas fa-bookmark me-2 text-secondary"></i>
                            <span><?= htmlspecialchars($genre['name']) ?></span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Categories Card -->
        <div class="card sidebar-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Categories</h5>
            </div>
            <div class="card-body">
                <?php foreach($categories as $category): ?>
                    <div class="mb-2">
                        <a href="/app.php?page=books&category=<?= $category['id'] ?>" class="text-decoration-none d-flex align-items-center">
                            <i class="fas fa-folder me-2 text-info"></i>
                            <span><?= htmlspecialchars($category['name']) ?></span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-md-9">
        <!-- Promo Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                    <div class="card-body text-center">
                        <i class="fas fa-book fa-3x mb-3"></i>
                        <h5>10,000+ Books</h5>
                        <p>Vast collection of books</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                    <div class="card-body text-center">
                        <i class="fas fa-headphones fa-3x mb-3"></i>
                        <h5>Audiobooks</h5>
                        <p>Listen on the go</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white" style="background: linear-gradient(135deg, #27ae60, #229954);">
                    <div class="card-body text-center">
                        <i class="fas fa-shipping-fast fa-3x mb-3"></i>
                        <h5>Fast Delivery</h5>
                        <p>Quick book delivery</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabbed Book Sections -->
        <ul class="nav nav-tabs" id="bookTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="featured-tab" data-bs-toggle="tab" data-bs-target="#featured" type="button">
                    <i class="fas fa-star me-2"></i>Featured Books
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="latest-tab" data-bs-toggle="tab" data-bs-target="#latest" type="button">
                    <i class="fas fa-clock me-2"></i>Latest Arrivals
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="audiobooks-tab" data-bs-toggle="tab" data-bs-target="#audiobooks-section" type="button">
                    <i class="fas fa-headphones me-2"></i>Audiobooks
                </button>
            </li>
        </ul>

        <div class="tab-content" id="bookTabsContent">
            <!-- Featured Books -->
            <div class="tab-pane fade show active" id="featured" role="tabpanel">
                <div class="row mt-4">
                    <?php foreach($books as $book): ?>
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
                                        <p class="text-muted small mb-2">
                                            <i class="fas fa-tags me-1"></i><?= htmlspecialchars($book['genres']) ?>
                                        </p>
                                    <?php endif; ?>
                                    <div class="d-flex justify-content-between align-items-center">
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
                                                <i class="fas fa-sign-in-alt me-1"></i>Login to Buy
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
            </div>

            <!-- Latest Arrivals -->
            <div class="tab-pane fade" id="latest" role="tabpanel">
                <div class="row mt-4">
                    <?php 
                    $latestBooks = array_slice($books, 0, 8);
                    foreach($latestBooks as $book): 
                    ?>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card book-card">
                                <div class="position-relative">
                                    <img src="<?= htmlspecialchars($book['cover_image_url'] ?? 'https://via.placeholder.com/300x400/f8f9fa/6c757d?text=No+Cover') ?>" 
                                         class="card-img-top book-cover" alt="<?= htmlspecialchars($book['title']) ?>">
                                    <span class="badge bg-success position-absolute top-0 end-0 m-2">New</span>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title fw-bold"><?= htmlspecialchars($book['title']) ?></h6>
                                    <?php if (!empty($book['writers'])): ?>
                                        <p class="text-muted small mb-2">
                                            <i class="fas fa-user me-1"></i><?= htmlspecialchars($book['writers']) ?>
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
            </div>

            <!-- Audiobooks Section -->
            <div class="tab-pane fade" id="audiobooks-section" role="tabpanel">
                <div class="row mt-4">
                    <?php foreach($audiobooks as $audiobook): ?>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card book-card">
                                <div class="position-relative">
                                    <img src="<?= htmlspecialchars($audiobook['poster_url'] ?? 'https://via.placeholder.com/300x400/9b59b6/ffffff?text=Audiobook') ?>" 
                                         class="card-img-top book-cover" alt="<?= htmlspecialchars($audiobook['title']) ?>">
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <i class="fas fa-play-circle fa-3x text-white opacity-75"></i>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title fw-bold"><?= htmlspecialchars($audiobook['title']) ?></h6>
                                    <p class="text-muted small mb-1">
                                        <i class="fas fa-user me-1"></i><?= htmlspecialchars($audiobook['writer']) ?>
                                    </p>
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-clock me-1"></i><?= htmlspecialchars($audiobook['duration']) ?> mins
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-info fw-bold">
                                            <i class="fas fa-headphones me-1"></i>Listen
                                        </span>
                                        <a href="/app.php?page=audiobooks&id=<?= $audiobook['audiobook_id'] ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-play me-1"></i>Play
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
