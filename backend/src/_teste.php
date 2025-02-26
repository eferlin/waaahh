<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Tentando conexão com PDO...<br><br>";

// Configurações de conexão
$host = 'mysql-service.wispro.svc.cluster.local'; // ou IP direto
$dbname = 'mydatabase';
$username = 'ferlin';
$password = 'sua_senha';
$port = 3306;

try {
    // String de conexão (DSN)
    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    
    // Opções do PDO
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 5
    ];

    // Debug - mostrar informações de conexão
    echo "Tentando conectar com:<br>";
    echo "Host: {$host}<br>";
    echo "Database: {$dbname}<br>";
    echo "Username: {$username}<br>";
    echo "Port: {$port}<br><br>";

    // Criar conexão
    $pdo = new PDO($dsn, $username, $password, $options);

    echo "Conexão estabelecida com sucesso!<br><br>";

    // Teste simples - verificar versão do MySQL
    $stmt = $pdo->query('SELECT VERSION() as version');
    $version = $stmt->fetch();
    echo "Versão do MySQL: " . $version['version'] . "<br>";

    // Listar databases disponíveis
    echo "<br>Databases disponíveis:<br>";
    $stmt = $pdo->query('SHOW DATABASES');
    while ($row = $stmt->fetch()) {
        echo "- " . $row['Database'] . "<br>";
    }

} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage() . "<br>";
    echo "Código do erro: " . $e->getCode() . "<br>";
    
    // Debug adicional
    echo "<br>Stack trace:<br>";
    echo nl2br($e->getTraceAsString());
} catch (Exception $e) {
    echo "Erro geral: " . $e->getMessage() . "<br>";
} finally {
    // Fechar conexão
    $pdo = null;
}
?>
