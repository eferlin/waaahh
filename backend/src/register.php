<?php
ob_start();
session_start();

include 'connection.php';

if(isset($_POST['submit'])) {
    try {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $user = trim($_POST['username']);
        $pass = $_POST['password'];

        $errors = [];

        if(empty($name)) $errors[] = "Name is required.";
        if(empty($email)) $errors[] = "Email is required.";
        if(empty($user)) $errors[] = "Username is required.";
        if(empty($pass)) $errors[] = "Password is required.";
        
        if(empty($errors)) {
            // Check if username or email already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM login WHERE username = :username OR email = :email");
            $stmt->execute(['username' => $user, 'email' => $email]);
            
            if($stmt->fetchColumn() > 0) {
                $errors[] = "Username or email already exists.";
            } else {
                // Insert new user
                $stmt = $pdo->prepare("INSERT INTO login (name, email, username, password) VALUES (:name, :email, :username, :password)");
                
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':username', $user);
                $stmt->bindValue(':password', md5($pass)); // Consider using password_hash
                
                if($stmt->execute()) {
                    $_SESSION['success'] = "Registration successful! Please login.";
                    // Limpa qualquer saída anterior
                    while (ob_get_level()) {
                        ob_end_clean();
                    }
                    header("Location: login.php");
                    exit();
                } else {
                    $errors[] = "Registration failed. Please try again.";
                }
            }
        }
    } catch(PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        $errors[] = "An error occurred during registration.";
    }
}

$pageTitle = 'Register';
include 'header.php';  // Movido para depois da lógica de processamento
?>

<div class="form-container">
    <h2 class="text-center">Register</h2>
    
    <?php if(!empty($errors)): ?>
        <div class="message message-error">
            <?php foreach($errors as $error): ?>
                <?php echo htmlspecialchars($error); ?><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="form-group">
            <label>Full Name:</label>
            <input type="text" name="name" class="form-control" required 
                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" class="form-control" required
                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        
        <div class="form-group text-center">
            <input type="submit" name="submit" value="Register" class="btn btn-primary">
        </div>
    </form>
    
    <div class="text-center mt-20">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<?php include 'footer.php'; ?>
