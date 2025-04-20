<?php
session_start();
require_once __DIR__ . '/config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $mc_type = mysqli_real_escape_string($conn, $_POST['mc_type']);
    $owner_name = mysqli_real_escape_string($conn, $_POST['owner_name']);
    $father_name = mysqli_real_escape_string($conn, $_POST['father_name']);
    $mobile_no = mysqli_real_escape_string($conn, $_POST['mobile_no']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $floor = mysqli_real_escape_string($conn, $_POST['floor']);
    $part = mysqli_real_escape_string($conn, $_POST['part']);
    $length = mysqli_real_escape_string($conn, $_POST['length']);
    $width = mysqli_real_escape_string($conn, $_POST['width']);
    $property_use = mysqli_real_escape_string($conn, $_POST['property_use']);
    $occupancy = mysqli_real_escape_string($conn, $_POST['occupancy']);

    // Prepare SQL statement
    $sql = "INSERT INTO property_records (mc_type, owner_name, father_name, mobile_no, location, address, 
            floor, part, length, width, property_use, occupancy) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssddss", 
        $mc_type, $owner_name, $father_name, $mobile_no, $location, $address,
        $floor, $part, $length, $width, $property_use, $occupancy
    );

    if ($stmt->execute()) {
        // Success message with styling
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Property record added successfully!',
            'style' => 'background: linear-gradient(to right, #34d399, #059669);
                       color: white;
                       padding: 1rem 1.5rem;
                       border-radius: 8px;
                       box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                       position: fixed;
                       top: 20px;
                       right: 20px;
                       z-index: 1000;
                       animation: slideInRight 0.5s ease-out forwards;'
        ];
        header("Location: add_property.php");
        exit();
    } else {
        // Error message
        $_SESSION['error_message'] = "Error: " . $stmt->error;
        header("Location: add_property.php");
        exit();
    }

    $stmt->close();
} else {
    header("Location: add_property.php");
    exit();
}

$conn->close();
?>