<?php

    session_start();
        
    $title = "الأمن السيبراني";

    include "init.php";

    include $template . "navbar.php";

    include $components . "container_categories.php";

    contentOfCategories(3, $title);

    include $template . "footer.php";