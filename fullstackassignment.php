<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premier League Football Clubs</title>

    <?php
    /* ---------------- SECURITY HEADERS ---------------- */
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");
    header("X-XSS-Protection: 1; mode=block");
    header("Content-Security-Policy: default-src 'self'; img-src 'self'; style-src 'self' 'unsafe-inline';");
    session_start();

    /* ---------------- CSRF TOKEN SETUP ---------------- */
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    $csrf = $_SESSION['csrf_token'];
    ?>

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

/* ---------------- SAFE DELETION (POST + CSRF) ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die("<p style='color:#f87171'>Security error: Invalid CSRF token.</p>");
    }

    $clubName = $_POST['delete'];
    $stmt = $mysqli->prepare("DELETE FROM football_clubs WHERE football_club = ?");
    
    if ($stmt) {
        $stmt->bind_param("s", $clubName);
        $stmt->execute();
        $stmt->close();
        echo "<p style='color:#4ade80'>Club deleted securely!</p>";
    } else {
        echo "<p style='color:#f87171'>Error deleting club: " . htmlspecialchars($mysqli->error) . "</p>";
    }
}

/* ---------------- FETCH ALL CLUBS ---------------- */
$stmt = $mysqli->prepare("SELECT football_club FROM football_clubs ORDER BY football_club ASC");
$stmt->execute();
$results = $stmt->get_result();
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

      <?php while ($row = $results->fetch_assoc()): 
              $safeClub = htmlspecialchars($row['football_club']);
              $urlClub  = urlencode($row['football_club']);
      ?>

        <tr>
          <td>
            <a class="club-link" href="club-details.php?id=<?=$urlClub?>">
              <?=$safeClub?>
            </a>
          </td>
          <td>
            <a class="button edit" href="edit-club.php?id=<?=$urlClub?>">Edit</a>

            <!-- DELETE MUST BE POST + CSRF -->
            <form method="POST" style="display:inline;" 
                  onsubmit="return confirm('Are you sure you want to delete this club?');">
              <input type="hidden" name="delete" value="<?=$safeClub?>">
              <input type="hidden" name="csrf_token" value="<?=$csrf?>">
              <button class="button delete" type="submit">Delete</button>
            </form>

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
