<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$connection = mysqli_connect("localhost", "root", "", "library_db", 3306);
if (!$connection) {
    echo json_encode(["error" => "DB connection failed: " . mysqli_connect_error()]);
    exit;
}

$sql = "SELECT book_name, book_author, book_image FROM books ORDER BY id DESC LIMIT 10";
$result = mysqli_query($connection, $sql);

$books = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }
} else {
    echo json_encode(["error" => "Query failed: " . mysqli_error($connection)]);
    exit;
}

mysqli_close($connection);

echo json_encode($books);
?>
