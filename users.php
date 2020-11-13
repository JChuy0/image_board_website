<!--
This script lets gives you admin access to the users table, allowing you to create, edit, or delete users.

-->


<?php
    require 'connect.php';
    require 'authenticate.php';

        $query = "SELECT * FROM User";
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

                <table id="user_table">
                    <tr>
                        <th>User ID</th>
                        <th>Username</td>
                        <th>Password</td>
                        <th>Email</td>
                        <th>Access Level</td>
                    </tr>
                    <?php while($row = $statement->fetch() ) : ?>
                        <tr>
                            <td><?=$row['User_ID'] ?></td>
                            <td><?=$row['Username'] ?></td>
                            <td><?=$row['Userpass'] ?></td>
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

