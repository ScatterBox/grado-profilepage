<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../../login.php");
    exit();
}

$loggedInUserId = $_SESSION['user']['user_id']; // Get the teacher's user ID

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gradingsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch distinct year levels and sections from the students table
$yearLevels = [];
$sections = [];

$yearLevelSql = "SELECT DISTINCT year_level FROM students";
$sectionSql = "SELECT DISTINCT section FROM students";

$yearLevelResult = $conn->query($yearLevelSql);
$sectionResult = $conn->query($sectionSql);

while ($row = $yearLevelResult->fetch_assoc()) {
    $yearLevels[] = $row['year_level'];
}

while ($row = $sectionResult->fetch_assoc()) {
    $sections[] = $row['section'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_name = $_POST["subject_name"];
    $year_level = $_POST["year_level"];
    $section = $_POST["section"];
    $created_by = $loggedInUserId;

    // Check if the year level and section exist in the students table
    $stmt = $conn->prepare("SELECT * FROM students WHERE year_level = ? AND section = ?");
    $stmt->bind_param("ss", $year_level, $section);
    $stmt->execute();
    $checkYearSectionResult = $stmt->get_result();
    $stmt->close();

    if ($checkYearSectionResult->num_rows == 0) {
        echo "Error: The year level and section do not exist";
        exit();
    }

    // Check if the same subject exists in the same year and section
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE subject_name = ? AND year_level = ? AND section = ?");
    $stmt->bind_param("sss", $subject_name, $year_level, $section);
    $stmt->execute();
    $checkResult = $stmt->get_result();
    $stmt->close();

    if ($checkResult->num_rows > 0) {
        echo "Error: The same subject exists in the same year and section";
        exit();
    }

    // Insert the subject into the subjects table
    $stmt = $conn->prepare("INSERT INTO subjects (subject_name, year_level, section, created_by) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $subject_name, $year_level, $section, $created_by);

    if ($stmt->execute()) {
        $subject_id = $stmt->insert_id;
        $stmt->close();

        // Assign the subject to students in the same year and section
        $assignStmt = $conn->prepare("INSERT INTO student_subjects (student_id, subject_id) 
                                      SELECT user_id, ? FROM students WHERE year_level = ? AND section = ?");
        $assignStmt->bind_param("iss", $subject_id, $year_level, $section);
        $assignStmt->execute();
        $assignStmt->close();

        echo "Success: New subject created successfully and assigned to students";
    } else {
        echo "Error: " . $stmt->error;
    }

    exit(); // Stop further script execution
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
            <h1>Class Create Form</h1>
            
            <div class="registration-form">
                <form id="classForm" method="POST" action="">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="subject_name">Subject Name:</label>
                                <select class="form-control item" id="subject_name" name="subject_name" required>
                                    <option value="" disabled selected>Select a subject</option>
                                    <option value="English">English</option>
                                    <option value="Science">Science</option>
                                    <option value="Filipino">Filipino</option>
                                    <option value="Math">Math</option>
                                    <option value="TLE">T.L.E (Technology & Livelihood Education)</option>
                                    <option value="ESP">E.S.P (Edukasyon sa Pagpapakatao)</option>
                                    <option value="MAPEH">M.A.PE.H (Music, Arts, Physical Education, Health)</option>
                                    <option value="Araling Panlipunan">Araling Panlipunan</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="year_level">Year Level:</label>
                                <select class="form-control item" id="year_level" name="year_level" required>
                                    <option value="" disabled selected>Select Year Level</option>
                                    <?php foreach ($yearLevels as $yearLevel): ?>
                                        <option value="<?php echo $yearLevel; ?>"><?php echo $yearLevel; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="section">Section assigned:</label>
                                <select class="form-control item" id="section" name="section" required>
                                    <option value="" disabled selected>Select Section</option>
                                    <?php foreach ($sections as $section): ?>
                                        <option value="<?php echo $section; ?>"><?php echo $section; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" id="created_by" name="created_by" value="<?php echo $loggedInUserId; ?>">

                        <div class="col-md-8">
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">Create Class</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('classForm').addEventListener('submit', function(event) {
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
            .then(response => response.text()) // Expect plain text response
            .then(text => {
                if (text.startsWith("Success")) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: text.substring(8) // Remove "Success: " from the message
                    }).then(() => {
                        document.getElementById('classForm').reset();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: text
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong.'
                });
            });
        }
    });
});

    </script>
</body>

</html>