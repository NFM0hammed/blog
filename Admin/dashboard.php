<?php

    session_start();

    if(!isset($_SESSION["username"])) {

        header("Location: index.php");

        exit();

    } else {

        $title = "dashboard";

        include "init.php";

        include $template . "navbar.php";

        // Get number of all data
        $users    = getNumberAllData("user_id", "users", "WHERE", "group_id = 0");

        $articles = getNumberAllData("article_id", "articles");

        $likes    = getNumberAllData("article_id", "likes");

        $comments = getNumberAllData("comment_id", "comments");

        ?>

            <div class="container">
                <h1 class="title">Dashboard</h1>
                <!-- Statistics -->
                <div class="stats">
                    <!-- Number of users -->
                    <div class="number-users">
                        <i class="fa-solid fa-user"></i>
                        <h3>Users</h3>
                        <span><?php if($users > 0) echo $users; else echo 0 ?></span>
                    </div>
                    <!-- Number of articles -->
                    <div class="number-articles">
                        <i class="fa-solid fa-newspaper"></i>
                        <h3>Articles</h3>
                        <span><?php if($articles > 0) echo $articles; else echo 0 ?></span>
                    </div>
                    <!-- Number of likes on articles -->
                    <div class="number-likes-articles">
                        <i class="fa-solid fa-thumbs-up"></i>
                        <h3>Likes</h3>
                        <span><?php if($likes > 0) echo $likes; else echo 0 ?></span>
                    </div>
                    <!-- Number of comments on articles -->
                    <div class="number-comms-articles">
                        <i class="fa-solid fa-comment"></i>
                        <h3>Comments</h3>
                        <span><?php if($comments > 0) echo $comments; else echo 0 ?></span>
                    </div>
                </div>
                <!-- Last statistics -->
                <div class="last-stats">
                    <!-- Last three users registered -->
                    <div class="last-three-users">
                        <h3 class="last-three">Last three users registered</h3>

                        <?php

                            $rowsUsers  = selectData(
                                "user_id, username",
                                "users",
                                "",
                                "WHERE",
                                "group_id = 0",
                                "ORDER BY user_id DESC",
                                "LIMIT 3"
                            );

                            if($users > 0) {

                                foreach($rowsUsers as $rowUser) {

                                    ?>

                                        <span><?=$rowUser["username"]?></span>

                                    <?php

                                }

                            } else {

                                ?>

                                    <p>There're no users !</p>

                                <?php

                            }

                        ?>
                        
                    </div>
                    <!-- Last three articles -->
                    <div class="last-three-articles">
                        <h3 class="last-three">Last three articles</h3>

                        <?php

                            $rowsArticles  = selectData(
                                "article_id, article_title",
                                "articles",
                                "",
                                "",
                                "",
                                "ORDER BY article_id DESC",
                                "LIMIT 3"
                            );

                            if($articles > 0) {

                                foreach($rowsArticles as $rowArticle) {

                                    ?>

                                        <span><?=$rowArticle["article_title"]?></span>

                                    <?php

                                }

                            } else {

                                ?>

                                    <p>There're no articles !</p>

                                <?php

                            }

                        ?>
                        
                    </div>
                </div>
                <!-- Last three comments -->
                <div class="last-three-comms">
                    <h3 class="last-three">Last three comments</h3>

                    <?php

                        $rowsComments  = selectData(
                            "comment_id, comment_content",
                            "comments",
                            "",
                            "",
                            "",
                            "ORDER BY comment_id DESC",
                            "LIMIT 3"
                        );

                        if($comments > 0) {

                            foreach($rowsComments as $rowComment) {

                                ?>

                                    <span><?=$rowComment["comment_content"]?></span>

                                <?php

                            }

                        } else {

                            ?>

                                <p>There're no comments !</p>

                            <?php

                        }

                    ?>
                    
                </div>
            </div>

        <?php
    }
    
    include "includes/templates/footer.php";