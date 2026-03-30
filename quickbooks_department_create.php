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
function createDepartmentsInQB()
{
  include 'config.php';
  $successfulDepartments = [];
  $failedDepartments = [];
  $operationSuccess = true;

  // Set the maximum number of departments to process in this batch.
  $maxDepartments = 20;
  $totalProcessed = 0;

  try {
    $subsystemQuery = "SELECT `id`, `name` FROM `sub_system` WHERE `qb_id` IS NULL LIMIT {$maxDepartments}";
    $subsystemResult = mysqli_query($conn, $subsystemQuery);

    if ($subsystemResult && mysqli_num_rows($subsystemResult) > 0) {
      while ($subsystemRow = mysqli_fetch_assoc($subsystemResult)) {
        $subsystemId = $subsystemRow['id'];
        $subsystemName = $subsystemRow['name'];

        // Prepare data for the QBAddDepartment function
        $departmentDataForQB = [
          'Name' => $subsystemName,
          'SubDepartment' => false,
        ];

        // Send department to QuickBooks
        $qb_result = QBAddDepartment($departmentDataForQB);

        if (
          isset($qb_result['status']) &&
          $qb_result['status'] == 'success'
        ) {
          $qb_department_id = mysqli_real_escape_string(
            $conn,
            $qb_result['qb_department_id']
          );
          $db_subsystem_id = mysqli_real_escape_string(
            $conn,
            $subsystemId
          );

          $updateQuery = "UPDATE `sub_system` SET `qb_id` = '{$qb_department_id}' WHERE `id` = '{$db_subsystem_id}'";
          $updateResult = mysqli_query($conn, $updateQuery);

          if ($updateResult) {
            $successfulDepartments[] = [
              'name' => $subsystemName,
              'id' => $subsystemId,
              'qb_id' => $qb_result['qb_department_id'],
            ];
          } else {
            $failedDepartments[] = [
              'name' => $subsystemName,
              'id' => $subsystemId,
              'reason' =>
                'Database update failed: ' .
                mysqli_error($conn),
            ];
          }
        } else {
          $failedDepartments[] = [
            'name' => $subsystemName,
            'id' => $subsystemId,
            'reason' =>
              isset($qb_result['message']) &&
              !empty($qb_result['message'])
              ? $qb_result['message']
              : 'Unknown QuickBooks error',
          ];
        }

        $totalProcessed++;

        // CORRECTED: Stop processing after reaching the defined limit.
        if ($totalProcessed >= $maxDepartments) {
          break;
        }
      }
    }
  } catch (Exception $e) { // Changed from Throwable $th
    $operationSuccess = false;
    // Note: This will not catch all fatal errors like Throwable does.
    $failedDepartments[] = [
      'name' => 'System Error',
      'id' => 'N/A',
      'reason' => $e->getMessage() // Use $e
    ];
  }

  // Output a summary report (unchanged)
  echo "<h3>Department Creation Report</h3>";
  echo "Total Departments Processed: {$totalProcessed}<br>";
  echo "Successful Departments: " . count($successfulDepartments) . "<br>";
  echo "Failed Departments: " . count($failedDepartments) . "<br>";

  if (!empty($successfulDepartments)) {
    echo "<h4>Successful Departments</h4><ul>";
    foreach ($successfulDepartments as $department) {
      echo "<li>Department: {$department['name']} (ID: {$department['id']}) updated with QB ID: {$department['qb_id']}</li>";
    }
    echo "</ul>";
  }

  if (!empty($failedDepartments)) {
    echo "<h4>Failed Departments</h4><ul>";
    foreach ($failedDepartments as $department) {
      echo "<li>Department: {$department['name']} (ID: {$department['id']}) - Reason: {$department['reason']}</li>";
    }
    echo "</ul>";
  }

  return $operationSuccess;
}

// Run the function to process departments in QuickBooks.
createDepartmentsInQB();
?>