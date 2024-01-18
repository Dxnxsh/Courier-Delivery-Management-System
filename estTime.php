<?php
// Assuming you have a valid database connection
require_once('Connections/dbConDelivery.php');
if (isset($_POST['sesID'])) { // Fetch data for a specific sesID from the 'request' table

    $sesID = $_POST['sesID']; // Replace with the actual sesID you want to calculate estimated time for
    $sqlFetchRequests = "SELECT * FROM request WHERE sesID = $sesID ORDER BY reqID";
    $result = mysql_query($sqlFetchRequests);

    // Define initial values and time increments
    $initialTimes = [
        'Alpha' => 7,
        'Beta' => 5,
        'Gamma' => 12,
    ];

    $timeIncrement = [
        'sameCol' => 4,
        'diffCol' => 6,
        'alphaBetaGamma' => 15,
    ];

    // Initialize variables
    $totalTime = 0;
    $prevCol = null;

    // Loop through the fetched data
    while ($row = mysql_fetch_assoc($result)) {
        $currentCol = $row['recevCol'];

        // Calculate time based on rules
        if ($prevCol === null) {
            // First entry
            $totalTime += $initialTimes[$currentCol];
        } elseif ($prevCol === $currentCol) {
            // Same column as the previous entry
            $totalTime += $timeIncrement['sameCol'];
        } elseif (
            ($prevCol === 'Alpha' || $prevCol === 'Beta') && $currentCol === 'Gamma'
            ||
            ($prevCol === 'Gamma' && ($currentCol === 'Alpha' || $currentCol === 'Beta'))
        ) {
            // Different column, add specific time increment
            $totalTime += $timeIncrement['alphaBetaGamma'];
        } else {
            // Different column (not Alpha, Beta, Gamma), add default time increment
            $totalTime += $timeIncrement['diffCol'];
        }

        // Update previous column
        $prevCol = $currentCol;
    }

    // Now $totalTime contains the estimated time for the given sesID
    echo "$totalTime minutes";
} else {
    echo "Invalid parameters";
}

?>