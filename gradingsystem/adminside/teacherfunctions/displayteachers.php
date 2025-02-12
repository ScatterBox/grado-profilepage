<?php
include('../../conn.php'); // Include your database connection file
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// If the check passes, the user is an admin and logged in
// Continue with your admin page content below...
// Fetch the faculty data from the database
$sql = "SELECT t.user_id, t.fname, t.mname, t.lname, t.ename, t.age, t.sex, t.address, 
               GROUP_CONCAT(s.subject_name SEPARATOR ', ') AS subjects 
        FROM teachers t
        LEFT JOIN subjects s ON t.user_id = s.created_by
        GROUP BY t.user_id";

$result = $conn->query($sql);

$teachers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
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


    <!-- Include custom styles -->
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
            <h1 style="text-align: center;">List of Teachers</h1>
            <a href="addteacher.php"
                style="margin-bottom: 20px; padding: 10px 20px; font-size: 16px; color: white; background-color: #007BFF; text-decoration: none; border: none; border-radius: 5px; cursor: pointer; display: inline-block;">
                Add New Teacher
            </a>
            <label for="excelFile"
                style="padding: 10px 20px; font-size: 16px; color: white; background-color: #28A745; text-decoration: none; border: none; border-radius: 5px; cursor: pointer; display: inline-block;">
                Excel Import
            </label>
            <input type="file" id="excelFile" name="excelFile" accept=".xls,.xlsx" style="display: none;"
                onchange="handleFileUpload(event)">
            <table id="teacherTable" class="table table-striped smaller-table" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fullname</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>Sex</th>
                        <th>Subjects</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teachers as $index => $teacher): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo "{$teacher['fname']} {$teacher['mname']} {$teacher['lname']} {$teacher['ename']}"; ?>
                            </td>
                            <td><?php echo $teacher['age']; ?></td>
                            <td><?php echo $teacher['address']; ?></td>
                            <td><?php echo $teacher['sex']; ?></td>
                            <td><?php echo $teacher['subjects'] ?: 'No subjects'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#teacherTable').DataTable();
        });
    </script>
    <script src="../../scripts/excelupload.js"></script>

</body>

</html>