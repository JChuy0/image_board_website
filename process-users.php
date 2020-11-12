<?php 
    require "connect.php"; 
 
print_r($_POST); 
print("<br><br><br>"); 

 
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
    $userpass = filter_input(INPUT_POST, 'password_one', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email    = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


    if(($_POST['command']) === 'Register') {

        //Build the parameterized SQL query and bind sanitized values to the parameters 
        $query     = "INSERT INTO User (Username, Userpass, Email, AccessLevel) values (:username, :userpass, :email, :accesslevel)"; 
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':userpass', $userpass);
        $statement->bindValue(':email', $email);
        $statement->bindvalue(':accesslevel', 1);
 
        //Execute the INSERT prepared statement. 
        $statement->execute(); 
 
        //Determine the primary key of the inserted row. 
        $insert_id = $db->lastInsertId();

        print("User '{$username}' has been successfully registered.");
             
    } else if (($_POST['command']) === 'Login') {
        $query    = "SELECT Username, AccessLevel FROM User WHERE Username = :username AND Userpass = :userpass";
        $statement = $db->prepare($query);
        $statement->bindvalue(':username', $username);
        $statement->bindvalue(':userpass', $userpass);

        $statement->execute();

        $row = $statement->fetch();

        if($statement->rowcount() > 0) {
            session_start();
            $_SESSION = [];
            $_SESSION['Username'] = $row['Username'];
            $_SESSION['AccessLevel'] = $row['AccessLevel'];
            print("You have logged in as '{$username}'.<BR>");

            print_r($_SESSION);
        } else {
            print("The username/paassword you have entered does not match our records. Please try again.");

//            header("Location: login.php");
        }

    }

//        header("Location: index.php"); 
//        exit(); 



     
?>