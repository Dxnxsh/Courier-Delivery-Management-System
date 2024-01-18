<?php require_once('Connections/dbConDelivery.php'); ?>
<?php
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

if (isset($_GET['reqID']) && !empty($_GET['reqID'])) {
  $deleteID = $_GET['reqID'];

  // Validate and sanitize $deleteID
  $deleteID = intval($deleteID);

  $deleteSQL = sprintf("DELETE FROM request WHERE reqID=%s", GetSQLValueString($deleteID, "int"));

  mysql_select_db($database_dbConDelivery, $dbConDelivery);

  $Result1 = mysql_query($deleteSQL, $dbConDelivery);
  if (!$Result1) {
    die('Error in SQL query: ' . mysql_error());
  }

  header("Location: requestlist.php");

  exit();
}
?>