<?php

    session_start();

    if(!isset($_SESSION["username"])) {

        header("Location: index.php");

        exit();

    } else {

        $title = "Members";

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

            $rows = selectData(
                "*",
                "users",
                "WHERE",
                "group_id = 0"
            );
            
            $count = count($rows);

            if($count > 0) {

                ?>
                
                    <div class="container">
                        <h1 class="title">Members</h1>
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

                                                            <img
                                                                    class   =   "avatar"
                                                                    src     =   "uploads\<?=$row["user_img"]?>"
                                                                    alt     =   ""
                                                            >

                                                        <?php

                                                    } else {

                                                        ?>
                                                            <!-- Defalut image -->
                                                            <img
                                                                    class   =   "avatar"
                                                                    src     =   "uploads\defualt_image.png"
                                                                    alt     =   ""
                                                            >

                                                        <?php

                                                    }

                                                ?>

                                            </td>
                                            <td class="date-reg"><?=$row["date_registration"]?></td>
                                            <td>
                                                <a
                                                    class   =   "delete"
                                                    href    =   "?action=delete&id=<?=$row["user_id"]?>">Delete</a>

                                                <?php
                                                    
                                                    // Show add button [add admin] only by root
                                                    if($checkRole === 1) {

                                                        ?>

                                                            <a
                                                                class   =   "add"
                                                                href    =   "?action=add&id=<?=$row["user_id"]?>">Add</a>

                                                        <?php
                                                    }

                                                ?>

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

        } elseif($action === "delete") {

            $userid = isset($_GET["id"]) && is_numeric($_GET["id"]) ? intval($_GET["id"]) : 0;

            $check = checkData(
                "users",
                "user_id",
                $userid,
                "AND group_id = 0"
            );

            if($check > 0) {

                deleteData(
                    "users", 
                    "user_id",
                    $userid
                );

                show_msg(
                    "Delete is done !",
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

        } elseif($action === "add") {

            // Add admin to member [only by root]
            if($checkRole === 1) {

                $userid = isset($_GET["id"]) && is_numeric($_GET["id"]) ? intval($_GET["id"]) : 0;
    
                $check = checkData(
                    "users",
                    "user_id",
                    $userid,
                    "AND group_id = 0"
                );

                if($check > 0) {

                    dealAdmin(
                        $userid,
                        1
                    );

                    show_msg(
                        "Add admin is done !",
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

        } else {

            show_msg(
                "This page doesn't exists !",
                "error"
            );

            redirectPage();

        }

    }

    include "includes/templates/footer.php";