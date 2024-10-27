<?php
include("conn.php");
$row = [];
$message = '';

// Check if ID is set in URL
if (isset($_GET['ID'])) {
    $id = $_GET['ID'];
    

    $stmt = mysqli_prepare($conn, "SELECT * FROM ticket_sales WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        $message = "Record not found.";
    }
    mysqli_stmt_close($stmt);
}

// Handle form submission for update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $visitorName = $_POST['visitorName'];
    $visitorEmail = $_POST['visitorEmail'];
    $infants = $_POST['infants'];
    $toddlers = $_POST['toddlers'];
    $kids = $_POST['kids'];
    $adults = $_POST['adults'];
    $senior_citizens = $_POST['senior_citizens'];
    $disabled = $_POST['disabled'];

    // Use a prepared statement to update the record
    $stmt = mysqli_prepare($conn, "UPDATE ticket_sales SET visitorName = ?, visitorEmail = ?, infants = ?, toddlers = ?, kids = ?, adults = ?, senior_citizens = ?, 
	disabled = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssiiiiiii", $visitorName, $visitorEmail, $infants, $toddlers, $kids, $adults, $senior_citizens, $disabled, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $message = "Record updated successfully.";
    } else {
        $message = "Error updating record: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0b27a;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
        .message {
            text-align: center;
            color: green;
            font-weight: bold;
            margin: 10px 0;
        }
        .error {
            color: red;
        }
        .container {
            width: 400px;
            margin: 30px auto;
            padding: 20px;
            background-color: #f6ddcc;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        form input[type="text"], form input[type="submit"] {
            width: 100%;
            padding: 7px;
            margin: 7px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form input[type="submit"] {
            background-color: #FFA500;
            color: white;
            border: none;
            cursor: pointer;
        }
        form input[type="submit"]:hover {
            background-color: #45a049;
        }
        .back-button {
            display: inline-block;
            width: 50%;
            text-align: center;
            padding: 6px;
            margin-top: 10px;
            background-color: #ff6600;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #ff3385;
        }
    </style>
</head>
<body>
    <h2>Update Ticket</h2>

    <!-- Display message -->
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Container for the form -->
    <div class="container">
        <form method="POST" action="update.php">
            <input type="hidden" name="id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
            
            Visitor Name: <input type="text" name="visitorName" value="<?php echo isset($row['visitorName']) ? htmlspecialchars($row['visitorName']) : ''; ?>" required><br>
            
            Visitor Email: <input type="text" name="visitorEmail" value="<?php echo isset($row['visitorEmail']) ? htmlspecialchars($row['visitorEmail']) : ''; ?>" required><br>
            
            Infants: <input type="text" name="infants" value="<?php echo isset($row['infants']) ? htmlspecialchars($row['infants']) : ''; ?>" required><br>
            
            Toddlers: <input type="text" name="toddlers" value="<?php echo isset($row['toddlers']) ? htmlspecialchars($row['toddlers']) : ''; ?>" required><br>
            
            Kids: <input type="text" name="kids" value="<?php echo isset($row['kids']) ? htmlspecialchars($row['kids']) : ''; ?>" required><br>
            
            Adults: <input type="text" name="adults" value="<?php echo isset($row['adults']) ? htmlspecialchars($row['adults']) : ''; ?>" required><br>
            
            Senior Citizens: <input type="text" name="senior_citizens" value="<?php echo isset($row['senior_citizens']) ? htmlspecialchars($row['senior_citizens']) : ''; ?>" 
			required><br>
            
            Disabled: <input type="text" name="disabled" value="<?php echo isset($row['disabled']) ? htmlspecialchars($row['disabled']) : ''; ?>" required><br>
            
            <input type="submit" name="update" value="Update">
           <a href="sales_report.php" class="back-button">Back</a>
        </form>
    </div>
</body>
</html>
