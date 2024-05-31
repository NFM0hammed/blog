<?php

    session_start();
    
    $title = "مدونة التقني";

    include "init.php";

    include $template . "navbar.php";

?>

<!-- Start banner section -->
<div class="banner">
    <div class="info">
        <h1>أهلًا وسهلًا بكم في مدونة التقني</h1>
        <p>
            مدونة التقني هي مدونة تقوم بتغطية كل ماهو جديد في عالم التقنية
            من مقالات وأخبار عن 
            <br>
            <span>البرمجة, الذكاء الصناعي, الشبكات والعديد من المجالات.</span>
        </p>
    </div>
</div>

<!-- Start last articles section -->
<div class="last-articles-section">
    <div class="container">
        <h1 class="title">آخر المقالات</h1>
        <div class="articles">
            <?php

                $rows = selectData(
                    "*",
                    "articles",
                    "INNER JOIN users ON articles.user_id = users.user_id",
                    "",
                    "ORDER BY article_id DESC",
                    "LIMIT 4"
                );

                $count = count($rows);
                
                if($count > 0) {

                    foreach($rows as $row) {

                        ?>

                            <div class="article">
                                <img
                                        src     =   "Admin/uploads/<?=$row["article_img"]?>"
                                        alt     =   ""
                                >
                                <h3>
                                    <a href="articles.php?action=article&id=<?=$row["article_id"]?>"><?=$row["article_title"]?></a>
                                </h3>
                                <span>الكاتب: <?=$row["username"]?></span>
                                <span>تاريخ النشر: <?=$row["date_publication"]?></span>
                            </div>

                        <?php

                    }

                }

            ?>
        </div>
    </div>
</div>

<!-- Start most articles liked section -->
<div class="articles-liked-section">
    <div class="container">
        <h1 class="title">أكثر المقالات إعجابًا</h1>
        <div class="articles-liked">

            <?php

                $stmt = $conn->prepare(
                    "SELECT
                        likes.article_id, article_title, article_img, COUNT(likes.article_id) MOST_LIKES
                    FROM
                        likes
                    INNER JOIN
                        articles ON likes.article_id = articles.article_id
                    GROUP BY
                        likes.article_id
                    ORDER BY
                        MOST_LIKES
                    DESC
                    LIMIT
                        3"
                );
                                
                $stmt->execute();

                $mostLikes = $stmt->fetchAll();

                foreach($mostLikes as $mostLike) {

                    ?>

                        <div class="article-liked">
                            <img
                                    src     =   "Admin/uploads/<?=$mostLike["article_img"]?>"
                                    alt     =   ""
                            >
                            <h3>
                                <a href="articles.php?action=article&id=<?=$mostLike["article_id"]?>"><?=$mostLike["article_title"]?></a>
                            </h3>
                            <div class="likes">
                                <i class="fa-solid fa-thumbs-up"></i>
                                <span><?=$mostLike["MOST_LIKES"]?></span>
                            </div>
                        </div>

                    <?php

                }

            ?>

        </div>
    </div>
</div>

<!-- Start most articles views section -->
<div class="articles-views-section">
    <div class="container">
        <h1 class="title">أكثر المقالات مشاهدة</h1>
        <div class="articles-views">

            <?php
            
                $stmt = $conn->prepare(
                    "SELECT
                        *, COUNT(number_views)
                    FROM
                        views
                    INNER JOIN
                        articles ON views.article_id = articles.article_id
                    GROUP BY
                        number_views
                    DESC
                    LIMIT
                        3"
                );

                $stmt->execute();

                $mostViews = $stmt->fetchAll();

                foreach($mostViews as $mostView) {

                    ?>

                        <div class="article-view">
                            <img
                                    src     =   "Admin/uploads/<?=$mostView["article_img"]?>"
                                    alt     =   ""
                            >
                            <h3>
                                <a href="articles.php?action=article&id=<?=$mostView["article_id"]?>"><?=$mostView["article_title"]?></a>
                            </h3>
                            <div class="number-views">
                                <span>عدد المشاهدات</span>
                                <span><?=$mostView["number_views"]?></span>
                            </div>
                        </div>

                    <?php
                }

            ?>

        </div>
    </div>
</div>

<?php include $template . "footer.php"; ?>