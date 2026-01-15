<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Fetch all assets with office names
$assets = $pdo->query("
    SELECT 
        a.*,
        o.office_name
    FROM assets a
    LEFT JOIN offices o ON a.office_id = o.id
    ORDER BY a.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- MAIN CONTENT WRAPPER -->
<div class="content-wrapper px-4 py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Asset Inventory</h3>
        <button class="btn btn-danger" onclick="generatePDF()">
            <i class="fa fa-file-pdf"></i> Export PDF
        </button>
    </div>

    <table class="table table-bordered table-striped" id="assetsTable">
        <thead class="table-dark">
            <tr>
                <th>Property No</th>
                <th>Type</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Condition</th>
                <th>Purchase Date</th>
                <th>Warranty End</th>
                <th>Under Warranty</th>
                <th>Office</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($assets as $asset): ?>
                <?php
                // Under Warranty calculation
                $today = strtotime(date('Y-m-d'));
                $warrantyEnd = !empty($asset['warranty_end']) && $asset['warranty_end'] !== '0000-00-00'
                    ? strtotime($asset['warranty_end'])
                    : null;
                $underWarranty = $warrantyEnd && $warrantyEnd >= $today
                    ? '<span class="badge bg-success">Yes</span>'
                    : '<span class="badge bg-danger">No</span>';

                // Condition badge
                $badge = match ($asset['condition'] ?? '') {
                    'Good' => 'success',
                    'Repair' => 'warning',
                    'Condemned' => 'danger',
                    default => 'secondary'
                };
                ?>
                <tr>
                    <td><?= htmlspecialchars($asset['property_no'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($asset['asset_type'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($asset['brand'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($asset['model'] ?? '—') ?></td>
                    <td>
                        <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($asset['condition'] ?? '—') ?></span>
                    </td>
                    <td>
                        <?= !empty($asset['purchase_date']) && $asset['purchase_date'] !== '0000-00-00'
                            ? date('M d, Y', strtotime($asset['purchase_date']))
                            : '—' ?>
                    </td>
                    <td>
                        <?= !empty($asset['warranty_end']) && $asset['warranty_end'] !== '0000-00-00'
                            ? date('M d, Y', strtotime($asset['warranty_end']))
                            : '—' ?>
                    </td>
                    <td><?= $warrantyEnd ? $underWarranty : '—' ?></td>
                    <td><?= htmlspecialchars($asset['office_name'] ?? 'Unassigned') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
<!-- END CONTENT WRAPPER -->

<!-- jsPDF -->
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.5.28/dist/jspdf.plugin.autotable.min.js"></script>

<script>
    function generatePDF() {
        const {
            jsPDF
        } = window.jspdf;
        const doc = new jsPDF("p", "pt", "a4");

        doc.setFontSize(14);
        doc.text("CITY GOVERNMENT OF GAPAN", 40, 40);
        doc.setFontSize(12);
        doc.text("Asset Inventory Report", 40, 60);

        doc.autoTable({
            html: '#assetsTable',
            startY: 80,
            styles: {
                fontSize: 10
            },
            headStyles: {
                fillColor: [52, 58, 64]
            },
            theme: 'grid',
            margin: {
                left: 40,
                right: 40
            }
        });

        doc.save("asset_inventory_report.pdf");
    }
</script>

<script>
    $(document).ready(function() {
        $('#assetsTable').DataTable({
            order: [
                [0, 'asc']
            ]
        });
    });
</script>

<?php require_once '../includes/footer.php'; ?>