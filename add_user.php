<?php
session_start();
include 'db.php';

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);
    $role = $_POST['role'];

    // Check if username exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $user);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {

        $error = "⚠ Username already exists!";

    } else {

        // Insert user
        $stmt = $conn->prepare("
            INSERT INTO users (username, password, role)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param("sss", $user, $pass, $role);

        if ($stmt->execute()) {
            $success = "✅ User Added Successfully!";
        } else {
            $error = "❌ Error adding user!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>

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
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            padding-top:80px;
        }

        .container{
            background:white;
            padding:30px;
            border-radius:15px;
            width:350px;
            text-align:center;
            box-shadow:0 10px 25px rgba(0,0,0,0.2);
        }

        h2{
            margin-bottom:20px;
            color:#333;
        }

        input, select{
            width:100%;
            padding:12px;
            margin:10px 0;
            border:1px solid #ccc;
            border-radius:8px;
            outline:none;
            font-size:15px;
        }

        input:focus,
        select:focus{
            border-color:#6c63ff;
        }

        button{
            width:100%;
            padding:12px;
            background:#6c63ff;
            border:none;
            color:white;
            border-radius:8px;
            font-size:16px;
            cursor:pointer;
            transition:0.3s;
        }

        button:hover{
            background:#574fd6;
        }

        .success{
            background:#d4edda;
            color:#155724;
            padding:10px;
            border-radius:8px;
            margin-bottom:15px;
        }

        .error{
            background:#f8d7da;
            color:#721c24;
            padding:10px;
            border-radius:8px;
            margin-bottom:15px;
        }

        .back{
            display:inline-block;
            margin-top:15px;
            text-decoration:none;
            color:white;
            background:#ff4d4d;
            padding:10px 18px;
            border-radius:8px;
            transition:0.3s;
        }

        .back:hover{
            background:#e60000;
        }

        @media(max-width:768px){

            .navbar{
                flex-direction:column;
                gap:15px;
            }

            .main{
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

        <a href="dashboard.php" class="dashboard">
            🏠 Dashboard
        </a>

        <a href="attendance_grid.php" class="attendance">
            📅 Mark Attendance
        </a>

        <a href="manage_users.php" class="users">
            👥 Manage Users
        </a>

        <a href="add_user.php" class="users">
            ➕ Add User
        </a>

        <a href="logout.php" class="logout">
            🚪 Logout
        </a>

    </div>

</div>

<!-- MAIN CONTENT -->
<div class="main">

    <div class="container">

        <h2>➕ Add User</h2>

        <?php if ($success != "") { ?>
            <div class="success">
                <?php echo $success; ?>
            </div>
        <?php } ?>

        <?php if ($error != "") { ?>
            <div class="error">
                <?php echo $error; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <input type="text"
                   name="username"
                   placeholder="Enter Username"
                   required>

            <input type="password"
                   name="password"
                   placeholder="Enter Password"
                   required>

            <select name="role">

                <option value="user">
                    User
                </option>

                <option value="admin">
                    Admin
                </option>

            </select>

            <button type="submit">
                Add User
            </button>

        </form>

        <a href="dashboard.php" class="back">
            ⬅ Back
        </a>

    </div>

</div>

</body>
</html>