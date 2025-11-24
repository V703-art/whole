<?php
include("db.php");

// Read value from form safely
$keywords = isset($_POST['keywords']) ? trim($_POST['keywords']) : '';

// Escape keywords to prevent SQL injection
$keywords_safe = mysqli_real_escape_string($mysqli, $keywords);

// Build SQL query
if ($keywords_safe !== '') {
    $sql = "SELECT * FROM football_clubs 
            WHERE football_club LIKE '%{$keywords_safe}%' 
               OR football_description LIKE '%{$keywords_safe}%'
            ORDER BY football_club";
} else {
    $sql = "SELECT * FROM football_clubs ORDER BY football_club";
}

// Execute query
$results = mysqli_query($mysqli, $sql);
if (!$results) {
    die("Query failed: " . mysqli_error($mysqli));
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $mysqli->prepare("DELETE FROM football_clubs WHERE football_club = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:#4ade80'>Club deleted successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title> Premier League Football Clubs Search</title>
<style>
body { 
    font-family: Arial, sans-serif; 
    margin: 20px; 
    background-color: #121212; /* Dark background */
    color: #f0f0f0; /* Light text */
}
h1 { color: #1e90ff; }
form { 
    margin-bottom: 20px; 
    background-color: #1e1e1e; 
    padding: 15px; 
    border-radius: 8px; 
}
input[type="text"] { 
    padding: 8px; 
    width: 300px; 
    border-radius: 4px; 
    border: 1px solid #555; 
    background-color: #2a2a2a; 
    color: #f0f0f0; 
}
button { 
    padding: 8px 12px; 
    border-radius: 4px; 
    border: none; 
    background-color: #1e90ff; 
    color: #fff; 
    cursor: pointer; 
    font-weight: bold;
}
button:hover { background-color: #1c7ed6; }

table { 
    border-collapse: collapse; 
    width: 100%; 
    margin-top: 20px; 
    background-color: #1e1e1e; 
    border-radius: 8px; 
    overflow: hidden;
}
th, td { 
    border: 1px solid #555; 
    padding: 10px; 
    text-align: left; 
}
th { background-color: #333333; color: #fff; }
tr:nth-child(even) { background-color: #2a2a2a; } 
tr:hover { background-color: #3a3a3a; }

/* Buttons inside table */
a.button {
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    color: #fff;
    font-weight: bold;
    margin-right: 10px; /* space between buttons */
    display: inline-block;
}
a.edit { background-color: #28a745; }
a.edit:hover { background-color: #218838; }
a.delete { background-color: #dc3545; }
a.delete:hover { background-color: #c82333; }
</style>
</head>
<body>

<h1>Premier League Football Clubs Search</h1>

<!-- Search Form -->
<form action="" method="post">
    <input type="text" name="keywords" placeholder="Enter club name or description" value="<?php echo htmlspecialchars($keywords); ?>">
    <button type="submit">Search</button>
</form>

<?php if (mysqli_num_rows($results) > 0): ?>
    <p>Found <?php echo mysqli_num_rows($results); ?> result(s)<?php if ($keywords) echo " for \"" . htmlspecialchars($keywords) . "\""; ?>.</p>
    <table>
        <tr>
            <th>Club Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($results)): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['football_club']); ?></td>
            <td><?php echo htmlspecialchars($row['football_description']); ?></td>
            <td>
                <a class="button edit" href="edit-club.php?id=<?=htmlspecialchars($row['football_club'])?>">Edit</a>
                <a class="button delete" href="?delete=<?=htmlspecialchars($row['football_club'])?>" onclick="return confirm('Are you sure you want to delete this club?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No results found<?php if ($keywords) echo " for \"" . htmlspecialchars($keywords) . "\""; ?>.</p>
<?php endif; ?>

</body>
</html>
