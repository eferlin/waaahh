<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$host = "mysql-service";
$dbname = "mydatabase";
$username = "ferlin";
$password = "p@ssw0rd!@#";

echo "Tentando conectar com:<br>";
echo "Host: $host<br>";
echo "Database: $dbname<br>";
echo "Username: $username<br>";

try {
    $dsn = "mysql:host=$host;dbname=$dbname";
    echo "DSN: $dsn<br>";
    
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexão bem sucedida!";
} catch(PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage() . "<br>";
    echo "Código do erro: " . $e->getCode();
}
?>
