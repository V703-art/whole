<?php
include("db.php");

// Get the search query (if any)
$query = isset($_POST['query']) ? trim($_POST['query']) : '';

// Prepare SQL safely
if ($query !== '') {
    $stmt = $mysqli->prepare("SELECT * FROM football_clubs WHERE football_name LIKE CONCAT('%', ?, '%') ORDER BY released_date DESC");
    $stmt->bind_param("s", $query);
} else {
    $stmt = $mysqli->prepare("SELECT * FROM football_clubs ORDER BY released_date DESC");
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Club Name</th><th>Description</th><th>Founded</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['football_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['football_description']) . "</td>";
        echo "<td>" . htmlspecialchars($row['released_date']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No clubs found.</p>";
}

$stmt->close();
$mysqli->close();
?>
