<?php
# Include your database connection file
require_once('Connections/dbConDelivery.php');

# Select all data in the 'request' table
$sql = "SELECT * FROM request WHERE sesID IS NULL";
$result = mysql_query($sql);

# Check if the query was successful
if (!$result) {
    die('Invalid query: ' . mysql_error());
}

# Initialize an associative array to store lists for each 'recevCol'
$listsByRecevCol = array();

# Loop through the results and dynamically create lists
while ($row = mysql_fetch_assoc($result)) {
    $recevCol = $row['recevCol'];

    # If the list for this 'recevCol' doesn't exist, create it
    if (!isset($listsByRecevCol[$recevCol])) {
        $listsByRecevCol[$recevCol] = array();
    }

    # Add the current row to the corresponding 'recevCol' list
    $listsByRecevCol[$recevCol][] = $row;
}

# Initialize a new list to store the remainder
$remainderList = array();

# Initialize a counter for session lists
$sessionCounter = 1;

# Loop through each 'recevCol' list and create a new list with the same 5 variables
foreach ($listsByRecevCol as $recevCol => &$list) {
    while (count($list) >= 5) {
        $tempList = array_slice($list, 0, 5);
        $list = array_slice($list, 5);

        # Create a session list (e.g., session1, session2, etc.)
        ${"session" . $sessionCounter} = $tempList;

        # Insert data into the 'session' table to generate 'sesID'
        $sqlInsertSession = "INSERT INTO session (staffID) VALUES (NULL)";
        mysql_query($sqlInsertSession);

        # Get the last inserted 'sesID'
        $sesID = mysql_insert_id();

        # Update the 'request' table with the corresponding 'sesID'
        foreach ($tempList as $item) {
            $reqID = $item['reqID'];
            $sqlUpdateRequest = "UPDATE request SET sesID = $sesID WHERE reqID = $reqID";
            mysql_query($sqlUpdateRequest);
        }

        $sessionCounter++;
    }

    # Move any remaining data to the remainder list
    $remainderList = array_merge($remainderList, $list);
}

# Initialize a counter for remainder lists
$remainderCounter = 1;

# Create remainder lists with the same 5 variables
while (!empty($remainderList)) {
    $tempList = array_slice($remainderList, 0, 5);
    $remainderList = array_slice($remainderList, 5);

    # Create a remainder list (e.g., remainder1, remainder2, etc.)
    ${"remainder" . $remainderCounter} = $tempList;

    # Insert data into the 'session' table to generate 'sesID'
    $sqlInsertSession = "INSERT INTO session (staffID) VALUES (NULL)";
    mysql_query($sqlInsertSession);

    # Get the last inserted 'sesID'
    $sesID = mysql_insert_id();

    # Update the 'request' table with the corresponding 'sesID'
    foreach ($tempList as $item) {
        $reqID = $item['reqID'];
        $sqlUpdateRequest = "UPDATE request SET sesID = $sesID WHERE reqID = $reqID";
        mysql_query($sqlUpdateRequest);
    }

    $remainderCounter++;
}

header("Location: genPage.php");

exit();
?>