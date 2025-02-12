<?php
session_start();
if ($_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
?>

<?php include 'styles/hui.php' ?>
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="styles/style.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />


<body>
    <div class="container-fluid">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="logo.jpg" alt="logo" />
                <div class="header-text">
                    <h2>Student</h2>
                    <h2>Dashboard</h2>
                </div>
            </div>
            <ul class="sidebar-links">
                <h4>
                    <span>Manage Info</span>
                    <div class="menu-separator"></div>
                </h4>
                <li>
                    <a href="#" onclick="showManageClass()">
                        <span class="material-symbols-outlined">
                            person
                        </span>
                        View Grades</a>
                </li>
                <li>
                <h4>
                    <span>Account</span>
                    <div class="menu-separator"></div>
                </h4> 
                <li>
                    <a href="logout.php" onclick="return confirmLogout()"><span class="material-symbols-outlined">
                            logout </span>Logout</a>
                </li>
            </ul>
            <div class="user-account">
                <div class="user-profile">
                <img src="<?php echo './images/' . $_SESSION['user']['img']; ?>" alt="Profile Image" />
                    <div class="user-detail">
                        <h3><?php echo $_SESSION['user']['nickname']; ?></h3>
                        <span><?php echo ucfirst($_SESSION['user']['role']); ?></span>
                    </div>
                </div>
            </div>

        </aside>

        <!-- Main content -->
        <div class="col-md-9" id="mainContent">

        </div>
    </div>

    <script>

        function confirmLogout() {
            var r = confirm("Are you sure you want to logout?");
            if (r == true) {
                // User confirmed they want to logout
                return true;
            } else {
                // User cancelled the logout operation
                return false;
            }
        }

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>