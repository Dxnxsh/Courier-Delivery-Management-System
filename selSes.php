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
$query_freeSes = "SELECT * FROM `session` WHERE staffID IS NULL";
$freeSes = mysql_query($query_freeSes, $dbConDelivery) or die(mysql_error());
$row_freeSes = mysql_fetch_assoc($freeSes);
$totalRows_freeSes = mysql_num_rows($freeSes);
session_start();


// Fetch data for the selected session
$selectedSession = null;
$locationCount = 0;
$trackNumbers = [];

if (isset($_GET['sesID'])) {
    $selectedSessionID = intval($_GET['sesID']); // Ensure $selectedSessionID is an integer

    // Fetch the selected session
    $sqlSelectedSession = "SELECT sesID, staffID FROM session WHERE sesID = $selectedSessionID";
    $resultSelectedSession = mysql_query($sqlSelectedSession);

    $query_Recordset1 = "SELECT * FROM request WHERE sesID = $selectedSessionID";

    $Recordset1 = mysql_query($query_Recordset1, $dbConDelivery);

    if (!$Recordset1) {
        die('Error in SQL query: ' . mysql_error());
    }

    $row_Recordset1 = mysql_fetch_assoc($Recordset1);
    $totalRows_Recordset1 = mysql_num_rows($Recordset1);

    // Check for query execution error
    if (!$resultSelectedSession) {
        die('Error fetching selected session: ' . mysql_error());
    }

    if (mysql_num_rows($resultSelectedSession) > 0) {
        $selectedSession = mysql_fetch_assoc($resultSelectedSession);

        // Fetch location count for the selected session
        $sqlLocationCount = "SELECT COUNT(DISTINCT recevCol) AS locationCount FROM request WHERE sesID = $selectedSessionID";
        $resultLocationCount = mysql_query($sqlLocationCount);

        // Check for query execution error
        if (!$resultLocationCount) {
            die('Error fetching location count: ' . mysql_error());
        }

        if (mysql_num_rows($resultLocationCount) > 0) {
            $locationCountRow = mysql_fetch_assoc($resultLocationCount);
            $locationCount = $locationCountRow['locationCount'];
        }

        // Fetch track numbers for the selected session with isDelivered status
        $sqlTrackNumbers = "SELECT trackNO, isDelivered FROM request WHERE sesID = $selectedSessionID";
        $resultTrackNumbers = mysql_query($sqlTrackNumbers);

        // Check for query execution error
        if (!$resultTrackNumbers) {
            die('Error fetching track numbers: ' . mysql_error());
        }

        $trackData = [];
        if (mysql_num_rows($resultTrackNumbers) > 0) {
            while ($row = mysql_fetch_assoc($resultTrackNumbers)) {
                $trackData[] = $row;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0" />
    <title>CMS | Select Session</title>

    <!-- ===== LINE AWESOME ICONS ===== -->
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />

    <!-- ===== BOX ICONS ===== -->
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />

    <!-- ===== MAIN CSS FOR ORDERS ADMIN ===== -->
    <link rel="stylesheet" href="assets/css/styles.css" />
    <link rel="stylesheet" href="assets/css/admin.css" />
    <link rel="stylesheet" href="https://unpkg.com/transition-style">

</head>

<body>


    <!--SIDEBAR-->
    <?php require_once 'navbar.php'; ?>
    <div class="main-content">

        <main>
            <h1 transition-style="in:wipe:down">Select Session</h1>
            <div class="recent-grid" style="grid-template-columns: auto 65% ; margin-top: 30px;">
                <div class="card">
                    <div class="card-header">
                        <h3 transition-style="in:wipe:down">Select a session</h3>
                    </div>
                    <div class="card-body" style="max-height: 70vh; overflow: auto">
                        <table width="100%">
                            <tbody>
                                <?php do { ?>
                                    <tr style="border-bottom: 1px solid #f0f0f0;" transition-style="in:wipe:down">
                                        <td><a href="?sesID=<?php echo $row_freeSes['sesID']; ?>">
                                                <p>Session
                                                    <?php echo $row_freeSes['sesID']; ?>
                                                </p>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } while ($row_freeSes = mysql_fetch_assoc($freeSes)); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if (isset($_GET['sesID'])): ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 transition-style="in:wipe:down">Session Details</h3>
                            <?php if ($_SESSION['role'] !== 'admin'): ?>
                                <button class="add-button"  transition-style="in:wipe:down" type="button"
                                    onclick="selRecord(<?php echo $selectedSession['sesID']; ?>)">Select</button>
                            <?php endif; ?>
                        </div>

                        <div class="card-body">
                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <th>
                                            <p transition-style="in:wipe:down"> Session
                                                <?php echo $selectedSession['sesID']; ?>
                                            </p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th transition-style="in:wipe:down">Estimated time:</th>
                                        <td>
                                            <p id="estTime"></p>
                                        </td>
                                    </tr>
                                    <tr transition-style="in:wipe:down">
                                        <th>Location:</th>
                                        <td>
                                            <p><span id="alpha"></span> Alpha, <span id="beta"></span> Beta, <span
                                                    id="gamma"></span> Gamma</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th transition-style="in:wipe:down">Delivery Requests:</th>
                                    </tr>
                                    <div class="table-responsive">
                                        <table width="100%">
                                            <thead>
                                                <tr transition-style="in:wipe:down">
                                                    <td>Tracking No.</td>
                                                    <td>Name</td>
                                                    <td>Address</td>
                                                    <td>Phone</td>
                                                    <td>Delivery Status</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php do { ?>
                                                    <tr style="border-bottom: 1px solid #f0f0f0;"  transition-style="in:wipe:down">
                                                        <td>
                                                            <?php echo $row_Recordset1['trackNO']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $row_Recordset1['recevNM']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $row_Recordset1['recevRN']; ?>,
                                                            <?php echo $row_Recordset1['recevCol']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $row_Recordset1['reqPN']; ?>
                                                        </td>
                                                        <td style="padding:30px; padding-left: 50px">
                                                            <input type="checkbox" <?php echo ($row_Recordset1['isDelivered'] == 1) ? 'checked' : ''; ?>>
                                                        </td>
                                                    </tr>
                                                <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 transition-style="in:wipe:down">No session selected</h3>
                        </div>

                        <div class="card-body">
                            <table style="width: 100%;">
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- ===== JQUERY CDN ===== -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function selRecord(sesID) {
            if (confirm('Are you sure you want to select this session?')) {
                window.location.href = 'confirmSel.php?sesID=' + sesID;
            }
        }
        // Check URL parameters and display alert
        const urlParams = new URLSearchParams(window.location.search);
        const updateSuccess = urlParams.get('updateSuccess');

        if (updateSuccess !== null) {
            if (updateSuccess === 'false') {
                alert('Not allowed. Finish current session first');
            }
        }
    </script>
    <script>
        $(document).ready(function () {
            var sesID = <?php echo $selectedSessionID; ?>;

            $.ajax({
                type: 'POST',
                url: 'estTime.php',
                data: { sesID: sesID },
                success: function (response) {
                    $('#estTime').text(response);
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });

        $(document).ready(function () {
            // Replace 'YOUR_STAFF_ID' with the actual staff ID you want to use
            var sesID = <?php echo $selectedSessionID ?>;

            $.ajax({
                type: "GET",
                url: "countCat.php",
                data: { sesID: sesID },
                dataType: "json",
                success: function (result) {
                    // Handle the result (result object contains counts for Alpha, Beta, and Gamma)
                    console.log("Count of Alpha:", result.Alpha);
                    $('#alpha').text(result.Alpha);
                    console.log("Count of Beta:", result.Beta);
                    $('#beta').text(result.Beta);
                    console.log("Count of Gamma:", result.Gamma);
                    $('#gamma').text(result.Gamma);
                },
                error: function (error) {
                    console.error("Error fetching data:", error);
                }
            });
        });

    </script>

</body>

</html>