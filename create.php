<!--
This script is for creating new posts.

-->

<?php
    session_start();

    if(!isset($_SESSION['Username'])) {
        exit("Access Denied: If you would like to create a new post, please login.");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Best Blog Ever - New Blog Post</title>
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
    <form action="process.php" method="post" enctype="multipart/form-data">
        <fieldset>
            <legend>New Blog Post</legend>
            <p>
                <label for="title">Title</label>
                <input name="title" id="title" />
            </p>
            <p>
                <label for="content">Content</label>
                <textarea name="content" id="content"></textarea>
            </p>
            <p>
                <input type="submit" name="command" value="Create" />
            </p>
        </fieldset>

        <p>
            <label for="image">Image Filename:</label>
            <input type="file" name="fileToUpload" id="fileToUpload">
        </p>

    </form>


</div> <!-- END div id="all_blogs" -->



    <div id="footer">
        Copywrong 2020 - No Rights Reserved
    </div> <!-- END div id="footer" -->

</div> <!-- END div id="wrapper" -->
    


</body>
</html>