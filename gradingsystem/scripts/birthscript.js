document.getElementById('birthdate').addEventListener('change', function () {
    const birthdate = new Date(this.value); // Get the selected birthdate
    const today = new Date(); // Get today's date
    let age = today.getFullYear() - birthdate.getFullYear(); // Calculate the initial age
    const monthDiff = today.getMonth() - birthdate.getMonth(); // Calculate the month difference

    // Adjust the age if the birthday hasn't occurred yet this year
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
        age--;
    }

    // Display the calculated age
    document.getElementById('age').value = age;
});