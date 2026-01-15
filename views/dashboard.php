<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Fetch all offices for the dropdown
$offices = $pdo->query("SELECT * FROM offices ORDER BY office_name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Get selected office if any
$office_id = $_GET['office_id'] ?? null;

// Base SQL for counts
$whereOffice = $office_id ? "WHERE office_id = ?" : "";
$params = $office_id ? [$office_id] : [];

// Fetch counts
$totalAssets     = $pdo->prepare("SELECT COUNT(*) FROM assets $whereOffice");
$totalAssets->execute($params);
$totalAssets = $totalAssets->fetchColumn();

$goodAssets      = $pdo->prepare("SELECT COUNT(*) FROM assets WHERE `condition` = 'Good' " . ($office_id ? "AND office_id = ?" : ""));
$goodAssets->execute($params);
$goodAssets = $goodAssets->fetchColumn();

$repairAssets    = $pdo->prepare("SELECT COUNT(*) FROM assets WHERE `condition` = 'Repair' " . ($office_id ? "AND office_id = ?" : ""));
$repairAssets->execute($params);
$repairAssets = $repairAssets->fetchColumn();

$condemnedAssets = $pdo->prepare("SELECT COUNT(*) FROM assets WHERE `condition` = 'Condemned' " . ($office_id ? "AND office_id = ?" : ""));
$condemnedAssets->execute($params);
$condemnedAssets = $condemnedAssets->fetchColumn();
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">
            <i class="fa fa-dashboard me-2 text-primary"></i> Dashboard
        </h3>
        <span class="text-muted">Asset Inventory Overview</span>
    </div>

    <!-- Office Selector -->
    <div class="mb-4">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="office_id" class="form-select">
                    <option value="">-- All Offices --</option>
                    <?php foreach ($offices as $office): ?>
                        <option value="<?= $office['id'] ?>" <?= ($office_id == $office['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($office['office_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>

    <div class="row g-3">

        <!-- TOTAL ASSETS -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 text-primary fs-1">
                        <i class="fa fa-laptop"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Assets</h6>
                        <h2 class="fw-bold mb-0"><?= $totalAssets ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- GOOD -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 text-success fs-1">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Good Condition</h6>
                        <h2 class="fw-bold mb-0 text-success"><?= $goodAssets ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- REPAIR -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 text-warning fs-1">
                        <i class="fa fa-wrench"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">For Repair</h6>
                        <h2 class="fw-bold mb-0 text-warning"><?= $repairAssets ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONDEMNED -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 text-danger fs-1">
                        <i class="fa fa-trash"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Condemned</h6>
                        <h2 class="fw-bold mb-0 text-danger"><?= $condemnedAssets ?></h2>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- QUICK ACTIONS -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="fa fa-bolt text-primary me-2"></i> Quick Actions
                    </h5>

                    <a href="assets.php" class="btn btn-outline-primary me-2">
                        <i class="fa fa-list"></i> View Assets
                    </a>

                    <a href="assets.php#addAssetModal" class="btn btn-outline-success">
                        <i class="fa fa-plus"></i> Add Asset
                    </a>

                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>