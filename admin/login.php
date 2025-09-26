<?php 
session_start();

require '../incld/autoload.php';
    $cntr = new Contr\UserContr();
    if(isset($_SESSION['user_id'])) {
        $home_pg = $_SESSION['home_pg'];
        header("Location:{$home_pg}");
    }
    if(isset($_POST['submit'])) {
        $rqst = json_decode(json_encode($_POST));
        $cntr->loginUser($rqst);
    }

    include('../incld/header.php');
    include('../admin/form_login.php');
    include('../incld/jslib.php');
?>