<?php 

// Inicia a sessão
session_start();

require_once './../conn_open.php';

$uri = $_SERVER['REQUEST_URI'];
$uriSegments = explode('/', trim($uri, '/'));
$userId = end($uriSegments);

$getUsers = $conn->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
$getUsers->execute(['id' => $userId]);
$user = $getUsers->fetch(PDO::FETCH_ASSOC);

if(getenv('REQUEST_METHOD') === 'POST'){
    $name = isset($_POST['name']) ? $_POST['name'] : $user['name'];
    $email = isset($_POST['email']) ? $_POST['email'] : $user['email'];
    $password = isset( $_POST['password']) ? $_POST['password'] : $user['password'];
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : $user['password'];
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
        $update = $conn->prepare('UPDATE users SET name = :name, email = :email, password = :password, created_at = :created_at WHERE id = :id');
        $update->execute(['name' => $name, 'email' => $email, 'password' => $password, 'created_at' => $created_at, 'id' => $userId]);
        
        echo '<script>alert("User registered successfully.");</script>';
    }
}
?>

<?php include_once './../head.php'; ?>
    <div class="d-flex justify-content-center">
        <div class="col-6" >
            <a href="<?=ROOT?>usersList" class="btn btn-danger mt-2 mb-0">Back</a>
        </div>

        <div class="col-6" style="background-color: green; height: 100vh;">
            <h2 class="text-white mt-5">Edit User</h2>
            <form action="" method="POST" class="text-white">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="<?= $user['name'] ?>" plarequired>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" class="form-control" id="email" name="email"  placeholder="<?= $user['email'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                <div class="form-group">
                    <label for="acess_level">Access Level:</label>
                    <select class="form-control" id="acess_level" name="acess_level" required>
                        <option value="common">Common</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Register</button>
            </form>
        </div>
    </div>
<?php include_once "./../foot.php"; ?>