<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Clubs</title>
    <style>
      body { font-family: Arial, sans-serif; margin: 20px; }
      h1 { color: #333; }
      input[type="text"], input[type="date"], textarea {
        padding: 8px; margin: 5px 0; width: 250px;
      }
      button { padding: 8px 15px; cursor: pointer; }
      table { border-collapse: collapse; width: 100%; margin-top: 20px; }
      th, td { border: 1px solid #ddd; padding: 10px; }
      th { background-color: #333; color: white; }
      tr:nth-child(even) { background: #f9f9f9; }
      tr:hover { background: #f1f1f1; }
      #status { margin-top: 10px; color: green; }
    </style>
  </head>
  <body>
    <h1>⚽ Football Clubs</h1>

    <h3>Add a New Club</h3>
    <form id="addForm">
      <input type="text" id="name" placeholder="Club Name" required><br>
      <textarea id="description" placeholder="Description"></textarea><br>
      <input type="date" id="date" required><br>
      <button type="submit">Add Club</button>
    </form>
    <div id="status"></div>

    <hr>

    <h3>Search Clubs</h3>
    <input type="text" id="search" placeholder="Type to search...">

    <div id="results"></div>

    <script>
      const resultsDiv = document.getElementById('results');
      const searchBox = document.getElementById('search');
      const statusDiv = document.getElementById('status');
      const addForm = document.getElementById('addForm');

      // Fetch clubs (with optional query)
      function fetchClubs(query = '') {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'fetch_clubs.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
          resultsDiv.innerHTML = this.responseText;
        };
        xhr.send('query=' + encodeURIComponent(query));
      }

      // Initial load
      fetchClubs();

      // Live search
      searchBox.addEventListener('keyup', function() {
        fetchClubs(this.value);
      });

      // Add new club
      addForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const name = document.getElementById('name').value;
        const desc = document.getElementById('description').value;
        const date = document.getElementById('date').value;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'add_club.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
          statusDiv.textContent = this.responseText;
          addForm.reset();
          fetchClubs(); // refresh table
        };
        xhr.send('name=' + encodeURIComponent(name) + '&description=' + encodeURIComponent(desc) + '&date=' + encodeURIComponent(date));
      });
    </script>
  </body>
</html>
