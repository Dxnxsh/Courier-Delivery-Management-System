<?php
// Include your database connection file
require_once('Connections/dbConDelivery.php');

if (isset($_POST['reqID'])) {
    $reqID = intval($_POST['reqID']); // Convert reqID to integer

    // Log reqID for debugging
    file_put_contents('log.txt', 'reqID: ' . $reqID . PHP_EOL, FILE_APPEND);

    // Retrieve old data from the database based on reqID
    $query = "SELECT * FROM request WHERE reqID = " . $reqID;
    file_put_contents('log.txt', 'Query: ' . $query . PHP_EOL, FILE_APPEND); // Log the query

    $result = mysql_query($query, $dbConDelivery);

    if ($result) {
        // Fetch the data as an associative array
        $data = mysql_fetch_assoc($result);

        // Log the fetched data
        file_put_contents('log.txt', 'Fetched Data: ' . print_r($data, true) . PHP_EOL, FILE_APPEND);

        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        // Log the error message
        file_put_contents('log.txt', 'MySQL Error: ' . mysql_error() . PHP_EOL, FILE_APPEND);

        // Handle the error if the query fails
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Unable to fetch data'));
    }
} else {
    // Handle the case where reqID is not set
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'reqID is not set'));
}
?>