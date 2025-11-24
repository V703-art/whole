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

      /* Football logo styling (Bigger Round + Glow) */
      .logo {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 90px;      /* Increased size */
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 0 12px #FFD700, 0 0 25px #FFD700;
      }

      /* Headings in yellow */
      h1, h2 {
        color: #FFD700;
        font-weight: bold;
      }

      table { 
        border-collapse: collapse; 
        width: 100%; 
        margin-top: 20px; 
      }
      th, td { 
        border: 1px solid #555; 
        padding: 8px; 
        text-align: left; 
      }
      tr:nth-child(even) { background-color: #111111; } 
      tr:hover { background-color: #222222; } 
      th { background-color: #333333; color: white; }

      form { margin-bottom: 15px; }
      input[type="text"] { 
        padding: 6px; 
        width: 200px; 
        background-color: #222; 
        color: #fff; 
        border: 1px solid #555; 
      }
      input[type="submit"] { 
        padding: 6px 10px; 
        background-color: #444; 
        color: #fff; 
        border: 1px solid #555; 
        cursor: pointer; 
      }
      input[type="submit"]:hover { background-color: #555; }

      a.button {
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        color: #fff;
        font-weight: bold;
        margin-right: 10px;
        display: inline-block;
      }
      a.edit { background-color: #28a745; }
      a.edit:hover { background-color: #218838; }
      a.delete { background-color: #dc3545; }
      a.delete:hover { background-color: #c82333; }

      a.club-link {
        color: #FFD700;
        text-decoration: underline;
      }
    </style>
  </head>
  <body>

    <!-- Football logo in the top-right corner -->
    <img src="football.png" class="logo" alt="Football Logo">

    <h1>Premier League Football Clubs!</h1>

    <form action="search-games.php" method="post">
      <input type="text" name="keywords" placeholder="Search">
      <input type="submit" value="Go!">
    </form>

    <?php
      include("db.php");
      if (!isset($mysqli)) {
          die("Database connection not found. Check db.php");
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

      $sql = "SELECT * FROM football_clubs ORDER BY football";
      $results = mysqli_query($mysqli, $sql);
      if (!$results) {
          die("Query failed: " . mysqli_error($mysqli));
      }

      if (mysqli_num_rows($results) > 0): 
    ?>

    <h2>Premier League Football Clubs</h2>

    <table>
      <thead>
        <tr>
          <th>Club Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($a_row = mysqli_fetch_assoc($results)): ?>
          <tr>
            <td>
              <a class="club-link" href="club-details.php?id=<?=urlencode($a_row['football_club'])?>">
                <?=htmlspecialchars($a_row['football_club'])?>
              </a>
            </td>
            <td>
              <a class="button edit" href="edit-club.php?id=<?=htmlspecialchars($a_row['football_club'])?>">Edit</a>
              <a class="button delete" href="?delete=<?=htmlspecialchars($a_row['football_club'])?>" 
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
