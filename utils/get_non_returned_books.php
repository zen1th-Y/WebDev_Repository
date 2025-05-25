<?php
header('Content-Type: application/json');
$host = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT nrb.nrb_id, nrb.book_id, nrb.student_id, nrb.date_student_received_book, nrb.return_date, nrb.days_left, nrb.overdued_book,
               u.name AS student_name, b.book_name
        FROM non_returned_books nrb
        JOIN users u ON nrb.student_id = u.student_id
        JOIN books b ON nrb.book_id = b.book_id
        ORDER BY nrb.return_date ASC";
$result = $conn->query($sql);

$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
echo json_encode($rows);
$conn->close();
?>