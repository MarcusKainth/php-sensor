<?php
include ('config.php');
/* Establishes connection with database */
$mysqli = new mysqli($server, $user, $password, $db);
/* Prints out error information */
if ( $mysqli->connect_errno ) {
    echo "<div>Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . "</div>";
}
?>
