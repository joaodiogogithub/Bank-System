<?php
    session_start();
    ob_flush();

    require_once './../conn_open.php';     
    $userID = $_SESSION['id'];
    $accessLevel = $_SESSION['acess_level'];

    if(isset($userID) && $accessLevel==='common'){
        $getUserData = $conn->prepare('SELECT datatime, balance FROM logs WHERE user_id = :id');
        $getUserData->execute(['id' => $userID]);
        $userData = $getUserData->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo '<script>history.back();</script>';
    }
?>

<?php include_once './../head.php'; ?>
    <div class="container mt-4">
        <a href="<?= ROOT ?>/components/common.php" class="btn btn-primary mb-2">Back</a>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Date</th>
                <th>Balance</th>
            </tr>
            </thead>
            <tbody>
            <?php 
            foreach($userData as $log){
                echo '<tr>';
                echo '<td>'.$log["datatime"].'</td>';
                echo '<td>'.$log["balance"].'</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
<?php include_once "./../foot.php"; ?>
