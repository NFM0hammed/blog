<?php

    session_start();
        
    $title = "البرمجة";

    include "init.php";

    include $template . "navbar.php";

    include $components . "container_categories.php";

    contentOfCategories(2, $title);

    include $template . "footer.php";