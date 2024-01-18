<?php
require_once('Connections/dbConDelivery.php');
session_start();

if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        if (PHP_VERSION < 6) {
            $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        }

        $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }
}

if (isset($_GET['sesID']) && !empty($_GET['sesID'])) {
    $sesID = $_GET['sesID'];

    // Validate and sanitize $sesID
    $sesID = intval($sesID);

    // Check if there's a match with user_id, staffID, and isComplete
    $checkSQL = sprintf(
        "SELECT * FROM session WHERE staffID=%s AND isComplete=0",
        GetSQLValueString($_SESSION['user_id'], "int")
    );

    mysql_select_db($database_dbConDelivery, $dbConDelivery);

    $checkResult = mysql_query($checkSQL, $dbConDelivery);

    if (!$checkResult) {
        die('Error in SQL query: ' . mysql_error());
    }

    // Check if a row is returned, meaning there's a match
    if (mysql_num_rows($checkResult) == 0) {
        // No match, proceed with the update
        $sesSQL = sprintf(
            "UPDATE session SET staffID=%s WHERE sesID=%s",
            GetSQLValueString($_SESSION['user_id'], "int"),
            GetSQLValueString($sesID, "int")
        );

        $updateResult = mysql_query($sesSQL, $dbConDelivery);

        if (!$updateResult) {
            die('Error in SQL query: ' . mysql_error());
        }

        // Redirect to the previous page with a success parameter
        header("Location: selSes.php");
        exit();
    } else {
        // There's a match, redirect to the previous page with an error parameter
        header("Location: selSes.php?updateSuccess=false");
        exit();
    }
}
?>