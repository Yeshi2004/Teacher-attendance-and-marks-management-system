<?php
session_start();   // ✅ ONLY ONCE

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #d7d6e7);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .dashboard {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            width: 380px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        h2 {
            margin-bottom: 25px;
            color: #333;
        }

        a {
            display: block;
            margin: 10px 0;
            padding: 12px;
            text-decoration: none;
            color: white;
            background: #06944d;
            border-radius: 10px;
            transition: 0.3s;
            font-size: 16px;
        }

        a:hover {
            background: #574fd6;
            transform: scale(1.05);
        }

        .view {
            background: #007bff;
        }

        .view:hover {
            background: #0056b3;
        }

        .grid {
            background: #ff9800;
        }

        .grid:hover {
            background: #e68900;
        }

        .admin {
            background: #6f42c1;
        }

        .admin:hover {
            background: #5933a8;
        }

        .logout {
            background: #ff4d4d;
        }

        .logout:hover {
            background: #e60000;
        }

    </style>

</head>

<body>

<div class="dashboard">

    <h2>📊 Dashboard</h2>

    <a href="checkin.php">
        ✔ Check In
    </a>

    <a href="checkout.php">
        ⏰ Check Out
    </a>

    <a href="view.php">
        📄 View Checkdate
    </a>
    
    <a href="view_attendance.php" class="view">
        📄 View Attendance Table
    </a>

    <!-- ADMIN ONLY -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>

        <a href="attendance_grid.php" class="grid">
            📅 Mark Attendence
        </a>

        <a href="add_user.php" class="admin">
            ➕ Add User
        </a>

        <a href="manage_users.php" class="admin">
            👥 Manage Users
        </a>
        <a href="add_marks.php" class="admin">
    ➕ Add Internal Marks
        </a>
        <!-- IMPORT MARKS -->
        <a href="import_marks.php" class="admin">
            📥 Import Internal Marks
        </a>
    <?php }?>
        <!-- VIEW MARKS -->
        <a href="view_marks.php" class="view">
            📊 View Internal Marks
        </a>


    <a href="logout.php" class="logout">
        🚪 Logout
    </a>

</div>

</body>
</html>