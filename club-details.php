<?php
include("db.php");
if (!isset($mysqli)) { die("Database connection not found."); }

// Check if a club ID is provided
if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
    // Redirect to club list if no ID
    header("Location: club-list.php");
    exit();
}

// Decode URL-encoded club name
$club_name_input = urldecode(trim($_GET['id']));

// Fetch club details (case-insensitive)
$stmt = $mysqli->prepare("SELECT football_club, football_description FROM football_clubs WHERE football_club = ? COLLATE utf8_general_ci");
if (!$stmt) { die("Database query failed: " . $mysqli->error); }

$stmt->bind_param("s", $club_name_input);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) { $stmt->close(); die("Club not found."); }

$stmt->bind_result($football_club, $football_description);
$stmt->fetch();
$stmt->close();

// Fallbacks
$football_club = $football_club ?: "Unknown Club";
$football_description = $football_description ?: "No description available.";

$back_url = "club-list.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=htmlspecialchars($football_club)?> - Details</title>
<style>
body{font-family:Arial,sans-serif;background:#000;color:#fff;margin:20px;}
h1{color:#FFD700;}
p{font-size:18px;}
a.button{display:inline-block;padding:6px 12px;margin-top:15px;background:#28a745;color:#fff;text-decoration:none;border-radius:4px;}
a.button:hover{background:#218838;}
</style>
</head>
<body>
<h1><?=htmlspecialchars($football_club)?></h1>
<p><?=htmlspecialchars($football_description)?></p>
<a class="button" href="<?= $back_url ?>">Back to Club List</a>
</body>
</html>

