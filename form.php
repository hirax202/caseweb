<?php
session_start();
include('conn.php'); 

// Function to calculate total price based on ticket types
function totalPrice($infants, $toddlers, $kids, $adults, $senior_citizens, $disabled) {
    // Ticket prices as per KidZania
    $toddlerPrice = 41;
    $kidPrice = 85;
    $adultPrice = 47;
    $seniorCitizenPrice = 35;
    $disabledPrice = 35;

    // Calculate total price (infants are free)
    $totalPrice = ($toddlers * $toddlerPrice) + ($kids * $kidPrice) + ($adults * $adultPrice) + 
                  ($senior_citizens * $seniorCitizenPrice) + ($disabled * $disabledPrice);
    return $totalPrice;
}

// Define variables and set to empty values
$visitorName = $visitorEmail = $paymentMethod = "";
$infants = $toddlers = $kids = $adults = $senior_citizens = $disabled = 0;
$totalPrice = 0;

// Check if form is submitted to calculate the total
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btnCalc'])) {
    $visitorName = $_POST['visitorName'];
    $visitorEmail = $_POST['visitorEmail'];
    $infants = intval($_POST['infants'] ?? 0);
    $toddlers = intval($_POST['toddlers'] ?? 0);
    $kids = intval($_POST['kids'] ?? 0);
    $adults = intval($_POST['adults'] ?? 0);
    $senior_citizens = intval($_POST['seniorCitizens'] ?? 0);
    $disabled = intval($_POST['disabled'] ?? 0);
    $paymentMethod = $_POST['paymentMethod']; // Capture payment method

    // Calculate total price
    $totalPrice = totalPrice($infants, $toddlers, $kids, $adults, $senior_citizens, $disabled);

    // Escape variables to prevent SQL injection
    $visitorName = mysqli_real_escape_string($conn, $visitorName);
    $visitorEmail = mysqli_real_escape_string($conn, $visitorEmail);
    $paymentMethod = mysqli_real_escape_string($conn, $paymentMethod);

    // Insert the ticket data into the database with concatenated variables
    $sql = "INSERT INTO ticket_sales (visitorName, visitorEmail, infants, toddlers, kids, adults, senior_citizens, disabled, totalPrice, paymentMethod, sale_date) 
            VALUES ('$visitorName', '$visitorEmail', $infants, $toddlers, $kids, $adults, $senior_citizens, $disabled, $totalPrice, '$paymentMethod', NOW())";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the view page after successful insert
        header("Location: view.php"); 
        exit(); 
    } else {
        echo "<script>alert('Error saving ticket data: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Form</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('back.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-top: 30px;
            color: #333;
        }
        form[name="detail"] {
            width: 80%;
            max-width: 600px;
            opacity: 90%;
            margin: 0 auto;
            padding: 30px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.8); 
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }
        input[type="text"], input[type="email"], input[type="number"], select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border 0.3s;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="number"]:focus, select:focus {
            border: 1px solid #ff0066;
            outline: none;
        }
        input[type="submit"], input[type="reset"], .back-button {
            font-weight: bold;
            width: 200px;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 10px 5px;
        }
        input[type="submit"] {
            background-color: #ff0066;
        }
        input[type="submit"]:hover {
            background-color: #ff3385;
        }
        input[type="reset"] {
            background-color: grey;
        }
        input[type="reset"]:hover {
            background-color: #6e6d6d;
        }
        .back-button {
            background-color: #007bff; 
            text-align: center;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        .center {
            text-align: center;
        }
        footer {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: #fff;
            text-align: center;
        }
        .total-price {
            font-weight: bold;
            font-size: 1.2em;
            color: #ff0066; 
        }
    </style>
</head>
<body>
    <center>
        <h2>Welcome <?php echo htmlspecialchars($_SESSION['name']); ?> :: <?php echo htmlspecialchars($_SESSION['today']); ?></h2>
    </center>
    <h1>Ticket form</h1>
    <form name="detail" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <table style="width: 100%;">
            <tr>
                <td>Visitor Name: <input type="text" name="visitorName" value="<?php echo htmlspecialchars($visitorName); ?>" required></td>
                <td>Visitor Email: <input type="email" name="visitorEmail" value="<?php echo htmlspecialchars($visitorEmail); ?>" required></td>
            </tr>
            <tr>
                <td>Number of Infant Tickets (Free): <input type="number" name="infants" value="<?php echo $infants; ?>" min="0"></td>
                <td>Number of Toddler Tickets (RM 41): <input type="number" name="toddlers" value="<?php echo $toddlers; ?>" min="0"></td>
            </tr>
            <tr>
                <td>Number of Kids Tickets (RM 85): <input type="number" name="kids" value="<?php echo $kids; ?>" min="0"></td>
                <td>Number of Adult Tickets (RM 47): <input type="number" name="adults" value="<?php echo $adults; ?>" min="0"></td>
            </tr>
            <tr>
                <td>Number of Senior Citizen Tickets (RM 35): <input type="number" name="seniorCitizens" value="<?php echo $senior_citizens; ?>" min="0"></td>
                <td>Number of Disabled Tickets (RM 35): <input type="number" name="disabled" value="<?php echo $disabled; ?>" min="0"></td>
            </tr>
            <tr>
                <td colspan="2">
                    Payment Method: 
                    <select name="paymentMethod" required>
                        <option value="">Select Payment Method</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Debit Card">Debit Card</option>
                        <option value="PayPal">PayPal</option>
                        <option value="Cash">Cash</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="center">
                    <input type="submit" name="btnCalc" value="Save">
                    <input type="reset" name="btnReset" value="Reset">
                </td>
            </tr>
            <?php if ($totalPrice > 0): ?>
            <tr>
                <td colspan="2" class="center total-price">Total Price: RM <?php echo number_format($totalPrice, 2); ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </form>
    <div class="center">
        <a href="index.php" class="back-button">Back to login</a>
    </div>
</body>
</html>
