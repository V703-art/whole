<?php
include("db.php");

// Check if ID is provided
if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
    // Redirect to club list instead of dying
    header("Location: fullstackassignment.php");
    exit();
}

// Get club name from URL
$club_name = trim($_GET['id']);

// Fetch club details (use prepared statement for safety)
$stmt = $mysqli->prepare("SELECT football_club, football_description FROM football_clubs WHERE football_club = ?");
$stmt->bind_param("s", $club_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Club not found.");
}

$row = $result->fetch_assoc();
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
    </style>
</head>
<body>

<a class="back-link" href="fullstackassignment.php">Back to Clubs List</a>

<h1>Edit Football Club</h1>

<form action="edit-club.php" method="post">
    <input type="hidden" name="original_club" value="<?php echo htmlspecialchars($row['football_club']); ?>">

    <label>Club Name:</label><br>
    <input type="text" name="football_club" value="<?php echo htmlspecialchars($row['football_club']); ?>" required><br>

    <label>Description:</label><br>
    <textarea name="football_description"><?php echo htmlspecialchars($row['football_description']); ?></textarea><br>

    <input type="submit" name="update" value="Update Club">
</form>

</body>
</html>
