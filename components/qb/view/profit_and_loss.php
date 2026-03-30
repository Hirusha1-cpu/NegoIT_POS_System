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
            <input type="hidden" name="action" value="profit_and_loss">
            <table class="styled-table" align="center" border="0">
                <tbody>
                    <tr>
                        <td>
                            <label for="start_date">Start Date:</label>
                            <input type="date" id="start_date" name="start_date" required value="<?php if (isset($startDate))
                                echo htmlspecialchars($startDate, ENT_QUOTES, 'UTF-8'); ?>">
                        </td>
                        <td>
                            <label for="end_date">End Date:</label>
                            <input type="date" id="end_date" name="end_date" required value="<?php if (isset($endDate))
                                echo htmlspecialchars($endDate, ENT_QUOTES, 'UTF-8'); ?>">
                        </td>
                        <td></td>
                        <td>
                            <label for="department">Sub System:</label>
                            <select id="department" name="department" required onchange="this.form.submit();">
                                <option value="">--Select Sub System--</option>
                                <?php
                                if (isset($_COOKIE['top_manager']) && !empty($_COOKIE['top_manager'])) {
                                    // Top manager: Show all sub systems
                                    $selectedDepartment = isset($_REQUEST['department']) ? $_REQUEST['department'] : '';
                                    if (isset($sub_system_id) && isset($sub_system_qb_id) && isset($sub_system_name)) {
                                        foreach ($sub_system_id as $index => $id) {
                                            $qbId = $sub_system_qb_id[$index];
                                            $sub_system = $sub_system_id[$index];  // Assuming this is the user's sub system ID
                                            $selected = ($sub_system == $_COOKIE['sub_system']) ? 'selected' : '';
                                            $selected = ($qbId == $selectedDepartment) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($qbId, ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' .
                                                htmlspecialchars($sub_system_name[$index], ENT_QUOTES, 'UTF-8') .
                                                '</option>';
                                        }
                                    }
                                } else {
                                    $selectedDepartment = isset($_REQUEST['department']) ? $_REQUEST['department'] : '';
                                    if (isset($sub_system_id) && isset($sub_system_qb_id) && isset($sub_system_name) && isset($_COOKIE['sub_system'])) {
                                        foreach ($sub_system_id as $index => $id) {
                                            if ($sub_system_id[$index] == $_COOKIE['sub_system']) {  // Match with their sub system
                                                $qbId = $sub_system_qb_id[$index];
                                                $selected = 'selected';  // Always select for non-top managers
                                                echo '<option value="' . htmlspecialchars($qbId, ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' .
                                                    htmlspecialchars($sub_system_name[$index], ENT_QUOTES, 'UTF-8') .
                                                    '</option>';
                                            }
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td></td>
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
                        <strong style="padding-left: 10px">QuickBooks Profit and Loss Report</strong>
                    </td>
                </tr>
                <tr>
                    <!-- <th width="20px" align="left">#</th> -->
                    <th width="120px" align="left">Section</th>
                    <!-- <th width="120px" align="left">Account Name</th> -->
                    <th width="120px" align="right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rowIndex = 1; // Initialize row index counter
            
                // Function to format section names by adding spaces before capital letters
                function formatSectionName($name)
                {
                    return preg_replace('/([a-z])([A-Z])/', '$1 $2', $name);
                }

                // Function to display a row with the account name and total, dynamically using the currency
                function displayRow($row, $decimal, $currency)
                {
                    // Check if the row has a total value, otherwise skip the row
                    if (isset($row->ColData[1]->value) && (float) $row->ColData[1]->value != 0) {
                        echo '<tr>';
                        echo '<td width="120px" align="left" style="padding-left: 40px"> + ' . htmlspecialchars($row->ColData[0]->value, ENT_QUOTES, 'UTF-8') . '</td>';
                        echo '<td width="120px" align="right">' . htmlspecialchars($currency . ' ' . number_format((float) $row->ColData[1]->value, $decimal), ENT_QUOTES, 'UTF-8') . '</td>';
                        echo '</tr>';
                    }
                }

                // Recursive function to handle nested rows and sections, dynamically using the currency
                function displaySection($section, $decimal, $currency)
                {
                    if (isset($section->Rows->Row) && is_array($section->Rows->Row)) {
                        foreach ($section->Rows->Row as $row) {
                            // If the row contains nested Rows, recursively handle them
                            if (isset($row->Rows->Row)) {
                                if (isset($row->Header)) {
                                    echo '<tr>';
                                    echo '<td width="120px" align="left" colspan="2"><strong> - ' . htmlspecialchars($row->Header->ColData[0]->value, ENT_QUOTES, 'UTF-8') . '</strong></td>';
                                    echo '</tr>';
                                }
                                displaySection($row, $decimal, $currency); // No indent level needed
                            } else {
                                // Display the data rows without padding
                                displayRow($row, $decimal, $currency);
                            }
                        }
                    }

                    // Display the summary for the section, if available
                    if (isset($section->Summary)) {
                        // Check if the summary title contains the word 'Total' to apply highlighting
                        $summaryTitle = $section->Summary->ColData[0]->value;
                        $highlight = strpos($summaryTitle, 'Total') !== false ? 'style="font-weight:bold"' : ''; // Highlight rows containing 'Total'
            
                        echo '<tr>';
                        echo '<td width="120px" align="left" ' . $highlight . '>' . htmlspecialchars($summaryTitle, ENT_QUOTES, 'UTF-8') . '</td>';
                        if (isset($section->Summary->ColData[1]) && isset($section->Summary->ColData[1]->value)) {
                            echo '<td width="120px" align="right" ' . $highlight . '>' . htmlspecialchars($currency . ' ' . number_format((float) $section->Summary->ColData[1]->value, $decimal), ENT_QUOTES, 'UTF-8') . '</td>';
                        } else {
                            echo '<td width="120px" align="right" ' . $highlight . '>N/A</td>';
                        }
                        echo '</tr>';
                    }
                }

                if (isset($profitAndLossReport->Rows->Row) && is_array($profitAndLossReport->Rows->Row)) {
                    // Retrieve the currency from the report header
                    $currency = isset($profitAndLossReport->Header->Currency) ? $profitAndLossReport->Header->Currency : '';

                    // Loop through sections and display the rows, skipping the "Net Income" section
                    foreach ($profitAndLossReport->Rows->Row as $section) {
                        // Skip the "Net Income" section to avoid duplication
                        if (isset($section->group) && $section->group == 'NetIncome') {
                            continue;
                        }

                        if (isset($section->Header)) {
                            echo '<tr>';
                            echo '<td width="120px" align="left" colspan="2">' . htmlspecialchars($section->Header->ColData[0]->value, ENT_QUOTES, 'UTF-8') . '</td>';
                            echo '</tr>';
                        }

                        // Call the recursive function to display the section and its nested rows
                        displaySection($section, $decimal, $currency);
                    }

                    // Display the final Net Income once at the end
                    foreach ($profitAndLossReport->Rows->Row as $section) {
                        if (isset($section->group) && $section->group == 'NetIncome') {
                            $netIncome = $section->Summary;
                            echo '<tr>';
                            echo '<td width="120px" align="left">' . htmlspecialchars($netIncome->ColData[0]->value, ENT_QUOTES, 'UTF-8') . '</td>';
                            if (isset($netIncome->ColData[1]) && isset($netIncome->ColData[1]->value)) {
                                echo '<td width="120px" align="right">' . htmlspecialchars($currency . ' ' . number_format((float) $netIncome->ColData[1]->value, $decimal), ENT_QUOTES, 'UTF-8') . '</td>';
                            } else {
                                echo '<td width="120px" align="right">N/A</td>';
                            }
                            echo '</tr>';
                        }
                    }
                } else {
                    echo '<tr><td colspan="4">No data available</td></tr>';
                }

                ?>
            </tbody>
        </table>
    </div>
<?php } ?>
<?php
include_once 'template/footer.php';
?>