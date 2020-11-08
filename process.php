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
 
            $image_name = verify_Image();

 
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

            if(isset($_POST['imageCheckBox'])) {
                $query = "SELECT Image_Name FROM Dioramas WHERE Diorama_ID = :id"; 
                $statement = $db->prepare($query); 
                $statement->bindValue(':id', $id, PDO::PARAM_INT); 
                $statement->execute(); 
            
                $row = $statement->fetch();
                unlink("uploads/{$row['Image_Name']}"); 
            
                $query = "UPDATE dioramas SET Image_Name = NULL WHERE Diorama_ID = :id";
                $statement = $db->prepare($query);
                $statement->bindvalue(':id', $id, PDO::PARAM_INT);
                $statement->execute();
            }



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
    $resizeWidth = ($image_width * 0.3);
    $resizeHeight = ($image_height * 0.3);
    $imageLayer = imagecreatetruecolor($resizeWidth, $resizeHeight);
    imagecopyresampled($imageLayer, $resourceType, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $image_width, $image_height);
    return $imageLayer;
}


function delete_Image() {
}





     
?>