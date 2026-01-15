<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Fetch assets with office name
$stmt = $pdo->query("
    SELECT assets.*, offices.office_name
    FROM assets
    LEFT JOIN offices ON assets.office_id = offices.id
    ORDER BY assets.id DESC
");
$assets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch offices for dropdown
$offices = $pdo->query("SELECT * FROM offices ORDER BY office_name ASC")
    ->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid p-4">
    <h3 class="mb-3">ICT Assets</h3>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addAssetModal">
        <i class="fa fa-plus"></i> Add Asset
    </button>

    <table class="table table-bordered table-striped" id="assetsTable">
        <thead class="table-dark">
            <tr>
                <th>Property No</th>
                <th>Type</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Serial</th>
                <th>Condition</th>
                <th>Purchase Date</th>
                <th>Warranty End</th>
                <th>Under Warranty</th>
                <th>Office</th>
                <th>Remarks</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($assets as $asset): ?>
                <?php
                // Calculate Under Warranty
                $today = strtotime(date('Y-m-d'));
                $warrantyEnd = !empty($asset['warranty_end']) && $asset['warranty_end'] !== '0000-00-00'
                    ? strtotime($asset['warranty_end'])
                    : null;
                $underWarranty = $warrantyEnd && $warrantyEnd >= $today
                    ? '<span class="badge bg-success">Under Warranty</span>'
                    : '<span class="badge bg-danger">Expired</span>';
                ?>
                <tr>
                    <td><?= htmlspecialchars($asset['property_no']) ?></td>
                    <td><?= htmlspecialchars($asset['asset_type']) ?></td>
                    <td><?= htmlspecialchars($asset['brand']) ?></td>
                    <td><?= htmlspecialchars($asset['model'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($asset['serial_no'] ?? '-') ?></td>
                    <td>
                        <?php
                        $badge = match ($asset['condition']) {
                            'Good' => 'success',
                            'Repair' => 'warning',
                            'Condemned' => 'danger',
                            default => 'secondary'
                        };
                        ?>
                        <span class="badge bg-<?= $badge ?>">
                            <?= htmlspecialchars($asset['condition'] ?? '-') ?>
                        </span>
                    </td>
                    <td>
                        <?= isset($asset['purchase_date']) && $asset['purchase_date'] !== '0000-00-00'
                            ? date('M d, Y', strtotime($asset['purchase_date']))
                            : '-' ?>
                    </td>
                    <td>
                        <?= isset($asset['warranty_end']) && $asset['warranty_end'] !== '0000-00-00'
                            ? date('M d, Y', strtotime($asset['warranty_end']))
                            : '-' ?>
                    </td>
                    <td><?= $warrantyEnd ? $underWarranty : '-' ?></td>
                    <td><?= htmlspecialchars($asset['office_name'] ?? 'Unassigned') ?></td>
                    <td><?= htmlspecialchars($asset['remarks'] ?? '-') ?></td>
                    <td>
                        <a href="../reports/asset_profile.php?id=<?= $asset['id'] ?>"
                            class="btn btn-sm btn-info" target="_blank" title="View Asset Profile">
                            <i class="fa fa-file"></i>
                        </a>
                        <a href="../views/asset_edit.php?id=<?= $asset['id'] ?>"
                            class="btn btn-sm btn-warning" title="Edit Asset">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="../actions/asset.delete.php?id=<?= $asset['id'] ?>"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('Delete this asset?')"
                            title="Delete Asset">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- ADD ASSET MODAL -->
<div class="modal fade" id="addAssetModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="../actions/asset.store.php" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fa fa-plus-circle me-2 text-primary"></i> Add Asset
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Property No</label>
                        <input name="property_no" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Asset Type</label>
                        <input name="asset_type" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Brand</label>
                        <input name="brand" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Model</label>
                        <input name="model" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Serial No</label>
                        <input name="serial_no" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Condition</label>
                        <select name="condition" class="form-select" required>
                            <option value="Good">Good</option>
                            <option value="Repair">Repair</option>
                            <option value="Condemned">Condemned</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="fa fa-calendar me-1"></i> Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="fa fa-shield me-1"></i> Warranty End Date</label>
                        <input type="date" name="warranty_end" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Office</label>
                        <select name="office_id" class="form-select">
                            <option value="">-- Assign Office --</option>
                            <?php foreach ($offices as $office): ?>
                                <option value="<?= $office['id'] ?>">
                                    <?= htmlspecialchars($office['office_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2"
                            placeholder="Optional notes, issues, or history"></textarea>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Save Asset
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#assetsTable').DataTable({
            "order": [
                [0, "desc"]
            ],
            "columnDefs": [{
                    "orderable": false,
                    "targets": -1
                } // Actions column
            ]
        });
    });
</script>

<?php require_once '../includes/footer.php'; ?>