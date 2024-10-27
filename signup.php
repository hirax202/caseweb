<?php
session_start();
include('conn.php'); // Ensure this file connects to your database

// Define variables
$name = $email = $password = "";
$nameErr = $emailErr = $passwordErr = $signupErr = "";

// Check if sign-up form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btnSignup'])) {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    // Basic validation for required fields
    if (empty($name)) {
        $nameErr = "Name is required.";
    }
    if (empty($email)) {
        $emailErr = "Email is required.";
    }
    if (empty($password)) {
        $passwordErr = "Password is required.";
    }

    // If all fields are provided, proceed with sign-up
    if (empty($nameErr) && empty($emailErr) && empty($passwordErr)) {
        if ($conn) {
            // Check if the email already exists
            $check_sql = "SELECT * FROM login WHERE email=?";
            $stmt = mysqli_prepare($conn, $check_sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $signupErr = "Email already exists. Please choose another one.";
            } else {
                // Prepare to insert the new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert_sql = "INSERT INTO login (name, email, password) VALUES (?, ?, ?)";
                $insert_stmt = mysqli_prepare($conn, $insert_sql);
                mysqli_stmt_bind_param($insert_stmt, "sss", $name, $email, $hashed_password);

                if (mysqli_stmt_execute($insert_stmt)) {
                    $_SESSION['email'] = $email;
                    header("Location: index.php"); // Redirect to login or another page after sign-up
                    exit();
                } else {
                    $signupErr = "Error signing up. Please try again.";
                }
                mysqli_stmt_close($insert_stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $signupErr = "Database connection failed.";
        }
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <style>
        /* Your styles here */
    </style>
</head>
<body>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h1>Sign Up</h1>
            
            <!-- Name Field -->
            <label>Name<span class="error">* <?php echo $nameErr; ?></span></label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            
            <!-- Email Field -->
            <label>Email<span class="error">* <?php echo $emailErr; ?></span></label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            
            <!-- Password Field -->
            <label>Password<span class="error">* <?php echo $passwordErr; ?></span></label>
            <input type="password" name="password" required>
            
            <!-- Error Message for Sign-Up -->
            <?php if (!empty($signupErr)): ?>
                <div class="error"><?php echo $signupErr; ?></div>
            <?php endif; ?>
            
            <!-- Submit Button -->
            <input type="submit" value="Sign Up" name="btnSignup">
            <p>Already have an account? <a href="login.php">Log in here</a></p>
        </form>
    </div>
</body>
</html>
