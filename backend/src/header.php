<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle ?? 'Product Management System'; ?></title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div id="header">
        Product Management System
    </div>
    <div class="navigation">
        <?php if(isset($_SESSION['valid'])): ?>
            <a href="index.php">Home</a> | 
            <a href="view.php">View Products</a> | 
            <a href="add.php">Add Product</a> | 
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="index.php">Home</a> | 
            <a href="login.php">Login</a> | 
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
