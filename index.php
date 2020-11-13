<!--
This script runs the home page.

-->


<?php
    require 'connect.php';

    $query = "SELECT * FROM dioramas ORDER BY diorama_id DESC";
    $statement = $db->prepare($query);
    $statement->execute();

    session_start();
    print_r($_SESSION);
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Best Blog Ever</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>

<div id="wrapper">
    <div id="header">
        <h1><a href="index.php">DIY Crafters - Index</a></h1>
    </div> <!-- END div id="header" -->


    <div id="user_control_panel">
        <p>Welcome back, <?= $_SESSION['Username']; ?>
            (<a href="logout.php?logout=true" onclick="confirmScript()">Logout</a>)</p>
    </div>


<ul id="menu">
    <li><a href="index.php" class='active'>Home</a></li>
    <li><a href="create.php" >New Post</a></li>
    <li id="register_page"><a href="register.php" >Register</a></li>
    <li id="login_page"><a href="login.php" >Login</a></li>
    <li><a href="users.php" >Users</a></li>
</ul> <!-- END div id="menu" -->


<div id="all_blogs">
    <?php while($row = $statement->fetch() ) : ?>

        <div class="blog_post">
        <h2><a href="show.php?id=<?= $row['Diorama_ID'] ?>"><?= $row['Title'] ?></a></h2>
        </div> <!-- END div class="blog_post" -->
    <?php endwhile ?>
</div> <!-- END div id="all_blogs" -->



    <div id="footer">
        Copywrong 2020 - No Rights Reserved
    </div> <!-- END div id="footer" -->

</div> <!-- END div id="wrapper" -->


<?php if(isset($_SESSION['Username'])) : ?>
    <script>
        document.getElementById("register_page").style.display = "none";
        document.getElementById("login_page").style.display = "none";
        document.getElementById("user_control_panel").style.display = "block";
    </script>
<?php else : ?>
    <script>
        document.getElementById("register_page").style.display = "inline";
        document.getElementById("login_page").style.display = "inline";
        document.getElementById("user_control_panel").style.display = "none";
    </script>
<?php endif ?>



</body>
</html>

<script>
function confirmScript() {
    $result = confirm("Log out?");
    if(!$result) {
        event.preventDefault();
    }
}

</script>


<!--        document.getElementById("user_control_panel").style.display = "block"; -->
