<?php
include("db.php");
if (!isset($mysqli)) { die("Database connection not found."); }
if (!isset($_GET['id'])) { die("No club specified."); }

$club_name = $_GET['id'];

// Fetch club details
$stmt = $mysqli->prepare("SELECT * FROM football_clubs WHERE football_club = ?");
$stmt->bind_param("s", $football_club);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows === 0){
    die("Club not found.");
}

$stmt->bind_result($footbll_club, $football_description);
$stmt->fetch();
$stmt->close();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=htmlspecialchars($club_name)?> - Details</title>
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
<a class="button" href="index.php">Back to Clubs List</a>
</body>
</html>
