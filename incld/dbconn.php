<?php
    $host = "agilesaptech.com";
    $user = "agiletwn_bcpl";
    $pass = "agiletwn_bcpl";
    $dbnm = "agiletwn_bcpl";
    
    // Create connection
    $conn = new mysqli($host, $user, $pass, $dbnm);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>