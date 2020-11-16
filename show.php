<!--
This script lets you see the entire message of a post.


-->


<?php
    require 'connect.php';

    if((filter_input(INPUT_GET, 'diorama_id', FILTER_VALIDATE_INT)) ) {
        $diorama_id = filter_input(INPUT_GET, 'diorama_id', FILTER_SANITIZE_NUMBER_INT);

        $query = "SELECT Diorama_ID, Title, Content, Date_Posted, Date_Edited, Image_Name, Username, users.User_ID
                    FROM dioramas, users
                        WHERE users.User_ID = dioramas.User_ID
                        AND Dioramas.Diorama_ID = :diorama_id";
        $statement = $db->prepare($query);
        $statement->bindvalue(':diorama_id', $diorama_id, PDO::PARAM_INT);
        $statement->execute();

        

        if($statement->rowcount() === 0) {
            header("Location: index.php");
            exit();
        }

        $query = "SELECT User_Comment, Date_Posted, Username
                    FROM comments, users
                    WHERE users.User_ID = comments.User_ID
                        AND diorama_ID = :diorama_id
                    ORDER BY User_Comment DESC";
        $comment_Statement = $db->prepare($query);
        $comment_Statement->bindValue(':diorama_id', $diorama_id, PDO::PARAM_INT);
        $comment_Statement->execute();

    
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

        <h2><a href="show.php?diorama_id=<?= $row['Diorama_ID'] ?>"><?= $row['Title'] ?></a></h2>
        <p>
            <small>
            Posted by - <?= $row['Username'] ?> <br>
            <?= date('F d, Y, h:i a', strtotime($row['Date_Posted'])) ?> -
            <a href="edit.php?diorama_id=<?= $row['Diorama_ID'] ?>">edit</a>

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

        <input type="hidden" name="diorama_id" value="<?= $diorama_id?>" />
        <input type="hidden" name="user_id" value="<?= $row['User_ID']?>" />
        <input type="submit" name="command" value="Comment" />
    </form>

    <fieldset>

    <p id="comment_block">
        <?php if($comment_Statement->rowcount() !== 0) : ?>
            <?php while($row = $comment_Statement->fetch() ) : ?>
                <p class="individual_comment">
                    <?= $row['Username'] ?> <br>
                    <?= $row['Date_Posted'] ?> <br>
                    <?= $row['User_Comment'] ?>
                </p>
            <?php endwhile ?>
        <?php else : ?>
            <p class="indivual_comment">There are no comments to display. Why not post one?</p>
        <?php endif ?>
    </p>

    </fieldset>

</fieldset>
</div> <!-- END div id="all_blogs" -->



    <div id="footer">
        Copywrong 2020 - No Rights Reserved
    </div> <!-- END div id="footer" -->

</div> <!-- END div id="wrapper" -->
    


</body>
</html>



