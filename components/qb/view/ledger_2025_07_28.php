<?php
include_once 'template/header.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';
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

<div>
    <?php if (!empty($message)): ?>
        <div style="color: red; font-weight: bold; text-align: center; margin: 20px;">
            <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <?php
    function displayReportHeader($reportObject, $startDate, $endDate, $id)
    {
        // Check if the report object and Header exist
        if (!$reportObject || !isset($reportObject->Header)) {
            return;
        }

        $header = $reportObject->Header;
        $accountName = ''; // Initialize account name variable
        $parentAccountName = ''; // Initialize parent account name
    
        // Extract account names from Rows if available
        if (isset($reportObject->Rows->Row) && is_array($reportObject->Rows->Row)) {
            // Look for the first Row with a non-empty account name
            foreach ($reportObject->Rows->Row as $row) {
                // Check if the row has a Header with ColData
                if (isset($row->Header->ColData[0]->value) && !empty($row->Header->ColData[0]->value)) {
                    $accountName = $row->Header->ColData[0]->value;

                    // Check if there are nested rows
                    if (isset($row->Rows->Row) && is_array($row->Rows->Row)) {
                        $firstNestedRow = $row->Rows->Row[0];
                        if (isset($firstNestedRow->Header->ColData[0]->value) && !empty($firstNestedRow->Header->ColData[0]->value)) {
                            $parentAccountName = $accountName;
                            $accountName = $firstNestedRow->Header->ColData[0]->value;
                            break;
                        }
                    }
                    break;
                }
            }
        }

        // Format account name
        $displayAccountName = $accountName;
        if (!empty($parentAccountName)) {
            $displayAccountName .= " (" . $parentAccountName . ")";
        }
        echo '<form method="get" action="">';
        echo '<input type="hidden" name="components" value="qb">';
        echo '<input type="hidden" name="action" value="general_ledger">';
        echo '<input type="hidden" name="id" value="' . $id . '">';
        echo '<table align="center" style="border-collapse: collapse; margin-bottom: 2px;">';
        echo '<tbody>';

        // echo '<table align="center" style="border-collapse: collapse; margin-bottom: 2px;">';
        // echo '<tbody>';
    
        // Define fields to display
        $fieldsToDisplay = array(
            'AccountName' => 'Account Name', // Dynamic account name
            'ReportName' => 'Report Name',
            // 'DateMacro' => 'Date Range',
            'ReportBasis' => 'Report Basis',
            'StartPeriod' => 'Start Period',
            'EndPeriod' => 'End Period',
            'StartDate' => 'Start Date',
            'EndDate' => 'End Date',
            'Currency' => 'Currency',
            'Time' => 'Generated Time'
        );

        // Display standard fields
        foreach ($fieldsToDisplay as $field => $label) {
            // Special handling for AccountName
            if ($field === 'AccountName') {
                if (!empty($displayAccountName)) {
                    echo '<tr>';
                    echo '<th style="text-align: left; padding: 5px; background-color: #f2f2f2;">' . htmlspecialchars($label) . '</th>';
                    echo '<td style="padding: 5px;">' . htmlspecialchars($displayAccountName) . '</td>';
                    echo '</tr>';
                }
                continue;
            }

            if (isset($header->$field)) {
                echo '<tr>';
                echo '<th style="text-align: left; padding: 5px; background-color: #f2f2f2;">' . htmlspecialchars($label) . '</th>';

                // Special handling for Time field
                if ($field === 'Time') {
                    $dateTime = new DateTime($header->$field);
                    $readableTime = $dateTime->format('F j, Y, g:i A');
                    echo '<td style="padding: 5px;">' . htmlspecialchars($readableTime) . '</td>';
                } else if ($field === 'ReportName') {
                    echo '<td style="padding: 5px;">General Ledger</td>';
                } else {
                    echo '<td style="padding: 5px;">' . htmlspecialchars($header->$field) . '</td>';
                }
                if ($field === 'StartDate') {
                    echo '<tr>';
                    echo '<th style="text-align: left; padding: 5px; background-color: #f2f2f2;">' . htmlspecialchars($label) . '</th>';
                    echo '<td style="padding: 5px;">' . htmlspecialchars($startDate) . '</td>';
                    echo '</tr>';
                    continue;
                }
                if ($field === 'EndDate') {
                    echo '<tr>';
                    echo '<th style="text-align: left; padding: 5px; background-color: #f2f2f2;">' . htmlspecialchars($label) . '</th>';
                    echo '<td style="padding: 5px;">' . htmlspecialchars($endDate) . '</td>';
                    echo '</tr>';
                    continue;
                }
                echo '</tr>';
            }
        }

        // Handle Options, specifically checking NoReportData
        if (isset($header->Option) && is_array($header->Option)) {
            foreach ($header->Option as $option) {
                // Skip displaying NoReportData if its value is 'false'
                if (isset($option->Name) && isset($option->Value)) {
                    if (
                        strtolower($option->Name) === 'noreportdata' &&
                        (strtolower($option->Value) === 'false' || $option->Value === false)
                    ) {
                        continue; // Skip this option
                    }

                    // Display other options
                    echo '<tr>';
                    echo '<th style="text-align: left; padding: 5px; background-color: #f2f2f2;">' . htmlspecialchars($option->Name) . '</th>';
                    echo '<td style="padding: 5px;">' . htmlspecialchars($option->Value) . '</td>';
                    echo '</tr>';
                }
            }
        }

        // Date range form row
        echo '<tr>';
        echo '<th style="text-align: left; padding: 5px; background-color: #f2f2f2;">Date Range</th>';
        echo '<td style="padding: 5px;" colspan="3">';
        echo '<label for="start_date">Start Date:</label> ';
        echo '<input type="date" id="start_date" name="start_date" required value="' . htmlspecialchars($startDate, ENT_QUOTES, 'UTF-8') . '"> ';
        echo '<label for="end_date" style="margin-left:10px;">End Date:</label> ';
        echo '<input type="date" id="end_date" name="end_date" required value="' . htmlspecialchars($endDate, ENT_QUOTES, 'UTF-8') . '"> ';
        if (!empty($id)) {
            echo '<input type="submit" value="Get Report" style="height: 30px; font-weight: bold; margin-left:10px;">';
        }
        echo '</td>';
        echo '</tr>';

        // echo '</tbody>';
        // echo '</table>';
        echo '</tbody>';
        echo '</table>';
        echo '</form>';
    }

    function displayAllRows($data)
    {
        $index = 1;
        function recursiveRowDisplay($currentRow, &$index, $depth = 0)
        {
            // Handle different nested row structures
            if (isset($currentRow->Rows->Row)) {
                // First check if Rows->Row is an array
                $rowsToProcess = is_array($currentRow->Rows->Row) ?
                    $currentRow->Rows->Row :
                    array($currentRow->Rows->Row);

                foreach ($rowsToProcess as $nestedRow) {
                    // Check for different possible row structures
                    if (isset($nestedRow->ColData)) {
                        // Direct ColData structure
                        echo '<tr>';
                        echo '<td>' . str_pad($index, 2, '0', STR_PAD_LEFT) . '</td>';
                        foreach ($nestedRow->ColData as $colData) {
                            echo '<td>' . htmlspecialchars($colData->value) . '</td>';
                        }
                        echo '</tr>';
                        $index++;
                    } elseif (isset($nestedRow->Header)) {
                        // Nested row with Header
                        echo '<tr>';
                        // echo '<td>' . str_pad($index, 2, '0', STR_PAD_LEFT) . '</td>';
                        echo '<td></td>';
                        foreach ($nestedRow->Header->ColData as $colData) {
                            echo '<td>' . htmlspecialchars($colData->value) . '</td>';
                        }
                        echo '</tr>';
                        // $index++;
    
                        // Check for further nested rows
                        if (isset($nestedRow->Rows->Row)) {
                            recursiveRowDisplay($nestedRow, $index, $depth + 1);
                        }
                    }
                }
            }

            // Handle Summary row
            if (isset($currentRow->Summary)) {
                echo '<tr style="background-color: #f3f3f3; font-weight: bold;">';
                echo '<td></td>';
                foreach ($currentRow->Summary->ColData as $colData) {
                    echo '<td>' . htmlspecialchars($colData->value) . '</td>';
                }
                echo '</tr>';
            }
        }

        // Start the table
        echo '<table align="center" border="0" class="styled-table">';
        echo '<thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Payee No. Type</th>
                        <th>Num</th>
                        <th>Name</th>
                        <th>Memo</th>
                        <th>Payee Account</th>
                        <th>Payment</th>
                        <th>Balance</th>
                    </tr>
                </thead>';
        echo '<tbody>';

        // Check for different possible top-level structures
        if (isset($data->Rows->Row)) {
            // Ensure Row is always an array
            $rowsToProcess = is_array($data->Rows->Row) ?
                $data->Rows->Row :
                array($data->Rows->Row);

            foreach ($rowsToProcess as $row) {
                recursiveRowDisplay($row, $index);
            }
        }

        echo '</tbody>';
        echo '</table>';
    }

    displayReportHeader($data, $startDate, $endDate, $id);
    displayAllRows($data);
    ?>
</div>


<?php
include_once 'template/footer.php';
?>