<?php
session_start();
require_once 'conn.php'; // Adjust the path to your config file

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['user_id']) || !isset($data['new_username'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required data']);
        exit();
    }

    $user_id = $data['user_id'];
    $new_username = trim($data['new_username']);
    
    // Validate username
    if (empty($new_username)) {
        echo json_encode(['success' => false, 'message' => 'Username cannot be empty']);
        exit();
    }

    if (strlen($new_username) < 3 || strlen($new_username) > 50) {
        echo json_encode(['success' => false, 'message' => 'Username must be between 3 and 50 characters']);
        exit();
    }

    $role = $_SESSION['user']['role'];

    // Determine the table based on the user's role
    switch ($role) {
        case 'admin':
            $table = 'users'; 
            break;
        case 'teacher':
            $table = 'teachers';
            break;
        case 'student':
            $table = 'students';
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid role']);
            exit();
    }

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
        exit();
    }

    // Check if username already exists
    $check_stmt = $conn->prepare("SELECT user_id FROM $table WHERE username = ? AND user_id != ?");
    $check_stmt->bind_param("si", $new_username, $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
        $check_stmt->close();
        exit();
    }
    $check_stmt->close();

    // Update the username
    $sql = "UPDATE $table SET username = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_username, $user_id);
    
    if ($stmt->execute()) {
        // Update the session variable
        $_SESSION['user']['username'] = $new_username;
        echo json_encode([
            'success' => true,
            'message' => 'Username updated successfully',
            'new_username' => $new_username
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update username',
            'error' => $conn->error
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>