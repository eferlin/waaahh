<?php 
ob_start();
session_start(); 

if(!isset($_SESSION['valid'])) {
    header('Location: login.php');
    exit();
}

include_once("connection.php");

// Configurações de paginação
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Configurações de ordenação
$orderBy = isset($_GET['order']) ? $_GET['order'] : 'id';
$orderDir = isset($_GET['dir']) ? $_GET['dir'] : 'DESC';
$allowedOrders = ['id', 'name', 'price', 'qty'];
$orderBy = in_array($orderBy, $allowedOrders) ? $orderBy : 'id';
$orderDir = $orderDir === 'ASC' ? 'ASC' : 'DESC';

// Função para formatar preço
function formatPrice($price) {
    return '€' . number_format($price, 2);
}

// Função para criar links de ordenação
function getSortLink($field, $currentOrder, $currentDir) {
    $newDir = ($currentOrder === $field && $currentDir === 'ASC') ? 'DESC' : 'ASC';
    return "?order=" . urlencode($field) . "&dir=" . $newDir;
}

try {
    // Contar total de registros para paginação
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM products");
    $countStmt->execute();
    $totalRecords = $countStmt->fetchColumn();
    $totalPages = ceil($totalRecords / $perPage);

    // Buscar produtos com paginação e ordenação
    $stmt = $pdo->prepare("SELECT products.*, login.name as user_name 
                          FROM products 
                          LEFT JOIN login ON products.login_id = login.id 
                          ORDER BY {$orderBy} {$orderDir} 
                          LIMIT :offset, :perPage");
    
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    die("An error occurred while fetching the products.");
}

$pageTitle = 'View Products';
include 'header.php';
?>

<div class="content-container">
    <table>
        <tr>
            <th><a href="<?php echo getSortLink('name', $orderBy, $orderDir); ?>">Name</a></th>
            <th><a href="<?php echo getSortLink('qty', $orderBy, $orderDir); ?>">Quantity</a></th>
            <th><a href="<?php echo getSortLink('price', $orderBy, $orderDir); ?>">Price (EUR)</a></th>
            <th>Added By</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($results && count($results) > 0) {
            foreach($results as $res) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($res['name']) . "</td>";
                echo "<td>" . htmlspecialchars($res['qty']) . "</td>";
                echo "<td>" . formatPrice($res['price']) . "</td>";    
                echo "<td>" . htmlspecialchars($res['user_name']) . "</td>";
		echo "<td class='actions'>
     		   <a href=\"edit.php?id=" . htmlspecialchars($res['id']) . "\">Edit</a> | 
     		   <a href=\"delete.php?id=" . htmlspecialchars($res['id']) . "\" 
         	      onclick=\"return confirm('Are you sure you want to delete this product?');\">Delete</a>
     		      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='no-records'>No products found</td></tr>";
        }
        ?>
    </table>

    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=1&order=<?php echo $orderBy; ?>&dir=<?php echo $orderDir; ?>">&laquo; First</a>
            <a href="?page=<?php echo ($page-1); ?>&order=<?php echo $orderBy; ?>&dir=<?php echo $orderDir; ?>">&lsaquo; Previous</a>
        <?php endif; ?>

        <?php
        for ($i = max(1, $page-2); $i <= min($totalPages, $page+2); $i++) {
            if ($i == $page) {
                echo "<a href='#' class='active'>$i</a>";
            } else {
                echo "<a href='?page=$i&order=$orderBy&dir=$orderDir'>$i</a>";
            }
        }
        ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo ($page+1); ?>&order=<?php echo $orderBy; ?>&dir=<?php echo $orderDir; ?>">Next &rsaquo;</a>
            <a href="?page=<?php echo $totalPages; ?>&order=<?php echo $orderBy; ?>&dir=<?php echo $orderDir; ?>">Last &raquo;</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
