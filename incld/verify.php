<?php
    session_start();
    $pcol = $_SERVER['SERVER_PROTOCOL'];
    if(strpos($pcol, "HTTPS")) {
        $pcol="https://";
    } else {
        $pcol="http://";   
    }
    $_SESSION['page_id'] = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];;
    if(!isset($_SESSION['user_id'])) {
        $_SESSION['status'] = 'Please login to access the Page';
        header('Location:../admin/login.php');
    } 
?>