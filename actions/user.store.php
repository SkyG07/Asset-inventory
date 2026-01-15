<?php
require_once '../config/database.php';
require_once '../includes/log_helper.php';

$name = trim($_POST['name']);
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$role = $_POST['role'] ?? 'staff';

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert user
$stmt = $pdo->prepare("
    INSERT INTO users (name, username, password, role)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([$name, $username, $hashed_password, $role]);

// Log the action
logAsset($pdo, null, 'USER CREATED', "New user '$username' added with role '$role'");

// Redirect back
header('Location: ../views/users.php');
exit;
