<?php
session_start();
require_once '../config/database.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/login.php');
    exit;
}

// Get email and password
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// Check empty fields
if ($email === '' || $password === '') {
    $_SESSION['error'] = 'Please fill in all fields';
    header('Location: ../views/login.php');
    exit;
}

// Fetch user by email
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['error'] = 'Invalid credentials';
    header('Location: ../views/login.php');
    exit;
}

// Use email as username if username column doesn't exist
$_SESSION['user'] = [
    'id' => $user['id'],
    'username' => $user['username'] ?? $user['email'],
    'name' => $user['name'],
    'role' => $user['role']
];

// Optional: log login if function exists
if (function_exists('logAsset')) {
    logAsset($pdo, null, 'USER LOGIN', "User '{$_SESSION['user']['username']}' logged in");
}

// Redirect to dashboard
header('Location: ../views/dashboard.php');
exit;
