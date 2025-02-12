<?php
require_once 'connect.php';

if (isset($_POST['signUp'])) {
    // Retrieve form data
    $name = $_POST['fName'] . ' ' . $_POST['lName']; // Combine first and last name
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Insert user into the database
    $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $name, $email, $password);

    if ($stmt->execute()) {
        header("Location: ../html/volenteer.html?message=Registered successfully");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

if (isset($_POST['signIn'])) {
    // Retrieve login form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the database for the user
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: ../html/volindex.html");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found!";
    }
}

?>
