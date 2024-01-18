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

mysql_select_db($database_dbConDelivery, $dbConDelivery);
$query_countDelivered = "SELECT * FROM request WHERE isDelivered = 1";
$countDelivered = mysql_query($query_countDelivered, $dbConDelivery) or die(mysql_error());
$row_countDelivered = mysql_fetch_assoc($countDelivered);
$totalRows_countDelivered = mysql_num_rows($countDelivered);

mysql_select_db($database_dbConDelivery, $dbConDelivery);
$query_countSession = "SELECT * FROM `session`";
$countSession = mysql_query($query_countSession, $dbConDelivery) or die(mysql_error());
$row_countSession = mysql_fetch_assoc($countSession);
$totalRows_countSession = mysql_num_rows($countSession);

mysql_select_db($database_dbConDelivery, $dbConDelivery);
$query_countNotDelivered = "SELECT * FROM request WHERE isDelivered = 0";
$countNotDelivered = mysql_query($query_countNotDelivered, $dbConDelivery) or die(mysql_error());
$row_countNotDelivered = mysql_fetch_assoc($countNotDelivered);
$totalRows_countNotDelivered = mysql_num_rows($countNotDelivered);

mysql_select_db($database_dbConDelivery, $dbConDelivery);

// Perform the query to get the data
$query = "SELECT recevCol, COUNT(*) AS count FROM request GROUP BY recevCol";
$result = mysql_query($query, $dbConDelivery);

// Prepare data for Charts.js
$data = [];
while ($row = mysql_fetch_assoc($result)) {
    $data['labels'][] = $row['recevCol'];
    $data['data'][] = $row['count'];
}

mysql_select_db($database_dbConDelivery, $dbConDelivery);

$query_Recordset1 = "SELECT * FROM request ORDER BY reqID DESC";

$Recordset1 = mysql_query($query_Recordset1, $dbConDelivery);

if (!$Recordset1) {
    die('Error in SQL query: ' . mysql_error());
}

$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0" />
    <title>CMS | Dashboard</title>

    <!-- ===== LINE AWESOME ICONS ===== -->
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />

    <!-- ===== BOX ICONS ===== -->
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />

    <!-- ===== MAIN CSS FOR ORDERS ADMIN ===== -->
    <link rel="stylesheet" href="assets/css/styles.css" />
    <link rel="stylesheet" href="assets/css/admin.css" />
    <link rel="stylesheet" href="https://unpkg.com/transition-style">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

    <!--SIDEBAR-->
    <?php require_once 'navbar.php'; ?>
    <?php
    mysql_free_result($countSession);

    mysql_free_result($countNotDelivered);
    ?>
    <div class="main-content">

        <main>
            <h1 transition-style="in:wipe:down">Welcome,
                <?php echo $_SESSION['name']; ?>.
            </h1>
            <div class="cards" style=" margin-top: 30px">
                <div class="card-single">
                    <div>
                        <h1 transition-style="in:wipe:down">Pending Delivery Request</h1><br>
                        <span transition-style="in:wipe:down">
                            <?php echo $totalRows_countNotDelivered ?>
                        </span>
                    </div>
                </div>
                <div class="card-single">
                    <div>
                        <h1 transition-style="in:wipe:down">Total Delivered Request</h1><br>
                        <span transition-style="in:wipe:down">
                            <?php echo $totalRows_countDelivered ?>
                        </span>
                    </div>
                </div>
                <div class="card-single">
                    <div>
                        <h1 transition-style="in:wipe:down">Total Session Generated</h1><br>
                        <span transition-style="in:wipe:down">
                            <?php echo $totalRows_countSession ?>
                        </span>
                    </div>
                </div>
                <div class="card-single">
                    <div>
                        <h1 transition-style="in:wipe:down">Request By College</h1>
                    </div>
                    <div style="height: 200px;">
                        <canvas id="myPieChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="card" style="margin-top: 30px;">
                <div class="card-header">
                    <h3 transition-style="in:wipe:down">Recent Request</h3>
                    <button transition-style="in:wipe:down" onclick="window.location.href = 'requestlist.php'"><a
                            href="requestlist.php" style="color:#fff;">See all<span
                                class="bx bx-right-arrow-alt"></span></a></button>
                </div>
                <div class="card-body" style="max-height: 36vh; overflow: auto">
                    <div class="table-responsive">
                        <table width="100%">
                            <thead>
                                <tr>
                                    <td transition-style="in:wipe:down">Tracking No.</td>
                                    <td transition-style="in:wipe:down">Name</td>
                                    <td transition-style="in:wipe:down">Address</td>
                                    <td transition-style="in:wipe:down">Phone</td>
                                    <td transition-style="in:wipe:down">Delivery Status</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php do { ?>
                                    <tr style="border-bottom: 1px solid #f0f0f0;">
                                        <td transition-style="in:wipe:down">
                                            <?php echo $row_Recordset1['trackNO']; ?>
                                        </td>
                                        <td transition-style="in:wipe:down">
                                            <?php echo $row_Recordset1['recevNM']; ?>
                                        </td>
                                        <td transition-style="in:wipe:down">
                                            <?php echo $row_Recordset1['recevRN']; ?>,
                                            <?php echo $row_Recordset1['recevCol']; ?>
                                        </td>
                                        <td transition-style="in:wipe:down">
                                            <?php echo $row_Recordset1['reqPN']; ?>
                                        </td>
                                        <td transition-style="in:wipe:down" style="padding:30px; padding-left: 50px">
                                            <input type="checkbox" <?php echo ($row_Recordset1['isDelivered'] == 1) ? 'checked' : ''; ?>>
                                        </td>
                                    </tr>
                                <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- ===== JQUERY CDN ===== -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        // Create a pie chart using Charts.js
        var ctx = document.getElementById('myPieChart').getContext('2d');
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($data['labels']); ?>,
                datasets: [{
                    data: <?php echo json_encode($data['data']); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                    ],
                }],
            },
            Options: {
                maintainAspectRatio: false,
                responsive: true,
            }
        });
    </script>
</body>

</html>