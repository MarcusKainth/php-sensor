<?php
include('connect.php');
/* Gets latest temperature stored by Arduino in database */
$query = "SELECT Average FROM Temps ORDER BY Time DESC LIMIT 1";
$stmt = $mysqli->prepare($query);
$stmt->execute();

$result = $stmt->get_result();

if($result) {
    $num_rows = $result->num_rows;

    if ($num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            /* Prints temperature */
            echo number_format($row["Average"], 1);
        }
    }
} else {
    echo "Error";
}
?>
