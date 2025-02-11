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

        echo '<script>alert("User registered successfully.");</script>';
    }
}
?>
<?php include_once './../head.php'; ?>
    <div class="container">
        <a href="index" class="btn btn-primary mt-2 mb-0">Back</a>
        <div class="row">
            <div class="col-12">
                <h1 class="mt-5 mb-5">Register</h1>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="name">Name <span class="">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password <span class="">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm your password <span class="">*</span></label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
<?php include_once './../foot.php'; ?>
