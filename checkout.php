<?php
session_start();
include 'db.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$message = "";
$type = "";

// Update latest check-in without checkout
$stmt = $conn->prepare("
    UPDATE attendance 
    SET check_out = NOW() 
    WHERE user_id = ? 
    AND check_out IS NULL
    ORDER BY id DESC 
    LIMIT 1
");

$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {
        $message = "✅ Checked Out Successfully!";
        $type = "success";
    } else {
        $message = "⚠ No active check-in found!";
        $type = "warning";
    }

} else {

    $message = "❌ Error while checking out!";
    $type = "error";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Check Out</title>

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
        .container{
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            padding-top:80px;
        }

        .box{
            background:white;
            padding:40px;
            border-radius:20px;
            text-align:center;
            width:350px;
            box-shadow:0 10px 25px rgba(0,0,0,0.2);
        }

        h2{
            margin-bottom:20px;
            color:#333;
        }

        .message{
            padding:15px;
            border-radius:10px;
            margin-bottom:20px;
            font-size:16px;
            font-weight:bold;
        }

        .success{
            background:#d4edda;
            color:#155724;
        }

        .warning{
            background:#fff3cd;
            color:#856404;
        }

        .error{
            background:#f8d7da;
            color:#721c24;
        }

        .back-btn{
            display:inline-block;
            margin-top:10px;
            padding:12px 20px;
            background:#007bff;
            color:white;
            text-decoration:none;
            border-radius:10px;
            transition:0.3s;
        }

        .back-btn:hover{
            background:#0056b3;
        }

        @media(max-width:768px){

            .navbar{
                flex-direction:column;
                gap:15px;
            }

            .container{
                padding-top:140px;
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

        <a href="dashboard.php" class="dashboard">🏠 Dashboard</a>

        <a href="checkin.php" class="checkin">✔ Check In</a>

        <a href="checkout.php" class="checkout">⏰ Check Out</a>

        <a href="view_attendance.php" class="attendance">
            📄 Attendance
        </a>

        <a href="logout.php" class="logout">🚪 Logout</a>

    </div>

</div>

<!-- CONTENT -->
<div class="container">

    <div class="box">

        <h2>⏰ Check Out</h2>

        <div class="message <?php echo $type; ?>">
            <?php echo $message; ?>
        </div>

        <a href="dashboard.php" class="back-btn">
            ⬅ Back to Dashboard
        </a>

    </div>

</div>

</body>
</html>