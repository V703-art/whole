<?php
include("db.php");

// Make sure POST fields exist
if (!isset($_POST['id'], $_POST['football_club'], $_POST['football_description'])) {
    die("fullstackassignment.php.");
}

$original_club = trim($_POST['id']);
$club = trim($_POST['football_club']);
$desc = trim($_POST['football_description']);

// Prepare statement to prevent SQL injection
$stmt = $mysqli->prepare(
    "UPDATE football_clubs
     SET football_club = ?, football_description = ?
     WHERE football_club = ?"
);
$stmt->bind_param("sss", $club, $desc, $original_club);

if (!$stmt->execute()) {
    die("Update failed: " . $stmt->error);
}

$stmt->close();

// Redirect back to index or list page
header("Location: index.php");
exit();
?>
