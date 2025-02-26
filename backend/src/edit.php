<?php
ob_start();
session_start();

if(!isset($_SESSION['valid'])) {
    header('Location: login.php');
    exit();
}

include 'connection.php';

if(isset($_POST['update'])) {
    try {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        $name = trim($_POST['name']);
        $qty = filter_var($_POST['qty'], FILTER_VALIDATE_INT);
        $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
        
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
            $stmt = $pdo->prepare("UPDATE products SET name = :name, qty = :qty, price = :price WHERE id = :id");
            
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':qty', $qty, PDO::PARAM_INT);
            $stmt->bindValue(':price', $price);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            if($stmt->execute()) {
                $_SESSION['success'] = "Product updated successfully.";
                while (ob_get_level()) {
                    ob_end_clean();
                }
                header('Location: view.php');
                exit();
            } else {
                $errors[] = "Failed to update product.";
            }
        }
    } catch(PDOException $e) {
        error_log("Error updating product: " . $e->getMessage());
        $errors[] = "An error occurred while updating the product.";
    }
}

// Get product data
try {
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if(!$id) {
        die("Invalid product ID");
    }

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    if($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $name = $product['name'];
        $qty = $product['qty'];
        $price = $product['price'];
    } else {
        die("Product not found");
    }
} catch(PDOException $e) {
    error_log("Error fetching product: " . $e->getMessage());
    die("An error occurred while fetching the product.");
}

$pageTitle = 'Edit Product';
include 'header.php';
?>

<div class="form-container">
    <h2 class="text-center">Edit Product</h2>
    
    <?php if(!empty($errors)): ?>
        <div class="message message-error">
            <?php foreach($errors as $error): ?>
                <?php echo htmlspecialchars($error); ?><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form name="form1" method="post" action="">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required
                   value="<?php echo htmlspecialchars($name); ?>">
        </div>
        
        <div class="form-group">
            <label>Quantity:</label>
            <input type="number" name="qty" class="form-control" min="0" required
                   value="<?php echo htmlspecialchars($qty); ?>">
        </div>
        
        <div class="form-group">
            <label>Price (EUR):</label>
            <input type="number" name="price" class="form-control" min="0" step="0.01" required
                   value="<?php echo htmlspecialchars($price); ?>">
        </div>
        
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        
        <div class="form-group text-center">
            <input type="submit" name="update" value="Update Product" class="btn btn-primary">
            <a href="view.php" class="btn btn-primary">Back to List</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>