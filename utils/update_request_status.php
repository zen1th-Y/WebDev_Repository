<?php
header('Content-Type: application/json');
$host = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$request_id = $data['request_id'];
$status = $data['status'];

$stmt = $conn->prepare("UPDATE request SET status=? WHERE request_id=?");
$stmt->bind_param("si", $status, $request_id);
$stmt->execute();
updatePendingRequestsCount($conn);
echo json_encode(['success' => true]);

function updatePendingRequestsCount($conn) {
    $result = $conn->query("SELECT COUNT(*) AS cnt FROM request WHERE status = 'pending'");
    $row = $result->fetch_assoc();
    $total = (int)$row['cnt'];
    // If count_items table is empty, insert a row
    $conn->query("INSERT INTO count_items (pending_requests) SELECT $total WHERE NOT EXISTS (SELECT 1 FROM count_items)");
    // Otherwise, update the row
    $conn->query("UPDATE count_items SET pending_requests = $total");
}
?>