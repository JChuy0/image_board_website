<!--
This script lets you see the entire message of a post.


-->


<?php
    require 'connect.php';

    if((filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) ) {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    
        $query = "SELECT * FROM Dioramas WHERE Diorama_ID = :id";
        $statement = $db->prepare($query);
        $statement->bindvalue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

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
    <title>Best Blog Ever - Show</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>

<div id="wrapper">
    <div id="header">
        <h1><a href="index.php">Best Blog Ever - Index</a></h1>
    </div> <!-- END div id="header" -->

<ul id="menu">
    <li><a href="index.php" class='active'>Home</a></li>
    <li><a href="create.php" >New Post</a></li>
</ul> <!-- END div id="menu" -->


<div id="all_blogs">

    <?php while($row = $statement->fetch() ) : ?>

        <div class="blog_post">

        <h2><a href="show.php?id=<?= $row['Diorama_ID'] ?>"><?= $row['Title'] ?></a></h2>
        <p>
            <small>
            <?= date('F d, Y, h:i a', strtotime($row['Date_Posted'])) ?> -
            <a href="edit.php?id=<?= $row['Diorama_ID'] ?>">edit</a>
            </small>
        </p>
            <div class='blog_content'>
                <?= $row['Content'] ?>
            </div>
        </div> <!-- END div class="blog_post" -->
    <?php endwhile ?>

</div> <!-- END div id="all_blogs" -->



    <div id="footer">
        Copywrong 2020 - No Rights Reserved
    </div> <!-- END div id="footer" -->

</div> <!-- END div id="wrapper" -->
    


</body>
</html>
