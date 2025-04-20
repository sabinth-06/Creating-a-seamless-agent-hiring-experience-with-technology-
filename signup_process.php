<?php
session_start();
require_once 'config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_raw = $_POST['password'];

    // Basic empty check
    if (empty($username) || empty($email) || empty($password_raw)) {
        header("Location: signup.html?error=empty");
        exit();
    }

    // Check if username or email already exists
    $check_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $check_stmt->close();
        $conn->close();
        header("Location: signup.html?error=exists");
        exit();
    }
    $check_stmt->close();

    // Hash the password
    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    // Insert new user
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $conn->close();
        header("Location: signup.html?error=db");
        exit();
    }

    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: login.html?signup=success");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: signup.html?error=failed");
        exit();
    }
}
?>
