<?php
session_start(); // added session_start() to start session
include('database.php');

if (isset($_POST['update_user'])) {

    $userid = (int) $_SESSION['detsuid'];
    $username = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // Update the user's information in the database using an UPDATE query
    $result = db_query($db, "UPDATE users SET name=?, email=?, phone=? WHERE id=?", "sssi", $username, $email, $phone, $userid);
    if ($result) {
        $message = "Profile updated successfully";
        echo "<script type='text/javascript'>alert('$message');</script>";
        echo " <script type='text/javascript'>window.location.href = 'user_profile.php';</script>";
        exit();
    } else {
        // Handle the error case
        echo "Error updating user information: " . mysqli_error($db);
    }
}

?>