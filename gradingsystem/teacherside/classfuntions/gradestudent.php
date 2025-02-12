<?php
session_start();
if ($_SESSION['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit();
}

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'gradingsystem';
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get student details from URL parameters
$user_id = isset($_GET['user_id']) ? htmlspecialchars($_GET['user_id']) : '';
$fname = isset($_GET['fname']) ? htmlspecialchars($_GET['fname']) : '';
$mname = isset($_GET['mname']) ? htmlspecialchars($_GET['mname']) : '';
$lname = isset($_GET['lname']) ? htmlspecialchars($_GET['lname']) : '';
$ename = isset($_GET['ename']) ? htmlspecialchars($_GET['ename']) : ''; // Optional
$section = isset($_GET['section']) ? htmlspecialchars($_GET['section']) : 'Unknown Section';
$year_level = isset($_GET['year_level']) ? htmlspecialchars($_GET['year_level']) : 'Unknown Year Level';

// Build full name
$fullname = trim("$fname $mname $lname $ename");

// Fetch subjects assigned to the student
$subjects = [];
if (!empty($user_id)) {
    $sql = "SELECT sb.subject_id, sb.subject_name 
            FROM student_subjects ss
            INNER JOIN subjects sb ON ss.subject_id = sb.subject_id
            WHERE ss.student_id = ? AND sb.created_by = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
    $stmt->close();
}
$conn->close();
?>

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
                    <a href="../../logout.php" onclick="confirmLogout(event)">
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
                            <span>Profile</span>
                        </div>
                    </div>
                </div>
            </a>

        </aside>

        <!-- Main content -->
        <div class="col-md-9" id="mainContent">
            <h1>Grade: <?php echo $fullname; ?></h1>
            <h4>Class: <?php echo strtoupper($year_level) . ' - ' . strtoupper($section); ?></h4>
            <!-- ✅ Fixed Format -->

            <form action="submit_grade.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="hidden" name="fullname" value="<?php echo $fullname; ?>">
                <input type="hidden" name="year_level" value="<?php echo $year_level; ?>">
                <input type="hidden" name="section" value="<?php echo $section; ?>">

                <!-- Choose Subject Dropdown -->
                <div class="mb-3">
                    <label for="subject" class="form-label">Choose Subject</label>
                    <select name="subject_id" id="subject" class="form-control" required>
                        <option value="" disabled selected>Select Subject</option>
                        <?php if (!empty($subjects)): ?>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?php echo $subject['subject_id']; ?>">
                                    <?php echo htmlspecialchars($subject['subject_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No subjects assigned</option>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Choose Category Dropdown -->
                <div class="mb-3">
                    <label for="category" class="form-label">Choose Category</label>
                    <select name="category" id="category" class="form-control" required>
                        <option value="" disabled selected>Select Category</option>
                        <option value="performance_tasks">Performance Task</option>
                        <option value="quarterly_assessments">Quarterly Assessment</option>
                        <option value="written_works">Written Works</option>
                    </select>
                </div>

                <!-- Score Input -->
                <div class="mb-3">
                    <label for="score" class="form-label">Score</label>
                    <input type="number" name="score" id="score" class="form-control" min="0" max="100" required>
                </div>

                <!-- Total Marks Input -->
                <div class="mb-3">
                    <label for="total_marks" class="form-label">Total Marks</label>
                    <input type="number" name="total_marks" id="total_marks" class="form-control" min="1" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success">Submit Grade</button>
            </form>
        </div>
    </div>


    <script src="../scripts/checkSession.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>