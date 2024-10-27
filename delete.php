<?php
// Include database connection
include("conn.php");

// Check if ID is set in the URL
if (isset($_GET['ID'])) {
    $IDdelete = $_GET['ID'];

    // Use a prepared statement for deletion
    $stmt = mysqli_prepare($conn, "DELETE FROM ticket_sales WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $IDdelete);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Redirect with a success message
        header("Location: view.php?message=Record deleted successfully");
        exit();
    } else {
        // Redirect with an error message
        header("Location: view.php?error=Failed to delete record");
        exit();
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    // Redirect if ID is not set in the URL
    header("Location: view.php?error=Invalid ID");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
