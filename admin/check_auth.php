<?php 
    if(isset($_SESSION['role_nm'])) {
        $role_nm = $_SESSION['role_nm'];
        if ($role_nm == 'suppl') {
            echo "<h1 style='text-align: center;;color:red;'>you are not authorized to access this page</h1>";
            die();
        }
    }
?>