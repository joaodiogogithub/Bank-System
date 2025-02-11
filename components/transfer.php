<?php 
    session_start();
    ob_flush();

    require_once './../conn_open.php';

    if(isset($_SESSION['id']) && $_SESSION['acess_level']==='common'){
        $getUserData = $conn->prepare('SELECT name, balance FROM users WHERE id = :id AND acess_level = :acess_level');
        $getUserData->execute(['id' => $_SESSION['id'], 'acess_level' => $_SESSION['acess_level']]);
        $userData = $getUserData->fetch(PDO::FETCH_ASSOC);
    } else {
        echo '<script>window.location.href = "./../index.php";</script>';
        exit;
    }

    $getUsers = $conn->prepare('SELECT id, name, balance FROM users WHERE acess_level = :acess_level');
    $getUsers->execute(['acess_level' => 'common']);
    $users = $getUsers->fetchAll(PDO::FETCH_ASSOC);

    if(getenv('REQUEST_METHOD') === 'POST'){
        $value = $_POST['value'] ?? '';
        $user_id = $_POST['user_id'] ?? '';
        var_dump($value);
        var_dump($user_id);
        if(empty($value) || empty($user_id)){
            echo '<script>alert("All fields are required.");</script>';
        } else {
            $getUserData = $conn->prepare('SELECT balance FROM users WHERE id = :id');
            $getUserData->execute(['id' => $user_id]);
            $userData = $getUserData->fetch(PDO::FETCH_ASSOC);
            $newBalance = $userData['balance'] + $value;
            $updateBalance = $conn->prepare('UPDATE users SET balance = :balance WHERE id = :id');
            $updateBalance->execute(['balance' => $newBalance, 'id' => $user_id]);
            echo '<script>alert("Balance added successfully.");</script>';
            echo '<script>window.location.href = "'.ROOT.'/common";</script>';

            $log = $conn->prepare('INSERT INTO logs (user_id, balance, datatime) VALUES (:user_id, :balance, :datatime)');
            $log->execute(['user_id' => $user_id, 'balance' => $value, 'datatime' => date('Y-m-d H:i:s')]);
        }
    }
?>

<?php include_once './../head.php'; ?>
    <div class="container">
        <a href="<?= ROOT ?>/common" class="btn btn-primary mt-5 mb-5">Back</a>
        <div class="row">
            <form action="" method="post" class="col-12">
            <select class="form-select" aria-label="Default select example" name="user_id">
                <option selected>Choose a user</option>
                <?php 
                foreach($users as $user){
                    echo '<option value="'.$user['id'].'">'.$user['name'].'</option>';
                }
                ?>
            </select>
            <div class="mb-3">
                    <label for="value" class="form-label">Value</label>
                    <input type="number" class="form-control" id="value" name="value">
                </div>
                <button type="submit" class="btn btn-primary">Transfer</button>
            </form>
        </div>
    </div>
<?php include_once "./../foot.php"; ?>