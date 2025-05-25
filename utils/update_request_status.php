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
echo json_encode(['success' => true]);
?>