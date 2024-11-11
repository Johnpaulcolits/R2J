<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../vendor/autoload.php';
require_once '../db/connection.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['email']) && !isset($data['otp'])) {
    $email = $data['email'];
    
    $connection = new Connection();
    $conn = $connection->conn;

    // Check if email exists in users table
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['otp_email'] = $email;  // Store email in session for later use
        $_SESSION['otp_verified'] = false;  // Reset OTP verification status

        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);

        // Set OTP expiry time (15 minutes)
        $otp_expiry = date('Y-m-d H:i:s', strtotime('+1 minutes'));

        // Update the OTP and expiry time in the database
        $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $otp, $otp_expiry, $email);

        if ($stmt->execute()) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'jagsportsapparelshop@gmail.com';
                $mail->Password = 'zzlg ufai yhnt rmtz';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('jagsportsapparelshop@gmail.com', 'J.A.G Sports Apparel Shop');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Your OTP for Password Reset';
                $mail->Body = "Your OTP is <strong>$otp</strong>. It will expire in 1 minutes.";

                if ($mail->send()) {
                    echo json_encode(['success' => true, 'message' => 'OTP sent to your email']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to send OTP']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update OTP in database']);
        }
    } else {
        // Email does not exist in the database
        echo json_encode(['success' => false, 'message' => 'Email not found']);
    }
} elseif (isset($data['otp'])) {
    // OTP Verification Logic
    $otp = $data['otp'];
    $email = $_SESSION['otp_email'] ?? null;  // Retrieve the email from the session

    if (!$email) {
        echo json_encode(['success' => false, 'message' => 'Session expired. Please request OTP again.']);
        exit;
    }

    $connection = new Connection();
    $conn = $connection->conn;

    $stmt = $conn->prepare("SELECT otp, otp_expiry FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($stored_otp, $otp_expiry);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        $current_time = new DateTime();
        $otp_expiry_time = new DateTime($otp_expiry);

        if ($current_time > $otp_expiry_time) {
            // OTP has expired
            echo json_encode(['success' => false, 'message' => 'OTP has expired']);
        } elseif ($stored_otp !== $otp) {
            // Invalid OTP
            echo json_encode(['success' => false, 'message' => 'Invalid OTP']);
        } else {
            // OTP is valid
            $_SESSION['otp_verified'] = true;  // Mark OTP as verified
            echo json_encode(['success' => true, 'message' => 'OTP verified successfully']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Email not found']);
    }

    $stmt->close();
}elseif (isset($data['newpassword'], $data['confirmpassword'])) {
    // Update Password Logic with OTP Expiry Check and OTP Verification Check
    $newPassword = $data['newpassword'];
    $confirmPassword = $data['confirmpassword'];
    $email = $_SESSION['otp_email'] ?? null;  // Retrieve the email from the session

    if (!$email) {
        echo json_encode(['success' => false, 'message' => 'Session expired. Please request OTP again.']);
        exit;
    }

    if ($newPassword !== $confirmPassword) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
        exit;
    }

    // Password strength check (at least 8 characters, must include both letters and numbers)
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $newPassword)) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long and include both letters and numbers']);
        exit;
    }

    if (!$_SESSION['otp_verified']) {
        echo json_encode(['success' => false, 'message' => 'Please verify OTP first']);
        exit;
    }

    $connection = new Connection();
    $conn = $connection->conn;

    // Check if OTP has expired
    $stmt = $conn->prepare("SELECT otp_expiry FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($otp_expiry);
        $stmt->fetch();

        $current_time = new DateTime();
        $otp_expiry_time = new DateTime($otp_expiry);

        if ($current_time > $otp_expiry_time) {
            echo json_encode(['success' => false, 'message' => 'OTP has expired. Request New.']);
            exit;
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashedPassword, $email);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update password']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'OTP not found.']);
    }

    $stmt->close();
}
 else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

?>
