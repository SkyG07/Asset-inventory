<?php
// router.php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Map the requested URI to a real file
$requestedFile = __DIR__ . $uri;

// Serve the requested file if it exists
if ($uri !== '/' && file_exists($requestedFile)) {
    return false; // Serve directly
}

// Otherwise, try to route to the file relative to the project root
// Remove leading slash
$path = ltrim($uri, '/');

// Check if the file exists relative to the root
if ($path && file_exists(__DIR__ . '/' . $path)) {
    include __DIR__ . '/' . $path;
    exit;
}

// If nothing found, show 404
http_response_code(404);
echo "<h2>404 - Page not found</h2>";
echo "<p>Cannot find <strong>{$uri}</strong></p>";
