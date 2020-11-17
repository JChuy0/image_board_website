<!--
This script lets users view all posts in the chosen category.

-->


<?php
    require 'connect.php';

    if(isset($_POST["categorySelect"])) {
        $categorySelected = filter_input(INPUT_POST, 'categorySelect', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $query = "SELECT Title, Diorama_ID, Name
                    FROM dioramas D, categories C
                    WHERE C.Name = :category
                        AND C.ID = D.Category_ID";
        $categoryStatement = $db->prepare($query);
        $categoryStatement->bindValue(':category', $categorySelected);
        $categoryStatement->execute();

    }

        $query = "SELECT * FROM categories";
        $statement = $db->prepare($query);
        $statement->execute();
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

    <form method="post">
    <p>Please select a category to see all existing posts in that category.
    <a href="add_category.php">Add a New Category</a></p>
        <?php while($row = $statement->fetch() ) : ?>
            <input type="submit" name="categorySelect" value="<?=$row['Name']?>" />
        <?php endwhile ?>
    </form>

    <?php if(isset($_POST['categorySelect'])) : ?>
        <?php if($categoryStatement->rowcount() === 0) : ?>
            <p>Sorry, there are no posts in that category.</p>
        <?php else : ?>
        <?php $row = $categoryStatement->fetch(); ?>
        <p><small>Displaying all posts in the '<?=$row['Name']?>' category.</small></p>
            <?php while($row = $categoryStatement->fetch() ) : ?>

                <div class="blog_post">
                    <h2><a href="show.php?diorama_id=<?= $row['Diorama_ID'] ?>"><?= $row['Title'] ?></a></h2>
                </div> <!-- END div class="blog_post" -->
            <?php endwhile ?>
        <?php endif ?>
    <?php endif ?>

</div> <!-- END div id="all_blogs" -->



    <div id="footer">
        Copywrong 2020 - No Rights Reserved
    </div> <!-- END div id="footer" -->

</div> <!-- END div id="wrapper" -->
    


</body>
</html>


<script>

function deleteUser() {
    $result = confirm("Delete user?");
    if(!$result) {
        event.preventDefault();
    }
}



</script>

