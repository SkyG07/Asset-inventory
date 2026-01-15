<?php
function logAsset($pdo, $asset_id, $action, $description = null)
{
    $user_id = $_SESSION['user']['id'] ?? null;

    $stmt = $pdo->prepare("
        INSERT INTO asset_logs (asset_id, action, description, performed_by)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([$asset_id, $action, $description, $user_id]);
}
