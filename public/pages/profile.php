<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: /app.php?page=login");
    exit;
}

// Get user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: /app.php?page=login");
    exit;
}

// Get user's recent orders (if orders table exists)
$recentOrders = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$_SESSION['user_id']]);
    $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Orders table might not exist yet
}

// Get cart summary
$stmt = $pdo->prepare("SELECT COUNT(*) as item_count, SUM(quantity) as total_items FROM cart WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cartSummary = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-md-4">
        <!-- Profile Card -->
        <div class="card">
            <div class="card-header text-center" style="background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%); color: white;">
                <h5><i class="fas fa-user me-2"></i>My Profile</h5>
            </div>
            <div class="card-body text-center">
                <?php if (!empty($user['profile_image_url'])): ?>
                    <img src="<?= htmlspecialchars($user['profile_image_url']) ?>" 
                         class="img-fluid rounded-circle mb-3" alt="Profile Picture"
                         style="width: 150px; height: 150px; object-fit: cover;">
                <?php else: ?>
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 150px; height: 150px;">
                        <i class="fas fa-user fa-4x text-white"></i>
                    </div>
                <?php endif; ?>
                
                <h4><?= htmlspecialchars($user['name']) ?></h4>
                <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                
                <div class="row text-center mt-4">
                    <div class="col-6">
                        <div class="border-end">
                            <h5 class="text-primary"><?= $cartSummary['total_items'] ?? 0 ?></h5>
                            <small class="text-muted">Cart Items</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="text-success"><?= count($recentOrders) ?></h5>
                        <small class="text-muted">Orders</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h6><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/app.php?page=cart" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-2"></i>View Cart
                    </a>
                    <a href="/app.php?page=books" class="btn btn-outline-primary">
                        <i class="fas fa-book me-2"></i>Browse Books
                    </a>
                    <a href="/app.php?page=audiobooks" class="btn btn-outline-info">
                        <i class="fas fa-headphones me-2"></i>Audiobooks
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Profile Information -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-info-circle me-2"></i>Profile Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($user['name']) ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($user['email']) ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <p class="form-control-plaintext">
                                <?= !empty($user['phone']) ? htmlspecialchars($user['phone']) : '<span class="text-muted">Not provided</span>' ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Member Since</label>
                            <p class="form-control-plaintext">
                                <?= date('F j, Y', strtotime($user['created_at'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Address</label>
                    <p class="form-control-plaintext">
                        <?= !empty($user['address']) ? nl2br(htmlspecialchars($user['address'])) : '<span class="text-muted">Not provided</span>' ?>
                    </p>
                </div>
                
                <div class="mt-4">
                    <button class="btn btn-primary me-2">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </button>
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-key me-2"></i>Change Password
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Recent Orders -->
        <?php if (!empty($recentOrders)): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h5><i class="fas fa-history me-2"></i>Recent Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentOrders as $order): ?>
                            <tr>
                                <td>#<?= $order['id'] ?></td>
                                <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                                <td>$<?= number_format($order['total_amount'], 2) ?></td>
                                <td>
                                    <span class="badge bg-<?= $order['status'] === 'completed' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View Details</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="card mt-4">
            <div class="card-body text-center py-4">
                <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                <h5>No Orders Yet</h5>
                <p class="text-muted">Start shopping to see your order history here</p>
                <a href="/app.php?page=books" class="btn btn-primary">
                    <i class="fas fa-book me-2"></i>Browse Books
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
