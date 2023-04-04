<?php

    session_start();
        
    $title = "تقنية المعلومات";

    include "init.php";

    include $template . "navbar.php";

    include $components . "container_categories.php";

    contentOfCategories(1, $title);
    
    include $template . "footer.php";