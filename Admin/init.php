<?php

    // Include the connection file
    include "connection.php";

    // Routes
    $template = "includes/templates/"; // template dir
    $function = "includes/functions/"; // function dir
    $css      = "layout/css/"; // css dir
    $js       = "layout/js/"; // js dir

    // Include files from includes folder
    include $function . "function.php";
    include $template . "header.php";