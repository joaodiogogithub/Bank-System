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
?>
<!-- Manager -->
<?php include_once './../head.php'; ?>
<div class="d-flex justify-content-center align-items-center">
    <div class="col-6">
        <a href="<?= ROOT ?>/logout" class="btn btn-danger mt-5 mb-5">Logout</a>
        <h1 class="mt-2 mb-5">Welcome, <?php echo $userData['name']; ?></h1>
        <div class="">
            <div class="d-flex flex-column w-50">
                <a href="<?= ROOT ?>/usersList" class="btn btn-success mt-4 mb-4 w-100">User list</a>
                <a href="<?= ROOT ?>/extractList" class="btn btn-success w-100">Extratos List</a>
            </div>
        </div>
    </div>

    <div class="col-6" style="background-color: green; height: 100vh;">
    </div>
</div>
<?php include_once "./../foot.php"; ?>
    