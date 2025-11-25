<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premier League Football Clubs</title>
    <style>
      body { 
        font-family: Arial, sans-serif; 
        margin: 20px; 
        background-color: #000000;
        color: #ffffff;
        position: relative;
      }
      .logo {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 0 12px #FFD700, 0 0 25px #FFD700;
      }
      h1, h2 { color: #FFD700; font-weight: bold; }
      table { border-collapse: collapse; width: 100%; margin-top: 20px; }
      th, td { border: 1px solid #555; padding: 8px; text-align: left; }
      tr:nth-child(even) { background-color: #111111; } 
      tr:hover { background-color: #222222; } 
      th { background-color: #333333; color: white; }
      form { margin-bottom: 15px; }
      input[type="text"] { padding: 6px; width: 200px; background-color: #222; color: #fff; border: 1px solid #555; }
      input[type="submit"] { padding: 6px 10px; background-color: #444; color: #fff; border: 1px solid #555; cursor: pointer; }
      input[type="submit"]:hover { background-color: #555; }
      a.button { padding: 6px 12px; border-radius: 4px; text-decoration: none; color: #fff; font-weight: bold; margin-right: 10px; display: inline-block; }
      a.edit { background-color: #28a745; }
      a.edit:hover { background-color: #218838; }
      a.delete { background-color: #dc3545; }
      a.delete:hover { background-color: #c82333; }
      a.club-link { color: #FFD700; text-decoration: underline; }
    </style>
  </head>
  <body>

    <img src="football.png" class="logo" alt="Football Logo">
    <h1>Premier League Football Clubs!</h1>

    <?php
    include("db.php");
    if (!isset($mysqli)) {
        die("Database connection not found. Check db.php");
    }

    // Handle deletion
    if (isset($_GET['delete'])) {
        $clubName = $_GET['delete'];
        $stmt = $mysqli->prepare("DELETE FROM football_clubs WHERE football_club = ?");
        if ($stmt) {
            $stmt->bind_param("s", $clubName);
            $stmt->execute();
            $stmt->close();
            echo "<p style='color:#4ade80'>Club deleted successfully!</p>";
        } else {
            echo "<p style='color:#f87171'>Error deleting club: " . $mysqli->error . "</p>";
        }
    }

    // Fetch all clubs
    $sql = "SELECT * FROM football_clubs ORDER BY football_club";
    $results = $mysqli->query($sql);
    if (!$results) {
        die("Query failed: " . $mysqli->error);
    }
    ?>

    <!-- CREATE NEW CLUB BUTTON -->
    <a href="add-club.php" 
       style="display:inline-block; padding:8px 14px; background:#1d4ed8; color:#fff; border-radius:6px; text-decoration:none; font-weight:bold; margin-bottom:15px;">
      + Create New Club
    </a>

    <?php if ($results->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Club Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $results->fetch_assoc()): ?>
            <tr>
              <td>
                <a class="club-link" href="club-details.php?id=<?=urlencode($row['football_club'])?>">
                  <?=htmlspecialchars($row['football_club'])?>
                </a>
              </td>
              <td>
                <a class="button edit" href="edit-club.php?id=<?=urlencode($row['football_club'])?>">Edit</a>
                <a class="button delete" href="?delete=<?=urlencode($row['football_club'])?>" 
                   onclick="return confirm('Are you sure you want to delete this club?');">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No football clubs found.</p>
    <?php endif; ?>

  </body>
</html>
