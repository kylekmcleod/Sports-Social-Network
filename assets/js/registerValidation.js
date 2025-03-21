document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    
    if (registerForm) {
        registerForm.addEventListener('submit', function(event) {
            const firstName = document.getElementById('firstName');
            const lastName = document.getElementById('lastName');
            const username = document.getElementById('username');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            
            const firstNameError = document.getElementById('firstNameError');
            const lastNameError = document.getElementById('lastNameError');
            const usernameError = document.getElementById('usernameError');
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');
            
            let isValid = true;
            

            if (firstName.value.trim() === '') {
                firstNameError.style.display = 'block';
                isValid = false;
            } else {
                firstNameError.style.display = 'none';
            }
            
            if (lastName.value.trim() === '') {
                lastNameError.style.display = 'block';
                isValid = false;
            } else {
                lastNameError.style.display = 'none';
            }
            
            if (username.value.trim() === '') {
                usernameError.style.display = 'block';
                isValid = false;
            } else {
                usernameError.style.display = 'none';
            }
            
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email.value.trim())) {
                emailError.style.display = 'block';
                isValid = false;
            } else {
                emailError.style.display = 'none';
            }
            
            if (password.value.trim() === '') {
                passwordError.style.display = 'block';
                isValid = false;
            } else {
                passwordError.style.display = 'none';
            }
            
            if (!isValid) {
                event.preventDefault();
            } else {
                if (!document.querySelector('input[name="register"]')) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'register';
                    hiddenInput.value = 'true';
                    registerForm.appendChild(hiddenInput);
                }
            }
        });
    }
});
