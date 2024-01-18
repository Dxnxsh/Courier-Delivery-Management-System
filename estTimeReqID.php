<?php
require_once('Connections/dbConDelivery.php');

if (isset($_POST['sesID']) && isset($_POST['reqID'])) {
    $sesID = $_POST['sesID'];
    $reqID = $_POST['reqID'];

    $sqlFetchRequests = "SELECT reqID, isDelivered, recevCol FROM request WHERE sesID = $sesID ORDER BY reqID";
    $resultRequests = mysql_query($sqlFetchRequests, $dbConDelivery) or die(mysql_error());

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

    $totalTime = 0;
    $subtractTime = 0;

    $prevCol = null;

    while ($row = mysql_fetch_assoc($resultRequests)) {
        if ($row['reqID'] <= $reqID) {
            $currentCol = $row['recevCol'];

            $initialTime = ($row['reqID'] == $reqID && $row['isDelivered'] == 0) ? $initialTimes[$currentCol] : 0;

            if ($prevCol === null) {
                $totalTime += $initialTime;
            } elseif ($prevCol === $currentCol) {
                if ($row['isDelivered'] == 0) {
                    $totalTime += $timeIncrement['sameCol'];
                }
            } elseif (
                ($prevCol === 'Alpha' || $prevCol === 'Beta') && $currentCol === 'Gamma'
                ||
                ($prevCol === 'Gamma' && ($currentCol === 'Alpha' || $currentCol === 'Beta'))
            ) {
                $totalTime += $timeIncrement['alphaBetaGamma'];
            } else {
                $totalTime += $timeIncrement['diffCol'];
            }

            $prevCol = $currentCol;
        }
    }

    $finalTime = max(0, $totalTime - $subtractTime);

    date_default_timezone_set("Asia/Kuala_Lumpur");
    $now = time();
    $timeInSeconds = $now + $finalTime * 60;
    $resultTime = date("H:i", $timeInSeconds);

    echo $resultTime;
} else {
    echo "Invalid parameters";
}
?>