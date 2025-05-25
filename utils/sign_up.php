<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "library_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    // Check if student_id or email already exists
    $check_stmt = $conn->prepare("SELECT * FROM users WHERE student_id = ? OR email = ?");
    $check_stmt->bind_param("ss", $student_id, $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Redirect back with error
        header("Location: http://localhost/WebDev_Repository/pages/sign_up.html?status=exists&show=register");
        exit();
    } else {
        $stmt = $conn->prepare("INSERT INTO users (student_id, name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $student_id, $name, $email, $password);

        if ($stmt->execute()) {
            // Redirect with success
            header("Location: http://localhost/WebDev_Repository/pages/sign_up.html?status=success&show=register");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $check_stmt->close();
}

$conn->close();
?>
