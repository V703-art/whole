<?php
include("db.php");
if (!isset($mysqli) || !$mysqli) die("Database connection not found.");

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) die("Invalid club ID.");

$stmt = $mysqli->prepare("DELETE FROM football_clubs WHERE football_id = ?");
if (!$stmt) die("Prepare failed: " . $mysqli->error);
$stmt->bind_param("i", $id);
$stmt->execute();

echo ($stmt->affected_rows > 0) ? "<p>Club deleted successfully.</p>" : "<p>No club found to delete.</p>";
$stmt->close();

header("refresh:1; url=index.php");
?>
