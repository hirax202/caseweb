<?php
// Include database connection
include("conn.php");

// Check if ID is set in the URL
if (isset($_GET['ID'])) {
    $id = $_GET['ID'];

    // Prepare SQL to get the record based on the provided ID
    $query = "SELECT * FROM ticket_sales WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if the record exists
    if ($row = mysqli_fetch_assoc($result)) {
        $visitorName = $row['visitorName'];
        $visitorEmail = $row['visitorEmail'];
        $infants = $row['infants'];
        $toddlers = $row['toddlers'];
        $kids = $row['kids'];
        $adults = $row['adults'];
        $senior_citizens = $row['senior_citizens'];
        $disabled = $row['disabled'];
        $totalPrice = $row['totalPrice'];
        $paymentMethod = $row['paymentMethod']; 
    } else {
        echo "No record found!";
        exit;
    }

    mysqli_stmt_close($stmt);
} else {
    echo "ID not specified!";
    exit;
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Ticket Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0b27a;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #f6ddcc;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            width: 450px;
        }
        .view-field {
            margin-bottom: 12px;
        }
        .view-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .view-value {
            display: block;
            padding: 8px;
            background-color: #ffffff;
            border-radius: 5px;
            color: #333;
            border: 1px solid #ccc;
        }
        .back-button {
            display: inline-block;
            padding: 12px 20px;
            background-color: #ff6600;
            color: white;
            font-weight: bold;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 15px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #ff3385;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Ticket View</h2>
        <div class="view-field">
            <span class="view-label">Visitor Name:</span>
            <span class="view-value"><?php echo htmlspecialchars($visitorName); ?></span>
        </div>
        <div class="view-field">
            <span class="view-label">Visitor Email:</span>
            <span class="view-value"><?php echo htmlspecialchars($visitorEmail); ?></span>
        </div>
        <div class="view-field">
            <span class="view-label">Infants:</span>
            <span class="view-value"><?php echo htmlspecialchars($infants); ?></span>
        </div>
        <div class="view-field">
            <span class="view-label">Toddlers:</span>
            <span class="view-value"><?php echo htmlspecialchars($toddlers); ?></span>
        </div>
        <div class="view-field">
            <span class="view-label">Kids:</span>
            <span class="view-value"><?php echo htmlspecialchars($kids); ?></span>
        </div>
        <div class="view-field">
            <span class="view-label">Adults:</span>
            <span class="view-value"><?php echo htmlspecialchars($adults); ?></span>
        </div>
        <div class="view-field">
            <span class="view-label">Senior Citizens:</span>
            <span class="view-value"><?php echo htmlspecialchars($senior_citizens); ?></span>
        </div>
        <div class="view-field">
            <span class="view-label">Disabled:</span>
            <span class="view-value"><?php echo htmlspecialchars($disabled); ?></span>
        </div>
        <div class="view-field">
            <span class="view-label">Total Price:</span>
            <span class="view-value"><?php echo htmlspecialchars($totalPrice); ?></span>
        </div>
        <div class="view-field">
            <span class="view-label">Payment Method:</span>
            <span class="view-value"><?php echo htmlspecialchars($paymentMethod); ?></span>
        </div>
        <button onclick="window.location.href='sales_report.php'" class="back-button">Back</button>
    </div>
</body>
</html>
