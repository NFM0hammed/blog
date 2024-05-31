<?php

    session_start();
    
    // Require the connection file
    require_once "../Admin/connection.php";

    // Get data to check it into database
    if(isset($_POST["email"]) && isset($_POST["password"])) {

        $email        = $_POST["email"];
        $password     = $_POST["password"];
        $hashPassword = sha1($password);

        // Sql command to check if email and password are the same
        $stmt = $conn->prepare(
            "SELECT
                user_id, username, email, password
            FROM
                users
            WHERE
                email    = ?
            AND
                password = ?
            AND
                group_id = 0"
        );

        $stmt->execute(array(
            $email,
            $hashPassword
        ));

        $getData = $stmt->fetch();

        $count   = $stmt->rowCount();

        if($count === 1) {
    
            $_SESSION["id"]   = $getData["user_id"];

            // Create the session with the name of the logged
            $_SESSION["user"] = $getData["username"];
    
            echo 1;
    
        }
        
    }