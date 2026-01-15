<?php
// /includes/sidebar.php
?>

<div class="d-flex">
    <nav class="bg-primary text-white p-3 vh-100" style="width:250px;">
        <h5 class="text-center mb-4">LGU ICT</h5>

        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a href="/views/dashboard.php" class="nav-link text-white">
                    <i class="fa fa-home me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item mb-2">
                <a href="/views/assets.php" class="nav-link text-white">
                    <i class="fa fa-desktop me-2"></i> Assets
                </a>
            </li>

            <li class="nav-item mb-2">
                <a href="/views/offices.php" class="nav-link text-white">
                    <i class="fa fa-building me-2"></i> Offices
                </a>
            </li>

            <li class="nav-item mb-2">
                <a href="/reports/asset_logs.php" class="nav-link text-white">
                    <i class="fa fa-history me-2"></i> Logs Report
                </a>
            </li>

            <li class="nav-item mb-2">
                <a href="/reports/asset_inventory.php" class="nav-link text-white">
                    <i class="fa fa-file-pdf me-2"></i> Asset Reports
                </a>
            </li>

            <li class="nav-item mb-2">
                <a href="/reports/asset_office.php" class="nav-link text-white">
                    <i class="fa fa-building me-2"></i> Assets per Office
                </a>
            </li>

            <li class="nav-item mt-4">
                <a href="/actions/logout.php" class="nav-link text-white">
                    <i class="fa fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

    <main class="p-4 w-100">
        <!-- Page content goes here -->