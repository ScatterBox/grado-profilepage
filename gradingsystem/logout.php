<?php
session_start();
session_unset();
session_destroy();

echo "<script>
    localStorage.setItem('isLoggedIn', 'false');
    localStorage.setItem('isLoggedOut', 'true'); // 🔹 Notify other tabs
    localStorage.removeItem('role');
    localStorage.removeItem('user_id');
    localStorage.setItem('logoutTrigger', Date.now()); // 🔹 Trigger storage event
    window.location.href = 'login.php';
</script>";
exit();
?>
