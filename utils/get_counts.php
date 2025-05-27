<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "library_db");
if ($conn->connect_error) {
    echo json_encode([
        'total_books' => 0,
        'total_categories' => 0,
        'total_members' => 0,
        'pending_requests' => 0,
        'total_non_returned_books' => 0,
        'total_issued_books' => 0,
        'total_suspended' => 0
    ]);
    exit;
}
$res = $conn->query("SELECT total_books, total_categories, total_members, pending_requests, total_non_returned_books, total_issued_books, total_suspended FROM count_items LIMIT 1");
$row = $res->fetch_assoc();
echo json_encode([
    'total_books' => (int)($row['total_books'] ?? 0),
    'total_categories' => (int)($row['total_categories'] ?? 0),
    'total_members' => (int)($row['total_members'] ?? 0),
    'pending_requests' => (int)($row['pending_requests'] ?? 0),
    'total_non_returned_books' => (int)($row['total_non_returned_books'] ?? 0),
    'total_issued_books' => (int)($row['total_issued_books'] ?? 0),
    'total_suspended' => (int)($row['total_suspended'] ?? 0)
]);
$conn->close();
?>