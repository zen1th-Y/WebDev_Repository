<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "library_db"; 

$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$db_check_query = "CREATE DATABASE IF NOT EXISTS $dbname";
$conn->query($db_check_query);

$conn->select_db($dbname);


$table_creation_query = "
CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";
$conn->query($table_creation_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    $stmt = $conn->prepare("INSERT INTO users (student_id, name, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $student_id, $name, $email, $password);

    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
