<?php 
    session_start();
    ob_flush();

    require_once './../conn_open.php';

    if(isset($_SESSION['id']) && $_SESSION['acess_level']==='common'){
        $getUserData = $conn->prepare('SELECT name, balance, id FROM users WHERE id = :id AND acess_level = :acess_level');
        $getUserData->execute(['id' => $_SESSION['id'], 'acess_level' => $_SESSION['acess_level']]);
        $userData = $getUserData->fetch(PDO::FETCH_ASSOC);
        $userBalance = $userData['balance'];
        $userID = $userData['id'];
    } else {
        echo '<script>window.location.href = "./../index.php";</script>';
        exit;
    }

    $getUsers = $conn->prepare('SELECT id, name, balance FROM users WHERE acess_level = :acess_level AND NOT id = :id');
    $getUsers->execute(['acess_level' => 'common' , 'id' => $userID]);
    $users = $getUsers->fetchAll(PDO::FETCH_ASSOC);

    if(getenv('REQUEST_METHOD') === 'POST'){
        $value = $_POST['value'] ?? '';
        $user_id = $_POST['user_id'] ?? '';
        if($value < $userBalance){
            if(empty($value) || empty($user_id)){
                echo '<script>alert("All fields are required.");</script>';
            } else {
                $getUserData = $conn->prepare('SELECT balance FROM users WHERE id = :id');
                $getUserData->execute(['id' => $user_id]);
                $userData = $getUserData->fetch(PDO::FETCH_ASSOC);
                $newBalance = $userData['balance'] + $value;
                $updateBalance = $conn->prepare('UPDATE users SET balance = :balance WHERE id = :id');
                $updateBalance->execute(['balance' => $newBalance, 'id' => $user_id]);

                $newBalance = $userBalance - $value;
                $updateBalance = $conn->prepare('UPDATE users SET balance = :balance WHERE id = :id');
                $updateBalance->execute(['balance' => $newBalance, 'id' => $userID]);

                echo '<script>alert("Balance added successfully.");</script>';
                echo '<script>window.location.href = "'.ROOT.'/common";</script>';
                
                $type = 'Transfer';
                $minus = '-';
                $log = $conn->prepare('INSERT INTO logs (user_id, balance, datatime, tipe, plus_minus) VALUES (:user_id, :balance, :datatime, :tipe, :plus_minus)');
                $log->execute(['user_id' => $user_id, 'balance' => $value, 'datatime' => date('Y-m-d H:i:s'), 'tipe' => $type, 'plus_minus' => "+"]);
           
                $logUser = $conn->prepare('INSERT INTO logs (user_id, balance, datatime, tipe, plus_minus) VALUES (:user_id, :balance, :datatime, :tipe, :plus_minus)');
                $logUser->execute(['user_id' => $userID, 'balance' => $value, 'datatime' => date('Y-m-d H:i:s'), 'tipe' => $type, 'plus_minus' => $minus]);
            }
        } else {
            echo '<script>alert("The value is more than your balance.");</script>';
        }
    }
?>

<?php include_once './../head.php'; ?>
    <div class="d-flex justify-content-center">
        <div class="col-6">
            <a href="<?= ROOT ?>/common" class="btn btn-danger mt-2 mb-5">Back</a>
            <div class="row">
                <form action="" method="post" class="col-12">
                <p for="user_id" class="form-label">Who will the transfer be for?</p>
                <select class="form-select" aria-label="Default select example" name="user_id">
                    <option selected>Choose a user</option>
                    <?php 
                    foreach($users as $user){
                        echo '<option value="'.$user['id'].'">'.$user['name'].'</option>';
                    }
                    ?>
                </select>
                <p class="mt-3">Your balance: <strong><?= $userBalance ?></strong></strong></p>
                <div class="mb-3">
                        <label for="value" class="form-label">How many you want to Transfer ?</label>
                        <input type="number" class="form-control" id="value" name="value">
                    </div>
                    <button type="submit" class="btn btn-success">Transfer</button>
                </form>
            </div>
        </div>

        <div class="col-6" style="background-color: green; height: 100vh;">
        </div>
    </div>
<?php include_once "./../foot.php"; ?>