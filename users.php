<!--
This script lets administrators access the users table, allowing them to view, create, edit, or delete users.

-->


<?php
    require 'connect.php';
    require 'authenticate.php';

    if( (isset($_POST['command'])) && ($_POST['command'] === 'Create New User') ) {
        $username    = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
        $userpass    = filter_input(INPUT_POST, 'userpass', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
        $email       = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $accesslevel = filter_input(INPUT_POST, 'accesslevel', FILTER_SANITIZE_NUMBER_INT);

        $hashed_password = password_hash($userpass, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (Username, Userpass, Email, AccessLevel) VALUES (:username, :userpass, :email, :accesslevel)";

        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':userpass', $hashed_password);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':accesslevel', $accesslevel);

        $statement->execute();
        $insert_id = $db->lastInsertId();

        header("Location: users.php");
    }

        $query = "SELECT * FROM Users";
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
        <legend>Create New User</legend>
        <p>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" autofocus/>

            <label for="userpass">Password:</label>
            <input type="text" name="userpass" id="userpass" />

            <label for="email">Email:</label>
            <input type="text" name="email" id="email" />

            <label for="accesslevel">Access Level:</label>
            <input type="text" name="accesslevel" id="accesslevel" />
        </p>

            <input type="hidden" name="id" />
            <input type="submit" name="command" value="Create New User" />
        </p>
        </fieldset>
    </form>

                <table id="user_table">
                    <tr>
                        <th>User ID</th>
                        <th>Username</td>
<!--                        <th>Password</td> -->
                        <th>Email</td>
                        <th>Access Level</td>
                    </tr>
                    <?php while($row = $statement->fetch() ) : ?>
                        <tr>
                            <td><?=$row['User_ID'] ?></td>
                            <td><?=$row['Username'] ?></td>
<!--                            <td><?=$row['Userpass'] ?></td> -->
                            <td><?=$row['Email'] ?></td>
                            <td><?=$row['AccessLevel'] ?></td>
                            <td><a href="edit_user.php?editUser=true&ID=<?=$row['User_ID']?>">Edit</a></td>
                            <td><a href="delete_User.php?deleteUser=true&ID=<?=$row['User_ID']?>" onclick="deleteUser()">Delete</a></td>
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

