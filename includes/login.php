<?php session_start(); ?>
<?php include "db.php"; ?>
<?php include "../admin/includes/functions.php" ?>


<?php
    if(isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];


        login_user($username, $password);

        

    }
?>