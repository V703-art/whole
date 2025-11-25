<?php
include("db.php"); // Ensure $mysqli is connected

$message = "";
$club_name = "";
$club_description = ""; // new description variable

// Helper: fetch required columns from the table
function getRequiredColumns($mysqli, $table) {
    $columns = [];
    $sql = "SHOW COLUMNS FROM `$table`";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        if ($row['Null'] === 'NO' && $row['Default'] === null && $row['Extra'] !== 'auto_increment') {
            $columns[] = $row['Field'];
        }
    }
    return $columns;
}

$requiredColumns = getRequiredColumns($mysqli, 'football_clubs');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $club_name = isset($_POST['football_club']) ? trim($_POST['football_club']) : '';
    $club_description = isset($_POST['football_description']) ? trim($_POST['football_description']) : '';

    // Validation
    if ($club_name === '') {
        $message = "<p class='error'>Please enter a club name.</p>";
    } elseif (strlen($club_name) > 100) {
        $message = "<p class='error'>Club name is too long.</p>";
    } else {

        // Check duplicate
        $stmt = $mysqli->prepare("SELECT 1 FROM football_clubs WHERE football_club = ?");
        $stmt->bind_param("s", $club_name);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "<p class='error'>Club already exists.</p>";
            $stmt->close();
        } else {
            $stmt->close();

            // Prepare data for insert
            $columns = [];
            $placeholders = [];
            $values = [];
            $types = '';

            foreach ($requiredColumns as $col) {
                $columns[] = $col;
                $placeholders[] = '?';
                if ($col === 'football_club') {
                    $values[] = $club_name;
                    $types .= 's';
                } elseif ($col === 'football_description') {
                    $values[] = $club_description; // use description from form
                    $types .= 's';
                } else {
                    // Auto default for other NOT NULL: integer = 0, string = ''
                    $result = $mysqli->query("SHOW COLUMNS FROM football_clubs LIKE '$col'");
                    $row = $result->fetch_assoc();
                    $type = strtolower($row['Type']);
                    if (strpos($type, 'int') !== false) {
                        $values[] = 0;
                        $types .= 'i';
                    } else {
                        $values[] = '';
                        $types .= 's';
                    }
                }
            }

            // Build INSERT statement
            $sql = "INSERT INTO football_clubs (" . implode(',', $columns) . ") VALUES (" . implode(',', $placeholders) . ")";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param($types, ...$values);

            if ($stmt->execute()) {
                $message = "<p class='success'>Club added successfully!</p>";
                $club_name = "";
                $club_description = "";
            } else {
                $message = "<p class='error'>Error adding club: " . htmlspecialchars($stmt->error) . "</p>";
            }

            $stmt->close();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add New Football Club</title>
<style>
body { font-family: Arial; background:#000; color:#fff; margin:20px; }
input[type="text"], textarea, input[type="submit"] { padding:6px; margin:4px 0; }
input[type="text"], textarea { width: 400px; background:#222; color:#fff; border:1px solid #555; }
textarea { height: 100px; resize: vertical; }
input[type="submit"] { background:#444; color:#fff; border:1px solid #555; cursor:pointer; }
input[type="submit"]:hover { background:#555; }
.success { color:#4ade80; }
.error { color:#f87171; }
a { color:#FFD700; text-decoration:none; }
a:hover { text-decoration:underline; }
</style>
</head>
<body>

<h1>Add New Football Club</h1>

<?php echo $message; ?>

<form action="" method="post">
    <label>Club Name:</label><br>
    <input type="text" name="football_club"
           value="<?php echo htmlspecialchars($club_name); ?>"
           required maxlength="100"><br><br>

    <label>Club Description:</label><br>
    <textarea name="football_description"><?php echo htmlspecialchars($club_description); ?></textarea><br><br>

    <input type="submit" value="Add Club">
</form>

<p><a href="index.php">← Back to club list</a></p>

</body>
</html>
