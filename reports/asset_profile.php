<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

// Get asset ID from URL
$asset_id = $_GET['id'] ?? null;
if (!$asset_id) {
    die('Asset not specified.');
}

// Fetch asset info
$stmt = $pdo->prepare("
    SELECT 
        a.*,
        o.office_name
    FROM assets a
    LEFT JOIN offices o ON a.office_id = o.id
    WHERE a.id = ?
");
$stmt->execute([$asset_id]);
$asset = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$asset) {
    die('Asset not found.');
}

// Fetch asset logs/history
$logsStmt = $pdo->prepare("
    SELECT 
        al.*,
        u.name AS user_name
    FROM asset_logs al
    LEFT JOIN users u ON al.performed_by = u.id
    WHERE al.asset_id = ?
    ORDER BY al.created_at DESC
");
$logsStmt->execute([$asset_id]);
$logs = $logsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Asset Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-4" id="reportArea">

        <h4 class="text-center">CITY GOVERNMENT OF GAPAN</h4>
        <h6 class="text-center">Single Asset Profile Report</h6>
        <hr>

        <!-- Asset Info -->
        <table class="table table-bordered table-sm" id="assetInfoTable">
            <tr>
                <th width="30%">Property No</th>
                <td><?= htmlspecialchars($asset['property_no'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Asset Name</th>
                <td><?= htmlspecialchars($asset['asset_name'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Office</th>
                <td><?= htmlspecialchars($asset['office_name'] ?? 'Unassigned') ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?= htmlspecialchars($asset['status'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Date Acquired</th>
                <td>
                    <?= isset($asset['date_acquired']) && $asset['date_acquired']
                        ? date('M d, Y', strtotime($asset['date_acquired']))
                        : '-' ?>
                </td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td><?= htmlspecialchars($asset['remarks'] ?? '-') ?></td>
            </tr>
        </table>

        <!-- Asset History -->
        <h6 class="mt-4">Asset History</h6>
        <table class="table table-bordered table-sm" id="assetHistoryTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>Performed By</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td>
                            <?= isset($log['created_at']) && $log['created_at']
                                ? date('M d, Y h:i A', strtotime($log['created_at']))
                                : '-' ?>
                        </td>
                        <td><?= htmlspecialchars($log['action'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($log['description'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($log['user_name'] ?? 'System') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

    <div class="text-center mt-3">
        <button class="btn btn-success" onclick="generatePDF()">Export PDF</button>
    </div>

    <!-- jsPDF library -->
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <!-- jsPDF AutoTable plugin -->
    <script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.5.28/dist/jspdf.plugin.autotable.min.js"></script>

    <script>
        function generatePDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(14);
            doc.text("CITY GOVERNMENT OF GAPAN", 105, 15, null, null, "center");
            doc.setFontSize(12);
            doc.text("Single Asset Profile Report", 105, 23, null, null, "center");

            // Asset Info Table
            doc.autoTable({
                html: '#assetInfoTable',
                startY: 30,
                styles: {
                    fontSize: 10
                },
                theme: 'grid'
            });

            // Asset History Table
            const finalY = doc.lastAutoTable.finalY + 10 || 50;
            doc.autoTable({
                html: '#assetHistoryTable',
                startY: finalY,
                styles: {
                    fontSize: 10
                },
                headStyles: {
                    fillColor: [52, 58, 64]
                }
            });

            doc.save(`asset_profile_${<?= json_encode($asset['property_no'] ?? $asset_id) ?>}.pdf`);
        }
    </script>

</body>

</html>