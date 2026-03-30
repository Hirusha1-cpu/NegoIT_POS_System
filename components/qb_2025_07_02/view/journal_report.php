<?php
// Assuming header.php includes necessary functions like getDecimalPlaces
include_once 'template/header.php';

// Assuming getJournalReport() is called before this snippet
// and sets $out, $message, $journalReport, $startDate, $endDate, $decimal
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
        min-width: 1300px;
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
        /* Adjust min-width based on the new column */
        min-width: 1450px;
        /* Increased from 1300px to accommodate Journal ID */
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

<!-- Notifications -->
<table align="center" style="font-size:12pt">
    <tr>
        <td>
            <?php
            if (!$out) {
                $color = 'red';
                print '<span style="color:' . $color . '; font-weight:bold;">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</span>';
            }
            ?>
        </td>
    </tr>
</table>
<!--// Notifications -->

<?php if ($out) { ?>
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
                        <td align="right">
                            <input type="submit" value="Get Report" style="height: 40px; font-weight: bold;">
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>

    <div>
        <table class="styled-table" align="center" border="0">
            <thead>
                <tr>
                    <!-- Colspan adjusted for the new column -->
                    <td colspan="9" style="color: black; background: #dddddd;" class="td-style">
                        <strong style="padding-left: 10px">QuickBooks Journal Report</strong>
                    </td>
                </tr>
                <tr>
                    <th width="100px" align="left">Date</th>
                    <!-- Added Journal ID column -->
                    <th width="80px" align="left">Journal ID</th>
                    <!-- Transaction Type, Num, Name remain commented out based on original code -->
                    <!-- <th width="120px" align="left">Transaction Type</th> -->
                    <!-- <th width="60px" align="left">Num</th> -->
                    <!-- <th width="120px" align="left">Name</th> -->
                    <th width="500px" align="left">Memo/Description</th>
                    <th width="200px" align="left">Account</th>
                    <th width="80px" align="right">Debit</th>
                    <th width="80px" align="right">Credit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rowIndex = 1; // Initialize row index counter

                function displayJournalRow($row, &$rowIndex, $decimal)
                {
                    // This function remains the same as in the previous good version
                    // It handles displaying individual data rows, hiding 0-00-00 dates
                    // and hiding 0.00 debit/credit values.
                    $dateValue = isset($row->ColData[0]->value) ? $row->ColData[0]->value : '';

                    echo '<tr>';

                    // Date column: Display the date unless it's "0-00-00"
                    echo '<td>';
                    if ($dateValue !== '0-00-00') { // Only display the date if it's NOT "0-00-00"
                        echo htmlspecialchars($dateValue, ENT_QUOTES, 'UTF-8');
                    } else {
                        echo '&nbsp;'; // Display a non-breaking space for empty cell
                    }
                    echo '</td>';

                    // Journal ID (from Transaction Type's id)
                    $journalId = isset($row->ColData[1]->id) ? $row->ColData[1]->id : '';
                    echo '<td>' . htmlspecialchars($journalId, ENT_QUOTES, 'UTF-8') . '</td>';

                    // Memo/Description
                    echo '<td>' . htmlspecialchars(isset($row->ColData[4]->value) ? $row->ColData[4]->value : '', ENT_QUOTES, 'UTF-8') . '</td>';

                    // Account
                    echo '<td>' . htmlspecialchars(isset($row->ColData[5]->value) ? $row->ColData[5]->value : '', ENT_QUOTES, 'UTF-8') . '</td>';

                    // Debit
                    $debitValue = isset($row->ColData[6]->value) ? (float) $row->ColData[6]->value : 0.0;
                    echo '<td align="right">';
                    // Hide "0.00" Debit value
                    if ($debitValue != 0.0) {
                        echo htmlspecialchars(number_format($debitValue, $decimal), ENT_QUOTES, 'UTF-8');
                    } else {
                        echo '&nbsp;'; // Use non-breaking space to maintain cell height
                    }
                    echo '</td>';

                    // Credit
                    $creditValue = isset($row->ColData[7]->value) ? (float) $row->ColData[7]->value : 0.0;
                    echo '<td align="right">';
                    // Hide "0.00" Credit value
                    if ($creditValue != 0.0) {
                        echo htmlspecialchars(number_format($creditValue, $decimal), ENT_QUOTES, 'UTF-8');
                    } else {
                        echo '&nbsp;'; // Use non-breaking space
                    }
                    echo '</td>';

                    echo '</tr>';
                }

                if (isset($journalReport->Rows->Row) && is_array($journalReport->Rows->Row)) {
                    foreach ($journalReport->Rows->Row as $row) {
                        if (isset($row->Summary)) {
                            // Check if this is the FINAL TOTAL row
                            // The 'TOTAL' row is typically the last row in the array
                            // and has a specific value in its first summary column.
                            $is_final_total = false;
                            if (isset($row->Summary->ColData[0]->value) && $row->Summary->ColData[0]->value === 'TOTAL') {
                                $is_final_total = true;
                            }

                            if ($is_final_total) {
                                // Display the FINAL TOTAL row
                                echo '<tr style="background-color: #f3f3f3; font-weight: bold;">'; // Added bold styling for total
                                // Colspan adjusted for the new column (Date + Journal ID + Memo/Description + Account = 4)
                                echo '<td colspan="4"><strong>' . htmlspecialchars(isset($row->Summary->ColData[0]->value) ? $row->Summary->ColData[0]->value : '', ENT_QUOTES, 'UTF-8') . '</strong></td>';

                                // Summary Debit
                                $summaryDebit = isset($row->Summary->ColData[6]->value) ? (float) $row->Summary->ColData[6]->value : 0.0;
                                echo '<td align="right">';
                                // Always show TOTAL Debit, even if 0.00
                                echo htmlspecialchars(number_format($summaryDebit, $decimal), ENT_QUOTES, 'UTF-8');
                                echo '</td>';

                                // Summary Credit
                                $summaryCredit = isset($row->Summary->ColData[7]->value) ? (float) $row->Summary->ColData[7]->value : 0.0;
                                echo '<td align="right">';
                                // Always show TOTAL Credit, even if 0.00
                                echo htmlspecialchars(number_format($summaryCredit, $decimal), ENT_QUOTES, 'UTF-8');
                                echo '</td>';
                                echo '</tr>';
                            } else {
                                // This is an intermediate summary row - DO NOT DISPLAY IT
                                // Optionally, you could output a simple separator row here if needed,
                                // but based on your request to hide them, we'll just skip.
                                // Example: echo '<tr><td colspan="6"><hr></td></tr>'; // Simple separator
                            }
                        } else {
                            // This is a data row - display it using the function
                            displayJournalRow($row, $rowIndex, $decimal);
                        }
                    }
                } else {
                    // Colspan adjusted for the new column
                    echo '<tr><td colspan="9">No data available</td></tr>';
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th width="100px" align="left">Date</th>
                    <th width="80px" align="left">Journal ID</th>
                    <!-- ... other header columns ... -->
                    <th width="500px" align="left">Memo/Description</th>
                    <th width="200px" align="left">Account</th>
                    <th width="80px" align="right">Debit</th>
                    <th width="80px" align="right">Credit</th>
                </tr>
            </tfoot>
        </table>
    </div>
<?php } ?>

<?php
include_once 'template/footer.php';
?>