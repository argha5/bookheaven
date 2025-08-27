<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: /app.php?page=login");
    exit;
}

// Get cart items
$stmt = $pdo->prepare("
    SELECT c.*, b.title, b.price, b.cover_image_url, 
           GROUP_CONCAT(DISTINCT w.name SEPARATOR ', ') as writers
    FROM cart c
    JOIN books b ON c.book_id = b.book_id
    LEFT JOIN book_writers bw ON b.book_id = bw.book_id
    LEFT JOIN writers w ON bw.writer_id = w.writer_id
    WHERE c.user_id = ?
    GROUP BY c.id
");
$stmt->execute([$_SESSION['user_id']]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<div class="row">
    <div class="col-md-8">
        <h2><i class="fas fa-shopping-cart me-2"></i>Shopping Cart</h2>
        
        <?php if (empty($cartItems)): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h4>Your cart is empty</h4>
                    <p class="text-muted">Start shopping to add items to your cart</p>
                    <a href="/app.php?page=books" class="btn btn-primary">
                        <i class="fas fa-book me-2"></i>Browse Books
                    </a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <img src="<?= htmlspecialchars($item['cover_image_url'] ?? 'https://via.placeholder.com/150x200') ?>" 
                                     class="img-fluid rounded" alt="<?= htmlspecialchars($item['title']) ?>">
                            </div>
                            <div class="col-md-4">
                                <h5><?= htmlspecialchars($item['title']) ?></h5>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-user me-1"></i><?= htmlspecialchars($item['writers']) ?>
                                </p>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary btn-sm" type="button">-</button>
                                    <input type="text" class="form-control text-center" value="<?= $item['quantity'] ?>" readonly>
                                    <button class="btn btn-outline-secondary btn-sm" type="button">+</button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <h5 class="text-primary">$<?= number_format($item['price'], 2) ?></h5>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <?php if (!empty($cartItems)): ?>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-receipt me-2"></i>Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>$<?= number_format($total, 2) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Shipping:</span>
                    <span>$5.00</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total:</strong>
                    <strong class="text-primary">$<?= number_format($total + 5, 2) ?></strong>
                </div>
                <button class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
                </button>
                <a href="/app.php?page=books" class="btn btn-outline-primary w-100">
                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
