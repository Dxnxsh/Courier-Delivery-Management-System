<?php
require_once('Connections/dbConDelivery.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update the session isComplete status to 1
    $sesID = intval($_POST['sesID']);

    // Perform the update query here
    $updateQuery = "UPDATE session SET isComplete = 1 WHERE sesID = $sesID";
    
    // Run the update query
    $result = mysql_query($updateQuery);

    if ($result) {
        // Update successful
        echo json_encode(['success' => true]);
    } else {
        // Update failed
        echo json_encode(['success' => false, 'error' => mysql_error()]);
    }
} else {
    // Invalid request method
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
