<?php
// Include db_conn.php for database connection
include "db_conn.php";

// Function to sanitize user input
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form is submitted
if(isset($_POST['uname']) && isset($_POST['password'])) {
    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']);

    // Check if username and password are not empty
    if(empty($uname) || empty($pass)) {
        header("Location: index.php?error=Username and Password are required");
        exit();
    } else {
        // Query the database to check if the user exists
        $sql = "SELECT * FROM users WHERE user_name='$uname'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            // Password verification
            if(password_verify($pass, $row['password'])) {
                // Password matches, start session and redirect
                session_start();
                $_SESSION['user_name'] = $row['user_name'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];
                header("Location: home.php");
                exit();
            } else {
                // Password doesn't match
                header("Location: index.php?error=Incorrect Password");
                exit();
            }
        } else {
            // No user found
            header("Location: index.php?error=User not found");
            exit();
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>
