<!--
This script lets you edit existing blog posts.


-->

<?php
  require 'connect.php';
  
  if((filter_input(INPUT_GET, 'diorama_id', FILTER_VALIDATE_INT)) ) {
    $diorama_id = filter_input(INPUT_GET, 'diorama_id', FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT * FROM Dioramas WHERE Diorama_ID = :diorama_id";
    $statement = $db->prepare($query);
    $statement->bindvalue(':diorama_id', $diorama_id, PDO::PARAM_INT);
    $statement->execute();
  
    $row = $statement->fetch();

    if($statement->rowcount() === 0) {
      header("Location: index.php");
      exit();
    }
  } else {
    header("Location: index.php");
    exit();
  }

  $query = "SELECT * FROM categories";
  $category_statement = $db->prepare($query);
  $category_statement->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Best Blog Ever - Edit Post</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">Best Blog Ever - Edit Post</a></h1>
        </div> <!-- END div id="header" -->
<ul id="menu">
    <li><a href="index.php" >Home</a></li>
    <li><a href="create.php" >New Post</a></li>
</ul> <!-- END div id="menu" -->




<div id="all_blogs">
  <form action="process.php" method="post">
    <fieldset>
      <legend>Edit Blog Post</legend>
      <p>
        <label for="title">Title</label>
        <input name="title" id="title" value="<?= $row['Title'] ?>" />
      </p>
      <p>
        <label for="content">Content</label>
        <textarea name="content" id="content"><?= $row['Content'] ?></textarea>
      </p>
      <p>
      <?php if(strlen($row['Image_Name']) > 1) : ?>
        <input type="checkbox" id="imageCheckBox" name="imageCheckBox" value="deleteImage" />
        <label for="imageCheckBox"> Delete Image?</label><br>
      <?php endif ?>

      <li>
          <label for="category">Category</label>
          <select id=category" name="category">
              <?php while($rowTwo = $category_statement->fetch() ) : ?>
                  <option value="<?=$rowTwo['ID']?>"><?=$rowTwo['Name']?></option>
              <?php endwhile ?>
          </select>
      </li>

      
        <input type="hidden" name="diorama_id" value="<?= $diorama_id?>" />
        <input type="submit" name="command" value="Update" />
        <input type="submit" name="command" value="Delete" onclick="return confirm('Are you sure you wish to delete this post?')" />
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
