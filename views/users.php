<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Only admins can access this page
if (!isAdmin()) {
    die('Access denied. Only Admins can manage users.');
}

// Fetch users
$users = $pdo->query("SELECT * FROM users ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<h3 class="mb-3">Users Management</h3>

<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
    <i class="fa fa-plus"></i> Add User
</button>

<table class="table table-bordered table-striped" id="usersTable">
    <thead class="table-dark">
        <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Role</th>
            <th width="120">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= ucfirst(htmlspecialchars($user['role'])) ?></td>
                <td>
                    <a href="../actions/user.delete.php?id=<?= $user['id'] ?>"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Delete this user?')">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- ADD USER MODAL -->
<div class="modal fade" id="addUserModal">
    <div class="modal-dialog">
        <form method="POST" action="../actions/user.store.php" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input name="name" class="form-control mb-2" placeholder="Full Name" required>
                <input name="username" class="form-control mb-2" placeholder="Username" required>
                <input name="password" type="password" class="form-control mb-2" placeholder="Password" required>
                <select name="role" class="form-control">
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#usersTable').DataTable();
    });
</script>

<?php require_once '../includes/footer.php'; ?>