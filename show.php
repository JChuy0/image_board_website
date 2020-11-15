<!--
This script lets you see the entire message of a post.


-->


<?php
    require 'connect.php';

    if((filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) ) {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        $query = "SELECT dioramas.*, comments.User_Comment, users.Username
                    FROM dioramas 
                        LEFT JOIN comments ON dioramas.Diorama_ID = comments.Diorama_ID  
                        LEFT JOIN users ON users.User_ID = comments.User_ID
                        WHERE Dioramas.Diorama_ID = :id";
        $statement = $db->prepare($query);
        $statement->bindvalue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch();

        print_r($row);
        

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

<fieldset>
    <?php ($row = $statement->fetch() ) ?>

    <div class="blog_post">

        <h2><a href="show.php?id=<?= $row['Diorama_ID'] ?>"><?= $row['Title'] ?></a></h2>
        <p>
            <small>
            Posted by - <?= $row['Username'] ?> <br>
            <?= date('F d, Y, h:i a', strtotime($row['Date_Posted'])) ?> -
            <a href="edit.php?id=<?= $row['Diorama_ID'] ?>">edit</a>

            <?php if($row['Date_Edited'] !== NULL) : ?>
                <br>Last Edited - <?= date('F d, Y, h:i a', strtotime($row['Date_Edited'])) ?>
            <?php endif ?>

            </small>
        </p>
            <div class='blog_content'>
                <?= $row['Content'] ?>
            </div>

        <?php if (strlen($row['Image_Name']) > 1) : ?>
            <img src="Uploads/<?= $row['Image_Name']?>" alt="<?= $row['Image_Name'] ?>" />
        <?php endif ?>

    </div> <!-- END div class="blog_post" -->
</fieldset>

<fieldset>
    <form action="process.php" method="post">
        <label for="comment">Add a Comment</label>
        <textarea name="comment" id="comment"></textarea>

        <input type="hidden" name="diorama_id" value="<?= $id?>" />
        <input type="hidden" name="user_id" value="<?= $id?>" />
        <input type="submit" name="command" value="Comment" />
    </form>

    <fieldset>
        <?= $row['User_Comment'] ?>
    </fieldset>

</fieldset>
</div> <!-- END div id="all_blogs" -->



    <div id="footer">
        Copywrong 2020 - No Rights Reserved
    </div> <!-- END div id="footer" -->

</div> <!-- END div id="wrapper" -->
    


</body>
</html>
