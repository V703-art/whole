<?php
include("db.php");

// Ensure required POST fields exist
if (!isset($_POST['id'], $_POST['football_club'], $_POST['football_description'])) {
    die("Missing required fields.");
}

$id = intval($_POST['id']); // ID must be integer
$club = trim($_POST['football_club']);
$desc = trim($_POST['football_description']);

// Prepare SQL to update club
$stmt = $mysqli->prepare(
    "UPDATE football_clubs
     SET football_club = ?, football_description = ?
     WHERE id = ?"
);

if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}

$stmt->bind_param("ssi", $club, $desc, $id);

if (!$stmt->execute()) {
    die("Update failed: " . $stmt->error);
}

$stmt->close();

// Redirect back to main page
header("Location: fullstackassignment.php");
exit();
?>
