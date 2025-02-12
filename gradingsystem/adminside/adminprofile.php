<?php
session_start();
require_once '../conn.php'; // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['user']['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user']['user_id'];

// Determine user role based on table membership
$role = '';
$tables = ['admins' => 'admin', 'teachers' => 'teacher', 'students' => 'student'];

foreach ($tables as $table => $user_role) {
    $query = "SELECT user_id FROM $table WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $role = $user_role;
        break;
    }
    $stmt->close();
}

if ($role === '') {
    die("Error: User role cannot be determined.");
}

// Handle image upload if a file is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_img'])) {
    $target_dir = "../uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0775, true);
    }

    $imageFileType = strtolower(pathinfo($_FILES["profile_img"]["name"], PATHINFO_EXTENSION));
    $new_filename = "{$role}_{$user_id}_" . time() . "." . $imageFileType;
    $target_file = $target_dir . $new_filename;

    if (in_array($imageFileType, ['jpg', 'jpeg', 'png']) && move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file)) {
        $sql = "UPDATE $table SET img = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_filename, $user_id);

        if ($stmt->execute()) {
            $_SESSION['user']['img'] = $new_filename;
            echo json_encode(["status" => "success", "filename" => $new_filename]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database update failed."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "File upload failed or invalid format."]);
    }
    exit();
}

// Fetch user image
$userImg = !empty($_SESSION['user']['img']) ? '../uploads/' . $_SESSION['user']['img'] : '../images/default-profile.jpg';
if (!file_exists(dirname(__FILE__) . '/../uploads/' . basename($userImg))) {
    $userImg = '../images/default-profile.jpg';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Profile</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="../styles/style1.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

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
                        <span class="material-symbols-outlined"> person </span>Students</a>
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
            <a href="profile.php" class="user-account-link">
                <div class="user-account">
                    <div class="user-profile">
                        <img src="<?php echo '../uploads/' . htmlspecialchars($_SESSION['user']['img']); ?>"
                            alt="Profile Image" />
                        <div class="user-detail">
                            <h3><?php echo htmlspecialchars($_SESSION['user']['nickname']); ?></h3>
                            <span>Admin</span>
                        </div>
                    </div>
                </div>
            </a>
        </aside>

        <!-- Main content -->
        <div class="col-md-9" id="mainContent">
            <div class="container py-5">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <div class="profile-image-container mb-3">
                                    <img id="profilePic" src="<?php echo htmlspecialchars($userImg); ?>"
                                        class="rounded-circle" width="180" height="180">
                                </div>
                                <form id="uploadForm" enctype="multipart/form-data">
                                    <input type="file" name="profile_img" id="profile_img" accept="image/*"
                                        style="display: none;">
                                    <button type="button" class="btn btn-primary" id="changeProfile">
                                        <span class="material-symbols-outlined">photo_camera</span> Change Photo
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Bio Section -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Bio</h5>
                                <div class="mb-3">
                                    <textarea class="form-control" id="userBio" rows="4"
                                        placeholder="Write something about yourself..."></textarea>
                                </div>
                                <button class="btn btn-outline-primary btn-sm" id="saveBio">Save Bio</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Personal Information</h5>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Full Name</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            <?php
                                            echo htmlspecialchars($_SESSION['user']['fname']) . ' ' .
                                                htmlspecialchars($_SESSION['user']['mname']) . ' ' .
                                                htmlspecialchars($_SESSION['user']['lname']) .
                                                (!empty($_SESSION['user']['ename']) ? ' ' . htmlspecialchars($_SESSION['user']['ename']) : '');
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Username</p>
                                    </div>
                                    <div class="col-sm-7">
                                        <p class="text-muted mb-0" id="usernameDisplay">
                                            <?php echo htmlspecialchars($_SESSION['user']['username']); ?>
                                        </p>
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-outline-primary btn-sm" id="changeUsername">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                                            Change
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Email</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            <?php echo htmlspecialchars($_SESSION['user']['email']); ?>
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Year Level</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            <?php echo htmlspecialchars($_SESSION['user']['year_level'] ?? 'Not set'); ?>
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Section</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            <?php echo htmlspecialchars($_SESSION['user']['section'] ?? 'Not set'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#changeProfile').click(function () {
                $('#profile_img').click();
            });

            $('#profile_img').change(function () {
                var formData = new FormData();
                formData.append("profile_img", $("#profile_img")[0].files[0]);

                $.ajax({
                    url: '', // Same file
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === "success") {
                            Swal.fire('Success', 'Profile picture updated!', 'success').then(() => {
                                $('#profilePic').attr("src", "../uploads/" + response.filename + "?" + new Date().getTime());
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Upload failed.', 'error');
                    }
                });
            });
        });
    </script>
    <script src="../scripts/checkSession.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.js"></script>
</body>

</html>