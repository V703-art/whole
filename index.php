<?php
include("db.php");
if (!isset($mysqli)) { die("Database connection not found."); }

// Fetch all clubs
$result = $mysqli->query("SELECT football_club FROM football_clubs ORDER BY football_club ASC");
if (!$result) { die("Error fetching clubs: " . $mysqli->error); }
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Football Clubs List</title>
<style>
body{font-family:Arial,sans-serif;background:#000;color:#fff;margin:20px;}
h1{color:#FFD700;}
ul{list-style:none;padding:0;}
li{margin:10px 0;}
a.button{display:inline-block;padding:6px 12px;margin-left:10px;background:#28a745;color:#fff;text-decoration:none;border-radius:4px;}
a.button:hover{background:#218838;}
a.view{background:#007bff;}
a.view:hover{background:#0069d9;}
</style>
</head>
<body>
<h1>Football Clubs</h1>
<ul>
<?php while ($row = $result->fetch_assoc()): ?>
    <li>
        <?=htmlspecialchars($row['football_club'])?>
        <a class="button view" href="club_details.php?id=<?=urlencode($row['football_club'])?>">View</a>
        <a class="button" href="edit-club.php?football_club=<?=urlencode($row['football_club'])?>">Edit</a>
    </li>
<?php endwhile; ?>
</ul>
</body>
</html>
