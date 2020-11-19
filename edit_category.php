<!-- 
This script allows users to edit the names of the categories.
 
-->

<?php
require 'connect.php';

    if( (isset($_POST['command'])) && ($_POST['command'] === 'Update') ) {
        print_r($_POST);
        $name = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
        $id   = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); 

        $query = "UPDATE categories SET Name = :name WHERE ID = :id";

        $statement = $db->prepare($query);
        $statement->bindValue(':name', $name);
        $statement->bindValue(':id', $id);

        $statement->execute();

        header("Location: add_category.php");
    } else {
        print_r($_GET);
        if($_GET['editCategory'] === 'true') {
            $id   = filter_input(INPUT_GET, 'ID', FILTER_SANITIZE_NUMBER_INT); 
            
            $query = "SELECT * FROM categories WHERE ID = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':id', $id);
            $statement->execute();
            
            $row = $statement->fetch();
        }
    }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Best Blog Ever - Edit Category</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">Best Blog Ever - Edit Category</a></h1>
        </div> <!-- END div id="header" -->
<ul id="menu">
    <li><a href="index.php" >Home</a></li>
    <li><a href="create.php" >New Post</a></li>
</ul> <!-- END div id="menu" -->




<div id="all_blogs">
  <form action="" method="post">
    <fieldset>
      <legend>Edit Category</legend>
      <p>
        <label for="category">Category:</label>
        <input type="text" name="category" id="category" value="<?=$row['Name']?>" autofocus required/>
      </p>

        <input type="hidden" name="id" value="<?= $row['ID']?>" />
        <input type="submit" name="command" value="Update" />
      </p>
    </fieldset>
  </form>
</div> <!-- END div id="all_blogs" -->
        <div id="footer">
            Copywrong 2020 - No Rights Reserved
        </div> <!-- END div id="footer" -->
    </div> <!-- END div id="wrapper" -->
</body>
</html>
