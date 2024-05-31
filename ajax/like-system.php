<?php

    session_start();

    // Require the connection file
    require_once "../Admin/connection.php";

    // Get like data to insert it into database
    if(isset($_POST["article_id"])) {

        $user_id    = $_SESSION["id"];
        $article_id = $_POST["article_id"];

        // Sql command to insert like data into database
        $stmt1 = $conn->prepare(
            "INSERT INTO
                likes (user_id, article_id)
            VALUES (:uid, :aid)"
        );
        
        // Execute the statement
        $stmt1->execute(array(
            "uid"   => $user_id,
            "aid"   => $article_id
        ));

        // Successfully inserted
        if($stmt1) {
        
            echo 1;

        }

    }