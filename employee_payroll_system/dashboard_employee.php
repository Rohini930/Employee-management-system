<?php
include 'session.php';
if ($_SESSION['user']['role'] !== 'employee') {
    header("Location: login.php");
    exit();
}
$user = $_SESSION['user'];
?>

<h1>Welcome,  <?= htmlspecialchars($user['name']) ?>(Employee)</h1>
<link rel="stylesheet" href="style.css">
<div class="dashboard-buttons">
    <a href="mark_attendance.php">Mark Attendance</a><br>
    <a href="view_attendance_employee.php">View Monthly Attendance</a><br>
    <a href="view_payslip.php">View Payslip</a><br>
    <a href="logout.php">Logout</a>
</div>
