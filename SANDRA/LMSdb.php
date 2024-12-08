<?php
// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Validate inputs
$title = htmlspecialchars(trim($_POST["title"]));
$authorName = htmlspecialchars(trim($_POST["authorName"]));
$subject = htmlspecialchars(trim($_POST["subjects"]));
$ISBN = htmlspecialchars(trim($_POST["ISBN"]));
$ISSN = htmlspecialchars(trim($_POST["ISSN"]));
$pYear = intval($_POST["pYear"]);

// Validate required fields
if (empty($title) || empty($authorName) || empty($subject) || empty($pYear)) {
    die("All fields marked as required must be filled out!");
}

// Validate ISBN--Must be 13-digit number
if (!preg_match('/^\d{13}$/', $ISBN)) {
    die("Invalid ISBN. It must be a 13-digit number.");
}

// Validate ISSN--Allow "N/A" or 8-digit number
if ($ISSN !== "N/A" && !preg_match('/^\d{8}$/', $ISSN)) {
    die("Invalid ISSN. It must be an 8-digit number or 'N/A'.");
}

// Database connection
$host = "localhost";
$username = "root";
$password = ""; 
$dbname = "bscs2d";
$port = 3307; 
$conn = mysqli_connect($host, $username, $password, $dbname, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the ISBN exists
$checkISBNQuery = "SELECT * FROM librarysystem WHERE ISBN = ?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $checkISBNQuery)) {
    die("SQL Error: " . mysqli_error($conn));
}

// Bind ISBN parameter and execute the query
mysqli_stmt_bind_param($stmt, "s", $ISBN);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

// If ISBN exists, stop the upload of data and display the error
if (mysqli_stmt_num_rows($stmt) > 0) {
    echo "Error: This ISBN already exists in the database.";
} else {
    // SQL query to insert data if and only if the ISBN is unique
    $sql = "INSERT INTO librarysystem (title, authorName, subjects, ISBN, ISSN, pYear)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die("SQL Error: " . mysqli_error($conn));
    }

    // Bind parameters and display the query
    mysqli_stmt_bind_param($stmt, "sssssi", $title, $authorName, $subject, $ISBN, $ISSN, $pYear);

    if (mysqli_stmt_execute($stmt)) {
        echo "Record saved successfully!";
        header("Location: libraryRecord.php");
        exit();
    } else {
        echo "Error saving record: " . mysqli_error($conn);
    }
}

// Close the connections
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
