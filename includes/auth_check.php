<?php
// /includes/auth_check.php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to login page
    header('Location: ../views/login.php');
    exit;
}

// Optional: role-based access functions
function isAdmin(): bool
{
    return ($_SESSION['user']['role'] ?? '') === 'admin';
}

function isStaff(): bool
{
    return ($_SESSION['user']['role'] ?? '') === 'staff';
}
