<?php
error_reporting(E_ALL);
ini_set(display_errors, 1);

$host = "mysql-service";
$dbname = "mydatabase";
$username = "ferlin";
$password = "p@ssw0rd!@#";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    echo "Conexão bem sucedida!";
} catch(PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
