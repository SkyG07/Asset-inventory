<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Fetch offices
$offices = $pdo->query("SELECT * FROM offices ORDER BY office_name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h3 class="mb-3">Offices</h3>

<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addOfficeModal">
    <i class="fa fa-plus"></i> Add Office
</button>

<table class="table table-bordered table-striped" id="officesTable">
    <thead class="table-dark">
        <tr>
            <th>Office Name</th>
            <th>Office Code</th>
            <th width="120">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($offices as $office): ?>
            <tr>
                <td><?= htmlspecialchars($office['office_name'] ?? '') ?></td>
                <td><?= htmlspecialchars($office['office_code'] ?? '') ?></td>
                <td>
                    <a href="../actions/office.delete.php?id=<?= $office['id'] ?>"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Delete this office?')">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- ADD OFFICE MODAL -->
<div class="modal fade" id="addOfficeModal">
    <div class="modal-dialog">
        <form method="POST" action="../actions/office.store.php" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Office</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input name="office_name" class="form-control mb-2" placeholder="Office Name" required>
                <input name="office_code" class="form-control" placeholder="Office Code (optional)">
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#officesTable').DataTable();
    });
</script>

<?php require_once '../includes/footer.php'; ?>