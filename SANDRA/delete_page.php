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

// POST request for book id
if (isset($_POST['id']) && !empty($_POST['id'])) {
    $book_id = intval($_POST['id']); // Get the book ID
    
    // Delete the book record with the given 'id'
    $sql = "DELETE FROM librarysystem WHERE id = ?";

    // Prepare the SQL statement
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 'i', $book_id);

        // Execute the deletion
        if (mysqli_stmt_execute($stmt)) {
            echo "Record deleted successfully!";
            // Redirect back to the records page after deletion
            header("Location: libraryRecord.php");
            exit();
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "SQL Error: " . mysqli_error($conn);
    }
} else {
    echo "No book ID provided for deletion.";
}

mysqli_close($conn);
?>
