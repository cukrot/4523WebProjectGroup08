// Toggle Between Forms
function initFormToggle() {
    const toggleLinks = document.querySelectorAll('.toggle-link');
    const loginForm = document.querySelector('.login-form');
    const signupForm = document.querySelector('.signup-form');

    toggleLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default link behavior
            const targetForm = link.getAttribute('data-toggle');

            if (targetForm === 'signup') {
                loginForm.classList.remove('active');
                signupForm.classList.add('active');
            } else if (targetForm === 'login') {
                signupForm.classList.remove('active');
                loginForm.classList.add('active');
            }
        });
    });

    // Check URL hash on page load to determine which form to show
    const hash = window.location.hash;
    if (hash === '#signup') {
        loginForm.classList.remove('active');
        signupForm.classList.add('active');
    }
}

// Login Form
function initLoginForm() {
    const loginForm = document.getElementById('login-form');
    loginForm.addEventListener('submit', (e) => {
        e.preventDefault(); // Prevent form submission

        const email = document.getElementById('login-email').value.trim();
        const password = document.getElementById('login-password').value.trim();

        if (email === '' || password === '') {
            alert('Please fill in all fields.');
            return;
        }

        if (!email.includes('@') || !email.includes('.')) {
            alert('Please enter a valid email address.');
            return;
        }

        // Simulate successful login
        alert('Login successful! Welcome back.');
        loginForm.reset(); // Clear the form
    });
}

// Sign Up Form
function initSignupForm() {
    const signupForm = document.getElementById('signup-form');
    signupForm.addEventListener('submit', (e) => {
        e.preventDefault(); // Prevent form submission

        const name = document.getElementById('signup-name').value.trim();
        const email = document.getElementById('signup-email').value.trim();
        const password = document.getElementById('signup-password').value.trim();
        const confirmPassword = document.getElementById('signup-confirm-password').value.trim();

        if (name === '' || email === '' || password === '' || confirmPassword === '') {
            alert('Please fill in all fields.');
            return;
        }

        if (!email.includes('@') || !email.includes('.')) {
            alert('Please enter a valid email address.');
            return;
        }

        if (password.length < 6) {
            alert('Password must be at least 6 characters long.');
            return;
        }

        if (password !== confirmPassword) {
            alert('Passwords do not match.');
            return;
        }

        // Simulate successful sign-up
        alert('Sign up successful! Welcome to Smile & Sunshine.');
        signupForm.reset(); // Clear the form
    });
}

// Initialize Forms and Toggle
function init() {
    initFormToggle();
    initLoginForm();
    initSignupForm();
}

// Run the initialization
init();