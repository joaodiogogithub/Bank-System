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
?>
<?php include_once './../head.php'; ?>
    <div class="container">
        <a href="<?= ROOT ?>/logout" class="btn btn-primary mt-5 mb-5">Logout</a>
        <div class="row"></div>
            <div class="col-12">
                <h1 class="mt-5 mb-5">Welcome, <?php echo $userData['name']; ?></h1>
                <p>Balance: <strong><?php echo $userData['balance']; ?></strong></p>
                <div class="d-flex flex-column w-50">
                    <a href="<?= ROOT ?>/addbalance" class="btn btn-primary mt-4 mb-4 w-100">ADD balance</a>
                    <a href="<?= ROOT ?>/extrato" class="btn btn-primary w-100">Extrato</a>
                    <a href="<?= ROOT ?>/transfer" class="btn btn-primary mt-4 w-100">Transfer</a>
                </div>
            </div>
        </div>
    </div>
<?php include_once "./../foot.php"; ?>
    