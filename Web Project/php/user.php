<?php
// Include database connection
include 'connect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the correct action is being called
if (isset($_GET['action']) && $_GET['action'] === 'fetch_users') {
    // Prepare the query to fetch users
    $query = "SELECT id, name, email, profile_pic FROM users";
    $result = $conn->query($query);

    // Check for query execution errors
    if (!$result) {
        http_response_code(500);
        echo json_encode(["error" => "Database query failed: " . $conn->error]);
        exit;
    }

    // Fetch users and prepare JSON response
    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    // Return user data as JSON
    header("Content-Type: application/json");
    echo json_encode($users);
    exit;
} else {
    // Handle incorrect or missing action parameter
    http_response_code(400);
    echo json_encode(["error" => "Invalid or missing 'action' parameter."]);
    exit;
}
?>
