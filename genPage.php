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

mysql_select_db($database_dbConDelivery, $dbConDelivery);
$query_freeReq = "SELECT * FROM request WHERE sesID IS NULL";
$freeReq = mysql_query($query_freeReq, $dbConDelivery) or die(mysql_error());
$row_freeReq = mysql_fetch_assoc($freeReq);
$totalRows_freeReq = mysql_num_rows($freeReq);
session_start() ?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en" style="overflow: hidden" ;>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>CMS | Generate Session</title>

  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Archivo+Black&amp;display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat&amp;display=swap">
  <link rel="stylesheet" href="assets/css/multistep.css">
  <link rel="stylesheet" href="assets/css/-Fixed-Navbar-start-with-transparency-background-BS4--transparency-menu.css">
  <link rel="stylesheet" href="assets/css/Search-Input-Responsive-with-Icon.css">
  <link rel="stylesheet" href="https://unpkg.com/transition-style">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/admin.css">

</head>

<body style="margin-top: 0px;">
  <?php require_once 'navbar.php'; ?>
  <?php
  mysql_free_result($freeReq);
  ?>

  <div class="main-content">
    <div class="container" style="margin: auto;text-align: center;padding-left: 0px;width: 100vw;margin-top: 40vh;">
      <h1 style="color: black; text-align: center;" transition-style="in:wipe:down">Generate Session</h1>
      <p transition-style="in:wipe:down">There is currently
        <?php echo $totalRows_freeReq ?> delivery request without session assigned.
      </p>
      <div style="margin-top: 8px;">
        <button class="btn btn-success" type="button" onclick="window.location.href = 'generateSession.php';"
          style="margin-right: 8px;border-width: 0px;" transition-style="in:wipe:down">Generate
        </button>
      </div>
    </div>
  </div>

</body>

</html>