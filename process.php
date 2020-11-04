<!--
This script sanitizes and filters new and edited posts before they are inserted to the database.


-->

<?php
    require "connect.php";

    $title   = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id      = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    if( (strlen($title) < 1) || (strlen($content) < 1) ) {
        $errorMsg = "Please make sure both the title and content text fields are at least 1 character long.";
    } else {
        if(($_POST['command']) === 'Create') {

            //Build the parameterized SQL query and bind sanitized values to the parameters
            $query     = "INSERT INTO Dioramas (Title, Content) values (:title, :content)";
            $statement = $db->prepare($query);
            $statement->bindValue(':title', $title);
            $statement->bindValue(':content', $content);
            
            //Execute the INSERT prepared statement.
            $statement->execute();

            //Determine the primary key of the inserted row.
            $insert_id = $db->lastInsertId();
            
        } elseif (($_POST['command']) === 'Update') {
            
            // Build the parameterized SQL query and bind the sanitized values to the parameters
            $query     = "UPDATE Dioramas SET Title = :title, Content = :content WHERE Diorama_ID = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':title', $title);        
            $statement->bindValue(':content', $content);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            
            // Execute the INSERT.
            $statement->execute();

        } elseif (($_POST['command']) === 'Delete') {

            $query = "DELETE FROM Dioramas WHERE Diorama_ID = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();
        }
    }

    if(isset($errorMsg)) {
        echo $errorMsg;
    } else {
        header("Location: index.php");
        exit();
}

print_r($_POST);

    
?>