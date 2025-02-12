<?php
session_start();
if ($_SESSION['role'] !== 'teacher') {
    header("Location: ../../login.php");
    exit();
}

$loggedInUserId = $_SESSION['user_id']; // Assuming user_id is stored in session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gradingsystem";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $subject_id = $_POST['subject_id'];

        // Prepare delete query
        $sql = "DELETE FROM subjects WHERE subject_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $subject_id);
        $success = $stmt->execute();
        $stmt->close();

        // Send plain text response
        if ($success) {
            echo "Success: Class deleted successfully.";
        } else {
            echo "Error: Failed to delete class.";
        }
        exit();
    }
}

$created_by = $_SESSION['user']['user_id'];
$sql = "SELECT subject_id, subject_name, year_level, section, created_by, 
               (SELECT CONCAT(fname, ' ', mname, ' ', lname, ' ', ename) FROM teachers WHERE user_id = created_by) AS created_by_fullname 
        FROM subjects 
        WHERE created_by = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $created_by);
$stmt->execute();
$result = $stmt->get_result();
$classes = [];

while ($row = $result->fetch_assoc()) {
    $classes[] = $row;
}
$stmt->close();
$conn->close();
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
    <link rel="stylesheet" href="../ttablestyles/style.css">
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
                        <a href="../teacher.php" class="dashboard-link">
                            <span class="admin-text">Teacher</span>
                            <span class="dashboard-text">Dashboard</span>
                        </a>
                    </h2>
                </div>
            </div>
            <ul class="sidebar-links">
                <li>
                    <a href="classcreate.php">
                        <span class="material-symbols-outlined"> person </span>Create Class</a>
                </li>
                <li>
                    <a href="classlist.php">
                        <span class="material-symbols-outlined"> person </span>Subjects List</a>
                </li>
                <li>
                    <a href="mystudents.php">
                        <span class="material-symbols-outlined"> person </span>My Students</a>
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
                            <span>Profile</span>
                        </div>
                    </div>
                </div>
            </a>

        </aside>

        <!-- Main content -->
        <div class="col-md-9" id="mainContent">
            <h1>My Subejcts</h1>
            <table id="classTable" class="table table-striped smaller-table" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subject Name</th>
                        <th>Year Level</th>
                        <th>Section</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $index => $class): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($class['subject_name']) ?></td>
                            <td><?= htmlspecialchars($class['year_level']) ?></td>
                            <td><?= htmlspecialchars($class['section']) ?></td>
                            <td><?= htmlspecialchars($class['created_by_fullname']) ?></td>
                            <td>
                                <a class="btn btn-info view-btn"
                                    href="classsubject.php?year_level=<?= urlencode($class['year_level']) ?>&section=<?= urlencode($class['section']) ?>&subject=<?= urlencode($class['subject_name']) ?>">View</a>
                                <button class="btn btn-danger delete-btn"
                                    data-id="<?= $class['subject_id'] ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function () {
    $('#classTable').DataTable();

    $('.delete-btn').click(function () {
        var subjectId = $(this).data('id');

        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the subject and cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, keep it"
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('', { action: 'delete', subject_id: subjectId }, function (response) {
                    if (response.includes("Success")) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.substring(8) // Remove "Success: " from response
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.substring(6) // Remove "Error: " from response
                        });
                    }
                });
            }
        });
    });
});

    </script>
</body>

</html>