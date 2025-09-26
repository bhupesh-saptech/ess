<?php 
    session_start();
    if(isset($_POST['submit'])) {
        include('../incld/dbconn.php');
        $user_id  = $_POST['user_id'];
        $pass_wd = $_POST['pass_wd'];
        $dtset = $conn->query("select * 
                                from usr_data
                               where user_id  ='$user_id' 
                                 and pass_wd  ='$pass_wd'");
        $conn->close();
        if($dtset->num_rows > 0 ) {
            $user = json_decode(json_encode($dtset->fetch_assoc()));
            $_SESSION['user_id']    = $user->user_id;
            $_SESSION['role_id']    = $user->role_id;
            $_SESSION['role_nm']    = $user->role_nm;
            $_SESSION['home_pg']    = $user->home_pg;
            $_SESSION['supp_id']    = $user->supp_id;
            $_SESSION['sup_qry']    = $user->sup_qry;
            $_SESSION['user_nm']    = $user->user_nm;
        } else {
            $_SESSION['status'] = 'Login Failed';
        }
    }
    if(isset($_SESSION['user_id'])) {
        $site_id = $user->home_pg;
        if(isset($_SESSION['page_id'])) {
            $page_id = $_SESSION['page_id'];
        } else {
            $page_id = $site_id;
        } 
        header("Location: ".$page_id);  
        exit(0);
    }
    include('../incld/header.php');
    include('../admin/form_login.php');
    include('../incld/jslib.php');
?>