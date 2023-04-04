<?php

    // Function to set title of pages
    function title() {

        global $title;
        
        if(isset($title)) {

            return $title;

        }
        
    }

    // Function to check data from database
    function checkData($table, $id, $getId, $checkPermissions = null) {

        global $conn;

        $stmt = $conn->prepare(
            "SELECT
                $id
            FROM
                $table
            WHERE
                $id = ? $checkPermissions"
        );

        $stmt->execute(array(
            $getId
        ));

        $count = $stmt->rowCount();

        return $count;

    }

    // Function to select data from database
    function selectData($number, $table, $innerJoin = null, $where = null, $checkPermissions = null, $order = null, $limit = null) {

        global $conn;

        $stmt = $conn->prepare(
            "SELECT
                $number
            FROM
                $table $innerJoin $where $checkPermissions $order $limit"
        );

        $stmt->execute();

        $rows = $stmt->fetchAll();

        return $rows;

    }

    // Function to delete data from table on database
    function deleteData($table, $id, $getId) {

        global $conn;

        $stmt = $conn->prepare(
            "DELETE FROM
                $table
            WHERE
                $id = ?"
        );

        $stmt->execute(array(
            $getId
        ));

    }
    
    // Function to select a specific data from database [based on session ID]
    function selectSpecificData($number, $table, $where, $getId) {

        global $conn;

        $stmt = $conn->prepare(
            "SELECT
                $number
            FROM
                $table $where"
        );

        $stmt->execute(array(
            $getId
        ));

        $row = $stmt->fetch();

        return $row;

    }

    // Function to get number of all data
    function getNumberAllData($id, $table, $where = null, $groupId = null) {

        global $conn;

        $stmt = $conn->prepare(
            "SELECT
                $id
            FROM
                $table $where $groupId"
        );

        $stmt->execute();

        $count = $stmt->rowCount();

        return $count;

    }

    // Function to remove or add admin to members
    function dealAdmin($getId, $getRole) {

        global $conn;

        $stmt = $conn->prepare(
            "UPDATE
                users
            SET
                group_id = $getRole
            WHERE
                user_id  = ?"
        );
        
        $stmt->execute(array(
            $getId
        ));

    }

    // Function to redirect page
    function redirectPage() {

        echo "<div class='container'>";
            echo "<p class='msg'>Redirect to homepage after 3 seconds...</p>";
        echo "</div>";
    
        header("Refresh: 3; index.php");

    }

    // Function to show message
    function show_msg($msg, $class) {

        echo "<div class='container'>";
            echo "<div class='$class'>$msg</div>";
        echo "</div>";

    }