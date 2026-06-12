<?php

session_start();
include "db.php";

/* ADMIN ONLY */
if (!isset($_SESSION['user_id']) ||
    $_SESSION['role'] != 'admin') {

    header("Location: dashboard.php");
    exit();
}

/* CLEAR OLD ATTENDANCE */

mysqli_query($conn, "DELETE FROM subject_attendance");

/* SAVE NEW ATTENDANCE */

if (isset($_POST['attendance'])) {

    foreach ($_POST['attendance'] as $user_id => $subjects) {

        foreach ($subjects as $subject => $days) {

            foreach ($days as $day => $value) {

                $subject = mysqli_real_escape_string(
                    $conn,
                    $subject
                );

                $sql = "INSERT INTO subject_attendance

                (user_id, subject_name, day_no, status)

                VALUES

                ('$user_id', '$subject', '$day', '1')";

                mysqli_query($conn, $sql);
            }
        }
    }
}

header("Location: attendance_grid.php");

?>