<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'employee') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$today = date('Y-m-d');
$message = "";

// Check if attendance is already marked for today
$query = "SELECT * FROM attendance WHERE employee_id = $user_id AND attendance_date = '$today'";
$result = $conn->query($query);
$attendanceMarked = $result->num_rows > 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$attendanceMarked) {
    $status = $_POST['status'];
    $stmt = $conn->prepare("INSERT INTO attendance (employee_id, attendance_date, status) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $today, $status);
    $stmt->execute();

    // If status is 'Present', insert or update payroll record
    if ($status == 'Present') {
        $month = date('n');
        $year = date('Y');

        $payrollCheck = $conn->query("SELECT * FROM payroll WHERE user_id = $user_id AND month = $month AND year = $year");
        if ($payrollCheck->num_rows > 0) {
            $conn->query("UPDATE payroll SET present_days = present_days + 1, total_days = total_days + 1, salary = salary + 1000 WHERE user_id = $user_id AND month = $month AND year = $year");
        } else {
            $conn->query("INSERT INTO payroll (user_id, month, year, total_days, present_days, absent_days, salary) VALUES ($user_id, $month, $year, 1, 1, 0, 1000)");
        }
    } else {
        $month = date('n');
        $year = date('Y');
        $payrollCheck = $conn->query("SELECT * FROM payroll WHERE user_id = $user_id AND month = $month AND year = $year");
        if ($payrollCheck->num_rows > 0) {
            $conn->query("UPDATE payroll SET total_days = total_days + 1, absent_days = absent_days + 1 WHERE user_id = $user_id AND month = $month AND year = $year");
        } else {
            $conn->query("INSERT INTO payroll (user_id, month, year, total_days, present_days, absent_days, salary) VALUES ($user_id, $month, $year, 1, 0, 1, 0)");
        }
    }

    $message = "Attendance marked successfully!";
    $attendanceMarked = true;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mark Attendance</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="center-box">
        <h2>Mark Attendance</h2>
        <?php if ($attendanceMarked): ?>
            <p>Attendance already marked for today (<?php echo $today; ?>).</p>
        <?php else: ?>
            <form method="post">
                <label>
                    <input type="radio" name="status" value="Present" required> Present
                </label>
                <label>
                    <input type="radio" name="status" value="Absent" required> Absent
                </label>
                <br><br>
                <button type="submit">Submit</button>
            </form>
        <?php endif; ?>
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <br>
        <a href="dashboard_employee.php">Back to Dashboard</a>
    </div>
</body>
</html>
