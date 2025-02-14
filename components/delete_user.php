<?php 
// Inicia a sessão
session_start();

require_once './../conn_open.php';

$uri = $_SERVER['REQUEST_URI'];
$uriSegments = explode('/', trim($uri, '/'));
$userId = end($uriSegments);

$deleteUser = $conn->prepare('DELETE FROM users WHERE id = :id');
$deleteUser->execute(['id' => $userId]);

echo '<script>alert("User deleted successfully.");
    window.location.href = "./../manager";
</script>';
?>

