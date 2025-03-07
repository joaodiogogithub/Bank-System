<?php
    session_start();
    ob_flush();

    require_once './../conn_open.php';     
    $userID = $_SESSION['id'];
    $accessLevel = $_SESSION['acess_level'];

    if(isset($userID) && $accessLevel==='common'){
        $getUserData = $conn->prepare('SELECT name, balance FROM users WHERE id = :id AND acess_level = :acess_level');
        $getUserData->execute(['id' => $userID, 'acess_level' => $accessLevel]);
        $userData = $getUserData->fetch(PDO::FETCH_ASSOC);
    } else {
        echo '<script>history.back();</script>';
    }

    if(getenv('REQUEST_METHOD') === 'POST'){
        $balance = $_POST['balance'] ?? '';
        if(empty($balance)){
            echo '<script>alert("All fields are required.");</script>';
        } else {
            $newBalance = $userData['balance'] + $balance;
            $updateBalance = $conn->prepare('UPDATE users SET balance = :balance WHERE id = :id');
            $updateBalance->execute(['balance' => $newBalance, 'id' => $userID]);
            echo '<script>alert("Balance added successfully.");</script>';
            echo '<script>window.location.href = "'.ROOT.'/common";</script>';

            if($balance < 0){
                $plus_minus = '-';
            } else {
                $plus_minus = '+';
            }

            $type = 'addBalance';
            $log = $conn->prepare('INSERT INTO logs (user_id, balance, datatime, plus_minus, tipe) VALUES (:user_id, :balance, :datatime, :plus_minus, :tipe)');
            $log->execute(['user_id' => $userID, 'balance' => $balance, 'datatime' => date('Y-m-d H:i:s'), 'plus_minus' => $plus_minus, 'tipe' => $type]);
        }
    }
?>

<?php include_once './../head.php'; ?>
        <div class="d-flex justify-content-center">
            <div class="col-6">
                <a href="<?= ROOT ?>/components/common.php" class="btn btn-danger mt-2 mb-2">Back</a>
                <h2>Your balance:<?= $userData["balance"]?></h2>
                <form action="<?= ROOT ?>/components/edit_balance.php" method="post">
                    <div class="form-group">
                        <label for="balance">Add balance</label>
                        <input type="number" class="form-control" id="balance" name="balance" required>
                    </div>
                    <button type="submit" class="btn btn-success">Confirmar</button>
                </form>
            </div>

            <div class="col-6" style="background-color: green; height: 100vh;">
            </div>
        </div>
<?php include_once "./../foot.php"; ?>
