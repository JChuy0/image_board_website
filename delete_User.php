<?php
require 'connect.php';
require 'authenticate.php';

    if($_GET['deleteUser'] === 'true') {

        $user_id = $_GET['ID'];

        $query = "DELETE FROM users WHERE users.User_ID = :user_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();

        header("Location: users.php");
    }

?>