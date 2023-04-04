<?php

    session_start();
        
    $title = "الشبكات";

    include "init.php";

    include $template . "navbar.php";

    include $components . "container_categories.php";

    contentOfCategories(4, $title);

    include $template . "footer.php";