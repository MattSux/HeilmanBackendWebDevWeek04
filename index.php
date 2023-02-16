<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            border-collapse: collapse;
            margin: 0 auto;
        }
        th, td {
            padding: 10px;
            border: 1px solid black;
        }
        th {
            background-color: lightgray;
        }
        form {
            margin: 20px 0;
        }
    </style>
</head>
<body>
<?php
require_once('database.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the item should be removed
    if (isset($_POST["remove"])) {
        // Get the item number from the form
        $itemNum = $_POST["remove"];
        // Delete the item from the database
        $query = "DELETE FROM todoitems WHERE ItemNum = :itemNum";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':itemNum', $itemNum);
        $stmt->execute();
        // Redirect back to the index page
        header("Location: index.php");
        exit();
    }
    // Otherwise, assume a new item is being added
    else {
        // Get the title and description from the form
        $title = $_POST["title"];
        $description = $_POST["description"];
        // Insert the new todo item into the database
        $query = "INSERT INTO todoitems (Title, Description) VALUES (:title, :description)";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':description', $description);
        $stmt->execute();
        // Redirect back to the index page
        header("Location: index.php");
        exit();
    }
}

// Query the database for all todo items
$query = "SELECT * FROM todoitems";
$stmt = $conn->prepare($query);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If there are no items, display a message
if (empty($rows)) {
    echo "No to do list items exist yet.";
}

// If there are items, display them in a table
else {
    echo "<table align='center' style='border: 1px solid black;'>";
    echo "<tr><th style='border: 1px solid black;'>ItemNum</th><th style='border: 1px solid black;'>Title</th><th style='border: 1px solid black;'>Description</th><th style='border: 1px solid black;'>Remove</th></tr>";
    foreach ($rows as $row) {
        echo "<tr><td style='border: 1px solid black;'>" . $row["ItemNum"] . "</td><td style='border: 1px solid black;'>" . $row["Title"] . "</td><td style='border: 1px solid black;'>" . $row["Description"] . "</td><td style='border: 1px solid black;'>";
        // Add a Remove button beside each item
        echo "<form method='post'><button type='submit' name='remove' value='" . $row["ItemNum"] . "' style='color: red;'>X</button></form>";
        echo "</td></tr>";
    }
    echo "</table>";
}

// Display the form for adding a new item
?>
<form method="post">
    <label for="title">Title:</label>
    <input type="text" name="title" id="title"><br>
    <label for="description">Description:</label>
    <input type="text" name="description" id="description"><br>
    <button type="submit">Add Item</button>
</form>
<?php

// Close the database connection
$conn = null;
?>
</body>
</html>
