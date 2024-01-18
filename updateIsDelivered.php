<?php
require_once('Connections/dbConDelivery.php');

if (isset($_POST['reqID']) && isset($_POST['isDelivered'])) {
  $reqID = intval($_POST['reqID']);
  $isDelivered = intval($_POST['isDelivered']);

  $updateQuery = "UPDATE request SET isDelivered = $isDelivered WHERE reqID = $reqID";
  $result = mysql_query($updateQuery, $dbConDelivery);

  if (!$result) {
    echo 'Error updating database: ' . mysql_error();
  } else {
    echo 'Database updated successfully';
  }
} else {
  echo 'Invalid data received';
}
?>