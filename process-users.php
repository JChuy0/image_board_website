<?php 
    require "connect.php"; 

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
    $userpass = filter_input(INPUT_POST, 'password_one', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email    = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $hashed_password = password_hash($userpass, PASSWORD_DEFAULT);

    if(($_POST['command']) === 'Register') {

        //Build the parameterized SQL query and bind sanitized values to the parameters 
        $query     = "INSERT INTO Users (Username, Userpass, Email, AccessLevel) values (:username, :userpass, :email, :accesslevel)"; 
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':userpass', $hashed_password);
        $statement->bindValue(':email', $email);
        $statement->bindvalue(':accesslevel', 1);
 
        //Execute the INSERT prepared statement. 
        $statement->execute(); 
 
        //Determine the primary key of the inserted row. 
        $insert_id = $db->lastInsertId();

        print("User '{$username}' has been successfully registered.");
             
    } else if (($_POST['command']) === 'Login') {

        $query    = "SELECT Username, Userpass, User_ID, AccessLevel FROM Users WHERE Username = :username";
        $statement = $db->prepare($query);
        $statement->bindvalue(':username', $username);

        $statement->execute();
        $row = $statement->fetch();


        if(password_verify($userpass, $row['Userpass'])) {
            session_start();
            $_SESSION = [];
            $_SESSION['Username'] = $row['Username'];
            $_SESSION['User_ID'] = $row['User_ID'];
            $_SESSION['AccessLevel'] = $row['AccessLevel'];
            print("You have logged in as '{$username}'.<BR>");

        } else {
            print("The username/password you have entered does not match our records. Please try again.");

            header("Location: login.php");
        }

    }

    header("Location: index.php"); 
    exit("Success message");
     
?>