<?php 
include('conn.php');
session_start();

$message = "";
$foundUser = false; // Variable to track if user was found
$userName = ""; // Variable to store the user's full name

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST['email_or_username']; // Allow both email & username

    // Search in all user tables
    $tables = ['students', 'teachers', 'admins'];
    $user = null;
    $tableFound = null;

    foreach ($tables as $table) {
        $stmt = $conn->prepare("SELECT user_id, fname, mname, lname, ename FROM $table WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $input, $input);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $tableFound = $table;
            break;
        }
    }

    if ($user) {
        $_SESSION['reset_user_id'] = $user['user_id'];
        $_SESSION['reset_user_table'] = $tableFound;

        // Construct the user's full name
        $userName = $user['fname'];
        if (!empty($user['mname'])) {
            $userName .= " " . $user['mname'];
        }
        $userName .= " " . $user['lname'];
        if (!empty($user['ename'])) {
            $userName .= " " . $user['ename'];
        }

        $foundUser = true; // User found, trigger Swal alert
    } else {
        $message = "No account found with that email or username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/ls.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert Library -->
</head>
<body>
    <div id="forgot_password" class="forgot_password d-flex align-items-center justify-content-center vh-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 d-flex align-items-center justify-content-center">
                    <form action="forgot_password.php" method="post" class="form_container p-4 shadow-lg rounded bg-white">
                        <div class="shesh mb-4 text-center">
                            <img src="/gradingsystem/images/logo.jpg" alt="Logo" class="mb-3" style="width: 80px;">
                            <p class="h2 fw-bold">FGSNHS</p>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email_or_username"><b>Enter Email or Username</b></label>
                            <input type="text" class="form-control" placeholder="Enter your Email or Username" name="email_or_username" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Confirm</button>
                        <div class="mt-3 text-center">
                            <a href="login.php">Back to Login</a>
                        </div>
                        <?php if ($message): ?>
                            <div class="alert alert-danger mt-3 text-center"><?php echo $message; ?></div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php if ($foundUser): ?>
    <script>
       Swal.fire({
            title: "Account Found!",
            text: "Is this you, " + "<?php echo $userName; ?>?" + "\nDo you want to proceed to reset your password?",
            icon: "success",
            showCancelButton: true,
            confirmButtonText: "Yes! Proceed",
            cancelButtonText: "No, it's not me."
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "reset_password.php"; // Redirect if "Yes! Proceed" is clicked
            } else {
                // If "No, it's not me" is clicked, clear the form and prevent resubmission
                document.querySelector('input[name="email_or_username"]').value = '';
                history.pushState(null, null, 'forgot_password.php'); // Change URL to prevent form resubmission
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>