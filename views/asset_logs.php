<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

$logs = $pdo->query("
    SELECT 
        al.*,
        a.property_no,
        u.name AS user_name
    FROM asset_logs al
    LEFT JOIN assets a ON al.asset_id = a.id
    LEFT JOIN users u ON al.performed_by = u.id
    ORDER BY al.created_at DESC
")->fetchAll();
?>

<h3 class="mb-3">Asset Logs</h3>

<table class="table table-bordered table-striped" id="logsTable">
    <thead class="table-dark">
        <tr>
            <th>Date</th>
            <th>Property No</th>
            <th>Action</th>
            <th>Description</th>
            <th>Performed By</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logs as $log): ?>
            <tr>
                <td><?= date('M d, Y h:i A', strtotime($log['created_at'])) ?></td>
                <td><?= htmlspecialchars($log['property_no'] ?? '—') ?></td>
                <td><?= htmlspecialchars($log['action']) ?></td>
                <td><?= htmlspecialchars($log['description']) ?></td>
                <td><?= htmlspecialchars($log['user_name'] ?? 'System') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $('#logsTable').DataTable({
            order: [
                [0, 'desc']
            ]
        });
    });
</script>

<?php require_once '../includes/footer.php'; ?>