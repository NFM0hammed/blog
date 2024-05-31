<?php
        
    session_start();

    $title = "الملف الشخصي";

    include "init.php";

    include $template . "navbar.php";

    $action = isset($_GET["action"]) ? $_GET["action"] : 0;

    // If there is GET action of id
    $id = isset($_GET["id"]) && is_numeric($_GET["id"]) ? intval($_GET["id"]) : 0;

    if($action === "profile") {

        // Check if there is a user in database
        $check = checkData(
            "users",
            "user_id",
            $id
        );

        // Means there is a user
        if($check === 1) {

            // Fetch specific data of user from database
            $data = selectSpecificData(
                "user_id, username, user_img, date_registration, group_id",
                "users",
                "",
                "WHERE user_id = ?",
                $id
            );

            $groupId = $data["group_id"];
            
            // Fetch number of all comments of specific user from database
            $comments = selectDataBasedId(
                "user_id",
                "comments",
                "",
                "WHERE user_id = ?",
                "",
                $id
            );
            
            // Fetch number of all articles of specific admin from database
            $articles = selectDataBasedId(
                "user_id",
                "articles",
                "",
                "WHERE user_id = ?",
                "",
                $id
            );
            
            ?>

                <div class="container">
                    <div class="user-profile">
                        <h3>الملف الشخصي</h3>

                        <?php

                            if(intval($groupId) === 1) {

                                ?>

                                    <div class="info">
                                        <div
                                                class   =   "stats"
                                                id      =   "border"
                                        >
                                            <span>عدد المقالات / <?=count($articles)?></span>
                                            <a href="?action=articles&id=<?=$data["user_id"]?>">جميع المقالات</a>
                                        </div>
                                        <aside class="user-info">

                                            <?php

                                                if($data["user_img"] != "") {

                                                    ?>

                                                        <img
                                                                src        =        "./admin/uploads/<?=$data["user_img"]?>"
                                                                alt        =        ""
                                                        >

                                                    <?php

                                                } else {

                                                    ?>

                                                        <!-- Defualt image -->
                                                        <img
                                                                src        =        "./admin/uploads/defualt_image.png"
                                                                alt        =        ""
                                                        >

                                                    <?php

                                                }

                                            ?>
                                            
                                            <span>إداري</span>
                                            <span><?=$data["username"]?></span>
                                            <span>رقم العضوية / <?=$data["user_id"]?></span>
                                            <span>تاريخ التسجيل / <?=$data["date_registration"]?></span>
                                        </aside>
                                    </div>

                                <?php

                            } else {

                                ?>

                                    <div class="info">
                                        <div
                                                class   =   "stats"
                                                id      =   "border"
                                        >
                                            <span>عدد المشاركات / <?=count($comments)?></span>
                                            <a href="?action=comments&id=<?=$data["user_id"]?>">جميع المشاركات</a>
                                        </div>
                                        <aside class="user-info">

                                            <?php

                                                if($data["user_img"] != "") {

                                                    ?>

                                                        <img
                                                                src        =        "./admin/uploads/<?=$data["user_img"]?>"
                                                                alt        =        ""
                                                        >

                                                    <?php

                                                } else {

                                                    ?>

                                                        <!-- Defualt image -->
                                                        <img
                                                                src        =        "./admin/uploads/defualt_image.png"
                                                                alt        =        ""
                                                        >

                                                    <?php

                                                }

                                            ?>
                                            
                                            <span>عضو</span>
                                            <span><?=$data["username"]?></span>
                                            <span>رقم العضوية / <?=$data["user_id"]?></span>
                                            <span>تاريخ التسجيل / <?=$data["date_registration"]?></span>
                                        </aside>
                                    </div>

                                <?php

                            }

                        ?>

                    </div>
                </div>

            <?php

        } else {

            ?>

                <div class="container">
                    <div class="error">
                        This id doesn't exists !
                        <i class="fa-solid fa-circle-exclamation error-icon"></i>
                    </div>
                </div>

            <?php

        }

    } elseif($action === "comments") {

        // Check if there is a user in database
        $check = checkUserData(
            "users",
            "0",
            $id
        );

        if($check === 1) {

            // Fetch all comments of specific user from database
            $comments = selectDataBasedId(
                "user_id, comment_content, comment_date, article_id",
                "comments",
                "",
                "WHERE user_id = ?",
                "ORDER BY comment_id DESC",
                $id
            );

            ?>

                <div class="container">
                    <div class="user-comments">
                        <h3>التعليقات</h3>
                        
                        <?php
                        
                            foreach($comments as $comment) {

                                // Get the article title that contains of all comments
                                $article_title = selectDataBasedId(
                                    "article_id, article_title",
                                    "articles",
                                    "",
                                    "WHERE article_id = ?",
                                    "",
                                    $comment["article_id"]
                                );

                                foreach($article_title as $title) {

                                    ?>

                                        <a href="articles.php?action=article&id=<?=$title["article_id"]?>"><?=$title["article_title"]?></a>

                                    <?php

                                }

                                ?>
                                
                                    <div
                                            class   =   "comments"
                                            id      =   "border"
                                    >
                                        <p><?=$comment["comment_content"]?></p>
                                        <span>كتب في تاريخ / <?=$comment["comment_date"]?></span>
                                    </div>
                                    <hr>

                                <?php

                            }

                        ?>

                    </div>
                </div>

            <?php

        } else {

            ?>

                <div class="container">
                    <div class="error">
                        This id doesn't exists !
                        <i class="fa-solid fa-circle-exclamation error-icon"></i>
                    </div>
                </div>

            <?php

        }

    } elseif($action === "articles") {

        // Check if there is a user in database
        $check = checkUserData(
            "users",
            "1",
            $id
        );
      
        if($check === 1) {

            // Fetch all articles of specific user from database
            $articles = selectDataBasedId(
                "user_id, article_id, article_title, article_content, date_publication",
                "articles",
                "",
                "WHERE user_id = ?",
                "",
                $id
            );

            ?>

                <div class="container">
                    <div class="user-articles">
                        <h3>المقالات</h3>
                        
                        <?php
                        
                            foreach($articles as $article) {

                                ?>
                                
                                    <a href="articles.php?action=article&id=<?=$article["article_id"]?>"><?=$article["article_title"]?></a>
                                    <div
                                            class   =   "articles"
                                            id      =   "border"
                                    >
                                        <p><?=substr($article["article_content"], 0, 100)?>...</p>
                                        <span>كتب في تاريخ / <?=$article["date_publication"]?></span>
                                    </div>
                                    <hr>

                                <?php
                            }

                        ?>

                    </div>
                </div>

            <?php

        } else {

            ?>

                <div class="container">
                    <div class="error">
                        This id doesn't exists !
                        <i class="fa-solid fa-circle-exclamation error-icon"></i>
                    </div>
                </div>

            <?php

        }
        
    } else {

        ?>

            <div class="container">
                <div class="error">
                    The page doesn't exists !
                    <i class="fa-solid fa-circle-exclamation error-icon"></i>
                </div>
            </div>

        <?php

    }

    include $template . "footer.php";
