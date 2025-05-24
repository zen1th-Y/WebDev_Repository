<?php
session_start(); // ✅ Start session at the top

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'] ?? '';
    $password_input = $_POST['password'] ?? '';

    // Get full user data including name
    $stmt = $conn->prepare("SELECT name, password FROM users WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password_input, $hashed_password)) {
            $_SESSION['user_name'] = $name; // ✅ Store full name in session

            // ✅ Redirect after setting session
            header("Location: http://localhost/WebDev_Repository/pages/user/InUser_home.html");
            exit();
        } else {
            echo "❌ Incorrect password.";
        }
    } else {
        echo "❌ Student ID not found.";
    }

    $stmt->close();
}

$conn->close();
?>
