<?php
include '../../fetch_admins.php';

session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
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
                    <a href="../teacherfunctions/displayteachers.php">
                        <span class="material-symbols-outlined"> person </span>Teachers</a>
                </li>
                <li>
                    <a href="displayadmins.php">
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
                </li>>
            </ul>
            <a href="profile.html" class="user-account-link">
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
            <h1 style="text-align: center;">List of Admins</h1>
            <a href="addadmin.php"
                style="margin-bottom: 20px; padding: 10px 20px; font-size: 16px; color: white; background-color: #007BFF; text-decoration: none; border: none; border-radius: 5px; cursor: pointer; display: inline-block;">
                Add New Admin
            </a>
            <label for="excelFile"
                style="padding: 10px 20px; font-size: 16px; color: white; background-color: #28A745; text-decoration: none; border: none; border-radius: 5px; cursor: pointer; display: inline-block;">
                Excel Import
            </label>
            <input type="file" id="excelFile" name="excelFile" accept=".xls,.xlsx" style="display: none;"
                onchange="handleFileUpload(event)">

            <table id="adminTable" class="table table-striped smaller-table" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fullname</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>Sex</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $count = 1;
                        while ($row = $result->fetch_assoc()) {
                            $fullname = $row['fname'] . " " . $row['mname'] . " " . $row['lname'] . " " . $row['ename'];
                            echo "<tr>
                            <th scope='row'>{$count}</th>
                            <td>{$fullname}</td>
                            <td>{$row['age']}</td>
                            <td>{$row['address']}</td>
                            <td>{$row['sex']}</td>
                          </tr>";
                            $count++;
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center;'>No admins found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

        </div>
    </div>

    <script src="../../scripts/excelupload.js"></script>
    <script src="../../scripts/showadmins.js"></script>

</body>

</html>