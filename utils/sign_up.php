<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // default MySQL username
$password = ""; // default MySQL password (empty if using XAMPP)
$dbname = "library_db"; // database name is library_db

// Create a connection (without selecting a database)
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the database exists, if not, create it
$db_check_query = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($db_check_query) === TRUE) {
    echo "Database '$dbname' is ready or already exists.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Now select the database
$conn->select_db($dbname);

// Create table if it doesn't exist
$table_creation_query = "
CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL
)";

// Execute table creation query
if ($conn->query($table_creation_query) === TRUE) {
    echo "Table 'users' is ready or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the input values from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $phone = $_POST['phone'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $phone);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
