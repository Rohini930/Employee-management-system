<?php
session_start();
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'hr') {
        header("Location: dashboard_hr.php");
    } else {
        header("Location: dashboard_employee.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Payroll System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('bg.jpg'); /* Use your actual background image path */
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            color: white;
        }
        .container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.5);
        }
        h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }
        a.button {
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 12px 25px;
            border-radius: 5px;
            font-size: 18px;
            transition: background 0.3s;
            margin: 10px;
        }
        a.button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Employee Attendance & Payroll System</h1>
        <a href="login.php" class="button">Login</a>
        <a href="register.php" class="button">Register</a>
    </div>
</body>
</html>
