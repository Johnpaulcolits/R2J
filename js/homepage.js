// This function is for the header of the view pages
function headerFunction() {
    document.addEventListener('DOMContentLoaded', () => {
        // Get all the navigation links
        const navLinks = document.querySelectorAll('.nav-link');

        // Function to highlight the active link
        function highlightActiveLink() {
            const currentPath = window.location.pathname;
            navLinks.forEach(link => {
                // Remove underline from all links
                link.classList.remove('underline', 'underline-offset-8', 'decoration-white');
                // Add underline to the link that matches the current path
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('underline', 'underline-offset-8', 'decoration-white');
                }
            });
        }

        // Call the function to highlight the active link on page load
        highlightActiveLink();

        // Add event listener to each link
        navLinks.forEach(link => {
            link.addEventListener('click', function () {
                // Store the clicked link's href in localStorage
                localStorage.setItem('activeLink', this.getAttribute('href'));
            });
        });

        // On page load, apply the active link style
        const activeLink = localStorage.getItem('activeLink');
        if (activeLink) {
            navLinks.forEach(link => {
                if (link.getAttribute('href') === activeLink) {
                    link.classList.add('underline', 'underline-offset-8', 'decoration-white');
                }
            });
        }
    });
}

headerFunction();




// function Authentication(){
    
//     document.addEventListener("DOMContentLoaded", () => {
//         const registrationForm = document.getElementById('registration-form');
//         const loginForm = document.getElementById('login-form');
    
//         if (registrationForm) {
//             registrationForm.addEventListener('submit', function(e) {
//                 e.preventDefault();
//                 const formData = new FormData(this);
//                 formData.append('action', 'register');
    
//                 fetch('../controller/user.php', {
//                     method: 'POST',
//                     body: formData
//                 })
//                 .then(response => response.text())
//                 .then(data => alert(data))
//                 .catch(error => console.error('Error:', error));
//             });
//         }
    
//         if (loginForm) {
//             loginForm.addEventListener('submit', function(e) {
//                 e.preventDefault();
//                 const formData = new FormData(this);
//                 formData.append('action', 'login');
    
//                 fetch('../controller/user.php', {
//                     method: 'POST',
//                     body: formData
//                 })
//                 .then(response => response.text())
//                 .then(data => alert(data))
//                 .catch(error => console.error('Error:', error));
//             });
//         }
//     });
//     }
    
//     Authentication();



// function googleAuth(){
//     document.addEventListener("DOMContentLoaded", () => {
//         const registrationForm = document.getElementById('registration-form');
//         const loginForm = document.getElementById('login-form');
    
//         // Handle Google OAuth response
//         window.handleCredentialResponse = function(response) {
//             // Send the ID token to the server for validation
//             fetch('../controller/user.php', {
//                 method: 'POST',
//                 headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
//                 body: 'id_token=' + response.credential + '&action=google_login'
//             })
//             .then(response => response.text())
//             .then(data => {
//                 if (data === 'success') {
//                     window.location.href = '../view/products/home.html';  // Redirect after successful login
//                 } else {
//                     alert('Google login failed: ' + data);
//                 }
//             })
//             .catch(error => console.error('Error:', error));
//         };
    
//         if (registrationForm) {
//             registrationForm.addEventListener('submit', function(e) {
//                 e.preventDefault();
//                 const formData = new FormData(this);
//                 formData.append('action', 'register');
    
//                 fetch('../controller/user.php', {
//                     method: 'POST',
//                     body: formData
//                 })
//                 .then(response => response.text())
//                 .then(data => alert(data))
//                 .catch(error => console.error('Error:', error));
//             });
//         }
    
//         if (loginForm) {
//             loginForm.addEventListener('submit', function(e) {
//                 e.preventDefault();
//                 const formData = new FormData(this);
//                 formData.append('action', 'login');
    
//                 fetch('../controller/user.php', {
//                     method: 'POST',
//                     body: formData
//                 })
//                 .then(response => response.text())
//                 .then(data => alert(data))
//                 .catch(error => console.error('Error:', error));
//             });
//         }
//     });
// }
// googleAuth();



// This function is for the header of the view pages
function headerFunction() {
    window.handleCredentialResponse = function(response) {
        fetch('../controller/user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id_token=' + response.credential + '&action=google_login'
        })
        .then(response => response.json())  // Expect JSON response
        .then(data => {
            if (data.status === 'user_exists') {
                // User exists, proceed to home page
                window.location.href = '../view/products/home.html';
            } else if (data.status === 'new_user') {
                // New user, redirect to home page after registration
                window.location.href = '../view/products/home.html';
            } else {
                // Handle error cases
                alert('Google login failed: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    };
    
}

headerFunction();





// if (registrationForm) {
//     registrationForm.addEventListener('submit', function(e) {
//         e.preventDefault();
//         const formData = new FormData(this);
//         formData.append('action', 'register');

//         fetch('../controller/user.php', {
//             method: 'POST',
//             body: formData
//         })
//         .then(response => response.text())
//         .then(data => alert(data))
//         .catch(error => console.error('Error:', error));
//     });
// }

// if (loginForm) {
//     loginForm.addEventListener('submit', function(e) {
//         e.preventDefault();
//         const formData = new FormData(this);
//         formData.append('action', 'login');

//         fetch('../controller/user.php', {
//             method: 'POST',
//             body: formData
//         })
//         .then(response => response.text())
//         .then(data => alert(data))
//         .catch(error => console.error('Error:', error));
//     });
// }