<?php
require_once '../config/database.php';
require_once '../includes/log_helper.php';

$id = $_GET['id'] ?? null;

if ($id) {
    // Fetch username for logging
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    // Log deletion
    logAsset($pdo, null, 'USER DELETED', "User '{$user['username']}' was removed");
}

header('Location: ../views/users.php');
exit;
