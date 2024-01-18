<?php
require_once('Connections/dbConDelivery.php');

// Fetch sessions from the session table
$sqlSessions = "SELECT sesID, staffID FROM session";
$resultSessions = mysql_query($sqlSessions);

// Check for query execution error
if (!$resultSessions) {
    die('Error fetching sessions: ' . mysql_error());
}

// Fetch data for the selected session
$selectedSession = null;
$locationCount = 0;
$trackNumbers = [];

if (isset($_GET['sesID'])) {
    $selectedSessionID = intval($_GET['sesID']); // Ensure $selectedSessionID is an integer

    // Debug output
    echo "Selected Session ID: $selectedSessionID<br>";

    // Fetch the selected session
    $sqlSelectedSession = "SELECT sesID, staffID FROM session WHERE sesID = $selectedSessionID";
    $resultSelectedSession = mysql_query($sqlSelectedSession);

    // Check for query execution error
    if (!$resultSelectedSession) {
        die('Error fetching selected session: ' . mysql_error());
    }

    if (mysql_num_rows($resultSelectedSession) > 0) {
        $selectedSession = mysql_fetch_assoc($resultSelectedSession);

        // Fetch location count for the selected session
        $sqlLocationCount = "SELECT COUNT(DISTINCT recevCol) AS locationCount FROM request WHERE sesID = $selectedSessionID";
        $resultLocationCount = mysql_query($sqlLocationCount);

        // Check for query execution error
        if (!$resultLocationCount) {
            die('Error fetching location count: ' . mysql_error());
        }

        if (mysql_num_rows($resultLocationCount) > 0) {
            $locationCountRow = mysql_fetch_assoc($resultLocationCount);
            $locationCount = $locationCountRow['locationCount'];

            // Debug output
            echo "Location Count: $locationCount<br>";
        }

        // Fetch track numbers for the selected session with isDelivered status
        $sqlTrackNumbers = "SELECT trackNO, isDelivered FROM request WHERE sesID = $selectedSessionID";
        $resultTrackNumbers = mysql_query($sqlTrackNumbers);

        // Check for query execution error
        if (!$resultTrackNumbers) {
            die('Error fetching track numbers: ' . mysql_error());
        }

        $trackData = [];
        if (mysql_num_rows($resultTrackNumbers) > 0) {
            while ($row = mysql_fetch_assoc($resultTrackNumbers)) {
                $trackData[] = $row;
            }
        }

        // Debug output
        echo "Track Data: ";
        print_r($trackData);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Page</title>
    <!-- Add your CSS styles here -->
</head>

<body>
    <div style="display: flex; height: 100vh;">
        <div style="flex: 7; padding: 10px; border-right: 1px solid #ccc;">
            <!-- List of clickable sessions -->
            <h3>Click to view session:</h3>
            <ul>
                <?php
                if (mysql_num_rows($resultSessions) > 0) {
                    while ($row = mysql_fetch_assoc($resultSessions)) {
                        echo '<li><a href="?sesID=' . $row['sesID'] . '">Session ' . $row['sesID'] . '</a></li>';
                    }
                } else {
                    echo '<li>No sessions available</li>';
                }
                ?>
            </ul>
        </div>
        <div style="flex: 3; padding: 10px;">
            <?php if ($selectedSession !== null): ?>
                <!-- Display information for the selected session -->
                <h2>Current Session</h2>
                <p>Session Number:
                    <?php echo $selectedSession['sesID']; ?>
                </p>
                <p>Location Count:
                    <?php echo $locationCount; ?>
                </p>
                <h3>Track Numbers:</h3>
                <form action="updateDelivery.php" method="post">
                    <ul>
                        <?php
                        foreach ($trackData as $track) {
                            $isChecked = $track['isDelivered'] == 1 ? 'checked' : '';
                            echo '<li>';
                            echo '<label>';
                            echo '<input type="checkbox" name="trackNumbers[]" value="' . $track['trackNO'] . '" ' . $isChecked . '>';
                            echo $track['trackNO'];
                            echo '</label>';
                            echo '</li>';
                        }
                        ?>
                    </ul>
                    <button type="submit">Update Delivery Status</button>
                </form>
            <?php else: ?>
                <p>No session selected.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>