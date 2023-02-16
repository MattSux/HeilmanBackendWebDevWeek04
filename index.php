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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["remove"])) {
            $itemNum = $_POST["remove"];
            $query = "DELETE FROM todoitems WHERE ItemNum = :itemNum";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':itemNum', $itemNum);
            $stmt->execute();
            header("Location: index.php");
            exit();
        }
        else {
            $title = $_POST["title"];
            $description = $_POST["description"];
            $query = "INSERT INTO todoitems (Title, Description) VALUES (:title, :description)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':description', $description);
            $stmt->execute();
            header("Location: index.php");
            exit();
        }
    }

    $query = "SELECT * FROM todoitems";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
        echo "<p>No to-do list items exist yet.</p>";
    }
    else {
        echo "<table>";
        echo "<tr><th>ItemNum</th><th>Title</th><th>Description</th><th>Remove</th></tr>";
        foreach ($rows as $row) {
            echo "<tr><td>" . $row["ItemNum"] . "</td><td>" . $row["Title"] . "</td><td>" . $row["Description"] . "</td><td>";
            echo "<form method='post'><button type='submit' name='remove' value='" . $row["ItemNum"] . "' style='color: red;'>X</button></form>";
            echo "</td></tr>";
        }
        echo "</table>";
    }
    ?>

    <form method="post">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title"><br>
        <label for="description">Description:</label>
        <input type="text" name="description" id="description"><br>
        <button type="submit">Add Item</button>
    </form>

    <?php
    $conn = null;
    ?>
</body>
</html>
