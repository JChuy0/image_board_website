<!--
This script runs the registration and login page.

-->


<?php
    require 'connect.php';


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

<ul id="menu">
    <li><a href="index.php" >Home</a></li>
    <li><a href="create.php" >New Post</a></li>
    <li><a href="login.php" class='active'>Login/Register</a></li>
</ul> <!-- END div id="menu" -->

<form action="process-users.php" method="post">
    <fieldset>
        <legend>Register</legend>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required><br><br>

        <label for="password_one">Password:</label>
        <input type="password" id="password_one" name="password_one" required><br><br>

        <label for="password_two">Re-enter Password:</label>
        <input type="password" id="password_two" name="password_two" required><br><br>
        
        <input type="submit" name="command" value="Register" onclick="passMatch()"/>
    </fieldset>
</form>


<script>
    // Displays an alert message if the two passwords entered are not identical.
    function passMatch() {
        var pass_one = document.getElementById("password_one").value;
        var pass_two = document.getElementById("password_two").value;

        if (pass_one !== pass_two) {
            alert("Please make sure both passwords are identical.");
            event.preventDefault()
        }
    }

</script>



    <div id="footer">
        Copywrong 2020 - No Rights Reserved
    </div> <!-- END div id="footer" -->

</div> <!-- END div id="wrapper" -->
    


</body>
</html>

