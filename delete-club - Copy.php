<?php
include("db.php");

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $sql = "DELETE FROM football_clubs WHERE id = $id";

    if (mysqli_query($mysqli, $sql)) {
        header("Location: index.php"); // Change to your page name
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($mysqli);
    }
} else {
    echo "Invalid request.";
}
?>
