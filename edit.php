<!--
This script lets you edit existing blog posts.


-->

<?php
  require 'connect.php';
  require 'authenticate.php';
  
  if((filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) ) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT * FROM Dioramas WHERE Diorama_ID = :id";
    $statement = $db->prepare($query);
    $statement->bindvalue(':id', $id, PDO::PARAM_INT);
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
        <input type="hidden" name="id" value="<?= $id?>" />
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
