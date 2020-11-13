<?php 

session_start();

  if( (!isset($_SESSION['AccessLevel'])) && ($_SESSION['AccessLevel'] !== '5') ) {
    exit("Access Denied: Admin access required."); 
  }
   
?>