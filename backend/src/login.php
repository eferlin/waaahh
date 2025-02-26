<?php
ob_start(); // Adiciona isto no início do arquivo, antes de qualquer saída
session_start();

$pageTitle = 'Login';
include 'connection.php';

if(isset($_POST['submit'])) {
    try {
        $user = trim($_POST['username']);
        $pass = trim($_POST['password']);

        if(empty($user) || empty($pass)) {
            $error = "Please enter both username and password.";
        } else {
            // Prepared statement
            $stmt = $pdo->prepare("SELECT * FROM login WHERE username = :username AND password = :password");
            
            $stmt->bindValue(':username', $user);
            $stmt->bindValue(':password', md5($pass));
            
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($row) {
                $_SESSION['valid'] = $row['username'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];
                
                // Limpa qualquer saída anterior
                while (ob_get_level()) {
                    ob_end_clean();
                }
                
                header('Location: index.php');
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        }
    } catch(PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        $error = "An error occurred during login.";
    }
}

include 'header.php';  // Move o include do header para depois da lógica de login
?>

<div class="form-container">
    <h2 class="text-center">Login</h2>
    
    <?php if(isset($error)): ?>
        <div class="message message-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        
        <div class="form-group text-center">
            <input type="submit" name="submit" value="Login" class="btn btn-primary">
        </div>
    </form>
    
    <div class="text-center mt-20">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

<?php include 'footer.php'; ?>
