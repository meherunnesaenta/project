<?php
// Database configuration
$host = 'localhost';
$user = 'root'; // Update with your database username
$password = ''; // Update with your database password
$database = 'hand2help'; // Update with your database name

// Connect to the database
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve form data
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $donationType = $conn->real_escape_string($_POST['donationType']);
    $amount = isset($_POST['amount']) && !empty($_POST['amount']) ? $conn->real_escape_string($_POST['amount']) : 0;

    // Insert the data into the database
    $sql = "INSERT INTO donations (name, email, phone, donation_type, amount) 
            VALUES ('$name', '$email', '$phone', '$donationType', '$amount')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
    exit();
}

// Handle data retrieval for "Your Impact" section (GET request)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');

    // Retrieve data and order by the latest donation (based on ID, DESC)
    $sql = "SELECT donation_type, COUNT(*) AS count FROM donations GROUP BY donation_type ORDER BY id DESC";
    $result = $conn->query($sql);

    $impactData = [];
    while ($row = $result->fetch_assoc()) {
        $impactData[] = [
            'type' => htmlspecialchars($row['donation_type']),
            'count' => htmlspecialchars($row['count'])
        ];
    }

    echo json_encode($impactData);
    exit();
}

// Close the connection
$conn->close();
?>
