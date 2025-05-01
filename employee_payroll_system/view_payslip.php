<?php
session_start();
include 'db.php';

// Only employees may view their own payslips
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employee') {
    header("Location: login.php");
    exit();
}

// Use the session array, not user_id directly
$user_id = $_SESSION['user']['id'];

// Fetch all payroll records for this user, newest first
$sql = "
    SELECT month, year, total_days, present_days, absent_days, salary
    FROM payroll
    WHERE user_id = ?
    ORDER BY year DESC, month DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$payslips = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Payslips</title>
    <style>
        body {
            background: url('bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .container {
            background: rgba(0,0,0,0.6);
            padding: 30px;
            margin: 100px auto;
            width: 60%;
            border-radius: 10px;
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
            color: white;
        }
        th, td {
            border: 1px solid white;
            padding: 10px;
        }
        a.back {
            display: inline-block;
            margin-top: 20px;
            color: #0af;
            text-decoration: none;
        }
    </style>
</head>
<body>
  <div class="container">
    <h2>Your Payslips</h2>
    <?php if (empty($payslips)): ?>
      <p>No payslips generated yet.</p>
    <?php else: ?>
      <table>
        <tr>
          <th>Month</th>
          <th>Year</th>
          <th>Total Days</th>
          <th>Present</th>
          <th>Absent</th>
          <th>Salary (₹)</th>
        </tr>
        <?php foreach ($payslips as $ps): ?>
          <tr>
            <td><?= date('F', mktime(0, 0, 0, $ps['month'], 1)) ?></td>
            <td><?= $ps['year'] ?></td>
            <td><?= $ps['total_days'] ?></td>
            <td><?= $ps['present_days'] ?></td>
            <td><?= $ps['absent_days'] ?></td>
            <td><?= $ps['salary'] ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>
    <a class="back" href="dashboard_employee.php">← Back to Dashboard</a>
  </div>
</body>
</html>