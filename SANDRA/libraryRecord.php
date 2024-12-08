<?php 

$host = "localhost";
$username = "root";
$password = ""; 
$dbname = "bscs2d";
$port = 3307; 
$conn = mysqli_connect($host, $username, $password, $dbname, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get and display data from database
$sql = "SELECT id, title, authorName, subjects, ISBN, ISSN, pYear FROM librarysystem";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="record.css">
        <title>Library Records</title>
    </head>

    <body>
        <h1>Library Records</h1>
    </body>
</html>

<?php

if ($result->num_rows > 0) {
    
    echo "<table>";
    echo "<thead>
            <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Subject</th>
                <th>ISBN</th>
                <th>ISSN</th>
                <th>Publication Year</th>
                <th>Delete</th>
            </tr>
          </thead>";
    
    echo "<tbody>";

    // Output for data in rows from librarysystem database
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["title"] . "</td>";
        echo "<td>" . $row["authorName"] . "</td>";
        echo "<td>" . $row["subjects"] . "</td>";
        echo "<td>" . $row["ISBN"] . "</td>";
        echo "<td>" . $row["ISSN"] . "</td>";
        echo "<td>" . $row["pYear"] . "</td>";
        echo "<td>
         <form action='delete_page.php' method='POST' onsubmit='return confirmDelete();'>
                <input type='hidden' name='id' value='" . $row['id'] . "'>
                <button type='submit' class='delbtn'>Delete</button>
            </form>
      </td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p class='empty'>Library Record is Empty.</p>";
}

$conn->close();
?>
