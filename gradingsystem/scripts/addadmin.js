// Get the form element
var form = document.getElementById('adminForm');

// Attach a submit event handler to the form
form.addEventListener('submit', function (event) {
    // Prevent the form from being submitted normally
    event.preventDefault();

    // Ask for confirmation before submitting
    Swal.fire({
        title: 'Is the information correct?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit it!',
        cancelButtonText: 'No, review it'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create a new FormData object from the form
            var formData = new FormData(form);

            // Use AJAX to submit the form data
            var request = new XMLHttpRequest();
            request.open('POST', '../adbconn.php');
            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    // The request has completed successfully
                    var response = JSON.parse(request.responseText);
                    if (response.success) {
                        Swal.fire(
                            'Success!',
                            response.message,
                            'success'
                        ).then(() => {
                            // Call showAdmin() after the success message
                            showAdmin();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }

                    // Reset the form fields
                    form.reset();
                }
            };
            request.send(formData);
        }
    });
});
