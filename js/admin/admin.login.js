function adminAuth() {
    document.addEventListener('DOMContentLoaded', function () {
        const registrationForm = document.getElementById('admin-registration-form');
        const loginForm = document.getElementById('admin-login-form');
    
        // Registration form submission
        if (registrationForm) {
            registrationForm.addEventListener('submit', function (e) {
                e.preventDefault();
    
                const formData = new FormData(this);
                formData.append('action', 'register');
    
                fetch('../../controller/admin.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())  // Expect a JSON response from the server
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Registration successful!');
                            // Redirect to login page or dashboard
                            window.location.href = '../view/products/home.html';
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        }
    
        // Login form submission
        if (loginForm) {
            loginForm.addEventListener('submit', function (e) {
                e.preventDefault();
    
                const formData = new FormData(this);
                formData.append('action', 'login');
    
                fetch('../../controller/admin.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())  // Expect a JSON response from the server
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Login successful!');
                            // Redirect to dashboard or homepage after login
                            window.location.href = '../../view/superadmin/superadminhome.html';
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        }
    });
    
}

adminAuth();




