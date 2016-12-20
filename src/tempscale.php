<?php
include ('includes/connect.php');

/* Sets high/low temperatures from nouislider on monitor page */
if ($_POST["low"] || $_POST["high"]) {
    if ($_POST["low"])
        $low = preg_replace('/d+/', '', $_POST["low"]);
    if ($_POST["high"])
        $high = preg_replace('/d+/', '', $_POST["high"]);

    if ($_POST["low"] && !isset($_POST["high"])) {
        $stmt = $mysqli->prepare("UPDATE TempSettings SET Low=? WHERE TempSettingsID=0");
        $stmt->bind_param("i", $low);
        $stmt->execute();
    } else if (!isset($_POST["low"]) && $_POST["high"]) {
        $stmt = $mysqli->prepare("UPDATE TempSettings SET High=? WHERE TempSettingsID=0");
        $stmt->bind_param("i", $high);
        $stmt->execute();
    } else {
        $stmt = $mysqli->prepare("UPDATE TempSettings SET Low=?, High=? WHERE TempSettingsID=0");
        $stmt->bind_param("ii", $low, $high);
        $stmt->execute();
    }
    /* Stores all values in table in database */
} else {
    /* Gets high/low temperatures for nouislider */
    $stmt = $mysqli->prepare("SELECT Low, High FROM TempSettings WHERE TempSettingsID=0");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while($row = $result->fetch_row()) {
            $data = array((int) $row[0], (int) $row[1]);
        }
        /* Encodes to JSON for use with JQuery */
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
?>
