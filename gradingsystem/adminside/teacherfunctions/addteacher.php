<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
// If the check passes, the user is an admin and logged in
// Continue with your admin page content below...
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'gradingsystem';
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST["fname"];
    $mname = $_POST["mname"];
    $lname = $_POST["lname"];
    $ename = $_POST["ename"];
    $nickname = $_POST["nickname"];
    $age = $_POST["age"];
    $sex = $_POST["sex"];
    $birthdate = $_POST["birthdate"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    $checkSql = "SELECT * FROM teachers WHERE username='$username'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Error: A user with the same username already exists.']);
    } else {
        $checkSql = "SELECT * FROM teachers WHERE fname='$fname' AND mname='$mname' AND lname='$lname'";
        if ($ename) {
            $checkSql .= " AND ename='$ename'";
        }
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Error: A user with the same full name already exists.']);
        } else {
            $sql = "INSERT INTO teachers (fname, mname, lname, ename, nickname, age, sex, birthdate, address, email, username, password)
                    VALUES ('$fname', '$mname', '$lname', '$ename', '$nickname', '$age', '$sex', '$birthdate', '$address', '$email', '$username', '$password')";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(['success' => true, 'message' => 'New record created successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
            }
        }
    }
    $conn->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Florentino Galang Sr. National High School Grading System</title>

    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

    <!-- Include Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Include DataTables CSS -->
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.css" rel="stylesheet">

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.js"></script>

    <!-- Include SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Include Material Icons CSS -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <script src="../../scripts/confirmlogout.js"></script>


    <link rel="stylesheet" href="../../styles/style1.css">
    <link rel="stylesheet" href="../tablestyles/style.css">
    <!-- Favicon -->
    <link rel="icon" href="logo.jpg">
</head>

<body>
    <div class="container-fluid">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../../images/logo.jpg" alt="logo" />
                <div class="header-text">
                    <h2 class="dashboard-title">
                        <a href="../admin.php" class="dashboard-link">
                            <span class="admin-text">Admin</span>
                            <span class="dashboard-text">Dashboard</span>
                        </a>
                    </h2>
                </div>
            </div>
            <ul class="sidebar-links">
                <li>
                    <a href="displayteachers.php">
                        <span class="material-symbols-outlined"> person </span>Teachers</a>
                </li>
                <li>
                    <a href="../adminfunctions/displayadmins.php">
                        <span class="material-symbols-outlined"> person </span>Admin</a>
                </li>
                <li>
                    <a href="../studentfunctions/displaystudents.php">
                        <span class="material-symbols-outlined">
                            Person
                        </span>
                        Students</a>
                </li>

                <h4>
                    <span>Account</span>
                    <div class="menu-separator"></div>
                </h4>
                <li>
                    <a href="../../logout.php" onclick="return confirmLogout()">
                        <span class="material-symbols-outlined">logout</span>Logout
                    </a>
                </li>
            </ul>
            <a href="profile.php" class="user-account-link">
                <div class="user-account">
                    <div class="user-profile">
                        <?php
                        $userImg = !empty($_SESSION['user']['img']) ? '../images/' . $_SESSION['user']['img'] : '../images/placeholder.png';
                        ?>
                        <img src="<?php echo $userImg; ?>" alt="Profile Image" />
                        <div class="user-detail">
                            <h3><?php echo $_SESSION['user']['nickname']; ?></h3>
                            <span>Admin</span>
                        </div>
                    </div>
                </div>
            </a>

        </aside>

        <!-- Main content -->
        <div class="col-md-9" id="mainContent">
            <h3>Teacher Submitter Form</h3>
            <div class="registration-form">
                <form id="teacherForm">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fname">First Name:</label>
                                <input type="text" class="form-control item" id="fname" name="fname"
                                    placeholder="Example: Juan" style="text-transform: capitalize;" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mname">Middle Name:</label>
                                <input type="text" class="form-control item" id="mname" name="mname"
                                    placeholder="Example: Dela" style="text-transform: capitalize;" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lname">Last Name:</label>
                                <input type="text" class="form-control item" id="lname" name="lname"
                                    placeholder="Example: Crunchy" style="text-transform: capitalize;" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ename">Extension Name:</label>
                                <select class="form-control item" id="ename" name="ename">
                                    <option value="" disabled selected>Select Extension Name</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                    <option value="V">V</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nickname">Display name:</label>
                                <input type="text" class="form-control item" id="nickname" name="nickname"
                                    placeholder="Example: Tutu" style="text-transform: capitalize;" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="birthdate">Birthdate:</label>
                                <input type="date" class="form-control item" id="birthdate" name="birthdate" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="age">Age:</label>
                                <input type="number" class="form-control item" id="age" name="age"
                                    placeholder="Example: 12" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sex">Sex:</label>
                                <select class="form-control item" id="sex" name="sex" required>
                                    <option value="" disabled selected>Select Sex</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input type="text" class="form-control item" id="address" name="address"
                                    placeholder="Example: Purok. Pinetree, Brgy. Oringao, Kabankalan City, Negros Occidental."
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control item" id="username" name="username"
                                    placeholder="Example: @JuanFGSNHS" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control item" id="email" name="email"
                                    placeholder="Example: juan@example.com" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control item" id="password" name="password"
                                    placeholder="" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">Create Account</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('teacherForm').addEventListener('submit', function (event) {
            event.preventDefault();
            Swal.fire({
                title: 'Is the information correct?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit it!',
                cancelButtonText: 'No, review it'
            }).then((result) => {
                if (result.isConfirmed) {
                    var formData = new FormData(this);
                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Success!', data.message, 'success').then(() => {
                                    document.getElementById('teacherForm').reset();
                                });
                            } else {
                                Swal.fire('Error!', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        });
                }
            });
        });
    </script>
    <script src="../../scripts/birthscript.js"></script>
</body>

</html>