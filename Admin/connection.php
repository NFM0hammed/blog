<?php

    // Information of database
    $dsn  = "mysql:host=localhost; dbname=blog";

    $user = "root";

    $pass = "";

    $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    );

    try {

        // Create a connection
        $conn = new PDO($dsn, $user, $pass, $options);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch(PDOException $e) {

        // Failed the connection
        echo "Faild the connection !" . $e->getMessage();

    }