<?php
session_start();
session_destroy();
header("Location: ../view/signin.html");
exit();





// // Start the session
// session_start();

// // Clear all session variables
// $_SESSION = array();

// // If using cookies for sessions, delete the session cookie
// if (ini_get("session.use_cookies")) {
//     $params = session_get_cookie_params();
//     setcookie(session_name(), '', time() - 42000,
//         $params["path"], $params["domain"],
//         $params["secure"], $params["httponly"]
//     );
// }

// // Destroy the session
// session_destroy();

// // Redirect to the sign-in page
// header("Location: ../view/signin.html");
// exit();


?>
