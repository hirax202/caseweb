<?php
include("conn.php");

// Get today's date and the current month
$today = date('Y-m-d');
$current_month = date('Y-m');

//  today's sales
$today_sales_query = "SELECT * FROM ticket_sales WHERE DATE(sale_date) = '$today'";
$today_sales_result = mysqli_query($conn, $today_sales_query);

//  current month's sales
$monthly_sales_query = "SELECT * FROM ticket_sales WHERE DATE_FORMAT(sale_date, '%Y-%m') = '$current_month'";
$monthly_sales_result = mysqli_query($conn, $monthly_sales_query);

//  all sales
$total_sales_query = "SELECT * FROM ticket_sales";
$total_sales_result = mysqli_query($conn, $total_sales_query);

// Calculate total revenue for each period
function calculateTotalRevenue($result) {
    $total = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $total += $row['totalPrice'];
    }
    return $total;
}

// Calculate revenues
$today_revenue = calculateTotalRevenue($today_sales_result);
$monthly_revenue = calculateTotalRevenue($monthly_sales_result);
$total_revenue = calculateTotalRevenue($total_sales_result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0b27a;
            text-align: center;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        .report-section {
            background-color: #fff;
            padding: 15px;
            margin: 15px auto;
            width: 80%;
            border: 1px solid #333;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .report-section p {
            font-size: 18px;
            color: #555;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .back-button {
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 20px;
            background-color: #ff6600;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #e55b00;
        }
    </style>
</head>
<body>
    <h2>Sales Report</h2>

    <div class="report-section">
        <h3>Today's Sales</h3>
        <p>Total Revenue: $<?= number_format($today_revenue, 2); ?></p>
    </div>

    <div class="report-section">
        <h3>Monthly Sales</h3>
        <p>Total Revenue: $<?= number_format($monthly_revenue, 2); ?></p>
    </div>

    <div class="report-section">
        <h3>Total Sales</h3>
        <p>Total Revenue: $<?= number_format($total_revenue, 2); ?></p>
    </div>

    <button onclick="window.print()">Print Report</button>
    <a href="sales_report.php" class="back-button">Back</a>
</body>
</html>
