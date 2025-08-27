<?php

// Simple autoloader for Laravel without Composer
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $file = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    // Try app directory
    $appFile = __DIR__ . '/../app/' . str_replace(['App\\', '\\'], ['', '/'], $class) . '.php';
    if (file_exists($appFile)) {
        require_once $appFile;
        return true;
    }
    
    return false;
});

// Define required constants
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}
