<?php
session_start(); // For $_SESSION['user_id'] if logging

require_once '../config/database.php';
require_once '../includes/log_helper.php';

// --- Basic validation ---
$required_fields = ['property_no', 'asset_type', 'brand', 'condition'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        die("Error: '$field' is required.");
    }
}

// --- Prepare insertion SQL ---
// Note: `condition` is reserved, so backtick it
$stmt = $pdo->prepare("
    INSERT INTO assets
    (property_no, asset_type, brand, model, serial_no, `condition`, purchase_date, warranty_end, office_id, remarks)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

// --- Execute insertion ---
$stmt->execute([
    $_POST['property_no'],
    $_POST['asset_type'],
    $_POST['brand'],
    $_POST['model'] ?: null,            // optional
    $_POST['serial_no'] ?: null,        // optional
    $_POST['condition'],
    $_POST['purchase_date'] ?: null,    // optional date
    $_POST['warranty_end'] ?: null,     // optional date
    $_POST['office_id'] ?: null,        // optional foreign key
    $_POST['remarks'] ?: null           // optional remarks
]);

// --- Get last inserted asset ID ---
$asset_id = $pdo->lastInsertId();

// --- Log the asset creation ---
$performed_by = $_SESSION['user_id'] ?? 1; // default admin if session missing
logAsset(
    $pdo,
    $asset_id,
    'ASSET CREATED',
    "Asset added: Property No {$_POST['property_no']}, Type {$_POST['asset_type']}, Brand {$_POST['brand']}",
    $performed_by
);

// --- Redirect back to assets page ---
header('Location: ../views/assets.php');
exit;
