<?php
// Inicia a sessão
session_start();

require_once './../conn_open.php';

if(getenv('REQUEST_METHOD') === 'POST'){
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $balance = $_POST['balance'] ?? 0;
    $created_at = date('Y-m-d H:i:s');

    if($password != $confirm_password){
        $erro = 'Passwords do not match.';
        echo '<script>alert("'.$erro.'");
            history.back();
        </script>';
        
        exit;
    } 

    if(empty($name) || empty($email) || empty($password)){
        $erro = 'All fields are required.';
        echo '<script>alert("'.$erro.'");</script>';
    } else {
        $register = $conn->prepare('INSERT INTO users (name, email, password, balance, acess_level, created_at) VALUES (:name, :email, :password, :balance, :acess_level, :created_at)');
        $register->execute(['name' => $name, 'email' => $email, 'password' => $password, 'balance' => $balance, 'acess_level' => 'common', 'created_at' => $created_at]);

        echo '<script>alert("User registered successfully.");
            window.location.href = "manager";
        </script>';
        
    }
}
?>

