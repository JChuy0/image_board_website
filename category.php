<!--
This script lets users add new categories.

-->


<?php
    require 'connect.php';
    session_start();

    if(!isset($_SESSION['Username'])) {
        exit("Sorry, only logged in users can create categories.");
    }

    if( (isset($_POST['command'])) && ($_POST['command'] === 'Create New Category') ) {
        $name    = filter_input(INPUT_POST, 'categoryname', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 

        $query = "INSERT INTO categories (Name) VALUES (:name)";

        $statement = $db->prepare($query);
        $statement->bindValue(':name', $name);

        $statement->execute();
        $insert_id = $db->lastInsertId();

        header("Location: category.php");
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

        <div class="blog_post">

            <div class='blog_content'>

    <form action="" method="post">
        <fieldset>
        <legend>Create New Category</legend>
        <p>
            <label for="categoryname">Category:</label>
            <input type="text" name="categoryname" id="categoryname" required autofocus/>
        </p>
            <input type="submit" name="command" value="Create New Category" />
        </p>
        </fieldset>
    </form>

                <table id="category_table">
                    <tr>
                        <th>Category ID</th>
                        <th>Name</td>
                    </tr>
                    <?php while($row = $statement->fetch() ) : ?>
                        <tr>
                            <td><?=$row['ID'] ?></td>
                            <td><?=$row['Name'] ?></td>
                        </tr>

                    <?php endwhile ?>
                </table>
            </div>

        </div> <!-- END div class="blog_post" -->

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

