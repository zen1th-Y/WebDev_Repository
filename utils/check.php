<?php
// Connection settings
$host = 'localhost';
$db = 'library_db'; // Change to your actual DB name
$user = 'root';         // Change if using a different MySQL user
$pass = '';             // Change if your MySQL has a password

// Try to connect
$conn = new mysqli($host, $user, $pass, $db);

// HTML Output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Connection Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .status-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="status-box">
    <h2>Database Connection Status</h2>
    <?php
    if ($conn->connect_error) {
        echo "<p class='error'>❌ Connection failed: " . $conn->connect_error . "</p>";
    } else {
        echo "<p class='success'>✅ Successfully connected to the database!</p>";
    }
    $conn->close();
    ?>
</div>

</body>
</html>

