<?php
// Inicia a sessão
session_start();

require_once './../conn_open.php';

// Destrói todas as variáveis de sessão
$_SESSION = array();

// Se é necessário matar o cookie de sessão, então destrua-o também.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destrói o cookie 'last_login' se existir
if (isset($_COOKIE['last_login'])) {
    setcookie('last_login', '', time() - 42000, '/');
    setcookie('user_acess_level', '', time() - 42000, '/');
}

// Finalmente, destrói a sessão
session_destroy();

// Redireciona para a página de login
header("Location: index");
exit;