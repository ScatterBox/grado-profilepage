<?php
require '../../excelhandler/SimpleXLSX.php';

session_start();
if ($_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'gradingsystem';
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];

    if ($xlsx = SimpleXLSX::parse($file)) {
        $rows = $xlsx->rows();
        $headers = array_shift($rows); // Get the headers

        $errors = [];

        foreach ($rows as $index => $row) {
            // Map the row data to the corresponding fields
            $data = array_combine($headers, $row);

            // Check if all required fields are present
            if (empty($data['First Name']) || empty($data['Middle Name']) || empty($data['Last Name']) || empty($data['Guardian']) || empty($data['LRN']) || empty($data['Nickname']) || empty($data['Age']) || empty($data['Sex']) || empty($data['Birthdate']) || empty($data['Address']) || empty($data['Username']) || empty($data['Password']) || empty($data['Section']) || empty($data['Year Level'])) {
                $errors[] = "Row $index: Missing required fields.";
                continue;
            }

            $fname = ucfirst($data['First Name']);
            $mname = ucfirst($data['Middle Name']);
            $lname = ucfirst($data['Last Name']);
            $ename = ucfirst($data['Extension Name']);
            $pname = ucfirst($data['Guardian']);
            $lrn = $data['LRN'];
            $nickname = ucfirst($data['Nickname']);
            $age = $data['Age'];
            $sex = ucfirst($data['Sex']);
            $birthdate = $data['Birthdate'];
            $address = ucfirst($data['Address']);
            $email = $data['Email'];
            $username = $data['Username'];
            $password = password_hash($data['Password'], PASSWORD_DEFAULT);
            $section = ucfirst($data['Section']);
            $year_level = ucfirst($data['Year Level']);

            // Insert data into the students table
            $sql = "INSERT INTO students (fname, mname, lname, ename, pname, lrn, nickname, age, sex, birthdate, address, email, username, password, section, year_level) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                $errors[] = "Row $index: Failed to prepare statement.";
                continue;
            }

            $stmt->bind_param("ssssssssssssssss", $fname, $mname, $lname, $ename, $pname, $lrn, $nickname, $age, $sex, $birthdate, $address, $email, $username, $password, $section, $year_level);
            if (!$stmt->execute()) {
                $errors[] = "Row $index: Failed to execute statement.";
            }

            $stmt->close();
        }

        $conn->close();

        if (empty($errors)) {
            echo json_encode(['success' => true, 'message' => 'File processed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Some rows failed to process', 'errors' => $errors]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => SimpleXLSX::parseError()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>