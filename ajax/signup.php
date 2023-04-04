<?php

    // Include the connection file
    require_once "../Admin/connection.php";

    // Get data to insert it into database
    if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"])) {

        $username     = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
        $email        = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password     = $_POST["password"];
        $hashPassword = sha1($password);

        // For validate email and inputs fields
        $validEmail       = false;
        $checkInputValues = false;

        // Sql command to check if email is already in database or not
        $stmt = $conn->prepare(
            "SELECT
                email
            FROM
                users
            WHERE
                email = ?"
        );

        // Execute the sql command
        $stmt->execute(array(
            $email
        ));
    
        // Nubmer of row
        $count = $stmt->rowCount();

        if($username != "" && $email != "" && $password != "") {

            $checkInputValues = true;

            // If the email isn't in database
            if($count === 0) {
    
                $validEmail = true;
    
                $sql = $conn->prepare(
                    "INSERT INTO
                            users (username, email, password, date_registration)
                    VALUES
                            (:uname, :mail, :pwd, now())"
                );

                $sql->execute(array(
                    "uname" => $username,
                    "mail"  => $email,
                    "pwd"   => $hashPassword
                ));
    
            }

        }

        // Means all input fields are fulfilled
        if($checkInputValues === true) {
            // Means the email isn't in the database
            if($validEmail === true) {
    
                echo 1;
    
            } else {
    
                echo 0;
    
            }
    
        }

    }