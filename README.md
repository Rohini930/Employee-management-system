# 🧾 Employee Attendance and Payroll Management System

## 📌 Project Overview

The **Employee Attendance and Payroll Management System** is a web-based application designed to **automate** and **simplify** the processes of employee attendance tracking and payroll generation in an organization. Built with PHP, MySQL, HTML, and CSS, the system enhances accuracy, transparency, and administrative efficiency.

It features **role-based access control** for two user types:
- **HR**: Full administrative access
- **Employee**: Access to their own attendance and payroll

This modular, secure, and user-friendly application eliminates manual paperwork, reduces errors, and ensures proper documentation and payroll processing.

---

## 👥 User Roles

### 🔹 HR (Admin Role)
- Can view all employee details
- Access attendance and payroll for any employee by month and year
- Manage system data centrally

### 🔸 Employee
- Can register and log in
- Mark daily attendance
- View monthly attendance status and payroll
- See categorized attendance:
  - ✅ Present
  - ❌ Absent
  - ⚠️ Missed (unmarked past days)
  - ⏳ Not marked yet (for today)
  - ⛔ N/A (for future dates)

---

## 🚀 Key Features

- 🧍 Employee self-registration and login
- 📅 Daily attendance marking
- 🧾 Automatic monthly payroll calculation
- 📊 Monthly attendance overview
- 🔒 Role-based access control (HR vs. Employee)
- 🧮 Dynamic payslip generation based on:
  - Working days
  - Days marked Present
- 🌐 Responsive and clean UI
- 💾 MySQL data storage
- 🔐 All credentials stored in plain text (⚠️ for demo purposes only)

---

## 🛠️ Tech Stack

| Component    | Technology Used           |
|--------------|----------------------------|
| Frontend     | HTML, CSS                  |
| Backend      | PHP                        |
| Database     | MySQL (XAMPP, port 3307)   |
| Server       | Apache (via XAMPP)         |
| IDE          | Visual Studio Code         |

---

## ⚙️ Setup Instructions

### 📥 Prerequisites
- XAMPP installed (Apache + MySQL)
- Visual Studio Code or any IDE

### 🔧 Installation Steps

1. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start **Apache** and **MySQL**

2. **Create MySQL Database**
   - Go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   - Create a new database named:
     ```
     attendance_payroll_db
     ```

3. **Import SQL File**
   - Use the **Import** tab in phpMyAdmin
   - Select the provided `attendance_payroll_db.sql` file

4. **Copy Project Files**
   - Copy your project folder to:
     ```
     C:\xampp\htdocs\
     ```
   - Example path:
     ```
     C:\xampp\htdocs\employee-attendance-payroll\
     ```

5. **Check Database Connection**
   - Open your PHP DB config file (e.g., `db.php`)
   - Confirm:
     ```php
     $conn = new mysqli("localhost", "root", "", "attendance_payroll_db", 3307);
     ```

6. **Run the Application**
   - Open browser and visit:
     ```
     http://localhost/employee-attendance-payroll/
     ```


