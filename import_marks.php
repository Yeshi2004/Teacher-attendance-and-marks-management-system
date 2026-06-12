<?php
session_start();
include "db.php";

/* ADMIN ONLY */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

$message = "";

if (isset($_POST['import'])) {

    if ($_FILES['csv_file']['name']) {

        $filename = $_FILES['csv_file']['tmp_name'];

        $file = fopen($filename, "r");

        // Skip header row
        fgetcsv($file);

        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {

            $name = mysqli_real_escape_string($conn, $data[1]);
            $reg_no = mysqli_real_escape_string($conn, $data[2]);

            $math = mysqli_real_escape_string($conn, $data[3]);
            $english = mysqli_real_escape_string($conn, $data[4]);
            $science = mysqli_real_escape_string($conn, $data[5]);
            $social = mysqli_real_escape_string($conn, $data[6]);

            $sql = "INSERT INTO internal_marks
            (student_name, reg_no, math, english, science, social)
            VALUES
            ('$name', '$reg_no', '$math', '$english', '$science', '$social')";

            mysqli_query($conn, $sql);
        }

        fclose($file);

        $message = "✅ Marks Imported Successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Import Marks</title>

    <style>

        
        .container{
            width:100%;
            min-height:calc(100vh - 100px);
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .box{
            background:white;
            padding:30px;
            width:400px;
            border-radius:20px;
            box-shadow:0 10px 25px rgba(0,0,0,0.2);
        }

        h2{
            text-align:center;
            margin-bottom:20px;
        }

        input[type=file]{
            width:100%;
            margin-bottom:20px;
            padding:10px;
        }

        button{
            width:100%;
            padding:12px;
            border:none;
            background:#6f42c1;
            color:white;
            border-radius:10px;
            font-size:16px;
            cursor:pointer;
        }

        .msg{
            background:#d4edda;
            color:#155724;
            padding:10px;
            border-radius:8px;
            margin-bottom:15px;
            text-align:center;
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
    gap:12px;
    align-items:center;
}

.nav-links a{
    text-decoration:none;
    color:white;
    padding:10px 15px;
    border-radius:8px;
    background:#444;
    transition:0.3s;
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

.logout:hover{
    background:#b52a37 !important;
}

/* BODY */

body{
    font-family:Arial;
    background:#eef2ff;
    padding-top:100px;
}

    </style>
</head>

<body>
<!-- NAVBAR -->
<div class="navbar">

    <div class="logo">
        📥 Import Marks
    </div>

    <div class="nav-links">

        <a href="dashboard.php">
            🏠 Dashboard
        </a>

        <a href="view_marks.php">
            📊 View Marks
        </a>

        <a href="import_marks.php" class="active">
            📥 Import Marks
        </a>

        <a href="add_marks.php">
                ➕Add Marks

        <a href="logout.php" class="logout">
            🚪 Logout
        </a>

    </div>

</div>
<div class="container">

    <div class="box">

        <h2>📥 Import Internal Marks</h2>

        <?php if($message != "") { ?>
            <div class="msg">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST" enctype="multipart/form-data">

            <input type="file" name="csv_file" required>

            <button type="submit" name="import">
                Import CSV
            </button>

        </form>

    </div>

</div>

</body>
</html>