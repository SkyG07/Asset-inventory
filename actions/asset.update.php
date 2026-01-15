<?php
require_once '../config/database.php';
require_once '../includes/log_helper.php';

// Get POST values
$id            = $_POST['id'] ?? null;
$property_no   = $_POST['property_no'] ?? '';
$asset_type    = $_POST['asset_type'] ?? '';
$brand         = $_POST['brand'] ?? '';
$model         = $_POST['model'] ?? '';
$serial_no     = $_POST['serial_no'] ?? '';
$condition     = $_POST['condition'] ?? 'Good';
$purchase_date = $_POST['purchase_date'] ?: null;
$warranty_end  = $_POST['warranty_end'] ?: null;
$office_id     = $_POST['office_id'] ?: null; // nullable
$remarks       = $_POST['remarks'] ?: null;   // new remarks field

if (!$id) {
    die('Invalid asset ID.');
}

// Update asset in DB
$stmt = $pdo->prepare("
    UPDATE assets SET
        property_no   = ?,
        asset_type    = ?,
        brand         = ?,
        model         = ?,
        serial_no     = ?,
        `condition`   = ?,
        purchase_date = ?,
        warranty_end  = ?,
        office_id     = ?,
        remarks       = ?
    WHERE id = ?
");

$stmt->execute([
    $property_no,
    $asset_type,
    $brand,
    $model,
    $serial_no,
    $condition,
    $purchase_date,
    $warranty_end,
    $office_id,
    $remarks,
    $id
]);

// Log update
logAsset(
    $pdo,
    $id,
    'ASSET UPDATED',
    "Updated asset $property_no | Type: $asset_type | Condition: $condition | Office ID: $office_id | Remarks: $remarks"
);

// Redirect back
header('Location: ../views/assets.php');
exit;
