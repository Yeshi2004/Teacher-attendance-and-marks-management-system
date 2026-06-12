<?php
session_start();
include 'db.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch attendance records
$result = $conn->query("
    SELECT * FROM attendance
    WHERE user_id = $user_id
    ORDER BY check_in DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance Records</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:Arial, sans-serif;
            background:linear-gradient(135deg,#d7d6e7,#f5f5ff);
        }

        /* NAVBAR */
        .navbar{
            width:100%;
            background:#222;
            padding:15px 30px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            position:fixed;
            top:0;
            left:0;
            z-index:1000;
        }

        .logo{
            color:white;
            font-size:24px;
            font-weight:bold;
        }

        .nav-links{
            display:flex;
            gap:15px;
            flex-wrap:wrap;
        }

        .nav-links a{
            text-decoration:none;
            color:white;
            padding:10px 15px;
            border-radius:8px;
            transition:0.3s;
            font-size:15px;
        }

        .nav-links a:hover{
            opacity:0.8;
        }

        .dashboard{
            background:#007bff;
        }

        .checkin{
            background:#28a745;
        }

        .checkout{
            background:#ff9800;
        }

        .attendance{
            background:#6f42c1;
        }

        .logout{
            background:#dc3545;
        }

        /* MAIN CONTENT */
        .main{
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
            padding:120px 20px 40px;
        }

        .container{
            background:white;
            padding:30px;
            border-radius:15px;
            width:90%;
            max-width:900px;
            box-shadow:0 10px 25px rgba(0,0,0,0.2);
            text-align:center;
            overflow-x:auto;
        }

        h2{
            margin-bottom:20px;
            color:#333;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th{
            background:#06944d;
            color:white;
            padding:12px;
        }

        td{
            padding:10px;
            border-bottom:1px solid #ddd;
        }

        tr:nth-child(even){
            background:#f9f9f9;
        }

        tr:hover{
            background:#f1f1f1;
        }

        .checkin-text{
            color:green;
            font-weight:bold;
        }

        .checkout-text{
            color:red;
            font-weight:bold;
        }

        .btn{
            display:inline-block;
            margin-top:20px;
            padding:10px 20px;
            background:#6c63ff;
            color:white;
            text-decoration:none;
            border-radius:8px;
            transition:0.3s;
        }

        .btn:hover{
            background:#574fd6;
        }

        @media(max-width:768px){

            .navbar{
                flex-direction:column;
                gap:15px;
            }

            .main{
                padding-top:180px;
            }
        }

    </style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">

    <div class="logo">
        📊 Attendance System
    </div>

    <div class="nav-links">

        <a href="dashboard.php" class="dashboard">
            🏠 Dashboard
        </a>

        <a href="checkin.php" class="checkin">
            ✔ Check In
        </a>

        <a href="checkout.php" class="checkout">
            ⏰ Check Out
        </a>

        <a href="view.php" class="attendance">
            📄 My Attendance
        </a>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>

            <a href="attendance_grid.php" class="attendance">
                📅 Attendance Grid
            </a>

            <a href="manage_users.php" class="attendance">
                👥 Manage Users
            </a>

        <?php } ?>

        <a href="logout.php" class="logout">
            🚪 Logout
        </a>

    </div>

</div>

<!-- MAIN CONTENT -->
<div class="main">

    <div class="container">

        <h2>📊 Attendance Records</h2>

        <table>

            <tr>
                <th>Date</th>
                <th>Check In</th>
                <th>Check Out</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()) { ?>

            <tr>

                <td>
                    <?php echo date("d M Y", strtotime($row['check_in'])); ?>
                </td>

                <td class="checkin-text">
                    <?php echo date("h:i A", strtotime($row['check_in'])); ?>
                </td>

                <td class="checkout-text">

                    <?php

                    echo $row['check_out']
                        ? date("h:i A", strtotime($row['check_out']))
                        : "—";

                    ?>

                </td>

            </tr>

            <?php } ?>

        </table>

        <a href="dashboard.php" class="btn">
            ⬅ Back to Dashboard
        </a>

    </div>

</div>

</body>
</html>