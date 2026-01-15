<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Fetch all offices for dropdown
$offices = $pdo->query("SELECT * FROM offices ORDER BY office_name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Get selected office
$office_id = $_GET['office_id'] ?? null;
$assets = [];

if ($office_id) {
    $stmt = $pdo->prepare("
        SELECT a.*, o.office_name
        FROM assets a
        LEFT JOIN offices o ON a.office_id = o.id
        WHERE o.id = ?
        ORDER BY a.property_no
    ");
    $stmt->execute([$office_id]);
    $assets = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container-fluid p-4">

    <h3 class="mb-3">Asset Inventory per Office</h3>

    <!-- Office Selector -->
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-md-6">
                <select name="office_id" class="form-select" required>
                    <option value="">-- Select Office --</option>
                    <?php foreach ($offices as $office): ?>
                        <option value="<?= $office['id'] ?>" <?= ($office_id == $office['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($office['office_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary">Generate</button>
            </div>
        </div>
    </form>

    <?php if ($office_id): ?>
        <h5 class="mb-3">
            Office: <strong><?= htmlspecialchars($assets[0]['office_name'] ?? 'No assets found') ?></strong>
        </h5>

        <table class="table table-bordered table-striped table-sm" id="officeAssetsTable">
            <thead class="table-dark">
                <tr>
                    <th>Property No</th>
                    <th>Type</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Condition</th>
                    <th>Purchase Date</th>
                    <th>Warranty End</th>
                    <th>Warranty Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($assets): ?>
                    <?php foreach ($assets as $asset): ?>
                        <?php
                        $purchaseDate = $asset['purchase_date'] ?? null;
                        $warrantyEnd  = $asset['warranty_end'] ?? null;

                        if ($warrantyEnd) {
                            $warrantyStatus = (strtotime($warrantyEnd) >= strtotime(date('Y-m-d')))
                                ? '<span class="badge bg-success">Under Warranty</span>'
                                : '<span class="badge bg-danger">Expired</span>';
                        } else {
                            $warrantyStatus = '-';
                        }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($asset['property_no'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($asset['asset_type'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($asset['brand'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($asset['model'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($asset['condition'] ?? '-') ?></td>
                            <td>
                                <?= $purchaseDate
                                    ? date('M d, Y', strtotime($purchaseDate))
                                    : '-' ?>
                            </td>
                            <td>
                                <?= $warrantyEnd
                                    ? date('M d, Y', strtotime($warrantyEnd))
                                    : '-' ?>
                            </td>
                            <td><?= $warrantyStatus ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            No assets found for this office
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($assets): ?>
            <button class="btn btn-danger mt-3" onclick="generatePDF()">
                <i class="fa fa-file-pdf"></i> Export PDF
            </button>
        <?php endif; ?>
    <?php endif; ?>

</div>

<!-- jsPDF -->
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.5.28/dist/jspdf.plugin.autotable.min.js"></script>

<script>
    function generatePDF() {
        const {
            jsPDF
        } = window.jspdf;
        const doc = new jsPDF('l');

        doc.setFontSize(14);
        doc.text("CITY GOVERNMENT OF GAPAN", 148, 15, {
            align: "center"
        });
        doc.setFontSize(12);
        doc.text("Asset Inventory per Office", 148, 23, {
            align: "center"
        });

        doc.autoTable({
            html: '#officeAssetsTable',
            startY: 30,
            styles: {
                fontSize: 9
            },
            headStyles: {
                fillColor: [52, 58, 64]
            }
        });

        const officeName = document.querySelector('h5 strong').innerText.replace(/\s+/g, '_');
        doc.save(`asset_inventory_${officeName}.pdf`);
    }
</script>

<?php require_once '../includes/footer.php'; ?>