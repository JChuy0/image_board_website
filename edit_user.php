<?php
require 'connect.php';
require 'authenticate.php';

    if( (isset($_POST['command'])) && ($_POST['command'] === 'Update') ) {
        $username    = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
        $userpass    = filter_input(INPUT_POST, 'userpass', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
        $email       = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $accesslevel = filter_input(INPUT_POST, 'accesslevel', FILTER_SANITIZE_NUMBER_INT);
        $id          = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); 

        $hashed_password = password_hash($userpass, PASSWORD_DEFAULT);


        $query = "UPDATE users SET Username = :username, Userpass = :userpass, Email = :email, AccessLevel = :accesslevel WHERE users.User_ID = :id";

        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':userpass', $hashed_password);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':accesslevel', $accesslevel);
        $statement->bindValue(':id', $id);

        $statement->execute();

        header("Location: users.php");
    } else {
        if($_GET['editUser'] === 'true') {
            $id = $_GET['ID'];
            
            $query = "SELECT * FROM users WHERE `User_ID` = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':id', $id);
            $statement->execute();
            
            $row = $statement->fetch();
        }
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
            <h1><a href="index.php">Best Blog Ever - Edit User</a></h1>
        </div> <!-- END div id="header" -->
<ul id="menu">
    <li><a href="index.php" >Home</a></li>
    <li><a href="create.php" >New Post</a></li>
</ul> <!-- END div id="menu" -->




<div id="all_blogs">
  <form action="" method="post">
    <fieldset>
      <legend>Edit User Information</legend>
      <p>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?=$row['Username']?>" autofocus required/>
      </p>
      <p>
        <label for="userpass">Password:</label>
        <input type="password" name="userpass" id="userpass" value="" required/>
      </p>
      <p>
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" value="<?=$row['Email']?>" required/>
      </p>
      <p>
        <label for="accesslevel">Access Level:</label>
        <input type="text" name="accesslevel" id="accesslevel" value="<?=$row['AccessLevel']?>" required/>
      </p>

        <input type="hidden" name="id" value="<?= $row['User_ID']?>" />
        <input type="submit" name="command" value="Update" />
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
