<?php
ob_start();
session_start();

if(!isset($_SESSION['valid'])) {
    header('Location: login.php');
    exit();
}

include 'connection.php';

if(isset($_POST['Submit'])) {
    try {
        $name = trim($_POST['name']);
        $qty = filter_var($_POST['qty'], FILTER_VALIDATE_INT);
        $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
        $loginId = $_SESSION['id'];
        
        $errors = [];
        
        if(empty($name)) {
            $errors[] = "Name field is empty.";
        }
        
        if($qty === false || $qty < 0) {
            $errors[] = "Please enter a valid quantity.";
        }
        
        if($price === false || $price < 0) {
            $errors[] = "Please enter a valid price.";
        }
        
        if(empty($errors)) {
            $stmt = $pdo->prepare("INSERT INTO products(name, qty, price, login_id) VALUES(:name, :qty, :price, :login_id)");
            
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':qty', $qty, PDO::PARAM_INT);
            $stmt->bindValue(':price', $price);
            $stmt->bindValue(':login_id', $loginId, PDO::PARAM_INT);
            
            if($stmt->execute()) {
                $_SESSION['success'] = "Product added successfully.";
                // Limpa qualquer saída anterior
                while (ob_get_level()) {
                    ob_end_clean();
                }
                header('Location: view.php');
                exit();
            } else {
                $errors[] = "Failed to add product.";
            }
        }
    } catch(PDOException $e) {
        error_log("Error adding product: " . $e->getMessage());
        $errors[] = "An error occurred while adding the product.";
    }
}

$pageTitle = 'Add Product';
include 'header.php';  // Movido para depois da lógica de processamento
?>

<div class="form-container">
    <h2 class="text-center">Add New Product</h2>
    
    <?php if(!empty($errors)): ?>
        <div class="message message-error">
            <?php foreach($errors as $error): ?>
                <?php echo htmlspecialchars($error); ?><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="" method="post" name="form1">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required
                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label>Quantity:</label>
            <input type="number" name="qty" class="form-control" min="0" required
                   value="<?php echo isset($_POST['qty']) ? htmlspecialchars($_POST['qty']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label>Price (EUR):</label>
            <input type="number" name="price" class="form-control" min="0" step="0.01" required
                   value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
        </div>
        
        <div class="form-group text-center">
            <input type="submit" name="Submit" value="Add Product" class="btn btn-primary">
            <a href="view.php" class="btn btn-primary">Back to List</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
