<?php
session_start();
require_once 'conn.php'; // Database connection

// ✅ Ensure user is logged in
if (!isset($_SESSION['user']['user_id']) || !isset($_SESSION['user']['role'])) {
    echo "Session error: User not logged in or session expired.";
    exit();
}

$user_id = $_SESSION['user']['user_id'];
$role = $_SESSION['user']['role']; // Get user role (admin, teacher, student)

// ✅ Define upload directory
$target_dir = "adminside/uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0775, true);
}

// ✅ Validate and process file
$imageFileType = strtolower(pathinfo($_FILES["profile_img"]["name"], PATHINFO_EXTENSION));
$new_filename = "{$role}_" . $user_id . "_" . time() . "." . $imageFileType;
$target_file = $target_dir . $new_filename;

// ✅ Allow only JPG, JPEG, PNG
if (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
    echo "Invalid file type.";
    exit();
}

// ✅ Move file and update DB
if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file)) {
    // ✅ Determine the correct table based on role
    $table = ($role === 'admin') ? 'admins' : (($role === 'teacher') ? 'teachers' : 'students');

    // ✅ Update the image in the correct table
    $sql = "UPDATE $table SET img = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_filename, $user_id);

    if ($stmt->execute()) {
        $_SESSION['user']['img'] = $new_filename; // ✅ Update session with new image
        echo $new_filename; // ✅ Return filename for JavaScript update
    } else {
        echo "Database update failed.";
    }

    $stmt->close();
} else {
    echo "File upload failed.";
}

$conn->close();
?>
