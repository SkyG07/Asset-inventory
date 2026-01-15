<?php
require_once '../config/database.php';

$office_name = trim($_POST['office_name']);
$office_code = trim($_POST['office_code']);

$stmt = $pdo->prepare("
    INSERT INTO offices (office_name, office_code)
    VALUES (?, ?)
");

$stmt->execute([$office_name, $office_code ?: null]);

header('Location: ../views/offices.php');
exit;
