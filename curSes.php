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

session_start();
$userID = $_SESSION['user_id'];
mysql_select_db($database_dbConDelivery, $dbConDelivery);
if (isset($_POST['search'])) {
    $searchValue = $_POST['searchValue'];
    $query_Recordset1 = "SELECT request.*
FROM request
JOIN session ON request.sesID = session.sesID
WHERE session.staffID = '$userID' AND session.isComplete = 0 AND trackNO LIKE '%$searchValue%'";
} else {
    $query_Recordset1 = "SELECT
  request.*
FROM
  request
JOIN
  session ON request.sesID = session.sesID
WHERE
  session.staffID = '$userID' AND session.isComplete = 0
GROUP BY
  request.reqID;
";
}

$Recordset1 = mysql_query($query_Recordset1, $dbConDelivery);

if (!$Recordset1) {
    die('Error in SQL query: ' . mysql_error());
}

$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
$sesID = $row_Recordset1['sesID'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0" />
    <title>CMS | Current Session</title>

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
            <h1 transition-style="in:wipe:down">Current Session</h1>
            <div style=" margin-top: 30px; margin-bottom:30px" class="card">
                <div class="card-header" style="border-bottom: 1px solid #f0f0f0;">
                    <h3 transition-style="in:wipe:down">Session Details</h3>
                </div>
                <div class="card-body">
                    <table style="width: 100%;">
                        <tbody>
                            <tr transition-style="in:wipe:down">
                                <th>Session Number</th>
                                <td>
                                    <?php echo $sesID; ?>
                                </td>
                            </tr>
                            <tr transition-style="in:wipe:down">
                                <th>Estimated Delivery Time</th>
                                <td>
                                    <p id="estTime"></p>
                                </td>
                            </tr>
                            <tr transition-style="in:wipe:down">
                                <th>Location</th>
                                <td>
                                    <p><span id="alpha"></span> Alpha, <span id="beta"></span> Beta, <span
                                            id="gamma"></span>
                                        Gamma</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ===== ORDER LIST FOR ADMIN ===== -->
            <section class="featured__container">
                <div class="orders">
                    <div class="card">
                        <div class="card-header">
                            <h3 transition-style="in:wipe:down">
                                <?php echo $totalRows_Recordset1 . ' orders found' ?>
                            </h3>

                            <div class="search-form" transition-style="in:wipe:down">
                                <form method="post" action="">
                                    <input class="text-box" style="margin-bottom: 0" type="text" name="searchValue"
                                        placeholder="Search by TrackNO" />
                                    <button type="submit" name="search" hidden></button>
                                </form>

                            </div>
                            <button class="add-button"  transition-style="in:wipe:down" type="button" onclick="updateSession(event)">Complete</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">

                                <table width="100%">
                                    <thead>
                                        <tr transition-style="in:wipe:down">
                                            <td>ID No.</td>
                                            <td>Tracking No.</td>
                                            <td>Name</td>
                                            <td>Address</td>
                                            <td>Phone</td>
                                            <td>Delivery Status</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php do { ?>
                                            <tr style="border-bottom: 1px solid #f0f0f0;" transition-style="in:wipe:down">
                                                <form method="post" action="../php/invoice-users">
                                                    <td>
                                                        <?php echo $row_Recordset1['reqID']; ?>
                                                    </td>
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
                                                        <input type="checkbox" class="deliveryCheckbox"
                                                            data-sesid="<?php echo $sesID; ?>" <?php echo ($row_Recordset1['isDelivered'] == 1) ? 'checked' : ''; ?>>
                                                    </td>
                                                </form>
                                            </tr>
                                        <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- ===== JQUERY CDN ===== -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            var sesID = <?php echo $sesID; ?>;

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
            var staffID = <?php echo $userID ?>;

            $.ajax({
                type: "GET",
                url: "countCatReqID.php",
                data: { staffID: staffID },
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

        $(document).ready(function () {
            $('input[type="checkbox"]').on('change', function () {
                // Find the closest parent tr and get the value of the first td
                var reqID = $(this).closest('tr').find('td:first').text();
                var isDelivered = this.checked ? 1 : 0;

                // Make sure you are sending the data correctly
                console.log("reqID: " + reqID + ", isDelivered: " + isDelivered);

                // Make an AJAX request to update the database
                $.ajax({
                    type: 'POST',
                    url: 'updateIsDelivered.php', // Replace with the actual PHP file to handle the update
                    data: { 'reqID': reqID, 'isDelivered': isDelivered },
                    success: function (response) {
                        // Handle the response if needed
                        console.log(response);
                    },
                    error: function (error) {
                        console.error('Error updating database:', error);
                    }
                });
            });
        });

        $(document).ready(function () {
            // Update button state when a checkbox is clicked
            $('.deliveryCheckbox').on('change', function () {
                updateButtonState();
            });

            function updateButtonState() {
                var allChecked = $('.deliveryCheckbox:checked').length === $('.deliveryCheckbox').length;
                $('#updateSessionBtn').prop('disabled', !allChecked);
            }

            // Initialize button state on page load
            updateButtonState();
        });

        function updateSession(event) {
            // Prevent the default form submission behavior
            event.preventDefault();

            // Get the session ID
            var sesID = <?php echo $sesID; ?>;

            // Add a check for all checkboxes being checked
            if ($('.deliveryCheckbox:checked').length === $('.deliveryCheckbox').length) {
                // All checkboxes are checked, proceed with the update
                $.ajax({
                    type: 'POST',
                    url: 'compSes.php',
                    data: { sesID: sesID },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            // Update successful, you can show a success message or perform other actions
                            alert('Session updated successfully');
                        } else {
                            // Update failed, you can show an error message or log the error
                            alert('Failed to update session. Error: ' + response.error);
                        }
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            } else {
                // Not all checkboxes are checked, show a message or handle it as needed
                alert('Please check all checkboxes before updating the session.');
            }
        }
    </script>

</body>

</html>