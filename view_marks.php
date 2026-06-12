<?php
session_start();
include "db.php";

/* LOGIN REQUIRED */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* DELETE ONLY FOR ADMIN */
/* DELETE ONLY FOR ADMIN */
if (
    isset($_GET['delete']) &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] == 'admin'
) {

    $id = intval($_GET['delete']);

    // DELETE RECORD
    mysqli_query($conn, "DELETE FROM internal_marks WHERE id=$id");

    // CREATE TEMP SERIAL NUMBER
    mysqli_query($conn, "SET @num := 0");

    // RESET IDS SEQUENTIALLY
    mysqli_query($conn,
        "UPDATE internal_marks
        SET id = (@num := @num + 1)
        ORDER BY id"
    );

    // GET MAX ID
    $result_max = mysqli_query($conn,
        "SELECT MAX(id) AS max_id FROM internal_marks"
    );

    $row_max = mysqli_fetch_assoc($result_max);

    $next_id = $row_max['max_id'] + 1;

    if ($next_id < 1) {
        $next_id = 1;
    }

    // RESET AUTO_INCREMENT
    mysqli_query($conn,
        "ALTER TABLE internal_marks AUTO_INCREMENT = $next_id"
    );

    header("Location: view_marks.php");
    exit();
}

/* FETCH DATA */
$sql = "SELECT * FROM internal_marks ORDER BY id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Internal Marks</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        
        h2{
            text-align:center;
            margin-bottom:30px;
            color:#333;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:white;
            box-shadow:0 5px 15px rgba(0,0,0,0.1);
        }

        table th{
            background:#6f42c1;
            color:white;
            padding:14px;
        }

        table td{
            padding:12px;
            text-align:center;
            border-bottom:1px solid #ddd;
        }

        tr:hover{
            background:#f1f1f1;
        }

        .delete-btn{
            background:#dc3545;
            color:white;
            padding:8px 12px;
            text-decoration:none;
            border-radius:6px;
            font-size:14px;
        }

        .delete-btn:hover{
            background:#b52a37;
        }

        .back{
            display:inline-block;
            margin-top:20px;
            padding:12px 18px;
            background:#6f42c1;
            color:white;
            text-decoration:none;
            border-radius:8px;
        }

        .edit-btn{
            background:#007bff;
            color:white;
            padding:8px 12px;
            text-decoration:none;
            border-radius:6px;
            font-size:14px;
            margin-right:5px;
        }

        .edit-btn:hover{
            background:#0056b3;
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

/* PAGE SPACING */

body{
    font-family:Arial;
    background:#f4f6ff;
    padding:100px 30px 30px;
}
    </style>
</head>

<body>
<!-- NAVBAR -->
<div class="navbar">

    <div class="logo">
        📊 Internal Marks
    </div>

    <div class="nav-links">

        <a href="dashboard.php">
            🏠 Dashboard
        </a>

        <a href="view_marks.php" class="active">
            📋 View Marks
        </a>

        <?php if ($_SESSION['role'] == 'admin') { ?>

            <a href="import_marks.php">
                📥 Import Marks
            </a>

            <a href="add_marks.php">
                ➕Add Marks

        <?php } ?>

        <a href="logout.php" class="logout">
            🚪 Logout
        </a>

    </div>

</div>
<h2>📊 Internal Marks</h2>

<table>

    <tr>
        <th>ID</th>
        <th>Student Name</th>
        <th>Register No</th>
        <th>Math</th>
        <th>English</th>
        <th>Science</th>
        <th>Social</th>

        <!-- ONLY ADMIN SEES ACTION -->
        <?php if ($_SESSION['role'] == 'admin') { ?>
            <th>Actions</th>
        <?php } ?>

    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>

    <tr>

        <td><?php echo $row['id']; ?></td>

        <td><?php echo $row['student_name']; ?></td>

        <td><?php echo $row['reg_no']; ?></td>

        <td><?php echo $row['math']; ?></td>

        <td><?php echo $row['english']; ?></td>

        <td><?php echo $row['science']; ?></td>

        <td><?php echo $row['social']; ?></td>

        <!-- DELETE BUTTON ONLY FOR ADMIN -->
        <?php if ($_SESSION['role'] == 'admin') { ?>

        <td>

    <!-- EDIT BUTTON -->
            <a
                href="edit_marks.php?id=<?php echo $row['id']; ?>"
                class="edit-btn"
            >
                ✏ Edit
            </a>

    <!-- DELETE BUTTON -->
            <a
                href="view_marks.php?delete=<?php echo $row['id']; ?>"
                class="delete-btn"
                onclick="return confirm('Are you sure you want to delete this record?');"
            >
                🗑 Delete
            </a>

        </td>

        <?php } ?>

    </tr>

    <?php } ?>

</table>

<a href="dashboard.php" class="back">
    ← Back to Dashboard
</a>

</body>
</html>