<?php
include('connect.php');
/* Converts temperature data from Arduino to JSON format for line chart */
$query = "SELECT DATE_FORMAT(Time, 'Date(%Y, %m, %e, %H, %i, %s)') AS Time, Temp1, Temp2, Average FROM Temps WHERE time > DATE_SUB(NOW(), INTERVAL 1 HOUR) AND time <= NOW()";
$stmt = $mysqli->prepare($query);
$stmt->execute();

$result = $stmt->get_result();

if($result) {
    /* Creates array and sets name of columns and data types */
    $rows = array();
    $table = array();
    $table['cols'] = array(array('label' => 'Time', 'type' => 'datetime'),array('label' => 'Temp1', 'type' => 'number'),array('label' => 'Temp2', 'type' => 'number'));

    foreach($result as $r) {
        /* Stores data from database/Arduino into array */
        $data = array();
        $data[] = array('v' => (string) $r['Time']);
        $data[] = array('v' => (float) $r['Temp1']);
        $data[] = array('v' => (float) $r['Temp2']);
        $rows[] = array('c' => $data);
    }

    $table['rows'] = $rows;
    /* Converts array to JSON readable format for line chart */
    echo json_encode($table);
} else {
    echo "Error";
}
?>
