<?php
include_once 'template/header.php';
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

  .styled-table tbody tr:hover {
    background-color: #f3f3f3;
  }
</style>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:30px;" /></div>

<div>
  <?php
  function displayEmployees($employees)
  {
    $index = 1;
    echo '<table align="center" border="0" class="styled-table">';
    echo '<thead>
            <tr>
                <td colspan="6" style="color: black; background: #dddddd;" class="td-style">
                    <strong style="padding-left: 10px">QuickBooks Employees List</strong>
                </td>
            </tr>
            <tr>
                <th width="20px" align="center">#</th>
                <th align="left">Given Name</th>
                <th align="left">Family Name</th>
                <th align="left">Phone</th>
                <th align="left">Active</th>
                <th align="left">QB Employee ID</th>
            </tr>
          </thead>';
    echo '<tbody>';

    if (!empty($employees) && is_array($employees)) {
      foreach ($employees as $employee) {
        echo '<tr>';
        echo '<td>' . str_pad($index, 2, '0', STR_PAD_LEFT) . '</td>';
        echo '<td>' . htmlspecialchars($employee->GivenName) . '</td>';
        echo '<td>' . htmlspecialchars($employee->FamilyName) . '</td>';
        // Display Primary Phone
        echo '<td>' . (isset($employee->PrimaryPhone) && isset($employee->PrimaryPhone->FreeFormNumber) ? htmlspecialchars($employee->PrimaryPhone->FreeFormNumber) : '-') . '</td>';
        // Display Active
        echo '<td>' . (isset($employee->Active) ? (strtolower($employee->Active) === 'true' ? 'Yes' : 'No') : '-') . '</td>';
        echo '<td>' . htmlspecialchars($employee->Id) . '</td>';
        echo '</tr>';
        $index++;
      }
    } else {
      echo '<tr><td colspan="6" style="text-align: center;">No employees found.</td></tr>';
    }

    echo '</tbody>';
    echo '<tfoot>
            <tr>
                <th width="20px" align="center">#</th>
                <th align="left">Given Name</th>
                <th align="left">Family Name</th>
                <th align="left">Phone</th>
                <th align="left">Active</th>
                <th align="left">QB Employee ID</th>
            </tr>
          </tfoot>';
    echo '</table>';
  }

  if (!empty($message)) {
    echo '<p style="text-align: center; color: red;">' . htmlspecialchars($message) . '</p>';
  } else {
    displayEmployees($employees);
  }
  ?>
</div>

<?php
include_once 'template/footer.php';
?>