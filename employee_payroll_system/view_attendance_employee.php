<?php
session_start();
include 'db.php';

// Only employees may access this page
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employee') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$month   = date("n");
$year    = date("Y");
$today   = date("Y-m-d");

// Fetch attendance data for this user/month
$sql = "
  SELECT attendance_date, status
  FROM attendance
  WHERE employee_id = ? 
    AND MONTH(attendance_date) = ? 
    AND YEAR(attendance_date) = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $month, $year);
$stmt->execute();
$result = $stmt->get_result();

$attendance = [];
while ($row = $result->fetch_assoc()) {
    $attendance[$row['attendance_date']] = $row['status'];
}
$stmt->close();
$conn->close();

// Number of days in this month
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Attendance</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background: url('bg.jpg') no-repeat center center fixed;
      background-size: cover;
      color: white;
      font-family: Arial, sans-serif;
    }
    .container {
      max-width: 800px;
      margin: 80px auto;
      background: rgba(0,0,0,0.6);
      padding: 30px;
      border-radius: 8px;
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      color: white;
    }
    th, td {
      padding: 10px;
      border: 1px solid white;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>My Attendance — <?php echo date("F Y"); ?></h2>
    <table>
      <tr>
        <th>Date</th>
        <th>Status</th>
      </tr>
      <?php
      for ($d = 1; $d <= $daysInMonth; $d++):
        $date = sprintf("%04d-%02d-%02d", $year, $month, $d);
        echo "<tr><td>{$date}</td><td>";
        if ($date < $today) {
          // Past days
          echo isset($attendance[$date]) ? $attendance[$date] : "Missed";
        } elseif ($date === $today) {
          // Today
          echo isset($attendance[$date]) ? $attendance[$date] : "Not marked yet";
        } else {
          // Future
          echo "N/A";
        }
        echo "</td></tr>";
      endfor;
      ?>
    </table>
    <p><a href="dashboard_employee.php" style="color: #0af;">← Back to Dashboard</a></p>
  </div>
</body>
</html>