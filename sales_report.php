<!DOCTYPE html>
<html>
<head>
    <title>Ticket Record</title>
    <script>
        function confirmDelete(id) {
            var confirmDelete = confirm("Are you sure you want to delete this data?");
            if (confirmDelete) {
                window.location.href = "delete.php?ID=" + id;
            }
        }

        function printTable() {
            window.print();
        }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0b27a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000000;
            padding: 8px;
        }
        th {
            background-color: #f6ddcc;
            text-align: left;
        }
        td {
            background-color: #ffffff;
        }
        h2 {
            text-align: center;
            padding: 20px;
            background-color: #f0b27a;
            margin: 0;
            color: #333;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
        }
        .search-container {
            text-align: center;
            padding: 15px;
        }
        .print-button, .back-button {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #f6ddcc;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .print-button:hover, .back-button:hover {
            background-color: #ff3385;
        }
    </style>
</head>
<body>
    <h2>Sales report</h2>
    <div class="search-container">
        <form method="POST" action="">
            Search by Name: 
            <input type="text" name="search" placeholder="Search by name...">
            <input type="submit" value="Search">
        </form>
    </div>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Visitor Name</th>
                    <th>Visitor Email</th>
                    <th>Infants</th>
                    <th>Toddlers</th>
                    <th>Kids</th>
                    <th>Adults</th>
                    <th>Senior Citizens</th>
                    <th>Disabled</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                include("conn.php");

                // Initialize search query
                $searchQuery = "";
                if (isset($_POST['search'])) {
                    $searchQuery = $_POST['search'];
                }

                // Fetch data from ticket_sales table with search query
                $query = "SELECT * FROM ticket_sales WHERE visitorName LIKE ? ORDER BY id ASC";
                $stmt = mysqli_prepare($conn, $query);
                $searchTerm = "%$searchQuery%";
                mysqli_stmt_bind_param($stmt, "s", $searchTerm);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {
                    $no = 1;
                    while($row = mysqli_fetch_assoc($result)) {
                        $id = $row['id'];
                        $visitorName = $row['visitorName'];
                        $visitorEmail = $row['visitorEmail'];
                        $infants = $row['infants'];
                        $toddlers = $row['toddlers'];
                        $kids = $row['kids'];
                        $adults = $row['adults'];
                        $senior_citizens = $row['senior_citizens'];
                        $disabled = $row['disabled'];
                        $totalPrice = $row['totalPrice'];
                ?>
                <tr align="center" id="record_<?php echo $id; ?>">
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($visitorName); ?></td>
                    <td><?php echo htmlspecialchars($visitorEmail); ?></td>
                    <td><?php echo htmlspecialchars($infants); ?></td>
                    <td><?php echo htmlspecialchars($toddlers); ?></td>
                    <td><?php echo htmlspecialchars($kids); ?></td>
                    <td><?php echo htmlspecialchars($adults); ?></td>
                    <td><?php echo htmlspecialchars($senior_citizens); ?></td>
                    <td><?php echo htmlspecialchars($disabled); ?></td>
                    <td><?php echo htmlspecialchars($totalPrice); ?></td>
                    <td>
                        <a href="update.php?ID=<?php echo $id; ?>" class="update"><img src="edit.png" width="20" height="20" alt="Edit"></a> ||
                        <a href="delete.php" onclick="confirmDelete(<?php echo $id; ?>);"><img src="bin.png" width="20" height="20" alt="Delete"></a> ||
                        <a href="vieww.php?ID=<?php echo $id; ?>"><img src="view.png" width="20" height="20" alt="view"></a>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='11' align='center'>No data found</td></tr>";
                }
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
        <button class="print-button" onclick="window.open('print.php', '_blank')">Print</button>
		<button onclick="window.location.href='form.php'" class="back-button">Back</button>
    </div>
</body>
</html>
