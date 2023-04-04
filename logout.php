<?php

    // Start the session
    session_start();
    
    // Clear data
    unset($_SESSION["user"]);
    unset($_SESSION["id"]);

    // Redirect to homepage
    header("Location: index.php");

    exit();