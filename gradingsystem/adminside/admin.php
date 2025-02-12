<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();


}

// If the check passes, the user is an admin and logged in
// Continue with your admin page content below...
?>




<?php include '../styles/hui.php' ?>
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="../styles/style1.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
<script src="../scripts/confirmlogout.js"></script>


<body>
    <div class="container-fluid">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../images/logo.jpg" alt="logo" />
                <div class="header-text">
                    <h2 class="dashboard-title">
                        <a href="admin.php" class="dashboard-link">
                            <span class="admin-text">Admin</span>
                            <span class="dashboard-text">Dashboard</span>
                        </a>
                    </h2>
                </div>
            </div>
            <ul class="sidebar-links">
                <li>
                    <a href="teacherfunctions/displayteachers.php">
                        <span class="material-symbols-outlined"> person </span>Teachers</a>
                </li>
                <li>
                    <a href="adminfunctions/displayadmins.php">
                        <span class="material-symbols-outlined"> person </span>Admin</a>
                </li>
                <li>
                    <a href="studentfunctions/displaystudents.php">
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
                    <a href="../logout.php" onclick="return confirmLogout()">
                        <span class="material-symbols-outlined">logout</span>Logout
                    </a>
                </li>

            </ul>
            <a href="adminprofile.php" class="user-account-link">
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

        </div>
    </div>

    <script>
        // Listen for storage events (Detect login/logout changes in other tabs)
        window.addEventListener('storage', function(event) {
            if (event.key === 'loginTrigger') {
                window.location.reload(); // Refresh page when login detected in another tab
            }
            if (event.key === 'isLoggedOut' && event.newValue === 'true') {
                window.location.href = '../login.php'; // Redirect to login page if logged out
            }
        });
    </script>
    <script src="../scripts/checkSession.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>