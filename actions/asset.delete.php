<?php
require_once '../config/database.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM assets WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: ../views/assets.php');
exit;
