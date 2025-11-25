
<?php
include("db.php");

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $original_club = trim($_POST['original_club']);
    $new_club = trim($_POST['football_club']);
    $new_description = trim($_POST['football_description']);

    // Check for empty club name
    if (empty($new_club)) {
        $message = "Club name cannot be empty.";
    } else {
        // Check if new club name already exists (excluding the original)
        $stmt_check = $mysqli->prepare("SELECT COUNT(*) FROM football_clubs WHERE football_club = ? AND football_club != ?");
        $stmt_check->bind_param("ss", $new_club, $original_club);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($count > 0) {
            $message = "Club name already exists!";
        } else {
            // Update the club
            $stmt = $mysqli->prepare("UPDATE football_clubs SET football_club = ?, football_description = ? WHERE football_club = ?");
            $stmt->bind_param("sss", $new_club, $new_description, $original_club);

            if ($stmt->execute()) {
                $message = "Club updated successfully!";
                $row['football_club'] = $new_club;
                $row['football_description'] = $new_description;
            } else {
                $message = "Error updating club. Please try again.";
            }
            $stmt->close();
        }
    }
}

// Handle GET request to fetch club details
if (!isset($row)) {
    if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
        header("Location: fullstackassignment.php");
        exit();
    }

    $club_name = trim($_GET['id']);
    $stmt = $mysqli->prepare("SELECT football_club, football_description FROM football_clubs WHERE football_club = ?");
    $stmt->bind_param("s", $club_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Club not found.");
    }

    $row = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Club</title>
    <style>
        body { background-color: #f0f0f0; font-family: Arial, sans-serif; padding:20px; }
        h1 { color: red; font-size: 36px; font-weight: bold; margin-bottom: 20px; text-shadow: 1px 1px 2px #000; }
        label { color: red; font-size: 18px; font-weight: bold; }
        a.back-link { display: inline-block; margin-bottom: 20px; padding: 10px 20px; text-decoration: none; color: white; background-color: red; font-weight: bold; border-radius: 4px; }
        a.back-link:hover { background-color: darkred; }
        input[type="text"], textarea { width: 100%; padding: 10px; margin-top:5px; margin-bottom:15px; border:1px solid #ccc; border-radius:4px; font-size:16px; }
        textarea { height:200px; resize:vertical; }
        input[type="submit"] { background-color:red; color:white; border:none; padding:12px 24px; font-weight:bold; cursor:pointer; border-radius:4px; font-size:16px; }
        input[type="submit"]:hover { background-color:darkred; }
        .message { margin-bottom: 20px; padding: 10px; border-radius: 4px; font-weight: bold; }
        .success { background-color: #d4edda; color: #155724; border:1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border:1px solid #f5c6cb; }
    </style>
</head>
<body>

<a class="back-link" href="fullstackassignment.php">Back to Clubs List</a>

<h1>Edit Football Club</h1>

<?php if (!empty($message)): ?>
    <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<form action="edit-club.php?id=<?php echo urlencode($row['football_club']); ?>" method="post">
    <input type="hidden" name="original_club" value="<?php echo htmlspecialchars($row['football_club']); ?>">

    <label>Club Name:</label><br>
    <input type="text" name="football_club" value="<?php echo htmlspecialchars($row['football_club']); ?>" required><br>

    <label>Description:</label><br>
    <textarea name="football_description"><?php echo htmlspecialchars($row['football_description']); ?></textarea><br>

    <input type="submit" name="update" value="Update Club">
</form>

</body>
</html>
