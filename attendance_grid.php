<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

include 'db.php';

// Fetch users
$users = $conn->query(
    "SELECT * FROM users
    WHERE role != 'admin'"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance Grid</title>

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

        .users{
            background:#17a2b8;
        }

        .logout{
            background:#dc3545;
        }

        /* CONTENT */
        .main{
            padding:110px 20px 30px;
        }

        .container{
            background:white;
            padding:20px;
            border-radius:15px;
            box-shadow:0 10px 25px rgba(0,0,0,0.2);
            overflow-x:auto;
        }

        h2{
            color:#333;
            margin-bottom:20px;
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

        input[type="checkbox"]{
            transform:scale(1.2);
            cursor:pointer;
        }

        button{
            margin-top:20px;
            padding:12px 25px;
            border:none;
            background:#6c63ff;
            color:white;
            border-radius:10px;
            font-size:16px;
            cursor:pointer;
            transition:0.3s;
        }

        button:hover{
            background:#574fd6;
        }

        @media(max-width:768px){

            .navbar{
                flex-direction:column;
                gap:15px;
            }

            .main{
                padding-top:170px;
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

        <a href="manage_users.php" class="users">
            👥 Manage Users
        </a>

        <a href="attendance_grid.php" class="attendance">
            📅 Mark Attendance 
        </a>

        <a href="logout.php" class="logout">
            🚪 Logout
        </a>

    </div>

</div>

<!-- MAIN CONTENT -->
<div class="main">

    <div class="container">

        <h2>📅 Monthly Attendance Grid</h2>

        <form method="POST" action="save_attendance.php">

            <table>

<tr>

    <th>Student</th>
    <th>Subject</th>

    <?php for ($d = 1; $d <= 31; $d++) { ?>
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

while ($row = $users->fetch_assoc()) {

    foreach ($subjects as $subject) {

?>

<tr>

    <td>
        <?php echo $row['username']; ?>
    </td>

    <td>
        <?php echo $subject; ?>
    </td>

    <?php for ($d = 1; $d <= 31; $d++) {

        // CHECK SAVED ATTENDANCE
        $uid = $row['id'];

        $check_sql = "SELECT * FROM subject_attendance

        WHERE user_id='$uid'
        AND subject_name='$subject'
        AND day_no='$d'
        AND status='1'";

        $check_result = mysqli_query($conn, $check_sql);

        $checked = mysqli_num_rows($check_result) > 0
            ? "checked"
            : "";

    ?>

    <td>

        <input
            type="checkbox"

            name="attendance[<?php echo $uid; ?>][<?php echo $subject; ?>][<?php echo $d; ?>]"

            <?php echo $checked; ?>
        >

    </td>

    <?php } ?>

</tr>

<?php

    }

}

?>

</table>

            <button type="submit">
                💾 Save Attendance
            </button>

        </form>

    </div>

</div>

</body>
</html>