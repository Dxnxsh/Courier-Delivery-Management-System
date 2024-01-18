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

$colname_SearchResult = "-1";
if (isset($_POST['track'])) {
    $colname_SearchResult = $_POST['track'];
}
mysql_select_db($database_dbConDelivery, $dbConDelivery);
$query_SearchResult = sprintf("SELECT * FROM request WHERE trackNO = %s", GetSQLValueString($colname_SearchResult, "text"));
$SearchResult = mysql_query($query_SearchResult, $dbConDelivery) or die(mysql_error());
$row_SearchResult = mysql_fetch_assoc($SearchResult);
$totalRows_SearchResult = mysql_num_rows($SearchResult);

$staffName = "Not yet in delivery";
if (!empty($row_SearchResult['sesID'])) {
    $sqlFetchStaff = "SELECT s.staffID, s.staffNM FROM staff s
                     INNER JOIN session se ON s.staffID = se.staffID
                     WHERE se.sesID = {$row_SearchResult['sesID']}";
    $resultStaff = mysql_query($sqlFetchStaff, $dbConDelivery) or die(mysql_error());
    $rowStaff = mysql_fetch_assoc($resultStaff);
    $staffName = !empty($rowStaff['staffNM']) ? $rowStaff['staffNM'] : "Not yet in delivery";
    mysql_free_result($resultStaff);
}

$currentDeliveryQueue = "Not yet in delivery";
$sesID = "No Session generated";
if ($row_SearchResult['sesID'] !== null) {
    $sesID = $row_SearchResult['sesID'];

    $sqlFetchRequests = "SELECT reqID, isDelivered FROM request WHERE sesID = $sesID";
    $resultRequests = mysql_query($sqlFetchRequests, $dbConDelivery) or die(mysql_error());


    if ($sesID !== null) {
        $sqlUndeliveredRequests = "SELECT COUNT(*) as undeliveredCount FROM request WHERE sesID = '{$row_SearchResult['sesID']}' AND isDelivered = false AND reqID < '{$row_SearchResult['reqID']}'";
        $resultUndeliveredRequests = mysql_query($sqlUndeliveredRequests, $dbConDelivery) or die(mysql_error());
        if ($rowUndeliveredRequest = mysql_fetch_assoc($resultUndeliveredRequests)) {
            $currentDeliveryQueue = $rowUndeliveredRequest['undeliveredCount'] + 1;
        }
        mysql_free_result($resultUndeliveredRequests);
    }
}

?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>CMS | Status</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Archivo+Black&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat&amp;display=swap">
    <link rel="stylesheet" href="assets/css/multistep.css">
    <link rel="stylesheet"
        href="assets/css/-Fixed-Navbar-start-with-transparency-background-BS4--transparency-menu.css">
    <link rel="stylesheet" href="assets/css/Search-Input-Responsive-with-Icon.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body style="background: url(&quot;assets/img/Group%207.png&quot;);">
    <nav class="navbar navbar-expand-lg fixed-top bg-white transparency border-bottom border-light navbar-light"
        id="transmenu" style="position: relative;">
        <div class="container"><a class="navbar-brand text-success" href="#header"><img
                    src="assets/img/Group%201.png"></a>
            <button data-bs-toggle="collapse" class="navbar-toggler collapsed"
                data-bs-target="#navcol-1"><span></span><span></span><span></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"></li>
                    <li class="nav-item"></li>
                    <li class="nav-item"><a class="btn btn-primary" role="button"
                            style="background: rgba(255,255,255,0);border-color: rgb(255,255,255);"
                            href="login.php">Admin Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <section style="border-width: 1px;margin: 6px;margin-right: 0px;margin-left: 0px;">
        <div class="container" style="padding-top: 5px;padding-bottom: 5px;width: auto;"><a href="index.php"
                style="font-family: 'Archivo Black', sans-serif;color: var(--bs-dark-text-emphasis);font-size: 14px;padding: 11px;padding-top: 11px;margin-top: 0px;background: var(--bs-gray-400);border-radius: 10px;border-width: 1px;">&lt;
                Back to homepage</a></div>
    </section>
    <section>
        <div class="container" style="padding: 20px;">
            <div class="row"
                style="background: #ced4da;padding: 26px;border-radius: 10px;border-width: 1px;height: 100%;">
                <div class="col-8">
                    <div></div>
                    <h2 style="margin: 0px;"><strong>#<span>
                                <?php echo $row_SearchResult['trackNO']; ?>
                            </span></strong></h2>
                    <div style="margin-top: 8px;margin-bottom: 8px;">
                        <p id="devBY" style="margin-bottom: 0px;font-size: 23px;">Delivered By: <span>
                                <?php echo $staffName; ?>
                            </span></p>
                        <p style="margin-bottom: 0px;font-size: 23px;">Session Number: <span>
                                <?php echo $sesID; ?>
                            </span></p>
                        <p style="margin-bottom: 0px;font-size: 23px;">Current Delivery Queue: <span>
                                <?php echo $currentDeliveryQueue; ?>
                            </span></p>
                    </div>
                    <div style="margin-top: 23px;margin-bottom: 8px;">
                        <p style="margin-bottom: 0px;font-size: 23px;">Receiver Name: <span>
                                <?php echo $row_SearchResult['recevNM']; ?>
                            </span></p>
                        <p style="margin-bottom: 0px;font-size: 23px;">Receiver Address: <span>
                                <?php echo $row_SearchResult['recevRN']; ?>
                            </span>, Kolej <span id="colName">
                                <?php echo $row_SearchResult['recevCol']; ?>
                            </span></p>
                    </div>
                    <hr>
                    <div style="text-align: center;">
                        <p style="margin: 0px;font-size: 33px;font-weight: bold;">Estimated Delivery Time</p>
                        <p id=estTime style="margin: 0px;font-size: 54px;"></p>
                    </div>
                </div>
                <div class="col" style="padding: 0px;">
                    <div>
                        <iframe id="map" allowfullscreen="" frameborder="0"
                            src="https://www.google.com/maps?q=<?php echo $row_SearchResult['recevCol'] . ' Uitm Tapah'; ?>&output=embed"
                            width="100%" height="400" style="border-radius: 10px;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/multistep.js"></script>
    <script src="assets/js/-Fixed-Navbar-start-with-transparency-background-BS4--transparency-menu.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            if (<?php echo $row_SearchResult['isDelivered']; ?> ===
                0
            ) {
                var sesID = <?php echo $sesID; ?>;
                var reqID = <?php echo $row_SearchResult['reqID']; ?>;

                $.ajax({
                    type: 'POST',
                    url: 'estTimeReqID.php',
                    data: { sesID: sesID, reqID: reqID },
                    success: function (response) {
                        var staffID = <?php echo json_encode($rowStaff['staffID']); ?>;
                        $('#estTime').text(staffID === null ? "N/A" : response);
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            }
            else {
                $('#estTime').text("Delivered");
            }
        });
    </script>


</body>

</html>
<?php
mysql_free_result($SearchResult);
?>