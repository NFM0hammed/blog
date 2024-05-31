<?php

    // Show content of all categories
    function contentOfCategories($numOfCatg, $title) {

        ?>

            <div class="container no-padding">
                <div class="categories">
                    <h1 class="title">مقالات عن <?=$title?></h1>
                    <h3
                            class   =   "description"
                            id      =   "border"
                    >
                        هنا مقالات وأخبار عن كل ماهو جديد في مجال <?=$title?>
                    </h3>
                    
                    <?php

                        $c = 1;

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
                                        <h3>
                                            <a href="articles.php?action=article&id=<?=$row["article_id"]?>"><?=$row["article_title"]?></a>
                                        </h3>
                                        <img
                                                src     =   "Admin\uploads\<?=$row["article_img"]?>"
                                                alt     =   ""
                                                class   =   "img-article"
                                        >
                                        <p><?=substr($row["article_content"], 0, 100)?><a href="articles.php?action=article&id=<?=$row["article_id"]?>">...المزيد</a></p>
                                        <div
                                                class   =   "info"
                                                id      =   "border"
                                        >
                                            <span>الكاتب / <?=$row["username"]?></span>
                                            <span>تاريخ النشر / <?=$row["date_publication"]?></span>
                                        </div>
                                        <hr>
                                    </div>

                                <?php

                            }

                        }

                    ?>

                    <div class="next-prev">
                        <button class="next">التالي</button>
                        <div class="number-articles">
                            <span>الصفحة <span id="current-page"></span> من <span id="all-pages"></span></span>
                        </div>
                        <button class="prev">السابق</button>
                    </div>

                </div>
            </div>

        <?php

    }

?>

<script>
    window.onload = function() {

        // Get all articles
        let allArticles = document.querySelectorAll(".categories .category");
        // Get the current page number
        let currentPage = document.querySelector(".next-prev .number-articles span #current-page");
        // Get the all pages number
        let allPages = document.querySelector(".next-prev .number-articles span #all-pages");
        // Get next page button
        let nextPage = document.querySelector(".next-prev .next");
        // Get previous page button
        let prevPage = document.querySelector(".next-prev .prev");
        // Count page number
        let countPageNumber = 0;
        // Number of all pages after we show just 4 articles each page
        let allPagesLength = 0;
        // The length of all articles
        let articlesLength = allArticles.length;
        // Count to show only 4 articles in one page
        let count;

        allPagesLength = (articlesLength % 4 == 0) ? (articlesLength / 4) - 1 : Math.floor(articlesLength / 4);

        // Default number to the current page
        currentPage.textContent = countPageNumber;

        // Number of all pages
        allPages.textContent = allPagesLength;

        // Function to handle articles to show just 4 articles in each page on previous | next page
        function handleArticles(display, countPageNumber) {

            count = 0;

            for(let i = (countPageNumber * 4); i < articlesLength; ++i) {

                allArticles[i].style.display = display;

                if(++count === 4) {

                    break;

                }

            }

        }

        handleArticles("block", countPageNumber);

        nextPage.onclick = function() {

            if(allPagesLength === countPageNumber) return;

            handleArticles("none", countPageNumber);

            countPageNumber++;

            currentPage.textContent = countPageNumber;

            handleArticles("block", countPageNumber);

            scrollToTop();

        }

        prevPage.onclick = function() {

            if(countPageNumber === 0) return;

            handleArticles("none", countPageNumber);

            countPageNumber--;

            currentPage.textContent = countPageNumber;

            handleArticles("block", countPageNumber);

            scrollToTop();

        }

        function scrollToTop() {

            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });

        }

    }
</script>