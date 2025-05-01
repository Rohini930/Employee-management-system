<?php
session_start();
include 'db.php';

// Only HR may access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'hr') {
    header('Location: login.php');
    exit();
}

// Fetch employee list for the dropdown
$empStmt = $conn->prepare("SELECT id, name FROM users WHERE role = 'employee'");
$empStmt->execute();
$employees = $empStmt->get_result();
$empStmt->close();

// Defaults
$selected_employee = '';
$selected_month    = date('m');
$selected_year     = date('Y');
$payroll_data      = [];

// When form is submitted, fetch payroll for that employee/month/year
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_employee = $_POST['employee_id'];
    $selected_month    = $_POST['month'];
    $selected_year     = $_POST['year'];

    // Query to fetch payroll details for the selected employee and period
    $payrollStmt = $conn->prepare("
        SELECT * 
        FROM payroll 
        WHERE user_id = ? 
          AND month = ? 
          AND year = ?
    ");
    $payrollStmt->bind_param("iii", $selected_employee, $selected_month, $selected_year);
    $payrollStmt->execute();
    $payrollResult = $payrollStmt->get_result();
    
    if ($payrollResult->num_rows > 0) {
        $payroll_data = $payrollResult->fetch_assoc();
    }
    $payrollStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Generate Payroll</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background: url('bg.jpg') no-repeat center center fixed;
      background-size: cover; color: white; font-family: Arial; text-align: center;
    }
    .container {
      max-width: 800px; margin: 80px auto; background: rgba(0,0,0,0.6);
      padding: 30px; border-radius: 8px;
    }
    form {
      margin-bottom: 30px;
    }
    select, input[type="number"], button {
      padding: 8px; margin-bottom: 0 5px;
      border-radius: 4px; border: none;
    }
    table {
      width: 100%; border-collapse: collapse; margin-top: 20px; color: white;
    }
    th, td {
      border: 1px solid white; padding: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Generate Payroll</h2>

    <form method="POST" action="">
      <label for="employee_id">Employee:</label>
      <select name="employee_id" id="employee_id" required>
        <option value="">-- Select Employee --</option>
        <?php while ($emp = $employees->fetch_assoc()): 
            $sel = ($emp['id'] == $selected_employee) ? 'selected' : '';
        ?>
          <option value="<?= $emp['id'] ?>" <?= $sel ?>>
            <?= htmlspecialchars($emp['name']) ?>
          </option>
        <?php endwhile; ?>
      </select>

      <label for="month">Month:</label>
      <input type="number" name="month" id="month" min="1" max="12"
             value="<?= htmlspecialchars($selected_month) ?>" required>

      <label for="year">Year:</label>
      <input type="number" name="year" id="year" min="2000" max="2100"
             value="<?= htmlspecialchars($selected_year) ?>" required>

      <button type="submit">View Payroll</button>
    </form>

    <?php if ($payroll_data): ?>
      <h3>Payroll for <?= htmlspecialchars(date('F', mktime(0,0,0,$selected_month,1))) ?> <?= htmlspecialchars($selected_year) ?></h3>
      <table>
        <tr><th>Total Days</th><th>Present Days</th><th>Absent Days</th><th>Salary</th></tr>
        <tr>
          <td><?= htmlspecialchars($payroll_data['total_days']) ?></td>
          <td><?= htmlspecialchars($payroll_data['present_days']) ?></td>
          <td><?= htmlspecialchars($payroll_data['absent_days']) ?></td>
          <td><?= htmlspecialchars($payroll_data['salary']) ?></td>
        </tr>
      </table>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
      <p>No payroll data found for the selected employee and period.</p>
    <?php endif; ?>

    <p><a href="dashboard_hr.php" style="color: #0af;">← Back to Dashboard</a></p>
  </div>
</body>
</html>
