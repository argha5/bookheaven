<?php
session_start();
require_once '../admin/db.php'; // Your database connection file

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    // Check for all active subscriptions
    $stmt = $pdo->prepare("
        SELECT us.*, sp.plan_name, sp.audiobook_quantity 
        FROM user_subscriptions us
        JOIN subscription_plans sp ON us.subscription_plan_id = sp.plan_id
        WHERE us.user_id = ? AND us.status = 'active' AND us.end_date > NOW()
        ORDER BY us.end_date DESC
    ");
    $stmt->execute([$userId]);
    $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($subscriptions)) {
        echo json_encode(['success' => false, 'message' => 'No active subscriptions found']);
        exit;
    }

    // Get all accessible audiobooks from all active subscriptions
    $allAudiobooks = [];
    $subscriptionIds = array_column($subscriptions, 'user_subscription_id');
    
    // Create placeholders for the IN clause
    $placeholders = implode(',', array_fill(0, count($subscriptionIds), '?'));
    
    $stmt = $pdo->prepare("
        SELECT ab.*, us.subscription_plan_id, sp.plan_name
        FROM audiobooks ab
        JOIN user_subscription_audiobook_access access ON ab.audiobook_id = access.audiobook_id
        JOIN user_subscriptions us ON access.user_subscription_id = us.user_subscription_id
        JOIN subscription_plans sp ON us.subscription_plan_id = sp.plan_id
        WHERE access.user_subscription_id IN ($placeholders) AND access.status = 'borrowed'
        ORDER BY us.end_date DESC, ab.title
    ");
    $stmt->execute($subscriptionIds);
    $audiobooks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'subscriptions' => $subscriptions,
        'audiobooks' => $audiobooks
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}