<?php
require 'connect.php';
require 'authenticate.php';

    if($_GET['deleteUser'] === 'true') {

        $id = $_GET['ID'];

        $query = "DELETE FROM `user` WHERE `user`.`User_ID` = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id);
        $statement->execute();

        header("Location: users.php");
    }

?>