<?php
session_start();
include 'db.php';

$message = "";

if (isset($_POST['signup'])) {
    $fname = trim($_POST['firstname']);
    $lname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $repeat = $_POST['repeat_password'];

    if ($password !== $repeat) {
        $message = "Passwords do not match!";
    } else {
        $hashed = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fname, $lname, $email, $hashed);

        if ($stmt->execute()) {
            $message = "Signup successful! <a href='login.php'>Login here</a>";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <link rel="stylesheet" href="signup.css">
    <link rel="stylesheet" href="header.css">
</head>
<body>
    <!-- 🔹 Add header here -->
    <?php include 'header.php'; ?>

    <div class="form-container">
        <h2>Create Account</h2>
        <form method="POST">
            <input type="text" name="firstname" placeholder="First Name" required>
            <input type="text" name="lastname" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="repeat_password" placeholder="Repeat Password" required>
            <button type="submit" name="signup">Sign Up</button>
        </form>

        <!-- Message display -->
        <p><?php echo $message; ?></p>

        <!-- Already have an account link -->
        <p class="account-link">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</body>
</html>
