<?php

    session_start();

    if(!isset($_SESSION["username"])) {

        header("Location: index.php");

        exit();

    } else {

        $title = "Profile";

        include "init.php";

        include $template . "navbar.php";

        $sessionID = $_SESSION["userid"];

        $action = isset($_GET["action"]) ? $_GET["action"] : "default";

        // To check if the admin is root
        $checkRole = checkData(
            "users",
            "user_id",
            $sessionID,
            "AND permissions = 1"
        );

        if($action === "default") {

            // Show admin data only from root
            if($checkRole === 1) {

                $rows  = selectData(
                    "*",
                    "users",
                    "",
                    "WHERE",
                    "group_id = 1 AND permissions = 0"
                );

                $count = count($rows);
                
                if($count > 0) {

                    ?>
                    
                        <div class="container">
                            <h1 class="title">Manage admins</h1>
                            <table>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Avatar</th>
                                    <th>Date_reg</th>
                                    <th>Manage</th>
                                </tr>

                                    <?php

                                        foreach($rows as $row) {

                                            ?>

                                                <tr>
                                                    <td><?=$row["user_id"]?></td>
                                                    <td><?=$row["username"]?></td>
                                                    <td><?=$row["email"]?></td>
                                                    <td>

                                                        <?php
                                                        
                                                            if($row["user_img"] != null) {

                                                                ?>

                                                                    <img class="avatar" src="uploads\<?=$row["user_img"]?>" alt="">

                                                                <?php

                                                            } else {

                                                                ?>
                                                                    <!-- Defalut image -->
                                                                    <img class="avatar" src="uploads\defualt_image.png" alt="">

                                                                <?php

                                                            }

                                                        ?>

                                                    </td>
                                                    <td class="date-reg"><?=$row["date_registration"]?></td>
                                                    <td>
                                                        <a class="delete" href="?action=delete&id=<?=$row["user_id"]?>">delete</a>
                                                    </td>
                                                </tr>

                                            <?php

                                        }

                                    ?>

                            </table>
                        </div>

                    <?php
                
                } else {

                    show_msg(
                        "There're no users data !",
                        "alert"
                    );

                }

            } else {

                $row = selectSpecificData(
                    "*",
                    "users",
                    "WHERE user_id = ?",
                    $sessionID
                );

                ?>

                    <div class="container">
                        <h1 class="title">Profile</h1>
                        <form class="show-profile" action="?action=update" method="POST" enctype= multipart/form-data>
                            <h4>Username</h4>
                            <input type="text" name="username" value="<?=$row["username"]?>" />
                            <h4>Email</h4>
                            <input type="text" name="email" value="<?=$row["email"]?>" />
                            <h4>Avatar</h4>
                            <input type="file" name="avatar"/>

                            <?php

                                if($row["user_img"] != null) {

                                    ?>

                                        <img class="avatar" src="uploads\<?=$row["user_img"]?>" alt="">

                                    <?php
                                    
                                } else {

                                    ?>

                                        <!-- Defalut image -->
                                        <img class="avatar" src="uploads\defualt_image.png" alt="">

                                    <?php

                                }

                            ?>

                            <input type="hidden" name="user_id" value="<?=$sessionID?>">
                            <input type="submit" value="Update" />
                        </form>
                    </div>

                <?php
                
            }

        } elseif($action === "delete") {

            // Remove permissions of admin only by root
            if($checkRole === 1) {

                $userid = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
    
                $check = checkData(
                    "users",
                    "user_id",
                    $userid,
                    "AND group_id = 1 AND permissions = 0"
                );
    
                if($check > 0) {
    
                    dealAdmin(
                        $userid,
                        0
                    );
    
                    show_msg(
                        "Remove admin is done !",
                        "success"
                    );

                    redirectPage();
    
                } else {

                    show_msg(
                        "There's no ID as you wrote !",
                        "error"
                    );

                    redirectPage();
    
                }

            } else {

                show_msg(
                    "This page doesn't exists !",
                    "error"
                );

                redirectPage();

            }

        } elseif($action === "update") {

            // Show update page only by admin [root can't open this page because, he didn't have an profile information]
            if($checkRole !== 1) {

                if($_SERVER["REQUEST_METHOD"] === "POST") {

                    $userid   = $_POST["user_id"];
                    $username = $_POST["username"];
                    $email    = $_POST["email"];
                    $avatar   = $_FILES["avatar"];

                    // Get info of avatar
                    $avatarName = $avatar["name"];
                    $avatarSize = $avatar["size"];
                    $avatarTmp  = $avatar["tmp_name"];

                    // Array to add allowed of extensions
                    $extensions = array("jpg", "jpeg", "png");

                    // Max size of img [200,000 Bytes]
                    $maxSize = 200000;

                    // Get the extension of img
                    $extensionImg = strtolower(end(explode(".", $avatarName)));

                    $errors = array();

                    $check = checkData(
                        "users",
                        "user_id",
                        $userid,
                        "AND group_id = 1"
                    );

                    if(empty($username)) {

                        $errors[] = "The username is empty !";

                    }

                    if(empty($email)) {

                        $errors[] = "The email is empty !";

                    }

                    if(empty($avatarName)) {

                        $errors[] = "The avatar is empty !";
    
                    } else {
    
                        // Check the extension of image
                        if(!in_array($extensionImg, $extensions)) {
    
                            $errors[] = "The extension of avatar isn't available !";
    
                        }
    
                        // Check the size of image
                        if($avatarSize > $maxSize) {
    
                            $errors[] = "The size of avatar is larger than 200,000 bytes !";
    
                        }
    
                    }
    

                    if(empty($errors)) {

                        if($check > 0) {

                            // For no repeating the name of avatar
                            $rand = rand(0, 1000000000);

                            // Name of avatar after random number
                            $nameOfImg = $rand . "_" . $avatarName;

                            // Add img to the uploads folder
                            move_uploaded_file($avatarTmp, "uploads\\" . $nameOfImg);
        
                            $stmt = $conn->prepare("UPDATE
                                                        users
                                                    SET
                                                        username = ?,
                                                        email    = ?,
                                                        user_img = ?
                                                    WHERE
                                                        user_id  = ?");
                            $stmt->execute(array(
                                $username,
                                $email,
                                $nameOfImg,
                                $userid
                            ));

                            show_msg(
                                "Update is done !",
                                "success"
                            );

                            redirectPage();

                        }

                    } else {

                        ?>

                            <div class="container">
                                <div class="error">

                                    <?php

                                        foreach($errors as $error) {

                                            echo $error . "</br></br>";

                                        }

                                    ?>

                                </div>
                            </div>

                        <?php

                    }

                } else {

                    show_msg(
                        "You can't open this page directly !",
                        "error"
                    );

                    redirectPage();
                    
                }

            } else {

                show_msg(
                    "This page doesn't exists !",
                    "error"
                );

                redirectPage();

            }
            
        } else {

            show_msg(
                "This page doesn't exists !",
                "error"
            );

            redirectPage();

        }

    }

    include "includes/templates/footer.php";