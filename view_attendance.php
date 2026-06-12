<?php
session_start();
include 'db.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch users
$users = $conn->query("
    SELECT * FROM users
    WHERE role != 'admin'
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance View</title>

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

        /* CONTENT */
        .main{
            padding:120px 20px 30px;
        }

        .container{
            background:white;
            padding:20px;
            border-radius:15px;
            box-shadow:0 10px 25px rgba(0,0,0,0.2);
            overflow-x:auto;
        }

        h2{
            margin-bottom:20px;
            color:#333;
            text-align:center;
        }

        table{
            border-collapse:collapse;
            margin:auto;
            min-width:900px;
        }

        th, td{
            border:1px solid #ddd;
            padding:8px;
            text-align:center;
        }

        th{
            background:#06944d;
            color:white;
            position:sticky;
            top:0;
        }

        td:first-child,
        th:first-child{
            position:sticky;
            left:0;
            background:#06944d;
            color:white;
            font-weight:bold;
        }

        tr:nth-child(even){
            background:#f9f9f9;
        }

        tr:hover{
            background:#f1f1f1;
        }

        .present{
            background:#4CAF50;
            color:white;
            font-weight:bold;
            border-radius:4px;
        }

        .absent{
            color:#aaa;
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

        <a href="view_attendance.php" class="attendance">
            📄 View Attendance
        </a>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>

            <a href="attendance_grid.php" class="attendance">
                📅 Mark Attendance
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

        <h2>📅 March Attendance (View)</h2>

        <table>

    <tr>

        <th>Name</th>
        <th>Subject</th>

        <?php for ($d=1; $d<=31; $d++) { ?>

            <th><?php echo $d; ?></th>

        <?php } ?>

    </tr>

    <?php

    $subjects = [
        "Math",
        "English",
        "Science",
        "Social"
    ];

    while ($user = $users->fetch_assoc()) {

        foreach ($subjects as $subject) {

    ?>

    <tr>

        <td>
            <?php echo $user['username']; ?>
        </td>

        <td>
            <?php echo $subject; ?>
        </td>

        <?php

        for ($d=1; $d<=31; $d++) {

            $uid = $user['id'];

            $check = $conn->query("
                SELECT * FROM subject_attendance

                WHERE user_id = '$uid'

                AND subject_name = '$subject'

                AND day_no = '$d'

                AND status = '1'
            ");

            if ($check->num_rows > 0) {

                echo "<td class='present'>✔</td>";

            } else {

                echo "<td class='absent'>—</td>";
            }
        }

        ?>

    </tr>

    <?php

        }

    }

    ?>

</table>

        <a href="dashboard.php" class="btn">
            ⬅ Back to Dashboard
        </a>

    </div>

</div>

</body>
</html>