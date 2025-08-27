<?php
// Simple entry point for XAMPP without full Laravel setup
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
    die("Connection failed: " . $e->getMessage());
}

// Simple routing
$page = $_GET['page'] ?? 'home';

switch($page) {
    case 'home':
        include 'simple_views/home.php';
        break;
    case 'books':
        include 'simple_views/books.php';
        break;
    case 'login':
        include 'simple_views/login.php';
        break;
    default:
        include 'simple_views/home.php';
}
?>
