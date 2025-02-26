<?php
ob_start();
session_start();

if(!isset($_SESSION['valid'])) {
    header('Location: login.php');
    exit();
}

include 'connection.php';

try {
    // Verifica se um ID foi fornecido
    if(!isset($_GET['id'])) {
        $_SESSION['error'] = "No product ID provided";
        header('Location: view.php');
        exit();
    }

    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if(!$id) {
        $_SESSION['error'] = "Invalid product ID";
        header('Location: view.php');
        exit();
    }

    // Query para deletar o produto
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
    // Executa a query e verifica se algo foi deletado
    if($stmt->execute()) {
        if($stmt->rowCount() > 0) {
            $_SESSION['success'] = "Product deleted successfully";
        } else {
            $_SESSION['error'] = "Product not found or already deleted";
        }
    } else {
        $_SESSION['error'] = "Failed to delete product";
    }

} catch(PDOException $e) {
    error_log("Error deleting product: " . $e->getMessage());
    $_SESSION['error'] = "An error occurred while deleting the product";
}

// Limpa o buffer antes do redirecionamento
while (ob_get_level()) {
    ob_end_clean();
}

header('Location: view.php');
exit();
?>
