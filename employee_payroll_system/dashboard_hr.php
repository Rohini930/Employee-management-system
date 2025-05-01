<?php
session_start();
include 'db.php';

// Only allow HRs here
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'hr') {
    header("Location: login.php");
    exit();
}

// Grab the logged‑in HR’s info
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HR Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body { 
      background: url('bg.jpg') no-repeat center center fixed;
      background-size: cover; color: white; font-family: Arial; text-align: center; 
    }
    .container { 
      margin: 80px auto; background: rgba(0,0,0,0.6); padding: 30px; border-radius: 8px; width: 80%;
    }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; color: white; }
    th, td { border: 1px solid white; padding: 10px; }
    .actions a { 
      display: inline-block; margin: 10px; padding: 10px 20px; background: #007bff; color: white; 
      text-decoration: none; border-radius: 4px;
    }
    .actions a:hover { background: #0056b3; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Welcome, <?= htmlspecialchars($user['name']) ?> (HR)</h2>
    <?php
    // Ensure the email key exists before displaying
    if (isset($user['email'])) {
        echo "<p>Email: " . htmlspecialchars($user['email']) . "</p>";
    }
    ?>
    <div class="actions">
      <a href="view_attendance_hr.php">View Attendance</a>
      <a href="generate_payroll.php">Generate Payroll</a>
      <a href="logout.php">Logout</a>
    </div>

    <h3>All Employees</h3>
    <?php
      $sql = "SELECT id, name, email, role FROM users WHERE role='employee'";
      $result = $conn->query($sql);

      if (!$result) {
          echo "<p style='color: red;'>Database error: " . htmlspecialchars($conn->error) . "</p>";
      } elseif ($result->num_rows === 0) {
          echo "<p>No employees found.</p>";
      } else {
          echo "<table>
                  <tr><th>Name</th><th>Email</th><th>Role</th></tr>";
          while ($emp = $result->fetch_assoc()) {
              echo "<tr>
                      <td>".htmlspecialchars($emp['name'])."</td>
                      <td>".htmlspecialchars($emp['email'])."</td>
                      <td>".htmlspecialchars($emp['role'])."</td>
                    </tr>";
          }
          echo "</table>";
      }

      $conn->close();
    ?>
  </div>
</body>
</html>
