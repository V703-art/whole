<?php
include("db.php");

// Fetch all clubs
$clubs_result = $mysqli->query("SELECT football_club FROM football_clubs ORDER BY football_club ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Football Clubs</title>
<style>
body { font-family: Arial, sans-serif; background:#b0b0b0; padding:20px; }
h1 { color:#FFD700; }
.club-link { display:block; background:#FFD700; color:#333; padding:10px; margin:5px 0; text-decoration:none; border-radius:5px; font-weight:bold; }
.club-link:hover { background:#e6c200; }
</style>
</head>
<body>

<h1>Football Clubs</h1>

<?php
if ($clubs_result->num_rows > 0) {
    while ($club = $clubs_result->fetch_assoc()) {
        echo '<a class="club-link" href="club-details.php?id=' . urlencode($club['football_club']) . '">' 
             . htmlspecialchars($club['football_club']) . '</a>';
    }
} else {
    echo "<p>No clubs found.</p>";
}
?>

</body>
</html>
