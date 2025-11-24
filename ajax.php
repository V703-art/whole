<?php

// Connect to database and run SQL query
include("db.php");

// Is a keyword provided in the URL?
if(isset($_GET['search']))
  $sql = "SELECT * FROM football_clubs WHERE game_name LIKE '%{$_GET['search']}%' ORDER BY released_date";
else
  $sql = "SELECT * FROM football_clubs ORDER BY football_clubs";

// Fetch all record, convert to JSON and return
$results = $mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);
print(json_encode($results));

?>
