<?php
require 'connect.php';
require 'authenticate.php';

    if($_GET['deleteComment'] === 'true') {

        $comment_id = filter_input(INPUT_GET, 'comment_id', FILTER_SANITIZE_NUMBER_INT);
        $diorama_id = filter_input(INPUT_GET, 'diorama_id', FILTER_SANITIZE_NUMBER_INT);

        $query = "DELETE FROM comments WHERE comments.comment_ID = :comment_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':comment_id', $comment_id);
        $statement->execute();

        header("Location: show.php?diorama_id=$diorama_id");
    }

?>