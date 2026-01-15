<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

$asset_id = $_GET['id'] ?? null;
if (!$asset_id) die('Asset not specified.');

// Fetch asset info
$stmt = $pdo->prepare("SELECT * FROM assets WHERE id = ?");
$stmt->execute([$asset_id]);
$asset = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$asset) die('Asset not found.');

// Fetch offices for dropdown
$offices = $pdo->query("SELECT * FROM offices ORDER BY office_name")->fetchAll(PDO::FETCH_ASSOC);

// Safely get asset fields
$property_no   = htmlspecialchars($asset['property_no'] ?? '');
$asset_type    = htmlspecialchars($asset['asset_type'] ?? '');
$brand         = htmlspecialchars($asset['brand'] ?? '');
$model         = htmlspecialchars($asset['model'] ?? '');
$serial_no     = htmlspecialchars($asset['serial_no'] ?? '');
$condition     = $asset['condition'] ?? 'Good';
$purchase_date = $asset['purchase_date'] ?? '';
$warranty_end  = $asset['warranty_end'] ?? '';
$office_id     = $asset['office_id'] ?? '';
$remarks       = htmlspecialchars($asset['remarks'] ?? '');
?>

<div class="container mt-4">
    <h3 class="mb-4">Edit Asset</h3>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fa fa-edit"></i> Asset Details
        </div>
        <div class="card-body">
            <form method="POST" action="../actions/asset.update.php">
                <input type="hidden" name="id" value="<?= $asset_id ?>">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Property No</label>
                        <input type="text" name="property_no" class="form-control"
                            value="<?= $property_no ?>" placeholder="Enter Property No" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Asset Type</label>
                        <input type="text" name="asset_type" class="form-control"
                            value="<?= $asset_type ?>" placeholder="Enter Asset Type" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Brand</label>
                        <input type="text" name="brand" class="form-control"
                            value="<?= $brand ?>" placeholder="Brand" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Model</label>
                        <input type="text" name="model" class="form-control"
                            value="<?= $model ?>" placeholder="Model">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Serial No</label>
                        <input type="text" name="serial_no" class="form-control"
                            value="<?= $serial_no ?>" placeholder="Serial No">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Condition</label>
                        <select name="condition" class="form-select">
                            <?php
                            $conditions = ['Good', 'Repair', 'Condemned'];
                            foreach ($conditions as $c): ?>
                                <option value="<?= $c ?>" <?= $condition === $c ? 'selected' : '' ?>>
                                    <?= $c ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control"
                            value="<?= $purchase_date ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Warranty End</label>
                        <input type="date" name="warranty_end" class="form-control"
                            value="<?= $warranty_end ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Office</label>
                        <select name="office_id" class="form-select">
                            <option value="">-- Assign Office --</option>
                            <?php foreach ($offices as $office): ?>
                                <option value="<?= $office['id'] ?>" <?= $office_id == $office['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($office['office_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3"
                            placeholder="Optional notes"><?= $remarks ?></textarea>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="assets.php" class="btn btn-secondary me-2">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Update Asset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>