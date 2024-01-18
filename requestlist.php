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
if (isset($_POST['search'])) {
  $searchValue = $_POST['searchValue'];
  $query_Recordset1 = "SELECT * FROM request WHERE trackNO LIKE '%$searchValue%'";
} else {
  $query_Recordset1 = "SELECT * FROM request";
}

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
  <title>CMS | Request List</title>

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
      <h1 transition-style="in:wipe:down">Request List</h1>
      <!-- ===== ORDER LIST FOR ADMIN ===== -->
      <section class="featured__container" style="margin-top: 30px;">
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
              <button class="add-button"  transition-style="in:wipe:down" type="button" onclick="window.location.href = 'addAdmin.php'">Add</button>
            </div>
            <div class="card-body" style="max-height: 70vh; overflow: auto">
              <div class="table-responsive">

                <table width="100%">
                  <thead transition-style="in:wipe:down">
                    <tr>
                      <td>ID No.</td>
                      <td>Tracking No.</td>
                      <td>Name</td>
                      <td>Address</td>
                      <td>Phone</td>
                      <td>Session ID</td>
                      <td>Delivery Status</td>
                      <td>Action</td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php do { ?>
                      <tr style="border-bottom: 1px solid #f0f0f0;" transition-style="in:wipe:down">
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
                        <td>
                          <?php echo $row_Recordset1['sesID']; ?>
                        </td>
                        <td style="padding:30px; padding-left: 50px">
                          <input type="checkbox" <?php echo ($row_Recordset1['isDelivered'] == 1) ? 'checked' : ''; ?>>
                        </td>
                        <td style="padding:30px; padding-left: 15px;">
                          <button type="edit" name="edit"
                            onclick="window.location.href = 'update.php?reqID=' + <?php echo $row_Recordset1['reqID']; ?>"
                            style="border:none;background-color:#fff;"><i class="bx bx-edit"
                              style="font-size:1.1rem;cursor:pointer;"></i></button>
                          <button name="delete" onclick="deleteRecord(<?php echo $row_Recordset1['reqID']; ?>)"
                            style="border:none;background-color:#fff;">
                            <i class="bx bx-trash" style="font-size:1.1rem;cursor:pointer;"></i>
                          </button>

                        </td>
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
    function deleteRecord(reqID) {
      if (confirm('Are you sure you want to delete this record?')) {
        window.location.href = 'delReq.php?reqID=' + reqID;
      }
    }

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
  </script>
</body>

</html>