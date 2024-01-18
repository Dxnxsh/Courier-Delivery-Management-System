<?php
// Assuming you have a separate file for database connection, include it here
require_once('Connections/dbConDelivery.php'); // Adjust the filename as per your setup

// Fetch data and count categories
$query = "
    SELECT recevCol, COUNT(*) AS count FROM request GROUP BY recevCol
";

$result = mysql_query($query, $dbConDelivery);

$categoriesCount = array("Alpha" => 0, "Beta" => 0, "Gamma" => 0);

while ($row = mysql_fetch_assoc($result)) {
    $category = $row['recevCol'];
    $count = $row['count'];

    // Assign counts to corresponding categories
    $categoriesCount[$category] = $count;
}

// Send the result back to the client as JSON
header('Content-Type: application/json');
echo json_encode($categoriesCount);
?>