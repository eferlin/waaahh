<?php
session_start();

// Limpa todas as variáveis de sessão
$_SESSION = array();

// Destroi o cookie da sessão se existir
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Destroi a sessão
session_destroy();

// Garante que todos os buffers de saída sejam limpos
while (ob_get_level()) {
    ob_end_clean();
}

// Redireciona para a página inicial
header("Location: index.php");
exit(); // Garante que nenhum código adicional seja executado
?>
