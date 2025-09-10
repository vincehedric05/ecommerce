<?php
session_start();
include 'db.php';

$message = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['firstname'];
            header("Location: index.php");
            exit;
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="header.css">
</head>
<body>

    <!-- 🔹 Include header -->
    <?php include 'header.php'; ?>

    <!-- 🔹 Wrapper to center login box below header -->
    <div class="login-wrapper">
        <div class="login-container">
            <h2>Login</h2>
            <form method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
            
            <p><?php echo $message; ?></p>
            
            <p class="account-link">
                Don’t have an account? <a href="signup.php">Sign up here</a>
            </p>
        </div>
    </div>

</body>
</html>

