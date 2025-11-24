<?php
// db.php
// Database connection settings

$db_host = 'localhost';     // or 127.0.0.1
$db_user = '2213214';
$db_pass = 'by4k51';
$db_name = 'db2213214';

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}

// Set charset
$mysqli->set_charset("utf8mb4");
?>

