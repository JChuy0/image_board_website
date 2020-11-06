<!--
This script sanitizes and filters new and edited posts before they are inserted to the database.


-->

<?php
    require "connect.php";

print_r($_POST);
print("<br><br><br>");

print_r($_FILES);
print("<br><br><br>");


    $title   = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id      = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
//    $image_name = filter_var($_FILES["fileToUpload"]["name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if( (strlen($title) < 1) || (strlen($content) < 1) ) {
        $errorMsg = "Please make sure both the title and content text fields are at least 1 character long.";
    } else {
        if(($_POST['command']) === 'Create') {

            verify_Image();
            $image_name = $_FILES["fileToUpload"]["name"];


            //Build the parameterized SQL query and bind sanitized values to the parameters
            $query     = "INSERT INTO Dioramas (Title, Content, Image_Name) values (:title, :content, :image_name)";
            $statement = $db->prepare($query);
            $statement->bindValue(':title', $title);
            $statement->bindValue(':content', $content);
            $statement->bindValue(':image_name', $image_name);

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

            $query = "SELECT Image_Name FROM Dioramas WHERE Diorama_ID = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();

            $row = $statement->fetch();

/*
            print_r($row);
            print("<br>");
            print($row['Image_Name']);
*/

            unlink("uploads/{$row['Image_Name']}");

/*
            $query = "DELETE FROM Dioramas WHERE Diorama_ID = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();
*/
        }
    }

    if(isset($errorMsg)) {
        echo $errorMsg;
    } else {
//        header("Location: index.php");
//        exit();
}


function verify_Image() {

    $image_upload_detected = isset($_FILES['fileToUpload']) && ($_FILES['fileToUpload']['error'] === 0);

    if ($image_upload_detected) {
        
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
    
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"    && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        } 
    
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

    }

}

    
?>