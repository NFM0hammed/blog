<?php

    session_start();
        
    $title = "المقال";

    include "init.php";

    include $template . "navbar.php";

    $action = isset($_GET["action"]) ? $_GET["action"] : 0;

    if($action === "article") {
        
        $article_id = isset($_GET["id"]) && is_numeric($_GET["id"]) ? intval($_GET["id"]) : 0;

        $sessionId  = isset($_SESSION["id"]) ? $_SESSION["id"] : 0;

        // Calculate the number of views of the article
        if($sessionId) {

            $check = checkData(
                "views",
                "article_id",
                $article_id
            );

            if($check > 0) {

                $row = selectSpecificData(
                    "*",
                    "views",
                    "",
                    "WHERE article_id = ?",
                    $article_id
                );

                $totalViews = $row["number_views"] + 1;

                $sql = $conn->prepare(
                    "UPDATE
                        views
                    SET
                        number_views = ?
                    WHERE
                        article_id   = ?"
                );

                $sql->execute(array(
                    $totalViews,
                    $article_id
                ));

            }

        }

        // Check for if the article is exists or not
        $count1 = checkData("articles", "article_id", $article_id);

        // To calculate all likes on the article that selected by article id
        $count2 = checkData("likes", "article_id", $article_id);

        if($count1 > 0) {

            $row1 = selectSpecificData(
                "*",
                "articles",
                "INNER JOIN users ON articles.user_id = users.user_id INNER JOIN categories ON articles.category_id = categories.category_id",
                "WHERE article_id = ?",
                $article_id
            );

            $stmt = $conn->prepare(
                "SELECT
                    user_id, article_id
                FROM
                    likes
                WHERE
                    user_id = ? and article_id = ?"
            );

            $stmt->execute(array(
                $sessionId,
                $article_id
            ));

            $checkUserAddedLike = $stmt->rowCount();

            ?>

                <div class="container no-padding">
                    <div class="get-articles">
                        <span><?=$row1["description"]?></span>
                        <h1 id="border">
                            <?=$row1["article_title"]?>
                        </h1>
                        <div class="writer">
                            <div class="info">
                                <a href="userprofile.php?action=profile&id=<?=$row1["user_id"]?>"><?=$row1["username"]?> بواسطة</a>

                                <!-- Check if the user has an img or not -->
                                <?php

                                    if($row1["user_img"] != "") {
                                        
                                        ?>

                                            <img
                                                    src     =   "Admin/uploads/<?=$row1["user_img"]?>"
                                                    alt     =   ""
                                            >

                                        <?php

                                    } else {

                                        ?>

                                            <!-- Defualt image -->
                                            <img
                                                    src     =   "Admin/uploads/defualt_image.png"
                                                    alt     =   ""
                                            >

                                        <?php

                                    }

                                ?>
                                
                            </div>
                            <span><?=$row1["date_publication"]?> كتب في تاريخ</span>
                        </div>
                        <hr>
                        <!-- Start article likes -->
                        <div class="article-likes">
                            <h4>
                                عدد الإعجابات
                                &nbsp;
                                <span><?=$count2?></span>
                                <i class="fa-solid fa-thumbs-up"></i>
                            </h4>

                            <?php

                                if($sessionId) {

                                    // Check if the user added like or not
                                    if($checkUserAddedLike == 0) {
    
                                        ?>
    
                                            <button class="add-like">
                                                <i class="fa-solid fa-thumbs-up"></i>
                                            </button>
    
                                        <?php
    
                                    } else {
    
                                        ?>
    
                                            <div class="added-like">تم إضافة إعجاب</div>
    
                                        <?php
    
                                    }

                                }

                            ?>
                            
                        </div>
                        <img
                                src     =   "Admin\uploads\<?=$row1["article_img"]?>"
                                alt     =   ""
                                class   =   "img-article"
                        >
                        <p>
                            <?=$row1["article_content"]?>
                        </p>
                        <div class="comments">
                           <h3>التعليقات</h3>

                                <?php

                                    $rows = selectDataBasedId(
                                        "*",
                                        "comments",
                                        "INNER JOIN users ON comments.user_id = users.user_id",
                                        "WHERE article_id = ?",
                                        "ORDER BY comment_id DESC",
                                        $article_id
                                    );

                                    $c = count($rows);

                                    if($c > 0) {

                                        foreach($rows as $row) {

                                            ?>

                                                <div class="show-comments">
                                                    <div class="info">
                                                        <div class="my-profile">
                                                            <a href="userprofile.php?action=profile&id=<?=$row["user_id"]?>">الملف الشخصي</a>
                                                        </div>
                                                        <div class="user-comment">
                                                            <span><?=$row["username"]?> / بواسطة</span>
                                                            <?php

                                                                if($row["user_img"] != "") {
        
                                                                    ?>
                                                                    
                                                                        <img
                                                                                src     =   "Admin\uploads\<?=$row["user_img"]?>"
                                                                                alt     =   ""
                                                                        >
        
                                                                    <?php
        
                                                                } else {
        
                                                                    ?>
        
                                                                        <!-- Defualt image -->
                                                                        <img
                                                                                src     =   "Admin\uploads\defualt_image.png"
                                                                                alt     =   ""
                                                                        >
        
                                                                    <?php
        
                                                                }
        
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="comment-content">
                                                        <p><?=$row["comment_content"]?></p>
                                                        <span><?=$row["comment_date"]?> كتب في تاريخ</span>
                                                    </div>
                                                </div>
    
                                            <?php

                                        }

                                    } else {

                                        ?>

                                            <div class="no-comments">لاتوجد تعليقات !</div>

                                        <?php

                                    }

                                ?>

                           <?php
    
                                if($sessionId) {
                                
                                    ?>

                                        <div class="add-comment">
                                            <div id="faild-comment"></div>
                                            <textarea placeholder="أضف تعليق.."></textarea>
                                            <input
                                                    type        =   "submit"
                                                    value       =   "أرسل"
                                            />
                                            <input
                                                    type        =   "hidden"
                                                    value       =   "<?=$row1["article_id"]?>"
                                            />
                                            <input
                                                    type        =   "hidden"
                                                    value       =   "<?=$row1["user_id"]?>" 
                                            />
                                            <input
                                                    type        =   "hidden"
                                                    value       =   "<?=$row1["user_img"]?>" 
                                            />
                                        </div>

                                    <?php

                                }

                           ?>

                        </div>
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

?>

<script>
    // Add comment Ajax
    let addBeforeAllComments = document.querySelector(".comments h3"),
        comment              = document.querySelector(".add-comment textarea"),
        submit               = document.querySelector(".add-comment input:first-of-type"),
        articleId            = document.querySelector(".add-comment input:nth-of-type(2)"),
        faildComment         = document.getElementById("faild-comment"),
        noComments           = document.querySelector(".no-comments");

    // Create a request
    let request = new XMLHttpRequest();

    // Post comment to database
    function addComment() {

        request.onreadystatechange = function () {

            if(this.readyState === 4 && this.status === 200) {

                // Means there's a comment
                if(comment.value != "") {

                    if(noComments) {

                        noComments.style.display = "none";

                    }
                    
                    faildComment.style.display = "none";

                    let showComment = document.createElement("div");

                    showComment.className = "show-comments";

                    showComment.innerHTML = this.responseText;

                    addBeforeAllComments.after(showComment);

                    comment.value = "";

                } else {

                    faildComment.style.display = "block";

                    faildComment.textContent = this.responseText;

                }

            }

        }

        // Make a request
        request.open("POST", "ajax/comment-system.php", true);

        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Send the request
        request.send(`comment=${comment.value}&article_id=${articleId.value}`);

    }

    // Post, get comment on click
    submit && submit.addEventListener("click", () => { addComment(); });
    
    // Add like Ajax
    let numberOflikes  = document.querySelector(".article-likes h4 span"),
        add_like       = document.querySelector(".article-likes .add-like"),
        added_like     = document.querySelector(".article-likes h4");

     // Post comment to database
    function addLike() {

        request.onreadystatechange = function () {

            if(this.readyState === 4 && this.status === 200) {

                if(this.responseText == 1) {
                    
                    numberOflikes.textContent = parseInt(numberOflikes.textContent) + parseInt(this.responseText);

                    add_like.style.display = "none";

                    let addedLike = document.createElement("div");

                    addedLike.className = "added-like";

                    addedLike.textContent = "تم الإعجاب";

                    added_like.after(addedLike);

                }

            }

        }

        // Make a request
        request.open("POST", "ajax/like-system.php", true);

        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Send the request
        request.send(`article_id=${articleId.value}`);

    }

    add_like && add_like.addEventListener("click", () => { addLike(); });
</script>

<?php include $template . "footer.php"; ?>