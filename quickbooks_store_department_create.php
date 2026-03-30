<?php
error_reporting(E_ALL);
set_time_limit(300);

include 'config.php';
include 'template/common.php';

/**
 * Processes a batch of stores by sending them to QuickBooks as sub-departments,
 * updating the local database, and reporting the results.
 */
function createStoreDepartmentsInQB()
{
  include 'config.php';
  $successfulStores = [];
  $failedStores = [];
  $operationSuccess = true;

  $maxStores = 20; // Batch limit
  $totalProcessed = 0;

  try {
    // NEW QUERY: Joins stores with sub_system to get the parent's QB ID.
    $storeQuery = "SELECT s.id, s.name, ss.qb_id AS parent_qb_id
                       FROM stores s
                       JOIN sub_system ss ON s.sub_system = ss.id
                       WHERE s.qb_id IS NULL AND ss.qb_id IS NOT NULL
                       LIMIT {$maxStores}";

    $storeResult = mysqli_query($conn, $storeQuery);

    if ($storeResult && mysqli_num_rows($storeResult) > 0) {
      while ($storeRow = mysqli_fetch_assoc($storeResult)) {
        $storeId = $storeRow['id'];
        $storeName = $storeRow['name'];
        $parentQbId = $storeRow['parent_qb_id'];

        // Prepare data for the QBAddDepartment function
        // This is the key part for creating a sub-department
        $departmentDataForQB = [
          'Name' => $storeName,
          'SubDepartment' => true, // Set to true
          'ParentRef' => [
            'value' => $parentQbId, // Provide the parent's QB ID
          ],
        ];

        // Send department to QuickBooks (we can reuse the same function!)
        $qb_result = QBAddDepartment($departmentDataForQB);

        if (
          isset($qb_result['status']) &&
          $qb_result['status'] == 'success'
        ) {
          $qb_department_id = mysqli_real_escape_string(
            $conn,
            $qb_result['qb_department_id']
          );
          $db_store_id = mysqli_real_escape_string($conn, $storeId);

          // NEW UPDATE: Update the `stores` table now
          $updateQuery = "UPDATE `stores` SET `qb_id` = '{$qb_department_id}' WHERE `id` = '{$db_store_id}'";
          $updateResult = mysqli_query($conn, $updateQuery);

          if ($updateResult) {
            $successfulStores[] = [
              'name' => $storeName,
              'id' => $storeId,
              'qb_id' => $qb_result['qb_department_id'],
            ];
          } else {
            $failedStores[] = [
              'name' => $storeName,
              'id' => $storeId,
              'reason' =>
                'Database update failed: ' .
                mysqli_error($conn),
            ];
          }
        } else {
          $failedStores[] = [
            'name' => $storeName,
            'id' => $storeId,
            'reason' =>
              isset($qb_result['message']) &&
              !empty($qb_result['message'])
              ? $qb_result['message']
              : 'Unknown QuickBooks error',
          ];
        }

        $totalProcessed++;

        if ($totalProcessed >= $maxStores) {
          break;
        }
      }
    }
  } catch (Exception $e) {
    $operationSuccess = false;
    $failedStores[] = [
      'name' => 'System Error',
      'id' => 'N/A',
      'reason' => $e->getMessage(),
    ];
  }

  // MODIFIED REPORT: Changed wording to "Store"
  echo "<h3>Store Sub-Department Creation Report</h3>";
  echo "Total Stores Processed: {$totalProcessed}<br>";
  echo "Successful Stores: " . count($successfulStores) . "<br>";
  echo "Failed Stores: " . count($failedStores) . "<br>";

  if (!empty($successfulStores)) {
    echo "<h4>Successful Stores</h4><ul>";
    foreach ($successfulStores as $store) {
      echo "<li>Store: {$store['name']} (ID: {$store['id']}) updated with QB ID: {$store['qb_id']}</li>";
    }
    echo "</ul>";
  }

  if (!empty($failedStores)) {
    echo "<h4>Failed Stores</h4><ul>";
    foreach ($failedStores as $store) {
      echo "<li>Store: {$store['name']} (ID: {$store['id']}) - Reason: {$store['reason']}</li>";
    }
    echo "</ul>";
  }

  return $operationSuccess;
}

// Run the function to process stores in QuickBooks.
createStoreDepartmentsInQB();
?>