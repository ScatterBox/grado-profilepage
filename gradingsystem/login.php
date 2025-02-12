<?php include 'styles/hui.php'; ?>
<link rel="stylesheet" href="styles/ls.css" />

<?php
include('conn.php');
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: adminside/admin.php");
        exit();
    } elseif ($_SESSION['role'] == 'student') {
        header("Location: studentside/student.php");
        exit();
    } elseif ($_SESSION['role'] == 'teacher') {
        header("Location: teacherside/teacher.php");
        exit();
    }
}

$errorMsg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameOrEmail = $_POST['uname'];
    $password = $_POST['psw'];

    // Function to check user in a specific table
    function check_user($conn, $table, $usernameOrEmail, $password)
    {
        $sql = "SELECT * FROM $table WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param('ss', $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // ✅ Compare passwords as plain text (⚠️ NOT RECOMMENDED)
            if ($password == $user['password']) {
                return $user;
            }
        }
        return false;
    }


    // Check in admins table
    $user = check_user($conn, 'admins', $usernameOrEmail, $password);
    if ($user) {
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = 'admin';
        session_regenerate_id();
        echo "<script>window.location.href = 'adminside/admin.php';</script>";
        exit();
    }

    // Check in students table
    $user = check_user($conn, 'students', $usernameOrEmail, $password);
    if ($user) {
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = 'student';
        session_regenerate_id();
        echo "<script>window.location.href = 'studentside/student.php';</script>";
        exit();
    }

    // Check in teachers table
    $user = check_user($conn, 'teachers', $usernameOrEmail, $password);
    if ($user) {
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = 'teacher';
        session_regenerate_id();
        echo "<script>window.location.href = 'teacherside/teacher.php';</script>";
        exit();
    }

    // If no match found
    $errorMsg = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>

<body>
    <div id="login" class="login d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 d-flex align-items-center justify-content-center">
                    <form action="login.php" method="post" class="form_container">
                        <div class="shesh mb-4">
                            <img src="/gradingsystem/images/logo.jpg" alt="">
                            <p class="h2 fw-bold">FGSNHS</p>
                        </div>
                        <div class="form-group mb-2">
                            <label for="uname"><b>Username or Email</b></label>
                            <input type="text" class="form-control" placeholder="Enter Username or Email" name="uname"
                                required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="psw"><b>Password</b></label>
                            <input type="password" class="form-control" placeholder="Enter Password" name="psw"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Login</button>
                        <div class="mt-3">
                            <a href="forgot_password.php">Forgot Password?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var errorMsg = "<?php echo $errorMsg; ?>";
            if (errorMsg) {
                alert(errorMsg);
                document.getElementsByName('uname')[0].value = '';
                document.getElementsByName('psw')[0].value = '';
            }

            // Check if the user is already logged in on page load
            if (localStorage.getItem('isLoggedIn') === 'true') {
                var role = localStorage.getItem('role');
                if (role === 'admin') {
                    window.location.href = 'adminside/admin.php';
                } else if (role === 'student') {
                    window.location.href = 'studentside/student.php';
                } else if (role === 'teacher') {
                    window.location.href = 'teacherside/teacher.php';
                }
            }

            // Listen for storage events (detect login/logout changes in other tabs)
            window.addEventListener('storage', function (event) {
                if (event.key === 'isLoggedIn' && event.newValue === 'true') {
                    location.reload(); // Refresh page when logged in from another tab
                }
                if (event.key === 'isLoggedOut' && event.newValue === 'true') {
                    window.location.href = 'login.php';
                }
            });

            // Mark login timestamp to notify other tabs
            if (localStorage.getItem('isLoggedIn') === 'true') {
                localStorage.setItem('lastLogin', Date.now());
            }

            // Detect login from other tabs
            window.addEventListener('storage', function (event) {
                if (event.key === 'lastLogin') {
                    location.reload(); // Refresh the page in other tabs
                }
            });
        });    
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>