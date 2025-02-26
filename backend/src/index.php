<?php
$pageTitle = 'Home';
include 'header.php';
?>

<div class="content-container">
    <?php if(isset($_SESSION['valid'])): ?>
        <div class="welcome-message">
            Welcome <?php echo htmlspecialchars($_SESSION['name']); ?>!
        </div>
        <div class="actions mt-20">
            <a href="view.php" class="btn btn-primary">View and Add Products</a>
        </div>
    <?php else: ?>
        <div class="welcome-message">
            <p>You must be logged in to view this page.</p>
            <div class="mt-20">
                <a href="login.php" class="btn btn-primary">Login</a>
                <a href="register.php" class="btn btn-primary">Register</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
