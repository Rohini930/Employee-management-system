<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, name, role, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $role, $db_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && $password === $db_password) {
        $_SESSION['user'] = ['id' => $id, 'name' => $name, 'role' => $role];
        if ($role === 'hr') {
            header("Location: dashboard_hr.php");
        } else {
            header("Location: dashboard_employee.php");
        }
        exit();
    } else {
        $error = "Invalid credentials.";
    }
}
?>
<!-- HTML form remains unchanged -->

<!-- Simple HTML Login Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css"> <!-- optional -->
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>
