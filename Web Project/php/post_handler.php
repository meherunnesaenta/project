<?php
session_start();
include 'connect.php'; // Ensure this includes your database connection logic

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit();
}

$userId = $_SESSION['user_id'];

// Handle Post Creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_post') {
    $content = trim($_POST['content']);
    $imagePath = null;

    // Handle image upload
    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $filename = basename($_FILES['post_image']['name']);
        $safeFilename = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $filename);
        $imagePath = $targetDir . $safeFilename;

        // Validate image type and size
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($_FILES['post_image']['type'], $allowedTypes) || $_FILES['post_image']['size'] > $maxSize) {
            echo json_encode(["success" => false, "message" => "Invalid image type or size."]);
            exit();
        }

        if (!move_uploaded_file($_FILES['post_image']['tmp_name'], $imagePath)) {
            echo json_encode(["success" => false, "message" => "Failed to upload image."]);
            exit();
        }
    }

    // Insert post into database
    $query = "INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $userId, $content, $imagePath);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Post created successfully."]);
    } else {
        error_log("Error creating post: " . $stmt->error);
        echo json_encode(["success" => false, "message" => "Error creating post."]);
    }
    exit();
}

// Handle Fetching Posts
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'fetch_posts') {
    $query = "SELECT content, image, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }

    echo json_encode($posts);
    exit();
}

// Default response for unsupported requests
echo json_encode(["success" => false, "message" => "Invalid request."]);
?>
