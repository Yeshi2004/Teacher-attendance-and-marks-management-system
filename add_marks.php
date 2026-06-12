<?php
session_start();
include "db.php";

/* ADMIN ONLY */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

$message = "";

if (isset($_POST['save'])) {

    $student_name = mysqli_real_escape_string($conn, $_POST['student_name']);

    $reg_no = mysqli_real_escape_string($conn, $_POST['reg_no']);

    $math = mysqli_real_escape_string($conn, $_POST['math']);

    $english = mysqli_real_escape_string($conn, $_POST['english']);

    $science = mysqli_real_escape_string($conn, $_POST['science']);

    $social = mysqli_real_escape_string($conn, $_POST['social']);

    $sql = "INSERT INTO internal_marks
    (student_name, reg_no, math, english, science, social)

    VALUES

    ('$student_name', '$reg_no', '$math',
    '$english', '$science', '$social')";

    mysqli_query($conn, $sql);

    $message = "✅ Internal Marks Added Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Internal Marks</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:Arial;
            background:#eef2ff;
            padding-top:100px;
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
        }

        .logo{
            color:white;
            font-size:24px;
            font-weight:bold;
        }

        .nav-links{
            display:flex;
            gap:12px;
        }

        .nav-links a{
            text-decoration:none;
            color:white;
            background:#444;
            padding:10px 15px;
            border-radius:8px;
        }

        .nav-links a:hover{
            background:#6f42c1;
        }

        .active{
            background:#007bff !important;
        }

        .logout{
            background:#dc3545 !important;
        }

        /* FORM */

        .container{
            width:100%;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .box{
            width:450px;
            background:white;
            padding:35px;
            border-radius:20px;
            box-shadow:0 10px 25px rgba(0,0,0,0.2);
        }

        h2{
            text-align:center;
            margin-bottom:25px;
        }

        input{
            width:100%;
            padding:12px;
            margin-bottom:15px;
            border:1px solid #ccc;
            border-radius:8px;
            font-size:15px;
        }

        button{
            width:100%;
            padding:14px;
            border:none;
            background:#6f42c1;
            color:white;
            border-radius:10px;
            font-size:16px;
            cursor:pointer;
        }

        button:hover{
            background:#5933a8;
        }

        .msg{
            background:#d4edda;
            color:#155724;
            padding:12px;
            border-radius:8px;
            margin-bottom:15px;
            text-align:center;
        }

    </style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">

    <div class="logo">
        ➕ Add Marks
    </div>

    <div class="nav-links">

        <a href="dashboard.php">
            🏠 Dashboard
        </a>

        <a href="view_marks.php">
            📊 View Marks
        </a>

        <a href="add_marks.php" class="active">
            ➕ Add Marks
        </a>

        <a href="logout.php" class="logout">
            🚪 Logout
        </a>

    </div>

</div>

<!-- FORM -->
<div class="container">

    <div class="box">

        <h2>➕ Add Internal Marks</h2>

        <?php if($message != "") { ?>
            <div class="msg">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <input
                type="text"
                name="student_name"
                placeholder="Student Name"
                required
            >

            <input
                type="text"
                name="reg_no"
                placeholder="Register Number"
                required
            >

            <input
                type="number"
                name="math"
                placeholder="Math Marks"
                required
            >

            <input
                type="number"
                name="english"
                placeholder="English Marks"
                required
            >

            <input
                type="number"
                name="science"
                placeholder="Science Marks"
                required
            >

            <input
                type="number"
                name="social"
                placeholder="Social Marks"
                required
            >

            <button type="submit" name="save">
                Save Marks
            </button>

        </form>

    </div>

</div>

</body>
</html>