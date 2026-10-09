<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock & Sales System</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; background-color: #f4f6f9; color: #333; }
        header { background: #2c3e50; color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        header a { color: #ecf0f1; text-decoration: none; margin-left: 15px; font-weight: 600; }
        .container { padding: 2rem; max-width: 1100px; margin: 20px auto; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.4rem; font-weight: bold; }
        input, select { width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #27ae60; color: white; border: none; padding: 0.7rem 1.4rem; cursor: pointer; border-radius: 4px; font-weight: bold; }
        button:hover { background: #219150; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #34495e; color: white; }
        .badge-danger { background: #e74c3c; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.85em; }
        .badge-success { background: #2ecc71; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.85em; }
        .error { color: #e74c3c; font-size: 0.9em; margin-top: 4px; }
    </style>
</head>
<body>
<header>
    <h2>Stock & Sales Manager</h2>
    <nav>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php">Products & Stock</a>
            <a href="sales.php">Record Sale</a>
            <a href="reports.php">Reports</a>
            <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['full_name']) ?>)</a>
        <?php else: ?>
            <a href="index.php">Login</a>
            <a href="register.php">Register User</a>
        <?php endif; ?>
    </nav>
</header>
<div class="container">