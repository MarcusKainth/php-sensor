<?php
include ('includes/connect.php');

/* Checks if Arduino has sent over GET request
 * with temperature and that the database
 * has been connected.
 */
if ($_GET["temp1"] && $_GET["temp2"]) {
    /* Replaces all other characters that aren't numericals with nothing */
    $temp1 = preg_replace('/d+/', '', $_GET["temp1"]);
    $temp2 = preg_replace('/d+/', '', $_GET["temp2"]);
    /* Checks if number is within a reasonable range */
    if (($temp1 >= 0 && $temp1 <= 100) && ($temp2 >= 0 && $temp2 <= 100)) {
        /* Outputs what is being added to the database */
        echo "Adding " . $post . " to database";
        $average = ($temp1 + $temp2) / 2;
        /* Prepares the query */
        $stmt = $mysqli->prepare("INSERT INTO Temps (Temp1, Temp2, Average) VALUES (?, ?, ?)");
        $stmt->bind_param("ddd", $temp1, $temp2, $average);
        $stmt->execute();
        /* Query has been executed */
    } else {
        echo "Temperatures posted is not in the given range 0 - 100";
    }
} else {
    echo "No data posted to website";
}
?>
