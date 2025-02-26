<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Função para tentar obter variável de ambiente de diferentes fontes
function getEnvVar($varname) {
    // Tenta diferentes métodos de obter variáveis de ambiente
    $value = getenv($varname);
    if ($value !== false) return $value;

    if (isset($_ENV[$varname])) return $_ENV[$varname];
    
    if (isset($_SERVER[$varname])) return $_SERVER[$varname];

    // Se não encontrar, retorna null
    return null;
}

// Obtém as variáveis de configuração
$host = getEnvVar('DB_HOST');
$dbname = getEnvVar('DB_NAME');
$username = getEnvVar('DB_USER');
$password = getEnvVar('DB_PASSWORD');
$port = getEnvVar('DB_PORT') ?: '3306';

// Log para debug (remover em produção)
error_log("Trying to connect with:");
error_log("Host: " . $host);
error_log("Database: " . $dbname);
error_log("Username: " . $username);
error_log("Port: " . $port);

// Verificar se as variáveis essenciais estão definidas
if (!$host || !$dbname || !$username || !$password) {
    error_log("Database configuration variables missing");
    die("Database configuration error");
}

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
    
    // Teste a conexão com uma query simples
    $pdo->query("SELECT 1");
    
} catch(PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    die("Database connection failed: " . $e->getMessage());
}

// Se chegou aqui, a conexão está ok
return $pdo;
?>
