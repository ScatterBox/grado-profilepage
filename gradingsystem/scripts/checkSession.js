function checkSession() {
    fetch('../check_session.php')
        .then(response => response.json())
        .then(data => {
            if (!data.loggedIn) {
                window.location.href = '../login.php';
            }
        })
        .catch(error => console.error('Error:', error));
}

// Check session every 5 seconds
setInterval(checkSession, 5000);

// Check session on page load
checkSession();

// Listen for storage events
window.addEventListener('storage', function(event) {
    if (event.key === 'isLoggedOut' && event.newValue === 'true') {
        window.location.href = '../login.php';
    }
});