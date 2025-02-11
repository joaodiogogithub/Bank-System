<?php 
if(isset($_COOKIE['last_login']) && $_COOKIE['last_login'] === 'true'){
    if($_COOKIE['user_acess_level']==='common'){
        echo '<script>window.location.href = "components/common.php";</script>';
    } else if($_COOKIE['user_acess_level']==='admin'){
        echo '<script>window.location.href = "components/manager.php";</script>';
    }
}
?>
<!-- INDEX -->
<?php include 'head.php'; ?>
    <div class="container">
        <?php include './components/login.php'; ?>
    </div>
<?php include 'foot.php'; ?>
