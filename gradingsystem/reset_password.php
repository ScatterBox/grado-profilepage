<?php
include('conn.php');
session_start();

$message = "";

// Check if the user is authorized to reset the password
if (!isset($_SESSION['reset_user_id']) || !isset($_SESSION['reset_user_table'])) {
    header("Location: forgot_password.php"); // Redirect if not coming from forgot password page
    exit();
}

$user_id = $_SESSION['reset_user_id'];
$user_table = $_SESSION['reset_user_table'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['password']; // Store as plain text (⚠️ NOT RECOMMENDED)

    // Update the password in the correct user table
    $stmt = $conn->prepare("UPDATE $user_table SET password = ? WHERE user_id = ?");
    $stmt->bind_param("si", $new_password, $user_id);
    $stmt->execute();

    // Clear session data after resetting password
    unset($_SESSION['reset_user_id']);
    unset($_SESSION['reset_user_table']);

    $message = "Password reset successfully!";
    $success = true; // Set success flag for SweetAlert
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/ls.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert Library -->
</head>
<body>
    <div id="reset_password" class="reset_password d-flex align-items-center justify-content-center vh-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 d-flex align-items-center justify-content-center">
                    <form action="reset_password.php" method="post" class="form_container p-4 shadow-lg rounded bg-white">
                        <div class="shesh mb-4 text-center">
                            <img src="/gradingsystem/images/logo.jpg" alt="Logo" class="mb-3" style="width: 80px;">
                            <p class="h2 fw-bold">FGSNHS</p>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password"><b>New Password</b></label>
                            <input type="password" class="form-control" placeholder="Enter new password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Password</button>
                        <div class="mt-3 text-center">
                            <a href="login.php">Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php if (!empty($success)): ?>
    <script>
        Swal.fire({
            title: "Success!",
            text: "Your password has been reset successfully. You can now log in with your new password.",
            icon: "success",
            confirmButtonText: "Go to Login"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "login.php"; // Redirect to login page after password reset
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
