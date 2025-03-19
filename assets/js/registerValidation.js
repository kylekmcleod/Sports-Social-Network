document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault();

    let isValid = true;

    // Reset all error messages
    const errorElements = document.querySelectorAll('.text-danger');
    errorElements.forEach(element => element.style.display = 'none');

    // Validate First Name
    const firstName = document.getElementById('firstName').value.trim();
    if (!firstName) {
        document.getElementById('firstNameError').style.display = 'block';
        isValid = false;
    }

    // Validate Last Name
    const lastName = document.getElementById('lastName').value.trim();
    if (!lastName) {
        document.getElementById('lastNameError').style.display = 'block';
        isValid = false;
    }

    // Validate Username
    const username = document.getElementById('username').value.trim();
    if (!username) {
        document.getElementById('usernameError').style.display = 'block';
        isValid = false;
    }

    // Validate Email
    const email = document.getElementById('email').value.trim();
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!email || !emailPattern.test(email)) {
        document.getElementById('emailError').style.display = 'block';
        isValid = false;
    }

    // Validate Password
    const password = document.getElementById('password').value.trim();
    if (!password) {
        document.getElementById('passwordError').style.display = 'block';
        isValid = false;
    }

    if (isValid) {
        this.submit();
    }
});
