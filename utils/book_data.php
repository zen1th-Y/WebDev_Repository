<?php

$BOOKname = $_POST['book_name'];
$BOOKcategory = $_POST['book_category'];
$BOOKauthor = $_POST['book_author'];


//DATABASE CONNECTION
$connection = mysqli_connect("localhost", "root", "", "library_db", 3306);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}else {
    // Prepare the SQL statement
    $sql = "INSERT INTO books ( book_name, book_category, book_author) VALUES ( ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "sss", $BOOKname, $BOOKcategory, $BOOKauthor);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . mysqli_error($connection);
    }

    // Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}