document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    let isValid = true;

    // Reset all error messages
    const errorElements = document.querySelectorAll('.text-danger');
    errorElements.forEach(element => element.style.display = 'none');

    // Validate Email
    const email = document.getElementById('usernameOrEmail').value.trim();
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
