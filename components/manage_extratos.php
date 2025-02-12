<?php 
    session_start();
    ob_flush();
    require_once './../conn_open.php';
    if(isset($_SESSION['id']) && $_SESSION['acess_level']==='admin'){
        $getUserData = $conn->prepare('SELECT name, balance FROM users WHERE id = :id AND acess_level = :acess_level');
        $getUserData->execute(['id' => $_SESSION['id'], 'acess_level' => $_SESSION['acess_level']]);
        $userData = $getUserData->fetch(PDO::FETCH_ASSOC);
    } else {
        echo '<script>window.location.href = "./../index.php";</script>';
        exit;
    }

    $getLogs = $conn->prepare('SELECT id, datatime, balance, tipe, plus_minus, user_id FROM logs');
    $getLogs->execute();
    $logs = $getLogs->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once './../head.php'; ?>
    <div class="d-flex justify-content-center align-items-center">
        <div class="col-6">
            <a href="<?= ROOT ?>/manager" class="btn btn-danger mt-5 mb-5">Back</a>
            <div class="">
            <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Date</th>
                <th>Balance</th>
                <th>Type</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php 
            foreach($logs as $log){
                echo '<tr>';
                echo '<td>'.$log["id"].'</td>';
                echo '<td>'.$log["user_id"].'</td>';
                echo '<td>'.$log["datatime"].'</td>';
                echo '<td>'.$log["balance"].'</td>';
                echo '<td>'.$log["tipe"].'</td>';
                echo '<td>'.$log["plus_minus"].'</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
            </div>
        </div>

        <div class="col-6" style="background-color: green; height: 100vh;">
            <form method="GET" action="">
                <div class="input-group mt-5 mb-5">
                    <input type="text" class="form-control" name="search_user_id" placeholder="Search by User ID">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>

            <?php
            if (isset($_GET['search_user_id']) && !empty($_GET['search_user_id'])) {
                $searchUserId = $_GET['search_user_id'];
                $getUser = $conn->prepare('SELECT name FROM users WHERE id = :id');
                $getUser->execute(['id' => $searchUserId]);
                $user = $getUser->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    echo '<div class="alert alert-success">User Name: ' . htmlspecialchars($user['name']) . '</div>';
                } else {
                    echo '<div class="alert alert-danger">User not found.</div>';
                }
            }
            ?>
        </div>
</div>
<?php include_once "./../foot.php"; ?>