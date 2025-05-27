<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'] ?? '';
    $password_input = $_POST['password'] ?? '';

    // Get user data including status
    $stmt = $conn->prepare("SELECT name, password, status FROM users WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($name, $hashed_password, $status);
        $stmt->fetch();

        if ($status === 'Suspended') {
            header("Location: http://localhost/WebDev_Repository/pages/sign_up.html?status=suspended");
            exit();
        }

        if (password_verify($password_input, $hashed_password)) {
            $_SESSION['user_name'] = $name;
            $_SESSION['student_id'] = $student_id;
            header("Location: http://localhost/WebDev_Repository/pages/user/InUser_home.html");
            exit();
        } else {
            header("Location: http://localhost/WebDev_Repository/pages/sign_up.html?status=invalid");
            exit();
        }
    } else {
        header("Location: http://localhost/WebDev_Repository/pages/sign_up.html?status=invalid");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>