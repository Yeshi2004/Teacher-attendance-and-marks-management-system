<?php
session_start();
include "db.php";

/* ONLY ADMIN */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

/* GET RECORD */
$id = $_GET['id'];

$sql = "SELECT * FROM internal_marks WHERE id='$id'";

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);

/* UPDATE */
if (isset($_POST['update'])) {

    $student_name = $_POST['student_name'];
    $reg_no = $_POST['reg_no'];

    $math = $_POST['math'];
    $english = $_POST['english'];
    $science = $_POST['science'];
    $social = $_POST['social'];

    $update_sql = "UPDATE internal_marks SET

    student_name='$student_name',
    reg_no='$reg_no',
    math='$math',
    english='$english',
    science='$science',
    social='$social'

    WHERE id='$id'";

    mysqli_query($conn, $update_sql);

    header("Location: view_marks.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Marks</title>

    <style>

        body{
            font-family:Arial;
            background:#eef2ff;
        }

        .container{
            width:100%;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .box{
            background:white;
            padding:35px;
            width:450px;
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

    </style>
</head>

<body>

<div class="container">

    <div class="box">

        <h2>✏ Edit Internal Marks</h2>

        <form method="POST">

            <input
                type="text"
                name="student_name"
                value="<?php echo $row['student_name']; ?>"
                required
            >

            <input
                type="text"
                name="reg_no"
                value="<?php echo $row['reg_no']; ?>"
                required
            >

            <input
                type="number"
                name="math"
                value="<?php echo $row['math']; ?>"
                required
            >

            <input
                type="number"
                name="english"
                value="<?php echo $row['english']; ?>"
                required
            >

            <input
                type="number"
                name="science"
                value="<?php echo $row['science']; ?>"
                required
            >

            <input
                type="number"
                name="social"
                value="<?php echo $row['social']; ?>"
                required
            >

            <button type="submit" name="update">
                Update Marks
            </button>

        </form>

    </div>

</div>

</body>
</html>