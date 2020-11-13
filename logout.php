<?php
    if($_GET['logout'] === 'true') {
        session_start();
        $_SESSION = [];
        
        header("Location: index.php"); 
        exit("Success message");
    }

?>