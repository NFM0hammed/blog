<?php

    session_start();

    require_once "../Admin/connection.php";

    // Get comment data to insert it into database
    if(isset($_POST["comment"]) && isset($_POST["article_id"])) {

        $user_id    = $_SESSION["id"];
        $username   = $_SESSION["user"];
        $comment    = filter_var($_POST["comment"], FILTER_SANITIZE_STRING);
        $article_id = $_POST["article_id"];

        // Means there's a comment
        if($comment != "") {

            // Sql command to insert comment data into database
            $stmt1 = $conn->prepare(
                "INSERT INTO
                    comments (user_id, comment_content, comment_date, article_id)
                VALUES (:uid, :comnt, now(), :aid)"
            );
            
            // Execute the statement
            $stmt1->execute(array(
                "uid"   => $user_id,
                "comnt" => $comment,
                "aid"   => $article_id
            ));

            // Successfully inserted
            if($stmt1) {
                
                // Select the comment that inserted by user
                $stmt2 = $conn->prepare(
                    "SELECT
                        *
                    FROM
                        comments
                    INNER JOIN users ON comments.user_id = users.user_id
                    WHERE
                        comments.user_id = ? and comments.article_id = ?
                    ORDER BY
                        comment_id
                    DESC LIMIT
                        1"
                );

                // Execute the statement
                $stmt2->execute(array(
                    $user_id,
                    $article_id
                ));

                // Calculate the row
                $count = $stmt2->rowCount();

                // If there's a row of comment that inserted by user
                if($count > 0) {

                    // Get the info of row
                    $row = $stmt2->fetch(); 

                    // Img of the user
                    $img = "";
                    
                    // If the user has an img then, show it
                    // If the user hasn't an img then, show the default img
                    if($row["user_img"] != "") {

                        $img = '<img src="Admin\uploads\\' . $row["user_img"] . '" alt="">';

                    } else {

                        $img = '<img src="Admin\uploads\defualt_image.png" alt="">';

                    }

                    // Output of the comment
                    $result = "
                    
                            <div class='info'>

                                <span> " . $row["username"] . " </span>

                                $img

                            </div>

                            <span> ". $row["comment_date"] . " </span>

                            <p> " . $row["comment_content"] . " </p>
                            
                    ";

                    // Get the response if it's inserted
                    echo $result;

                }

            }

        } else {

            // Get the response if it's not inserted
           $result = "لا يمكن إضافة حقل فارغ !";
           
           echo $result;

        }

    }