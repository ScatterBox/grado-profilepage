function confirmLogout(event) {
    event.preventDefault(); // Prevent default logout action

    Swal.fire({
        title: "Are you sure?",
        text: "You will be logged out of your account.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, logout",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = event.target.href; // Redirect to logout page
        }
    });
}
