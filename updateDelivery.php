<?php
require_once('Connections/dbConDelivery.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trackNumbers'])) {
    $selectedSessionID = $_GET['sesID'];

    foreach ($_POST['trackNumbers'] as $trackNumber) {
        // Sanitize the input to prevent SQL injection
        $trackNumber = mysql_real_escape_string($trackNumber);

        // Update the isDelivered status to 1 for the selected track numbers in the specified session
        $sqlUpdateDelivery = "UPDATE request SET isDelivered = 1 WHERE sesID = $selectedSessionID AND trackNO = '$trackNumber'";
        $resultUpdateDelivery = mysql_query($sqlUpdateDelivery);

        if (!$resultUpdateDelivery) {
            // Handle the update failure
            echo "Failed to update delivery status for track number $trackNumber.";
        }
    }

    // Redirect back to the same session page after updating
    header("Location: selSes.php?sesID=$selectedSessionID");
    exit;
}
?>