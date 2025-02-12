<?php
session_start();
include 'connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: register.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Handle Profile Picture Upload
if (isset($_FILES['profile_pic'])) {
    $targetDir = "../image/";
    $targetFile = $targetDir . basename($_FILES['profile_pic']['name']);

    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)) {
        $query = "UPDATE users SET profile_pic = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $targetFile, $userId);
        $stmt->execute();

        echo json_encode([
            "success" => true,
            "message" => "Profile picture updated successfully.",
            "profile_pic" => $targetFile // Include the full path of the image
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Error uploading file."]);
    }
    exit();
}

// Handle Profile Information Update
if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    $query = "UPDATE users SET name = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $name, $phone, $userId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Profile updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error updating profile: " . $conn->error]);
    }
    exit();
}






// Handle Fetching Profile Information and Profile Picture
if (isset($_GET['action']) && $_GET['action'] === 'fetch_profile') {
    $query = "SELECT name, phone, profile_pic FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Return user data including profile picture path
        echo json_encode([
            "name" => $row['name'],
            "phone" => $row['phone'],
            "profile_pic" => $row['profile_pic'] ?? "../image/edu.jpg" // Default image if none exists
        ]);
    } else {
        echo json_encode(["error" => "User not found."]);
    }
    exit();
}

// Default Response for Unsupported Requests
echo json_encode(["success" => false, "message" => "Invalid request."]);
?>
