<?php
$host = '';
include 'pdoconfig.php';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username,
$password);
} catch (PDOException $pe) {
die("Não foi possível se conectar ao banco de dados $dbname :" . $pe->getMessage());
}

$GLOBALS['conn'] = $conn;

// Define o caminho para a raiz do projeto
define('ROOT', '/bank_system/');

