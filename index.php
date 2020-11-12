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
        <h1><a href="index.php">Best Blog Ever - Index</a></h1>
    </div> <!-- END div id="header" -->


    <div id="user-control-panel">
        <p>Welcome back, <?= $_SESSION['Username']; ?></p>
        <a href="index.php" onclick="testfunc()" >(Logout)</a>
        <input type="submit" name="logout" value="Logout" onclick="testfunc()" />
    </div>



<ul id="menu">
    <li><a href="index.php" class='active'>Home</a></li>
    <li><a href="create.php" >New Post</a></li>
    <li id="register-page"><a href="register.php" >Register</a></li>
    <li id="login-page"><a href="login.php" >Login</a></li>
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


<!--
<?php if(isset($_SESSION)) : ?>
    <script>
        document.getElementById("register-page").style.display = "none";
        document.getElementById("login-page").style.display = "none";
        document.getElementById("user-control-panel").style.display = "block";
    </script>
<?php endif ?>
-->


</body>
</html>


<script>
    function testfunc() {
        <?php
        if(isset($_SESSION)) :
            $_SESSION = [];
        endif ?>
        
//        header("Location: index.php"); 

        alert("melon");
    }

    function anothertest() {
        alert("banana");
    }

</script>


