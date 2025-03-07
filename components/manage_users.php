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
    <div class="d-flex justify-content-center">
        <div class="col-6">
            <a href="<?= ROOT ?>/manager" class="btn btn-danger mt-2 mb-5">Back</a>
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
                echo '<td><a href="editUser/'.$user['id'].'" class="btn btn-primary">Edit</a></td>';
                echo '<td><a href="deleteUser/'.$user['id'].'" class="btn btn-danger">X</a></td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
            </div>
        </div>

        <div class="col-6" style="background-color: green; height: 100vh;">
            <h2 class="text-white mt-5">Register New User</h2>
            <form action="regUser" method="POST" class="text-white">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="balance">Balance:</label>
                    <input type="number" class="form-control" id="balance" name="balance" required>
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