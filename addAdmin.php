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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "requestForm")) {
    $insertSQL = sprintf(
        "INSERT INTO request (trackNO, recevNM, recevRN, recevCol, reqPN) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($_POST['tracking'], "text"),
        GetSQLValueString($_POST['name'], "text"),
        GetSQLValueString($_POST['roomnum'], "int"),
        GetSQLValueString($_POST['college'], "text"),
        GetSQLValueString($_POST['phone'], "text")
    );

    mysql_select_db($database_dbConDelivery, $dbConDelivery);
    $Result1 = mysql_query($insertSQL, $dbConDelivery) or die(mysql_error());

    $insertGoTo = "requestlist.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en" style="overflow: hidden">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>CMS | Add Request</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Archivo+Black&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat&amp;display=swap">
    <link rel="stylesheet" href="assets/css/multistep-admin.css">
    <link rel="stylesheet" href="assets/css/Search-Input-Responsive-with-Icon.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/transitions.css">
    <link rel="stylesheet" href="assets/css/admin.css">

</head>

<body class="justify-content-center" style="height: 100vh;">
    <?php require_once 'navbar.php'; ?>

    <div class="main-content">
        <form method="POST" name="requestForm" id="requestForm" action="<?php echo $editFormAction; ?>"
            style="margin: auto;min-width: 500px;margin-top: 10vh;">
            <h1 class="fs-4 text-center" style="margin-bottom: 17px;font-weight: bold;">Delivery Request</h1>
            <div class="d-flex form-header mb-4"><span class="stepIndicator finish active">Customer Details</span><span
                    class="stepIndicator">Location</span><span class="stepIndicator">Tracking Number</span></div>
            <div class="step" style="display: block;">
                <p class="text-center mb-4">Enter your details</p>
                <div class="mb-3"><input class="form-control" type="text" name="name" placeholder="Name"></div>
                <div class="mb-3"><input class="form-control" type="tel" name="phone" placeholder="Phone Number"></div>
                <div class="mb-3"></div>
            </div>
            <div class="step" style="display: none;">
                <p class="text-center mb-4">Enter room number and college</p>
                <div class="mb-3"><input type="text" name="roomnum" placeholder="Room Number"></div>
                <div class="mb-3"><select class="form-select" name="college" style="height: 56px;padding-left: 20px;">
                        <option value="Alpha" selected="">Alpha</option>
                        <option value="Beta">Beta</option>
                        <option value="Gamma">Gamma</option>
                    </select></div>
            </div>
            <div class="step" style="display: none;">
                <p class="text-center mb-4">Enter parcel tracking number</p>
                <div class="mb-3"><input type="text" name="tracking" placeholder="Tracking Number"></div>
                <div class="mb-3"></div>
                <div class="mb-3"></div>
            </div>
            <div class="d-flex form-footer">
                <button type="button" id="prevBtn" onclick="nextPrev(-1)" style="display: none;">Previous</button>
                <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
            </div>
            <div class="d-flex form-footer">
                <button type="button" onclick="window.location.href = 'requestlist.php';" id="homeBtn"
                    style="background: var(--bs-dark-text-emphasis);">Back to Homepage
                </button>
            </div>
            <input type="hidden" name="MM_insert" value="requestForm">
        </form>
    </div>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/multistep.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>