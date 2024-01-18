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

if ((isset($_POST['reqID'])) && ($_POST['reqID'] != "")) {
    $deleteSQL = sprintf(
        "DELETE FROM request WHERE reqID=%s",
        GetSQLValueString($_POST['reqID'], "int")
    );

    mysql_select_db($database_dbConDelivery, $dbConDelivery);
    $Result1 = mysql_query($deleteSQL, $dbConDelivery) or die(mysql_error());

    $deleteGoTo = "genPage.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
        $deleteGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $deleteGoTo));
}
?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en" style="overflow: hidden" ;>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Landing</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Archivo+Black&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat&amp;display=swap">
    <link rel="stylesheet" href="assets/css/multistep.css">
    <link rel="stylesheet"
        href="assets/css/-Fixed-Navbar-start-with-transparency-background-BS4--transparency-menu.css">
    <link rel="stylesheet" href="assets/css/Search-Input-Responsive-with-Icon.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/admin.css">

</head>

<body class="justify-content-center" style="height: 100vh; background: url(&quot;assets/img/05.jpg&quot;); background-size: 1136px 936px;
  background-repeat: repeat;">
    <?php require_once 'navbar.php'; ?>
    <div class="main-content">
        <form method="POST" name="requestForm" id="requestForm" style="margin: auto;min-width: 500px;margin-top: 25vh;">
            <h1 class="fs-4 text-center" style="margin-bottom: 17px;font-weight: bold;">Delete Request</h1>
            <div class="d-flex form-header mb-4">
                <span class="stepIndicator finish active">Request ID</span><span class="stepIndicator">Confirm
                    Details</span>
            </div>
            <div class="step" style="display: block;">
                <p class="text-center mb-4">Enter request ID</p>
                <div class="mb-3"><input class="form-control" type="text" name="reqID" id="reqID" placeholder="reqID">
                </div>
            </div>
            <div class="step" style="display: none;">
                <p class="text-center mb-4">Confirm details</p>
                <div class="mb-3"><input class="form-control" type="text" name="name" placeholder="Name" disabled></div>
                <div class="mb-3"><input class="form-control" type="tel" name="phone" placeholder="Phone Number"
                        disabled></div>
                <div class="mb-3"><input class="form-control" type="text" name="roomnum" placeholder="Room Number"
                        disabled>
                </div>
                <div class="mb-3"><select class="form-select" name="college" style="height: 56px;padding-left: 20px;"
                        disabled>
                        <option value="Alpha" selected="">Alpha</option>
                        <option value="Beta">Beta</option>
                        <option value="Gamma">Gamma</option>
                    </select></div>
                <div class="mb-3"><input class="form-control" type="text" name="tracking" placeholder="Tracking Number"
                        disabled>
                </div>
            </div>
            <div class="d-flex form-footer">
                <button type="button" id="prevBtn" onclick="nextPrev(-1)" style="display: none;">Previous
                </button>
                <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
            </div>
            <div class="d-flex form-footer">
                <button type="button" onclick="window.location.href = 'adminpage.php';" id="homeBtn"
                    style="background: var(--bs-dark-text-emphasis);">Back to Homepage
                </button>
            </div>
        </form>
    </div>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/multistep.js"></script>
    <script src="assets/js/-Fixed-Navbar-start-with-transparency-background-BS4--transparency-menu.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            // Event listener for reqID input field
            $('#reqID').on('blur', function () {
                var reqID = $(this).val();

                // Convert reqID to integer
                reqID = parseInt(reqID);

                // AJAX request
                $.ajax({
                    url: 'fetchOldData.php',
                    type: 'POST',
                    dataType: 'json', // Expect JSON response
                    data: { reqID: reqID },
                    success: function (data) {
                        // Update form fields with retrieved data
                        $('input[name="name"]').val(data.recevNM);
                        $('input[name="phone"]').val(data.reqPN);
                        $('input[name="roomnum"]').val(data.recevRN);
                        $('select[name="college"]').val(data.recevCol);
                        $('input[name="tracking"]').val(data.trackNO);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error: ' + textStatus);
                        console.log('Error Thrown: ' + errorThrown);
                    }
                });
            });
        });
    </script>
</body>

</html>