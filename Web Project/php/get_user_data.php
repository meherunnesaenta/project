<?php
// Include database connection
include('connect.php');

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$userId = $_SESSION['user_id'];

$query = "SELECT name, email FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user); // Return the user's data
    } else {
        echo json_encode(["error" => "User not found"]);
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "Failed to prepare statement"]);
}

$conn->close();
?>
