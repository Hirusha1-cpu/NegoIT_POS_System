<?php
// Set the maximum execution time (optional)
// This helps in case the process takes a long time for each batch.
error_reporting(E_ALL);
set_time_limit(300);

// Include configuration (make sure this file sets up your database connection, e.g., $conn2)
include('config.php');
include('template/common.php');

/**
 * Processes a batch of customers by sending them to QuickBooks,
 * updating the local database, and reporting the results.
 *
 * @return bool Returns true if the process runs without a system error,
 *              false otherwise.
 */
function createCustomersInQB()
{
  include('config.php');
  $successfulCustomers = [];
  $failedCustomers = [];
  $operationSuccess = true;

  // Set the maximum number of customers to process in this batch.
  $maxCustomers = 20;
  $totalProcessed = 0;

  try {
    // Fetch up to $maxCustomers customers who need to be added to QuickBooks.
    // The query selects customers having a NULL QuickBooks customer id.
    $custQuery = "SELECT `id`, `name` FROM `cust` WHERE `qb_cust_id` IS NULL LIMIT $maxCustomers";
    $custResult = mysqli_query($conn, $custQuery);

    if ($custResult && mysqli_num_rows($custResult) > 0) {
      while ($custRow = mysqli_fetch_assoc($custResult)) {
        $custId = mysqli_real_escape_string($conn, $custRow['id']);
        $custName = mysqli_real_escape_string($conn, $custRow['name']);

        // Send customer to QuickBooks using the QBCustomerAdd function.
        $qb_result = QBCustomerAdd($custId);

        if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
          $qb_cust_id = mysqli_real_escape_string($conn, $qb_result['qb_cust_id']);

          // Update the local database with the QuickBooks customer id.
          $updateQuery = "UPDATE `cust` SET `qb_cust_id` = '$qb_cust_id' WHERE `id` = '$custId'";
          $updateResult = mysqli_query($conn, $updateQuery);

          if ($updateResult) {
            $successfulCustomers[] = [
              'name' => $custName,
              'id' => $custId,
              'qb_id' => $qb_cust_id
            ];
          } else {
            $failedCustomers[] = [
              'name' => $custName,
              'id' => $custId,
              'reason' => 'Database update failed, ' . $updateQuery
            ];
          }
        } else {
          $failedCustomers[] = [
            'name' => $custName,
            'id' => $custId,
            'reason' => isset($qb_result['message'])
              ? $qb_result['message']
              : 'Unknown QuickBooks error'
          ];
        }

        $totalProcessed++;

        // Stop processing after reaching the defined limit.
        if ($totalProcessed >= $maxCustomers) {
          break;
        }
      }
    }
  } catch (Throwable $th) {
    $operationSuccess = false;
    $failedCustomers[] = [
      'name' => 'System Error',
      'id' => 'N/A',
      'reason' => $th->getMessage()
    ];
  }

  // Output a summary report
  echo "<h3>Customer Creation Report</h3>";
  echo "Total Customers Processed: {$totalProcessed}<br>";
  echo "Successful Customers: " . count($successfulCustomers) . "<br>";
  echo "Failed Customers: " . count($failedCustomers) . "<br>";

  // Optionally, you can print details about successes and failures.
  if (!empty($successfulCustomers)) {
    echo "<h4>Successful Customers</h4><ul>";
    foreach ($successfulCustomers as $customer) {
      echo "<li>Customer: {$customer['name']} (ID: {$customer['id']}) updated with QB ID: {$customer['qb_id']}</li>";
    }
    echo "</ul>";
  }

  if (!empty($failedCustomers)) {
    echo "<h4>Failed Customers</h4><ul>";
    foreach ($failedCustomers as $customer) {
      echo "<li>Customer: {$customer['name']} (ID: {$customer['id']}) - Reason: {$customer['reason']}</li>";
    }
    echo "</ul>";
  }

  return $operationSuccess;
}

// Run the function to process customers in QuickBooks.
createCustomersInQB();
?>