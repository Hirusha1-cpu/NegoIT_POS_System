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
        min-width: 1300px;
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
            <input type="hidden" name="action" value="trial_balance">
            <table class="styled-table" align="center" border="0">
                <tbody>
                    <tr>
                        <td>
                            <label for="start_date">Start Date:</label>
                            <input type="date" id="start_date" name="start_date" required
                                value="<?php if (isset($startDate))
                                    echo htmlspecialchars($startDate, ENT_QUOTES, 'UTF-8'); ?>">
                        </td>
                        <td>
                            <label for="end_date">End Date:</label>
                            <input type="date" id="end_date" name="end_date" required
                                value="<?php if (isset($endDate))
                                    echo htmlspecialchars($endDate, ENT_QUOTES, 'UTF-8'); ?>">
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
                    <td colspan="4" style="color: black; background: #dddddd;" class="td-style">
                        <strong style="padding-left: 10px">QuickBooks Trial Balance Report</strong>
                    </td>
                </tr>
                <tr>
                    <th width="20px" align="left">#</th>
                    <th width="120px" align="left">Account Name</th>
                    <th width="120px" align="right">Debit</th>
                    <th width="120px" align="right">Credit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $rowIndex = 1; // Initialize row index counter

                    function displayRow($row, &$rowIndex, $decimal, $isTotal = false)
                    {
                        echo '<tr' . ($isTotal ? ' style="background-color: #f3f3f3;"' : '') . '>';
                        if ($isTotal) {
                            echo '<td colspan="2"><strong>' . htmlspecialchars(isset($row->Summary->ColData[0]->value) ? $row->Summary->ColData[0]->value : '', ENT_QUOTES, 'UTF-8') . '</strong></td>';

                            // Credit
                            $creditValue = isset($row->Summary->ColData[1]->value) ? (float) $row->Summary->ColData[1]->value : 0;
                            echo '<td align="right"><strong>' . ($creditValue != 0 ? htmlspecialchars(number_format($creditValue, $decimal), ENT_QUOTES, 'UTF-8') : '') . '</strong></td>';

                            // Debit
                            $debitValue = isset($row->Summary->ColData[2]->value) ? (float) $row->Summary->ColData[2]->value : 0;
                            echo '<td align="right"><strong>' . ($debitValue != 0 ? htmlspecialchars(number_format($debitValue, $decimal), ENT_QUOTES, 'UTF-8') : '') . '</strong></td>';

                        } else {
                            echo '<td>' . sprintf('%02d', $rowIndex++) . '</td>';
                            echo '<td>' . htmlspecialchars(isset($row->ColData[0]->value) ? $row->ColData[0]->value : '', ENT_QUOTES, 'UTF-8') . '</td>';

                            // Credit
                            $creditValue = isset($row->ColData[1]->value) ? (float) $row->ColData[1]->value : 0;
                            echo '<td align="right">' . ($creditValue != 0 ? htmlspecialchars(number_format($creditValue, $decimal), ENT_QUOTES, 'UTF-8') : '') . '</td>';

                            // Debit
                            $debitValue = isset($row->ColData[2]->value) ? (float) $row->ColData[2]->value : 0;
                            echo '<td align="right">' . ($debitValue != 0 ? htmlspecialchars(number_format($debitValue, $decimal), ENT_QUOTES, 'UTF-8') : '') . '</td>';
                        }
                        echo '</tr>';
                    }

                    if (isset($trialBalanceReport->Rows->Row) && is_array($trialBalanceReport->Rows->Row)) {
                        foreach ($trialBalanceReport->Rows->Row as $row) {
                            if (isset($row->Summary)) {
                                displayRow($row, $rowIndex, $decimal, true);
                            } else {
                                displayRow($row, $rowIndex, $decimal);
                            }
                        }
                    } else {
                        echo '<tr><td colspan="4">No data available</td></tr>';
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th width="20px" align="left">#</th>
                    <th width="120px" align="left">Account Name</th>
                    <th width="120px" align="right">Debit</th>
                    <th width="120px" align="right">Credit</th>
                </tr>
            </tfoot>
        </table>
    </div>

<?php } ?>
<?php
include_once 'template/footer.php';
?>