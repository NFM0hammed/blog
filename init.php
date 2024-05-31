<?php

    // Include the connection file
    include "Admin/connection.php";

    // Routes
    $template   = "includes/templates/"; // Template dir
    $function   = "includes/functions/"; // Function dir
    $components = "includes/components/"; // Components dir
    $css        = "layout/css/"; // css dir
    $js         = "layout/js/"; // js dir

    // Include files from includes folder
    include $function . "function.php";
    include $template . "header.php";