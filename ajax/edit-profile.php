<?php
    
    // Include the connection file
    require_once "../Admin/connection.php";
    
    // // Get data to insert it into database
    if(isset($_POST["allData"])) {
    
        $allData  = json_decode($_POST["allData"]);

        $imgName      = $_FILES["file"]["name"]; // Name of img
        $imgSize      = $_FILES["file"]["size"]; // Size of img
        $tmpName      = $_FILES["file"]["tmp_name"]; // Temporary name of img
        $userId       = $allData[0]; // User id
        $username     = filter_var($allData[1], FILTER_SANITIZE_STRING); // Username
        $email        = filter_var($allData[2], FILTER_SANITIZE_EMAIL); // Email

        // Check the extension of img, size of img
        $faildImg  = true;
        $falidSize = true;

        // Array to add allowed of extensions
        $extensions = array("jpg", "jpeg", "png");

        // Max size of img [700,000 Bytes]
        $maxSize = 700000;

        // Get the extension of img
        $extensionImg = strtolower(end(explode(".", $imgName)));

        // Check the extension of image
        if(!in_array($extensionImg, $extensions)) {

            $faildImg = false;

        }

        // Check the size of image
        if($imgSize > $maxSize) {

            $falidSize = false;

        }

        if($username != "" && $email != "" && $imgName != "") {

            // Check if all is ok
            if($faildImg == true && $falidSize == true) {

                // Upload the img to uploads folder
                $rand = rand(0, 1000000000);

                $nameOfImg = $rand . "_" . $imgName;
            
                $location = "../Admin/uploads/" . $nameOfImg;
            
                move_uploaded_file($tmpName, $location);
        
                $stmt = $conn->prepare(
                    "UPDATE
                        users
                    SET
                        username = ?,
                        email    = ?,
                        user_img = ?
                    WHERE
                        user_id  = ?"
                );
                
                $stmt->execute(array(
                    $username,
                    $email,
                    $nameOfImg,
                    $userId
                ));
    
                echo 1;
    
            } else {
    
                echo 0;
    
            }

        } else {

            echo 0;

        }

    }