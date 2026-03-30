<?php
include_once 'template/header.php';
?>

<style>
  /* Your existing CSS */
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
    min-width: 1500px;
    /* Increased min-width */
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

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:30px;" /></div>

<div>
  <?php
  // Display all customers in a table
  function displayCustomers($customers)
  {
    $index = 1;
    echo '<table align="center" border="0" class="styled-table">';
    echo '<thead>
            <tr>
                <td colspan="8" style="color: black; background: #dddddd;" class="td-style">
                    <strong style="padding-left: 10px">QuickBooks Customers List</strong>
                </td>
            </tr>
            <tr>
                <th width="20px" align="center">#</th>
                <th align="left">ID</th>
                <th align="left">Name</th>
                <th align="left">Phone</th>
                <th align="left">City</th>
                <th width="100px" align="right">Balance</th>
                <th width="100px" align="right">Overdue Balance</th>
            </tr>
          </thead>';
    echo '<tbody>';

    if (!empty($customers) && is_array($customers)) {
      foreach ($customers as $customer) {
        echo '<tr>';
        echo '<td>' . str_pad($index, 2, '0', STR_PAD_LEFT) . '</td>';
        echo '<td>' . htmlspecialchars($customer->Id) . '</td>';
        echo '<td>' . htmlspecialchars($customer->DisplayName) . '</td>';
        // Display Primary Phone
        echo '<td>' . (isset($customer->PrimaryPhone) && isset($customer->PrimaryPhone->FreeFormNumber) ? htmlspecialchars($customer->PrimaryPhone->FreeFormNumber) : '-') . '</td>';
        // Display City
        echo '<td>' . (isset($customer->BillAddr) && isset($customer->BillAddr->City) ? htmlspecialchars($customer->BillAddr->City) : '-') . '</td>';
        // Display Balance
        echo '<td align="right">' . (isset($customer->Balance) ? htmlspecialchars($customer->Balance) : '0') . '</td>';
        // Display Overdue Balance
        echo '<td align="right">' . (isset($customer->OverDueBalance) ? htmlspecialchars($customer->OverDueBalance) : '0') . '</td>';
        echo '</tr>';
        $index++;
      }
    } else {
      echo '<tr><td colspan="11" style="text-align: center;">No customers found.</td></tr>'; // Updated colspan
    }

    echo '</tbody>';
    echo '<tfoot>
            <tr>
                <th width="20px" align="center">#</th>
                <th align="left">ID</th>
                <th align="left">Name</th>
                <th align="left">Phone</th>
                <th align="left">City</th>
                <th width="120px" align="right">Balance</th>
                <th width="120px" align="right">Overdue Balance</th>
            </tr>
          </tfoot>';
    echo '</table>';
  }


  // Check if there is an error message to display
  if (!empty($message)) {
    echo '<p style="text-align: center; color: red;">' . $message . '</p>';
  } else {
    // Display customer data
    displayCustomers($customers);
  }
  ?>
</div>

<?php
include_once 'template/footer.php';
?>