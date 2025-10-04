<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "bkh");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function getUserIP()
{
    return !empty($_SERVER['HTTP_X_FORWARDED_FOR'])
        ? $_SERVER['HTTP_X_FORWARDED_FOR']
        : $_SERVER['REMOTE_ADDR'];
}
// Signup
if (isset($_POST['sign_up'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $address = $conn->real_escape_string($_POST['address']);
    $dob = $_POST['date_of_birth'];
    $contact = $conn->real_escape_string($_POST['contact']);

    $checkEmail = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($checkEmail->num_rows > 0) {
        echo "<script>alert('Email already registered. Try logging in.');</script>";
    } else {
        $conn->query("INSERT INTO users (username, email, pass) VALUES ('$username', '$email', '$password')");
        $user_id = $conn->insert_id;

        $conn->query("INSERT INTO user_info (user_id, birthday, phone, address) VALUES ($user_id, '$dob', '$contact', '$address')");

        echo "<script>alert('Registration successful! You can now log in.');</script>";
    }
}

// Login
if (isset($_POST['sign_in'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
    } else {
        $query = "SELECT user_id, pass, two_step_verification FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['pass'];
            $user_id = $row['user_id'];
            $two_factor_enabled = $row['two_step_verification'];

            if (password_verify($password, $hashed_password)) {
                if ($two_factor_enabled == 1) {
                    // Initiate Two-Factor Authentication (2FA)
                    $otp = rand(100000, 999999);

                    // Insert OTP into the user_otp table
                    $insert_otp_query = "
                        INSERT INTO user_otp (user_id, otp_code, purpose,otp_attempts) 
                        VALUES (?, ?, 'two-factor', 1)
                    ";
                    $otp_stmt = $conn->prepare($insert_otp_query);
                    $otp_stmt->bind_param("is", $user_id, $otp);
                    $otp_stmt->execute();

                    // Store OTP and user information in session
                    $_SESSION['2fa_otp'] = $otp;
                    $_SESSION['2fa_user_id'] = $user_id;
                    $_SESSION['2fa_email'] = $email;

                    // Send OTP via Python script (ensure the Python script path is correct)
                    $python = "python";
                    $scriptPath = "C:/xampp/htdocs/BookHeaven2.0/sendotp.py";

                    $command = escapeshellcmd($python . ' ' . escapeshellarg($scriptPath) . ' '
                        . escapeshellarg($email) . ' '
                        . escapeshellarg($otp));
                    shell_exec($command);

                    echo "<script>window.location.href = 'verify_2fa.php';</script>";
                    exit();
                } else {
                    // Proceed with normal login
                    $_SESSION['user_id'] = $user_id;
                    $ip_address = getUserIP();
                    $update_query = "UPDATE users SET login_count = login_count + 1, last_login = NOW() WHERE user_id = ?";
                    $update_stmt = $conn->prepare($update_query);
                    $update_stmt->bind_param("i", $user_id);
                    $update_stmt->execute();
                    $status = 'active';
                    $activity_query = "INSERT INTO user_activities (user_id, login_ip, login_timestamp, logout_time, status)
                                       VALUES (?, ?, current_timestamp(), current_timestamp(), ?)";
                    $activity_stmt = $conn->prepare($activity_query);
                    $activity_stmt->bind_param("iss", $user_id, $ip_address, $status);
                    $activity_stmt->execute();

                    $_SESSION['user_id'] = $user_id;
                    echo "<script>window.location.href = '/BookHeaven2.0/index.php';</script>";
                    exit();
                }
            } else {
                echo "<script>alert('Invalid email or password.');</script>";
            }
        } else {
            echo "<script>alert('Invalid email or password.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Login & Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="/BookHeaven2.0/css/authentication.css"> -->
     <style>
        * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Montserrat", sans-serif;
}

body {
  background-color: #f5f5f5;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  position: relative;
  overflow: hidden;
}

/* Book-themed background animation */
.book {
  position: absolute;
  width: 60px;
  height: 80px;
  background: linear-gradient(45deg,rgb(28, 216, 249),rgb(55, 173, 206),rgb(63, 205, 177));
  border-radius: 5px 10px 10px 5px;
  box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
  transform-origin: left center;
  animation: float 15s infinite linear;
  opacity: 0.7;
  z-index: -1;
}

.book::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    90deg,
    rgba(255, 255, 255, 0.1) 0%,
    rgba(255, 255, 255, 0) 20%
  );
  border-radius: 5px 10px 10px 5px;
}

.book::after {
  content: "";
  position: absolute;
  top: 5px;
  right: 5px;
  width: 15px;
  height: 70px;
  background: linear-gradient(90deg, #8b4513, #a0522d);
  border-radius: 0 5px 5px 0;
}

.book-spine {
  position: absolute;
  top: 5px;
  left: 0;
  width: 5px;
  height: 70px;
  background: linear-gradient(90deg, #5d2906, #8b4513);
}

.book-title {
  position: absolute;
  top: 30px;
  left: 10px;
  width: 40px;
  height: 3px;
  background: #fff;
  transform: rotate(90deg);
}

@keyframes float {
  0% {
    transform: translateY(0) rotate(0deg);
    left: -100px;
  }

  100% {
    transform: translateY(-100vh) rotate(360deg);
    left: calc(100vw + 100px);
  }
}

/* Library shelf animation */
.shelf {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 20px;
  background: linear-gradient(to right, #8b4513, #a0522d, #8b4513);
  z-index: -1;
}

.shelf::before,
.shelf::after {
  content: "";
  position: absolute;
  width: 100%;
  height: 10px;
  background: linear-gradient(to right, #5d2906, #8b4513, #5d2906);
}

.shelf::before {
  top: -30px;
}

.shelf::after {
  top: -60px;
}

/* Main container styles */
.container {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
  position: relative;
  overflow: hidden;
  width: 768px;
  max-width: 100%;
  min-height: 480px;
}

.container h1 {
  font-size: 24px;
  margin-bottom: 10px;
  color: #333;
}

.container p {
  font-size: 14px;
  line-height: 20px;
  letter-spacing: 0.3px;
  margin: 15px 0;
  color: #666;
}

.container span {
  font-size: 12px;
  color: #888;
  margin-bottom: 15px;
  display: block;
}

.container a {
  color: rgb(49, 198, 196);
  font-size: 13px;
  text-decoration: none;
  margin: 10px 0;
  transition: color 0.3s ease;
}

.container a:hover {
  color: #311b92;
}

.container button {
  background-color: #512da8;
  color: #fff;
  font-size: 12px;
  padding: 12px 45px;
  border: none;
  border-radius: 20px;
  font-weight: 600;
  letter-spacing: 1px;
  text-transform: uppercase;
  margin: 10px 0;
  cursor: pointer;
  transition: all 0.3s ease;
}

.container button:hover {
  background-color: #311b92;
  transform: translateY(-2px);
}

.container button:active {
  transform: scale(0.98);
}

.container button.hidden {
  background-color: transparent;
  border: 1px solid #fff;
}

.container button.hidden:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.container form {
  background-color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  padding: 0 40px;
  height: 100%;
}

.container input {
  background-color: #eee;
  border: none;
  margin: 8px 0;
  padding: 12px 15px;
  font-size: 13px;
  border-radius: 8px;
  width: 100%;
  outline: none;
  transition: background-color 0.3s ease;
}

.container input:focus {
  background-color: #ddd;
}

/* Form containers */
.form-container {
  position: absolute;
  top: 0;
  height: 100%;
  transition: all 0.6s ease-in-out;
}

.sign-in {
  left: 0;
  width: 50%;
  z-index: 2;
}

.container.active .sign-in {
  transform: translateX(100%);
}

.sign-up {
  left: 0;
  width: 50%;
  opacity: 0;
  z-index: 1;
}

.container.active .sign-up {
  transform: translateX(100%);
  opacity: 1;
  z-index: 5;
  animation: move 0.6s;
}

@keyframes move {
  0%,
  49.99% {
    opacity: 0;
    z-index: 1;
  }

  50%,
  100% {
    opacity: 1;
    z-index: 5;
  }
}

/* Social icons */
.social-icons {
  margin: 15px 0;
}

.social-icons a {
  border: 1px solid #ddd;
  border-radius: 50%;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  margin: 0 5px;
  width: 40px;
  height: 40px;
  transition: all 0.3s ease;
}

.social-icons a:hover {
  transform: scale(1.1);
  border-color: #512da8;
  color: #512da8;
}

/* Toggle container */
.toggle-container {
  position: absolute;
  top: 0;
  left: 50%;
  width: 50%;
  height: 100%;
  overflow: hidden;
  transition: all 0.6s ease-in-out;
  border-radius: 150px 0 0 100px;
  z-index: 1000;
}

.container.active .toggle-container {
  transform: translateX(-100%);
  border-radius: 0 150px 100px 0;
}

.toggle {
  background: linear-gradient(to right, rgb(21, 199, 175), rgb(183, 135, 187));
  height: 100%;
  position: relative;
  left: -100%;
  width: 200%;
  transform: translateX(0);
  transition: all 0.6s ease-in-out;
}

.container.active .toggle {
  transform: translateX(50%);
}

.toggle-panel {
  position: absolute;
  width: 50%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  padding: 0 30px;
  text-align: center;
  top: 0;
  transform: translateX(0);
  transition: all 0.6s ease-in-out;
  color: #fff;
}

.toggle-left {
  transform: translateX(-200%);
}

.container.active .toggle-left {
  transform: translateX(0);
}

.toggle-right {
  right: 0;
  transform: translateX(0);
}

.container.active .toggle-right {
  transform: translateX(200%);
}

.toggle-panel h1 {
  color: black;
}

.toggle-panel p {
  color: black;
}

/* Password toggle */
.password-container {
  position: relative;
  width: 100%;
}

.toggle-password {
  position: absolute;
  top: 50%;
  right: 15px;
  transform: translateY(-50%);
  cursor: pointer;
  color: #777;
  transition: color 0.3s ease;
}

.toggle-password:hover {
  color: #512da8;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .container {
    width: 100%;
    min-height: 100vh;
    border-radius: 0;
  }

  .form-container {
    width: 100%;
  }

  .container.active .sign-in,
  .container.active .sign-up {
    transform: translateX(0);
  }

  .toggle-container {
    display: none;
  }
}

     </style>
</head>

<body>
    <!-- Background animation elements -->
    <div class="shelf"></div>
    <?php
    for ($i = 0; $i < 15; $i++) {
        echo '<div class="book" style="top: ' . rand(0, 100) . 'vh; left: ' . rand(0, 100) . 'vw; animation-delay: ' . rand(0, 10) . 's; animation-duration: ' . rand(10, 30) . 's;"></div>';
    }
    ?>
    <div class="container" id="container">
        <!-- Sign Up Form -->
        <div class="form-container sign-up">
            <form method="POST" action="">
                <h1>Create Account</h1>
                <span>Use your email for registration</span>
                <input type="text" name="username" placeholder="Name" required />
                <input type="email" name="email" placeholder="Email" required />
                <div class="password-container">
                    <input type="password" name="password" placeholder="Password" required />
                    <i class="fa-solid fa-eye-slash toggle-password"></i>
                </div>
                <input type="text" name="address" placeholder="Address" />
                <input type="date" name="date_of_birth" placeholder="Date of Birth" />
                <input type="text" name="contact" placeholder="Contact Number" />
                <button class="sign-up-btn" type="submit" name="sign_up">Sign Up</button>
            </form>
        </div>

        <!-- Sign In Form -->
        <div class="form-container sign-in">
            <form method="POST" action="">
                <h1>Sign In</h1>
                <span>Use your email and password</span>
                <input type="email" name="email" placeholder="Email" required />
                <div class="password-container">
                    <input type="password" name="password" placeholder="Password" required />
                    <i class="fa-solid fa-eye-slash toggle-password"></i>
                </div>
                <a href="/BookHeaven2.0/php/forgot_password.php">Forgot Password?</a>
                <button type="submit" name="sign_in">Sign In</button>
            </form>
        </div>

        <!-- Panel Toggle -->
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of the site's features</p>
                    <button class="hidden" id="login">Log In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to enjoy our services</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(toggle => {
            toggle.addEventListener('click', function () {
                const passwordField = this.previousElementSibling;
                const isPassword = passwordField.type === 'password';
                passwordField.type = isPassword ? 'text' : 'password';
                this.classList.toggle('fa-eye-slash');
                this.classList.toggle('fa-eye');
            });
        });

        // Toggle between login and signup forms
        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');

        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });
    </script>
</body>
</html>