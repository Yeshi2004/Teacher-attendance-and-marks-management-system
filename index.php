<?php
session_start();
include 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['role'] = $row['role']; 

        header("Location: dashboard.php");
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('bg.jpg') no-repeat center center/cover;
            position: relative;
        }

/* Dark overlay */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
            pointer-events: none; /* 🔥 THIS FIXES YOUR PROBLEM */
        }

/* Login box */
        .login-box {
            position: relative;   /* important */
            z-index: 1;           /* bring above overlay */
            background: white;
            padding: 30px;
            border-radius: 15px;
            width: 300px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        /* Update your existing .login-box and add these rules */

        .login-box form {
            display: flex;
            flex-direction: column; /* Stacks elements vertically */
            gap: 15px;              /* Adds space between inputs and button */
        }

        .login-box input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;            /* Makes inputs fill the box width */
            box-sizing: border-box; /* Prevents padding from breaking width */
        }

        .login-box button {
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .login-box button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login</h2>

    <?php if ($error != "") { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>