<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Adjust if needed
$database = "library_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookId = $_POST['book_id'];
    $bookName = $_POST['book_name'];
    $bookCategory = $_POST['book_category'];
    $bookAuthor = $_POST['book_author'];
    $bookDescription = $_POST['book_description'];

    $imagePath = "";

    // Handle file upload if a new image is provided
    if (isset($_FILES["book_image"]) && $_FILES["book_image"]["error"] === UPLOAD_ERR_OK) {
        $uploadDir = "/WebDev_Repository/uploads/";
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileTmpPath = $_FILES["book_image"]["tmp_name"];
        $fileName = basename($_FILES["book_image"]["name"]);
        $uniqueName = uniqid() . "_" . $fileName;
        $targetPath = $targetDir . $uniqueName;

        $imagePath = $uploadDir . $uniqueName;

        if (!move_uploaded_file($fileTmpPath, $targetPath)) {
            $imagePath = ""; // fallback if move fails
        }
    }

    if ($bookId > 0) {
        if ($imagePath !== "") {
            $stmt = $conn->prepare("UPDATE books SET book_name=?, book_category=?, book_author=?, book_description=?, book_image=? WHERE book_id=?");
            $stmt->bind_param("sssssi", $bookName, $bookCategory, $bookAuthor, $bookDescription, $imagePath, $bookId);
        } else {
            $stmt = $conn->prepare("UPDATE books SET book_name=?, book_category=?, book_author=?, book_description=? WHERE book_id=?");
            $stmt->bind_param("ssssi", $bookName, $bookCategory, $bookAuthor, $bookDescription, $bookId);
        }
        if ($stmt->execute()) {
            header("Location: /WebDev_Repository/pages/admin/edit_book.html?book_id=$bookId&status=edited");
            exit();
        } else {
            header("Location: /WebDev_Repository/pages/admin/edit_book.html?book_id=$bookId&status=error");
            exit();
        }
    }
}

$conn->close();
?>