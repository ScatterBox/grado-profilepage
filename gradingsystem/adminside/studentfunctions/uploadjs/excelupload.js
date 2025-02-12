function handleFileUpload(event) {
    const file = event.target.files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);

        fetch('../upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('File uploaded and processed successfully');
                alert('File uploaded and processed successfully');
            } else {
                console.error('Error:', data.message);
                alert('Error: ' + data.message);
                if (data.errors) {
                    console.error('Errors:', data.errors);
                    data.errors.forEach(error => {
                        console.error(error);
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred');
        });
    }
}

document.getElementById('excelFile').addEventListener('change', handleFileUpload);