<!--    This script allows users to create new posts, edit, and delete posts. And make comments on other posts.


    -->

<?php 
    require "connect.php"; 
 
print_r($_POST); 
print("<br><br><br>"); 

print_r($_FILES); 
print("<br><br><br>"); 


    $title       = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
    $content     = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
    $diorama_id  = filter_input(INPUT_POST, 'diorama_id', FILTER_SANITIZE_NUMBER_INT);
    $user_id     = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $comment     = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Image name was not sanitised because file names cannot contain certain characters. AND all images are renamed before being added to the database.
//    $image_name = filter_var($_FILES["fileToUpload"]["name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 


    if(($_POST['command']) === 'Create') { 
    
        $image_name = verify_Image();


        //Build the parameterized SQL query and bind sanitized values to the parameters 
        $query     = "INSERT INTO Dioramas (Title, Content, Image_Name, User_ID) VALUES (:title, :content, :image_name, :user_id)"; 
        $statement = $db->prepare($query); 
        $statement->bindValue(':title', $title); 
        $statement->bindValue(':content', $content); 
        $statement->bindValue(':image_name', $image_name);
        $statement->bindValue(':user_id', $user_id);

        //Execute the INSERT prepared statement. 
        $statement->execute(); 

        //Determine the primary key of the inserted row. 
        $insert_id = $db->lastInsertId(); 
        
    } elseif (($_POST['command']) === 'Update') { 

        if(isset($_POST['imageCheckBox'])) {
        $query = "SELECT Image_Name FROM Dioramas WHERE Diorama_ID = :diorama_id"; 
        $statement = $db->prepare($query); 
        $statement->bindValue(':diorama_id', $diorama_id, PDO::PARAM_INT); 
        $statement->execute(); 
        
        $row = $statement->fetch();

        // Unlinks and removes the image from the uploads folder.
        unlink("uploads/{$row['Image_Name']}"); 
        
        $query = "UPDATE dioramas SET Image_Name = NULL WHERE Diorama_ID = :diorama_id";
        $statement = $db->prepare($query);
        $statement->bindvalue(':diorama_id', $diorama_id, PDO::PARAM_INT);
        $statement->execute();
        }


        // Build the parameterized SQL query and bind the sanitized values to the parameters 
        $query     = "UPDATE Dioramas SET Title = :title, Content = :content WHERE Diorama_ID = :diorama_id"; 
        $statement = $db->prepare($query); 
        $statement->bindValue(':title', $title);     
        $statement->bindValue(':content', $content); 
        $statement->bindValue(':diorama_id', $diorama_id, PDO::PARAM_INT); 
        
        // Execute the INSERT. 
        $statement->execute(); 

    } elseif (($_POST['command']) === 'Delete') { 

        $query = "SELECT Image_Name FROM Dioramas WHERE Diorama_ID = :diorama_id"; 
        $statement = $db->prepare($query); 
        $statement->bindValue(':diorama_id', $diorama_id, PDO::PARAM_INT); 
        $statement->execute(); 

        $row = $statement->fetch();

        unlink("uploads/{$row['Image_Name']}");


        $query = "DELETE FROM Dioramas WHERE Diorama_ID = :diorama_id"; 
        $statement = $db->prepare($query); 
        $statement->bindValue(':diorama_id', $diorama_id, PDO::PARAM_INT); 
        $statement->execute();

    } elseif (($_POST['command']) === 'Comment') {

        $query = "INSERT INTO comments (User_Comment, Diorama_ID, User_ID) VALUES (:user_comment, :diorama_ID, :user_ID)";
        $statement = $db->prepare($query);
        $statement->bindValue(':user_comment', $comment);
        $statement->bindValue(':diorama_ID', $diorama_id);
        $statement->bindValue(':user_ID', $user_id);

        $statement->execute();
        $insert_id = $db->lastInsertId();

        header("Location: show.php?diorama_id=$diorama_id");
        exit();
    }


    header("Location: index.php"); 
    exit();



function verify_Image() { 
 
    $image_upload_detected = isset($_FILES['fileToUpload']) && ($_FILES['fileToUpload']['error'] === 0); 
 
    if ($image_upload_detected) {
        $uploadOk = 0;

        //Checks the image size of the uploaded file
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }  

        if($uploadOk === 1) {
            $fileName = $_FILES['fileToUpload']['tmp_name']; 
            $sourceProperties = getimagesize($fileName);
            $resizeFileName = time();
            $uploadPath = "./uploads/";
            $fileExt = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);
          
            $uploadImageType = $sourceProperties[2];
            $sourceImageWidth = $sourceProperties[0];
            $sourceImageHeight = $sourceProperties[1];

            switch ($uploadImageType) {
                case IMAGETYPE_JPEG:
                    $resourceType = imagecreatefromjpeg($fileName); 
                    $imageLayer = resizeImage($resourceType, $sourceImageWidth, $sourceImageHeight);
                    imagejpeg($imageLayer, $uploadPath."thumb_".$resizeFileName.'.'. $fileExt);
                    break;
          
                case IMAGETYPE_GIF:
                    $resourceType = imagecreatefromgif($fileName); 
                    $imageLayer = resizeImage($resourceType, $sourceImageWidth, $sourceImageHeight);
                    imagegif($imageLayer, $uploadPath."thumb_".$resizeFileName.'.'. $fileExt);
                    break;
           
                case IMAGETYPE_PNG:
                    $resourceType = imagecreatefrompng($fileName); 
                    $imageLayer = resizeImage($resourceType, $sourceImageWidth, $sourceImageHeight);
                    imagepng($imageLayer, $uploadPath."thumb_".$resizeFileName.'.'. $fileExt);
                    break;
           
                default:
                    break;
            }

            $newFileName = "thumb_".$resizeFileName.'.'.$fileExt;
            return ($newFileName);
        }
    }

}


function resizeImage($resourceType, $image_width, $image_height) {
    $resizeWidth = 690;
    $resizeHeight = 460;
    $imageLayer = imagecreatetruecolor($resizeWidth, $resizeHeight);
    imagecopyresampled($imageLayer, $resourceType, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $image_width, $image_height);
    return $imageLayer;
}


     
?>