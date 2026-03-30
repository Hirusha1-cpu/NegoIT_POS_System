<?php
include_once 'template/header.php';

// Determine the protocol, host, and directory dynamically
// Force HTTPS if the current page is HTTPS
$protocol = "https://"; // Always use HTTPS for internal requests if the page is HTTPS
$host = $_SERVER['HTTP_HOST'];
// If you need to maintain a particular directory or subdirectory, specify it here.
// For example, if the file is in the web root you can leave it empty.
$baseDir = "";

// Build the dynamic URL
$quickbooksUrl = "{$protocol}{$host}{$baseDir}/quickbooks_background_worker.php";
?>

<style>
  table {
    font-family: Calibri;
  }

  .tbl-header {
    font-family: Calibri;
    color: maroon;
    font-weight: bold;
    background: #EEEEEE;
    min-width: 1500px;
    /* Increased min-width to accommodate new columns */
  }

  .td-style {
    background-color: silver;
    color: navy;
    font-family: Calibri;
    font-size: 14pt;
  }

  .styled-table {
    border-collapse: collapse;
    margin-top: 25px;
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
    padding: 5px 15px;
  }

  .styled-table tbody tr {
    border-bottom: thin solid #dddddd;
  }

  .styled-table tbody tr:nth-of-type(even) {
    /* background-color: #f3f3f3; */
  }

  .styled-table tbody tr:last-of-type {
    border-bottom: 2px solid #205081;
  }

  .styled-table tbody tr:hover {
    background-color: #f3f3f3;
  }
</style>

<div>
  <table class="styled-table" width="50%" align="center">
    <thead>
      <th align="left">Figure</th>
      <th align="right">Count</th>
      <th align="right">Action</th>
    </thead>
    <tbody>
      <tr>
        <td align="left">QB Pending Queue Count</td>
        <td align="right">
          <?php if (isset($queue_total))
            echo $queue_total; ?>
        </td>
        <td align="right">
          <?php if (isset($queue_total) && $queue_total > 0) { ?>
            <button onclick="runQueue()" id="runQueueButton">Run Queue</button>
          <?php } ?>
        </td>
      </tr>
      <tr>
        <td align="left">QB Error Count</td>
        <td align="right">
          <?php if (isset($error_total))
            echo $error_total; ?>
        </td>
        <td align="right"></td>
      </tr>
      <tr>
        <td align="left">Total Customers/Registered in QuickBooks</td>
        <td align="right"><?php echo $customerCounts['total']; ?>/<?php echo $customerCounts['registered']; ?></td>
        <td align="right"></td>
      </tr>
      <?php if ($customerCounts['unregistered'] > 0) { ?>
        <tr>
          <td align="left">Customers Not Registered in QuickBooks</td>
          <td align="right"><?php echo $customerCounts['unregistered']; ?></td>
          <td align="right">
            <?php if ($customerCounts['unregistered'] > 0) { ?>
              <button onclick="runQuickBooksCustomerSync()" id="runQuickBooksCustomerSyncButton">
                Run Customer Create
              </button>
            <?php } ?>
          </td>
        </tr>
      <?php } ?>
      <tr>
        <td align="left">Total Employees/Registered in QuickBooks</td>
        <td align="right"><?php echo $employeeCounts['total']; ?>/<?php echo $employeeCounts['registered']; ?></td>
        <td align="right"></td>
      </tr>
      <?php if ($employeeCounts['unregistered'] > 0) { ?>
        <tr>
          <td align="left">Employees Not Registered in QuickBooks</td>
          <td align="right"><?php echo $employeeCounts['unregistered']; ?></td>
          <td align="right">
            <button onclick="runQuickBooksEmployeeSync()" id="runQuickBooksEmployeeSyncButton">
              Run Employee Create
            </button>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<script>
  // Store the dynamic URL (passed from PHP) in a JavaScript variable
  const quickbooksUrl = "<?php echo $quickbooksUrl; ?>";

  function runQueue() {
    const btn = document.getElementById("runQueueButton");
    // Save the original button text so we can revert back
    const originalText = btn.innerHTML;

    // Disable the button and show the loading gif with some text
    btn.disabled = true;
    btn.innerHTML =
      '<img src="images/loading.gif" alt="Loading..." style="vertical-align:middle; width:30px" /> Running Queue...';

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
      if (this.readyState == 4) {
        if (this.status == 200) {
          alert("Queue has been run successfully.");
          location.reload();
        } else {
          alert("There was an error running the queue.");
        }
        // Re-enable the button and restore its original text regardless of the response
        btn.disabled = false;
        btn.innerHTML = originalText;
      }
    };
    xhttp.open("GET", quickbooksUrl, true);
    xhttp.send();
  }

  function runQuickBooksCustomerSync() {
    const btn = document.getElementById("runQuickBooksCustomerSyncButton");
    const originalText = btn.innerHTML;

    // Disable the button and show a loading indicator
    btn.disabled = true;
    btn.innerHTML =
      '<img src="images/loading.gif" alt="Loading..." style="vertical-align:middle; width:30px" /> Syncing...';

    // Make an AJAX request to trigger the sync process
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
      if (this.readyState == 4) {
        if (this.status == 200) {
          alert("QuickBooks customer sync has been initiated successfully.");
          location.reload();
        } else {
          alert("There was an error during the QuickBooks customer sync.");
        }
        btn.disabled = false;
        btn.innerHTML = originalText;
      }
    };
    xhttp.open("GET", "quickbooks_cust_create.php", true);
    xhttp.send();
  }

  function runQuickBooksEmployeeSync() {
    const btn = document.getElementById("runQuickBooksEmployeeSyncButton");
    const originalText = btn.innerHTML;

    // Disable the button and show a loading indicator
    btn.disabled = true;
    btn.innerHTML =
      '<img src="images/loading.gif" alt="Loading..." style="vertical-align:middle; width:30px" /> Syncing...';

    // Make an AJAX request to trigger the sync process
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
      if (this.readyState == 4) {
        if (this.status == 200) {
          alert("QuickBooks employee sync has been initiated successfully.");
          location.reload();
        } else {
          alert("There was an error during the QuickBooks employee sync.");
        }
        btn.disabled = false;
        btn.innerHTML = originalText;
      }
    };
    xhttp.open("GET", "index.php?components=qb&action=create_users_in_qb", true);
    xhttp.send();
  }
</script>


<?php
include_once 'template/footer.php';
?>