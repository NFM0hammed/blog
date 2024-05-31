<?php

    // Function to set title of pages
    function title() {

        global $title;
        
        if(isset($title)) {

            return $title;

        }
        
    }

    // Function to check user data [users | admins] from database
    function checkUserData($table, $groupId, $getId) {

        global $conn;

        $stmt = $conn->prepare(
            "SELECT
                user_id
            FROM
                $table
            WHERE
                user_id = ? AND group_id = $groupId"
        );

        $stmt->execute(array(
            $getId
        ));

        $count = $stmt->rowCount();

        return $count;

    }

    // Function to check data from database
    function checkData($table, $id, $getId) {

        global $conn;

        $stmt = $conn->prepare(
            "SELECT
                $id
            FROM
                $table
            WHERE
                $id = ?"
        );

        $stmt->execute(array(
            $getId
        ));

        $count = $stmt->rowCount();

        return $count;

    }

    // Function to select data from database
    function selectData($number, $table, $innerJoin = null, $where = null, $order = null, $limit = null) {

        global $conn;

        $stmt = $conn->prepare(
            "SELECT
                $number
            FROM
                $table $innerJoin $where $order $limit"
        );
        
        $stmt->execute();

        $rows = $stmt->fetchAll();

        return $rows;
        
    }

    // Function to select specific data
    function selectSpecificData($number, $table, $innerJoin = null, $where, $getId) {

        global $conn;

        $stmt = $conn->prepare(
            "SELECT
                $number
            FROM
                $table $innerJoin $where"
        );

        $stmt->execute(array(
            $getId
        ));

        $row = $stmt->fetch();

        return $row;
        
    }

    // Function to select all data based on id
    function selectDataBasedId($number, $table, $innerJoin = null, $where, $order, $getId) {

        global $conn;

        $stmt = $conn->prepare(
            "SELECT
                $number
            FROM
                $table $innerJoin $where $order"
        );

        $stmt->execute(array(
            $getId
        ));

        $rows = $stmt->fetchAll();

        return $rows;
        
    }