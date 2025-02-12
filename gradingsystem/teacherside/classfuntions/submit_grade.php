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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $subject_id = isset($_POST['subject_id']) ? intval($_POST['subject_id']) : 0;
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $score = isset($_POST['score']) ? intval($_POST['score']) : 0;
    $total_marks = isset($_POST['total_marks']) ? intval($_POST['total_marks']) : 0;
    $teacher_id = $_SESSION['user_id']; // Logged-in teacher ID
    $date = date('Y-m-d'); // Auto-generate the date

    // Validate input
    if ($student_id == 0 || $subject_id == 0 || empty($category) || $score < 0 || $total_marks <= 0) {
        echo "<script>
                alert('Invalid input! Please ensure all fields are filled correctly.');
                window.history.back();
              </script>";
        exit();
    }

    // Ensure correct table names
    if ($category === "performance_tasks") {
        $table_name = "performance_tasks";
    } elseif ($category === "quarterly_assessments") {
        $table_name = "quarterly_assessment"; // ✅ Corrected table name
    } elseif ($category === "written_works") {
        $table_name = "written_works";
    } else {
        echo "<script>
                alert('Invalid category selected!');
                window.history.back();
              </script>";
        exit();
    }

    // Insert the grade into the correct table
    $sql = "INSERT INTO $table_name (name, total_score, date, subject_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisi", $category, $total_marks, $date, $subject_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Grade submitted successfully!');
                window.location.href = 'mystudents.php';
              </script>";
    } else {
        echo "<script>
                alert('Error submitting grade: " . $stmt->error . "');
                window.history.back();
              </script>";
    }

    $stmt->close();
}

$conn->close();
?>
