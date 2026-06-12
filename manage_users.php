<?php
session_start();
include 'db.php';

// Only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

// Delete user
if (isset($_GET['delete'])) {

    $id = (int)$_GET['delete'];

    // Prevent admin from deleting self
    if ($id != $_SESSION['user_id']) {

        // Delete subject attendance records
        $stmt1 = $conn->prepare("DELETE FROM subject_attendance WHERE user_id = ?");
        $stmt1->bind_param("i", $id);
        $stmt1->execute();

        // Delete attendance records
        $stmt2 = $conn->prepare("DELETE FROM attendance WHERE user_id = ?");
        $stmt2->bind_param("i", $id);
        $stmt2->execute();

        // Delete user
        $stmt3 = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt3->bind_param("i", $id);

        if ($stmt3->execute()) {
            echo "<script>
                    alert('User deleted successfully!');
                    window.location='manage_users.php';
                  </script>";
            exit();
        } else {
            echo "Error: " . $stmt3->error;
        }
    }
}

// Fetch users
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>

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
        }

        .dashboard{
            background:#007bff;
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

        .main{
            padding:120px 20px 30px;
        }

        .container{
            background:white;
            padding:25px;
            border-radius:15px;
            box-shadow:0 10px 25px rgba(0,0,0,0.2);
            max-width:900px;
            margin:auto;
        }

        h2{
            text-align:center;
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th, td{
            border:1px solid #ccc;
            padding:12px;
            text-align:center;
        }

        th{
            background:#6c63ff;
            color:white;
        }

        tr:nth-child(even){
            background:#f9f9f9;
        }

        .delete{
            background:red;
            color:white;
            padding:8px 12px;
            border-radius:6px;
            text-decoration:none;
        }

        .delete:hover{
            background:darkred;
        }

        .back{
            display:inline-block;
            margin-top:20px;
            padding:10px 18px;
            background:#007bff;
            color:white;
            text-decoration:none;
            border-radius:8px;
        }

        .back:hover{
            background:#0056b3;
        }
    </style>
</head>

<body>

<div class="navbar">

    <div class="logo">
        📊 Attendance System
    </div>

    <div class="nav-links">

        <a href="dashboard.php" class="dashboard">
            🏠 Dashboard
        </a>

        <a href="attendance_grid.php" class="attendance">
            📅 Mark Attendance
        </a>

        <a href="add_user.php" class="users">
            ➕ Add User
        </a>

        <a href="manage_users.php" class="users">
            👥 Manage Users
        </a>

        <a href="logout.php" class="logout">
            🚪 Logout
        </a>

    </div>

</div>

<div class="main">

    <div class="container">

        <h2>👥 Manage Users</h2>

        <table>

            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Action</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()) { ?>

            <tr>

                <td><?php echo $row['id']; ?></td>

                <td><?php echo $row['username']; ?></td>

                <td>
                    <?php
                    echo !empty($row['role'])
                        ? ucfirst($row['role'])
                        : 'User';
                    ?>
                </td>

                <td>

                    <?php if ($row['id'] != $_SESSION['user_id']) { ?>

                        <a class="delete"
                           href="?delete=<?php echo $row['id']; ?>"
                           onclick="return confirm('Are you sure you want to delete this user?')">
                           Delete
                        </a>

                    <?php } else { ?>

                        <span style="color:gray;">
                            Current User
                        </span>

                    <?php } ?>

                </td>

            </tr>

            <?php } ?>

        </table>

        <a href="dashboard.php" class="back">
            ⬅ Back to Dashboard
        </a>

    </div>

</div>

</body>
</html>