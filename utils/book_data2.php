<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Adjust if needed
$database = "library_db";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS $database");
$conn->select_db($database);

// Create table if not exists
$createTableSQL = "
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_name VARCHAR(255) NOT NULL,
    book_category VARCHAR(255) NOT NULL,
    book_author VARCHAR(255) NOT NULL,
    book_description TEXT,
    book_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
";
$conn->query($createTableSQL);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookName = $conn->real_escape_string($_POST['book_name']);
    $bookCategory = $conn->real_escape_string($_POST['book_category']);
    $bookAuthor = $conn->real_escape_string($_POST['book_author']);
    $bookDescription = $conn->real_escape_string($_POST['book_description']);

    $imagePath = "";

    // Handle file upload
    if (isset($_FILES["book_image"]) && $_FILES["book_image"]["error"] === UPLOAD_ERR_OK) {
        // This should be the URL path relative to your web server root
        $uploadDir = "/WebDev_Repository/uploads/";
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;

        // Ensure directory exists
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Generate unique filename
        $fileTmpPath = $_FILES["book_image"]["tmp_name"];
        $fileName = basename($_FILES["book_image"]["name"]);
        $uniqueName = uniqid() . "_" . $fileName;
        $targetPath = $targetDir . $uniqueName;

        // Final image path (used by frontend)
        $imagePath = $uploadDir . $uniqueName;

        if (!move_uploaded_file($fileTmpPath, $targetPath)) {
            $imagePath = ""; // fallback if move fails
        }
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO books (book_name, book_category, book_author, book_description, book_image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $bookName, $bookCategory, $bookAuthor, $bookDescription, $imagePath);

    if ($stmt->execute()) {
        header("Location: http://localhost/WebDev_Repository/pages/admin/create.html?status=success");
        exit();
    } else {
        header("Location: http://localhost/WebDev_Repository/pages/admin/create.html?status=error");
        exit();
    }


    $stmt->close();
}

$conn->close();
?>
