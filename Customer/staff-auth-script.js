function initStaffLoginForm() {
    const loginForm = document.getElementById('staff-login-form');
    loginForm.addEventListener('submit', (e) => {
        const sid = document.getElementById('staff-id').value.trim();
        const password = document.getElementById('staff-password').value.trim();

        if (sid === '' || password === '') {
            alert('Please fill in all fields.');
            e.preventDefault();
            return;
        }

        if (!/^\d+$/.test(sid)) {
            alert('Staff ID must be a number.');
            e.preventDefault();
            return;
        }

        if (password.length < 4) {
            alert('Password must be at least 4 characters long.');
            e.preventDefault();
            return;
        }
    });
}

function init() {
    initStaffLoginForm();
}

init();