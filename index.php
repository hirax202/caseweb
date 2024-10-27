<?php
session_start(); // Start session
include('conn.php');

// Define variables
$name = $password = "";
$nameErr = $passwordErr = $loginErr = "";

// Check if login form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btnLogin'])) {
    $name = strtolower(trim($_POST['name'])); // Changed from email to name
    $password = $_POST['password'] ?? '';

    // Basic validation for required fields
    if (empty($name)) {
        $nameErr = "Name is required.";
    }
    if (empty($password)) {
        $passwordErr = "Password is required.";
    }

    // If both fields are provided, proceed with login
    if (empty($nameErr) && empty($passwordErr)) {
        if ($conn) {
            $sql = "SELECT * FROM login WHERE name=?"; 
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $name); 
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // Check if a matching record is found
            if ($result && mysqli_num_rows($result) == 1) {
                $data = mysqli_fetch_assoc($result);
                // Verify the password
                if (password_verify($password, $data['password'])) {
                    $_SESSION['name'] = $data['name']; 
                    $_SESSION['today'] = date("d/m/y");

                    // Redirect to form.php on successful login
                    header("Location: form.php");
                    exit(); // Ensure redirection happens immediately
                } else {
                    $loginErr = "Invalid name or password."; 
                }
            } else {
                $loginErr = "Invalid name or password."; 
            }
            mysqli_stmt_close($stmt);
        } else {
            $loginErr = "Database connection failed.";
        }
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kidzania</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('back.jpg');
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-size: cover;
            background-position: center;
        }
        .container {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        form {
            width: 300px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        input[type="text"], input[type="password"] { /* Changed email to text */
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #ff3385;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
        }
        input[type="submit"]:hover {
            background-color: #ff0066;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h1>Login</h1>
            
            <!-- Name Field -->
            <label>Name<span class="error">* <?php echo $nameErr; ?></span></label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            
            <!-- Password Field -->
            <label>Password<span class="error">* <?php echo $passwordErr; ?></span></label>
            <input type="password" name="password" required>
            
            <!-- Error Message for Invalid Login -->
            <?php if (!empty($loginErr)): ?>
                <div class="error"><?php echo $loginErr; ?></div>
            <?php endif; ?>
            
            <!-- Submit Button -->
            <input type="submit" value="Login" name="btnLogin">
            <p>Don't have an account? <a href="signup.php">Sign up here</a></p>

        </form>
    </div>
</body>
</html>
