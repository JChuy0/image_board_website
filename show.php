<!--
This script lets you see the entire message of a post.


-->


<?php
    require 'connect.php';

    session_start();
    print_r($_SESSION);

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

        $query = "SELECT User_Comment, Comment_ID, Diorama_ID, Date_Posted, Username
                    FROM comments, users
                    WHERE users.User_ID = comments.User_ID
                        AND diorama_ID = :diorama_id
                    ORDER BY Date_Posted DESC";
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
    <?php ($row1 = $statement->fetch() ) ?>

    <div class="blog_post">

        <h2><a href="show.php?diorama_id=<?= $row1['Diorama_ID'] ?>"><?= $row1['Title'] ?></a></h2>
        <p>
            <small>
            Posted by - <?= $row1['Username'] ?> | <?= date('F d, Y, h:i a', strtotime($row1['Date_Posted'])) ?> -
            <a class="edit_post" href="edit.php?diorama_id=<?= $row1['Diorama_ID'] ?>">edit</a>

            <?php if($row1['Date_Edited'] !== NULL) : ?>
                <br>Last Edited - <?= date('F d, Y, h:i a', strtotime($row1['Date_Edited'])) ?>
            <?php endif ?>

            </small>
        </p>
            <div class='blog_content'>
                <?= $row1['Content'] ?>
            </div>

        <?php if (strlen($row1['Image_Name']) > 1) : ?>
            <img src="Uploads/<?= $row1['Image_Name']?>" alt="<?= $row1['Image_Name'] ?>" />
        <?php endif ?>

    </div> <!-- END div class="blog_post" -->
</fieldset>

<fieldset>
    <form action="process.php" method="post">
        <label for="comment">Add a Comment</label>
        <textarea name="comment" id="comment"></textarea>

        <input type="hidden" name="diorama_id" value="<?= $diorama_id?>" />
        <input type="hidden" name="user_id" value="<?= $_SESSION['User_ID']?>" />
        <input type="submit" name="command" value="Comment" />
    </form>

    <fieldset>

    <p id="comment_block">
        <?php if($comment_Statement->rowcount() !== 0) : ?>
            <?php while($row2 = $comment_Statement->fetch() ) : ?>
                <p class="individual_comment">
                    <?= $row2['Username'] ?> 
                    <a class="comment_mod" href="delete_comment.php?diorama_id=<?=$row2['Diorama_ID']?>&deleteComment=true&comment_id=<?=$row2['Comment_ID']?>"
                         onclick="deleteComment()">Delete</a> <br>
                    <?= $row2['Date_Posted'] ?> <br>
                    <?= $row2['User_Comment'] ?>

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



<?php if(isset($_SESSION['AccessLevel'])) : ?>
    <?php if(($_SESSION['User_ID'] === $row1['User_ID'] ) || ($_SESSION['AccessLevel'] === '5') ) : ?>
        <script>

            var x = document.getElementsByClassName("edit_post");
            var i;
            for(i = 0; i < x.length; i++) {
                x[i].style.display = "inline";
            }

        </script>
    <?php else : ?>
        <script>

            var x = document.getElementsByClassName("edit_post");
            var i;
            for(i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }

        </script>
        <?php endif ?>
<?php endif ?>


<?php if( (isset($_SESSION['AccessLevel'])) && ($_SESSION['AccessLevel'] === '5') ) : ?>
    <script>

        var x = document.getElementsByClassName("comment_mod");
        var i;
        for(i = 0; i < x.length; i++) {
            x[i].style.display = "inline";
        }

    </script>
<?php else : ?>
    <script>

        var x = document.getElementsByClassName("comment_mod");
        var i;
        for(i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }

    </script>
<?php endif ?>



</body>
</html>


<script>

function deleteComment() {
    $result = confirm("Delete comment?");
    if(!$result) {
        event.preventDefault();
    }
}



</script>

