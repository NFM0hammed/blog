<?php

    session_start();

    // Require the connection file
    require_once "../Admin/connection.php";

    if(isset($_POST)) {

        $data = json_decode(file_get_contents("php://input"), true);

        if(!empty($data["oldPassword"]) && !empty($data["newPassword"])) {

            $id          = $data["id"];
            $hashOldPass = sha1($data["oldPassword"]);
            $hashNewPass = sha1($data["newPassword"]);
    
            // Check if old password is the same password that you wrote
            $stmt = $conn->prepare(
                "SELECT
                    user_id, password
                FROM
                    users
                WHERE
                    user_id     = ?
                    AND
                    password    = ?"
            );
    
            $stmt->execute(array(
                $id,
                $hashOldPass
            ));
    
            $count = $stmt->rowCount();
    
            // Means the same password in the database
            if($count === 1) {
    
                $stmt2 = $conn->prepare(
                    "UPDATE
                        users
                    SET
                        password    = ?
                    WHERE
                        user_id     = ?"
                );
    
                $stmt2->execute(array (
                    $hashNewPass,
                    $id
                ));
    
                echo 1;
    
            } else {
    
                echo 0;
    
            }

        }

    }