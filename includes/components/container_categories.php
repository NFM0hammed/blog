<?php

    // Show content of all categories
    function contentOfCategories($numOfCatg, $title) {

        ?>

            <div class="container no-padding">
                <div class="categories">
                    <h1 class="title">مقالات عن <?=$title?></h1>
                    <h3 class="description">
                        .هنا مقالات وأخبار عن كل ماهو جديد في مجال <?=$title?>
                    </h3>
                    
                    <?php

                        $rows = selectData(
                            "*",
                            "articles",
                            "INNER JOIN users ON articles.user_id = users.user_id",
                            "WHERE category_id = $numOfCatg",
                            "ORDER BY article_id DESC"
                        );

                        $count = count($rows);

                        if($count > 0) {

                            foreach($rows as $row) {

                                ?>
                                
                                    <div class="category">
                                        <h3><a href="articles.php?action=article&id=<?=$row["article_id"]?>"><?=$row["article_title"]?></a></h3>
                                        <img src="Admin\uploads\<?=$row["article_img"]?>" alt="">
                                        <p><?=substr($row["article_content"], 0, 100)?><a href="articles.php?action=article&id=<?=$row["article_id"]?>">...المزيد</a></p>
                                        <span>الكاتب: <?=$row["username"]?></span>
                                        <span>تاريخ النشر: <?=$row["date_publication"]?></span>
                                        <hr>
                                    </div>

                                <?php

                            }

                        }

                    ?>

                </div>
            </div>

        <?php

    }