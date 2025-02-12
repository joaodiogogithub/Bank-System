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

    $getUsers = $conn->prepare('SELECT id, name, balance FROM users WHERE acess_level = :acess_level');
    $getUsers->execute(['acess_level' => 'common']);
    $users = $getUsers->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once './../head.php'; ?>
    <div class="d-flex justify-content-center align-items-center">
        <div class="col-6">
            <a href="<?= ROOT ?>/manager" class="btn btn-danger mt-5 mb-5">Back</a>
            <div class="">
            <table class="table table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Balance</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            <?php 
            foreach($users as $user){
                echo '<tr>';
                echo '<td>'.$user["name"].'</td>';
                echo '<td>'.$user["balance"].'</td>';
                echo '<td><a class="btn btn-primary">Edit</a></td>';
                echo '<td><a class="btn btn-danger">X</a></td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
            </div>
        </div>

        <div class="col-6" style="background-color: green; height: 100vh;">
        </div>
</div>
<?php include_once "./../foot.php"; ?>