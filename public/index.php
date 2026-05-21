<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Ensure storage directories exist (required for Laravel Cloud)
$storageDirs = [
    __DIR__.'/../storage/framework/cache',
    __DIR__.'/../storage/framework/cache/data',
    __DIR__.'/../storage/framework/sessions',
    __DIR__.'/../storage/framework/views',
    __DIR__.'/../storage/logs',
    __DIR__.'/../storage/app/public',
    __DIR__.'/../storage/app/public/produk',
];
foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Ensure storage symlink exists for public disk (required for Laravel Cloud)
$storageLink = __DIR__.'/storage';
$storageTarget = __DIR__.'/../storage/app/public';

// Remove broken symlink or directory
if (is_link($storageLink)) {
    @unlink($storageLink);
} elseif (is_dir($storageLink)) {
    @rmdir($storageLink);
} elseif (is_file($storageLink)) {
    @unlink($storageLink);
}

// Create fresh symlink
if (!file_exists($storageLink)) {
    @symlink($storageTarget, $storageLink);
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
