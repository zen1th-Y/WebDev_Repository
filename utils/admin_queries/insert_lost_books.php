<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Kunin lahat ng overdue books na hindi pa naka-log sa lost_books
$sql = "
INSERT INTO lost_books (book_id, student_id, fine_amount, days_overdue, status)
SELECT 
    nrb.book_id,
    nrb.student_id,
    b.fine_value,
    DATEDIFF(CURDATE(), nrb.return_date) AS days_overdue,
    'pending fine'
FROM non_returned_books nrb
JOIN books b ON nrb.book_id = b.book_id
LEFT JOIN lost_books lb ON lb.book_id = nrb.book_id AND lb.student_id = nrb.student_id
WHERE nrb.overdued_book = 1
  AND lb.lost_id IS NULL
  AND DATEDIFF(CURDATE(), nrb.return_date) > 0
";

if ($conn->query($sql) === TRUE) {
    echo "Overdue books inserted into lost_books table.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
