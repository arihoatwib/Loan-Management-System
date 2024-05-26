<?php
date_default_timezone_set("Africa/Nairobi");
require_once 'class.php';

try {
    // Check for form submission
    if (isset($_POST['update'])) {
        // Debug output for posted data
        var_dump($_POST);

        // Retrieve form data
        $loan_id = $_POST['loan_id'];
        $ref_no = $_POST['ref_no'];

        // Debug output for parameters
        echo "Loan ID: " . $loan_id . "<br>";
        echo "Ref No: " . $ref_no . "<br>";

        // Create a new instance of the database class
        $db = new db_class();

        // Check existing loan details
        $tbl_loan = $db->check_loan($loan_id);

        // Check if the array keys exist
        $fetch = $tbl_loan->fetch_array();
        $borrower_id = isset($fetch['borrower_id']) ? $fetch['borrower_id'] : null;
        $ltype_id = isset($fetch['ltype_id']) ? $fetch['ltype_id'] : null;
        $lplan_id = isset($fetch['lplan_id']) ? $fetch['lplan_id'] : null;
        $amount = isset($fetch['amount']) ? $fetch['amount'] : null;
        $purpose = isset($fetch['purpose']) ? $fetch['purpose'] : null;

        // Retrieve other form data
        $status = (int)$_POST['status']; // Cast status to integer
        $date_released = isset($fetch['date_released']) ? $fetch['date_released'] : null;

        // Debug output for status and date_released
        echo "Status: " . $status . "<br>";
        echo "Date Released: " . $date_released . "<br>";

        // Check if the date_released should be updated
        if ($status == 2 && empty($date_released)) {
            $date_released = date("Y-m-d H:i:s");
        }

        // Debug output for parameters
        echo "Borrower ID: " . $borrower_id . "<br>";
        echo "Loan Type ID: " . $ltype_id . "<br>";
        echo "Loan Plan ID: " . $lplan_id . "<br>";
        echo "Amount: " . $amount . "<br>";
        echo "Purpose: " . $purpose . "<br>";

        // Free the result set
        $tbl_loan->free_result();

        // Update loan in the database
        $update_result = $db->update_loan($loan_id, $ref_no, $ltype_id, $borrower_id, $purpose, $amount, $lplan_id, $status, $date_released);

        // Debug output for the SQL query
        echo "SQL Query: " . $db->conn->info . "<br>";

        // Check for database errors
        if ($db->conn->error) {
            echo "Database Error: " . $db->conn->error;
        } elseif ($update_result) {
            // Display success message and redirect
            echo "<script>alert('Update Loan successfully')</script>";
            echo "<script>window.location='loan.php'</script>";
        } else {
            // Display error message if update failed
            echo "<script>alert('Failed to update loan')</script>";
            echo "<script>window.location='loan.php'</script>";
        }

        // Close the database connection
        if ($db->conn) {
            $db->conn->close();
        }
    }
} catch (Exception $e) {
    // Display error message
    echo "<script>alert('Error: " . $e->getMessage() . "')</script>";
    echo "<script>window.location='loan.php'</script>";
}
?>
