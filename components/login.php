<?php
// Inicia a sessão
session_start();

require_once 'conn_open.php';
// Função para validar o login
function validarLogin($email, $password) {
    global $conn;
    // Aqui você deve substituir com a lógica de validação do seu banco de dados
    // Exemplo de validação simples
    $users = $conn->prepare('SELECT email, password, id, acess_level FROM users');
    $users->execute();
    $users = $users->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {
        if ($user['email'] === $email && $user['password'] === $password ) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['acess_level'] = $user['acess_level'];
            $lastLogin = $conn->prepare('UPDATE users SET logged_at = NOW() WHERE id = :id');
            $lastLogin->execute(['id' => $user['id']]);
            return true;
        }
    }
    echo '<script>alert("Email or Password is incorrect.");</script>';
    return false;
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Verifica se os campos são required
    if (empty($email) || empty($password)) {
        $erro = 'All fields are required.';
        echo '<script>alert("'.$erro.'");</script>';
    } else {
        // Valida o login
        if (validarLogin($email, $password)) {
            $_SESSION['usuario'] = $email;

            if($_SESSION['acess_level']==='common'){
                setcookie('last_login', 'true', time() + 86400);
                setcookie('user_id', $_SESSION['id'], time() + 86400);
                setcookie('user_acess_level', $_SESSION['acess_level'], time() + 86400);
                echo '<script>window.location.href = "common";</script>';

            } else if($_SESSION['acess_level']==='admin'){
                setcookie('last_login', 'true', time() + 86400);
                setcookie('user_id', $_SESSION['id'], time() + 86400);
                setcookie('user_acess_level', $_SESSION['acess_level'], time() + 86400);
                echo '<script>window.location.href = "manager";</script>';
            }
            exit;
        } else {
            $erro = 'Email or Password is incorrect.';
        }
    }
}
?>

<!-- Begin page content -->
<div class="container">
    <form class="mt-4" method="post" action="" id="login-form" style="max-width: 400px; margin: 0 auto;">
        <!-- Form elements -->
        <div class="form-group mb-2 position-relative check-valid">
            <div class="input-group input-group-lg mb-2">
                <span class="input-group-text text-theme bg-white border-end-0" style="height:40px;"><i class="bi bi-envelope" style="color:black;"></i></span>
                <div class="form-floating">
                    <input type="email" name="email" placeholder="Email Address" class="form-control border-start-0" autofocus id="email" style="height:40px;">
                </div>
            </div>

            <div class="input-group">
                <span class="input-group-text text-theme bg-white border-end-0" style="height:40px;"><i class="bi bi-lock" style="color:black; "></i></span>
                <div class="form-floating">
                    <input type="password" name="password" placeholder="Password" class="form-control border-start-0" autofocus id="password" style="height:40px;">
                </div>
                <button class="btn btn-theme btn-primary z-index-5 ml-2" type="submit" id="submitbtn" style=" width:100px; height:40px;"><i class="bi bi-arrow-right text-light"></i></button>
            </div>
        </div>
    </form>
    <div class="d-flex justify-content-center">
        <a href="register" class="btn btn-link">Register</a>
    </div>
</div>
