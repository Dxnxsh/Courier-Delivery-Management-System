<?php require_once('Connections/dbConDelivery.php'); ?>
<?php
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "registerForm")) {
  $insertSQL = sprintf(
    "INSERT INTO staff (staffNM, username, password, staffPN, `role`) VALUES (%s, %s, %s, %s, %s)",
    GetSQLValueString($_POST['name'], "text"),
    GetSQLValueString($_POST['username'], "text"),
    GetSQLValueString($_POST['password'], "text"),
    GetSQLValueString($_POST['phone'], "text"),
    GetSQLValueString($_POST['role'], "text")
  );

  mysql_select_db($database_dbConDelivery, $dbConDelivery);
  $Result1 = mysql_query($insertSQL, $dbConDelivery) or die(mysql_error());

  $insertGoTo = "signup.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en" style="overflow: hidden" ;>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>CMS | Register</title>
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Archivo+Black&amp;display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat&amp;display=swap">
  <link rel="stylesheet" href="assets/css/multistep-admin.css">
  <link rel="stylesheet" href="assets/css/-Fixed-Navbar-start-with-transparency-background-BS4--transparency-menu.css">
  <link rel="stylesheet" href="assets/css/Search-Input-Responsive-with-Icon.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/admin.css">
  <link rel="stylesheet" href="https://unpkg.com/transition-style">

</head>

<body class="justify-content-center" style="height: 100vh;">
  <?php require_once 'navbar.php'; ?>
  <div class="main-content">
    <form action="<?php echo $editFormAction; ?>" method="POST" name="requestForm" id="requestForm"
      style="margin: auto;min-width: 500px;margin-top: 25vh;">
      <h1 class="fs-4 text-center" style="margin-bottom: 17px;font-weight: bold;" transition-style="in:wipe:down">Register Staff</h1>
      <div class="d-flex form-header mb-4" transition-style="in:wipe:down"> <span class="stepIndicator finish active">Staff
          Details</span><span class="stepIndicator">Login Details</span><span class="stepIndicator">Role</span> </div>
      <div class="step" style="display: block;" transition-style="in:wipe:down">
        <p class="text-center mb-4">Enter staff details</p>
        <div class="mb-3">
          <input class="form-control" type="text" name="name" id="name" placeholder="Name">
        </div>
        <div class=" mb-3">
          <input class="form-control" type="tel" name="phone" id="phone" placeholder="Phone Number">
        </div>
      </div>
      <div class="step" style="display: none;">
        <p class="text-center mb-4">Enter username and password</p>
        <div class="mb-3">
          <input type="text" name="username" placeholder="Username">
        </div>
        <div class="mb-3">
          <input type="text" name="password" placeholder="Password">
        </div>
      </div>
      <div class="step" style="display: none;">
        <p class="text-center mb-4">Enter staff role</p>
        <div class="mb-3">
          <select class="form-select" name="role" style="height: 56px;padding-left: 20px;">
            <option value="admin" selected="">Admin</option>
            <option value="rider">Rider</option>
          </select>
        </div>
      </div>
      <div class="d-flex form-footer" transition-style="in:wipe:down">
        <button type="button" id="prevBtn" onclick="nextPrev(-1)" style="display: none;">Previous</button>
        <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
      </div>
      <div class="d-flex form-footer" transition-style="in:wipe:down">
        <button type="button" onclick="window.location.href = 'requestlist.php';" id="homeBtn"
          style="background: var(--bs-dark-text-emphasis);">Back to Homepage </button>
      </div>
      <input type="hidden" name="MM_insert" value="registerForm">
    </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/js/multistep.js"></script>
  <script src="assets/js/-Fixed-Navbar-start-with-transparency-background-BS4--transparency-menu.js"></script>
</body>

</html>