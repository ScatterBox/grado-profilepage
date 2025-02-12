<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'gradingsystem';
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student data from the database
$sql = "SELECT s.user_id, s.fname, s.mname, s.lname, s.ename, s.age, s.sex, s.birthdate, s.address, 
               s.year_level, s.section, s.email, s.lrn, 
               GROUP_CONCAT(sb.subject_name SEPARATOR ', ') AS subjects 
        FROM students s
        LEFT JOIN student_subjects ss ON s.user_id = ss.student_id
        LEFT JOIN subjects sb ON ss.subject_id = sb.subject_id AND sb.year_level = s.year_level AND sb.section = s.section
        GROUP BY s.user_id";

$result = $conn->query($sql);
$students = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = [
            'fullname' => $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'] . ' ' . $row['ename'],
            'age' => $row['age'],
            'sex' => $row['sex'],
            'birthdate' => $row['birthdate'],
            'address' => $row['address'],
            'year_level' => $row['year_level'],
            'section' => $row['section'],
            'subjects' => $row['subjects'] ?: 'No subjects',
            'email' => $row['email'],
            'lrn' => $row['lrn']
        ];
    }
}
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
            <h1 style="text-align: center;">List of Students</h1>
            <a href="addstudents.php"
                style="margin-bottom: 20px; padding: 10px 20px; font-size: 16px; color: white; background-color: #007BFF; text-decoration: none; border: none; border-radius: 5px; cursor: pointer; display: inline-block;">
                Add New Student
            </a>
            <label for="excelFile"
                style="padding: 10px 20px; font-size: 16px; color: white; background-color: #28A745; text-decoration: none; border: none; border-radius: 5px; cursor: pointer; display: inline-block;">
                Excel Import
            </label>
            <input type="file" id="excelFile" name="excelFile" accept=".xls,.xlsx" style="display: none;"
                onchange="handleFileUpload(event)">
            <table id="studentTable" class="table table-striped smaller-table" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Age</th>
                        <th scope="col">Sex</th>
                        <th scope="col">Birthdate</th>
                        <th scope="col">Address</th>
                        <th scope="col">Year Level</th>
                        <th scope="col">Section</th>
                        <th scope="col">Subjects</th>
                        <th scope="col">Email</th>
                        <th scope="col">LRN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $index => $student): ?>
                        <tr>
                            <th scope="row"><?php echo $index + 1; ?></th>
                            <td><?php echo htmlspecialchars($student['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($student['age']); ?></td>
                            <td><?php echo htmlspecialchars($student['sex']); ?></td>
                            <td><?php echo htmlspecialchars($student['birthdate']); ?></td>
                            <td><?php echo htmlspecialchars($student['address']); ?></td>
                            <td><?php echo htmlspecialchars($student['year_level']); ?></td>
                            <td><?php echo htmlspecialchars($student['section']); ?></td>
                            <td><?php echo htmlspecialchars($student['subjects']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td><?php echo htmlspecialchars($student['lrn']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#studentTable').DataTable();
        });
    </script>

    <script src="../../scripts/excelupload.js"></script>
</body>

</html>