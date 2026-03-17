<?php
include_once 'template/header.php';
?>
<style>
  body {
    font-family: Calibri, sans-serif;
    margin: 0;
    padding: 0;
  }

  .container {
    width: 90%;
    max-width: 1450px;
    margin: 0 auto;
    padding: 20px;
  }

  h2 {
    text-align: center;
    color: #3f83d7;
  }

  .styled-table {
    border-collapse: collapse;
    width: 100%;
    font-family: Calibri;
    box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
  }

  .styled-table thead tr {
    background-color: #3f83d7;
    color: #ffffff;
    text-align: left;
  }

  .styled-table th,
  .styled-table td {
    padding: 10px 15px;
  }

  .styled-table tbody tr {
    border-bottom: thin solid #dddddd;
  }

  .styled-table tbody tr:last-of-type {
    border-bottom: 2px solid #205081;
  }

  .styled-table tbody tr:hover {
    background-color: #f3f3f3;
  }

  .empty-row td {
    height: 10px;
    background-color: #ffffff;
  }

  .total-row {
    font-weight: bold;
    background-color: #f3f3f3;
    border-top: 2px solid #205081;
  }

  .pagination {
    text-align: center;
    margin: 20px 0;
  }

  .pagination .btn {
    display: inline-block;
    padding: 10px 20px;
    margin: 0 5px;
    background-color: #3f83d7;
    color: #ffffff;
    text-decoration: none;
    border-radius: 5px;
  }

  .pagination .btn:hover {
    background-color: #205081;
  }

  .pagination .btn.active {
    background-color: #205081;
    font-weight: bold;
    pointer-events: none;
  }

  inter-events: none;
  }
</style>
</head>

<body>
  <div class="container">
    <h2>QuickBooks Journal Report</h2>

    <!-- Filter Form -->
    <div>
      <form method="get" action="index.php">
        <input type="hidden" name="components" value="qb">
        <input type="hidden" name="action" value="journal_report">
        <table class="styled-table" align="center" border="0">
          <tbody>
            <tr>
              <td>
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required
                  value="<?php echo htmlspecialchars($startDate, ENT_QUOTES, 'UTF-8'); ?>">
              </td>
              <td>
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" required
                  value="<?php echo htmlspecialchars($endDate, ENT_QUOTES, 'UTF-8'); ?>">
              </td>
              <td>
                <label for="store">Store:</label>
                <select id="store" name="store">
                  <option value="">--SELECT A STORE--</option>
                  <?php
                  if (isset($_COOKIE['top_manager']) && !empty($_COOKIE['top_manager'])) {
                    // Top manager: Show all stores
                    $selectedStore = isset($_REQUEST['store']) ? $_REQUEST['store'] : '';
                    foreach ($departmentList as $qbId => $store) {
                      $selected = ($selectedStore == $qbId) ? 'selected' : ''; // Check if this store is selected
                      echo '<option value="' . htmlspecialchars($qbId, ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' .
                        htmlspecialchars($store['name'], ENT_QUOTES, 'UTF-8') .
                        '</option>';
                    }
                  } else {
                    // Non-top manager: Restrict to their specific store
                    if (isset($_COOKIE['store']) && !empty($_COOKIE['store'])) {
                      $storeId = $_COOKIE['store']; // Get the internal store ID from the cookie
                  
                      // Find the corresponding QuickBooks ID (qb_id) using the departmentList
                      $qbId = null;
                      foreach ($departmentList as $key => $store) {
                        if ($store['id'] == $storeId) {
                          $qbId = $key;
                          break;
                        }
                      }

                      if ($qbId && isset($departmentList[$qbId])) { // Check if the qb_id exists in the department list
                        echo '<option value="' . htmlspecialchars($qbId, ENT_QUOTES, 'UTF-8') . '" selected>' .
                          htmlspecialchars($departmentList[$qbId]['name'], ENT_QUOTES, 'UTF-8') .
                          '</option>';
                      } else {
                        // Debugging: Store ID or QuickBooks ID not found
                        echo '<option value="" disabled>Store not found</option>';
                      }
                    } else {
                      // Debugging: Store cookie is missing or empty
                      echo '<option value="" disabled>No store assigned</option>';
                    }
                  }
                  ?>
                </select>
              </td>

              <td>
                <label for="records_per_page">Records Per Page:</label>
                <select id="records_per_page" name="records_per_page" onchange="this.form.submit()">
                  <?php
                  // Define the available options for records per page
                  $recordsPerPageOptions = array(10, 25, 50, 100, 200, 500, 'All Records');

                  // Get the selected value from the request or set a default
                  $recordsPerPage = isset($_REQUEST['records_per_page']) ? (int) $_REQUEST['records_per_page'] : 50;

                  // Generate the dropdown options
                  foreach ($recordsPerPageOptions as $option) {
                    $selected = ($recordsPerPage == $option) ? 'selected' : '';
                    echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                  }
                  ?>
                </select>
              </td>

              <td align="right">
                <input type="submit" value="Get Report" style="height: 40px; font-weight: bold;">
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>

    <!-- Journal Report -->
    <?php if (empty($entries)) { ?>
      <p>No entries found.</p>
    <?php } else { ?>
      <table class="styled-table" align="center" border="0">
        <thead>
          <tr>
            <th width="60px" align="left">#</th>
            <th width="100px" align="left">Date</th>
            <th width="80px" align="left">Journal ID</th>
            <th width="600px" align="left">Memo/Description</th>
            <th width="200px" align="left">Account</th>
            <th width="120px" align="left">Store</th>
            <th width="80px" align="right">Debit</th>
            <th width="80px" align="right">Credit</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $totalDebit = 0;
          $totalCredit = 0;
          $entryCount = 1;

          foreach ($entries as $entry):
            if (isset($entry->Line) && is_array($entry->Line)):
              $isFirstLine = true;

              foreach ($entry->Line as $line):
                $postingType = isset($line->JournalEntryLineDetail->PostingType) ? $line->JournalEntryLineDetail->PostingType : null;
                $amount = isset($line->Amount) ? (float) $line->Amount : 0.0;

                if ($postingType === "Debit") {
                  $totalDebit += $amount;
                } elseif ($postingType === "Credit") {
                  $totalCredit += $amount;
                }

                $accountId = isset($line->JournalEntryLineDetail->AccountRef) ? $line->JournalEntryLineDetail->AccountRef : null;
                $accountName = isset($accountList[$accountId]) ? $accountList[$accountId] : 'Unknown Account';

                $departmentId = isset($line->JournalEntryLineDetail->DepartmentRef) ? $line->JournalEntryLineDetail->DepartmentRef : null;

                // Check if the department exists in the departmentList
                if (isset($departmentList[$departmentId])) {
                  $departmentName = $departmentList[$departmentId]['name']; // Get the store name
                } else {
                  $departmentName = 'Unknown Department'; // Fallback for unknown department
                }
                ?>
                <tr>
                  <td><?php echo $isFirstLine ? $entryCount : ''; ?></td>
                  <td><?php echo $isFirstLine ? htmlspecialchars($entry->TxnDate, ENT_QUOTES, 'UTF-8') : ''; ?></td>
                  <td><?php echo htmlspecialchars(sprintf('%02d', $entry->Id), ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($line->Description, ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($accountName, ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($departmentName, ENT_QUOTES, 'UTF-8'); ?></td>
                  <td align="right">
                    <?php echo $postingType === "Debit" ? htmlspecialchars(number_format($amount, 2), ENT_QUOTES, 'UTF-8') : ''; ?>
                  </td>
                  <td align="right">
                    <?php echo $postingType === "Credit" ? htmlspecialchars(number_format($amount, 2), ENT_QUOTES, 'UTF-8') : ''; ?>
                  </td>
                </tr>
                <?php
                $isFirstLine = false;
              endforeach;

              $entryCount++;
              ?>
              <tr class="empty-row">
                <td colspan="8"></td>
              </tr>
              <?php
            endif;
          endforeach;
          ?>
          <tr class="total-row">
            <td colspan="6">TOTAL</td>
            <td align="right"><?php echo htmlspecialchars(number_format($totalDebit, 2), ENT_QUOTES, 'UTF-8'); ?></td>
            <td align="right"><?php echo htmlspecialchars(number_format($totalCredit, 2), ENT_QUOTES, 'UTF-8'); ?></td>
          </tr>
        </tbody>
      </table>
      <div class="pagination">
        <?php
        if ($maxResults) { // Only show pagination if maxResults is set
          $baseUrl = "index.php?components=qb&action=journal_report&start_date=" . urlencode($startDate) . "&end_date=" . urlencode($endDate) . "&store=" . urlencode($selectedStore) . "&records_per_page=" . urlencode($maxResults);

          if ($currentPage > 1) {
            echo '<a href="' . $baseUrl . '&page=' . ($currentPage - 1) . '" class="btn">Previous</a>';
          }

          for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $currentPage) {
              echo '<span class="btn active">' . $i . '</span>';
            } else {
              echo '<a href="' . $baseUrl . '&page=' . $i . '" class="btn">' . $i . '</a>';
            }
          }

          if ($currentPage < $totalPages) {
            echo '<a href="' . $baseUrl . '&page=' . ($currentPage + 1) . '" class="btn">Next</a>';
          }
        }
        ?>
      </div>
    <?php } ?>
  </div>
</body>

<script>
  document.getElementById('records_per_page').addEventListener('change', function () {
    this.form.submit();
  });
  // Add event listeners to the date fields
  document.getElementById('start_date').addEventListener('change', function () {
    this.form.submit(); // Submit the form when the start date changes
  });

  document.getElementById('end_date').addEventListener('change', function () {
    this.form.submit(); // Submit the form when the end date changes
  });
</script>
<?php
include_once 'template/footer.php';
?>