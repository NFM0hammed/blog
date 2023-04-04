<?php

    // Start the session
    session_start();
    
    // Clear data
    unset($_SESSION["username"]);

    // Redirect to homepage
    header("Location: index.php");

    exit();