<?php
include_once 'template/header.php';
?>

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

<div>
    <form method="get" action="index.php">
        <input type="hidden" name="components" value="qb">
        <input type="hidden" name="action" value="balance_sheet">
        <table class="styled-table" align="center" border="0">
            <tbody>
                <tr>
                    <!-- <td>
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" required value="<?php if (isset($startDate))
                            echo htmlspecialchars($startDate, ENT_QUOTES, 'UTF-8'); ?>">
                    </td> -->
                    <td>
                        <label for="end_date">As Of:</label>
                        <input type="date" id="end_date" name="end_date" required value="<?php if (isset($endDate))
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

<?php
// Recursive function to render rows with alignment and formatting
function renderRows($rows, $indent = 0) {
    if (!isset($rows) || empty($rows)) {
        return; // Exit if there are no rows to process
    }

    foreach ($rows as $row) {
        // Display the header row if it exists
        if (isset($row->Header->ColData)) {
            echo '<div style="display: flex; justify-content: space-between; margin-left: ' . ($indent * 20) . 'px; margin-bottom: 5px;">';
            echo '<span>' . htmlspecialchars($row->Header->ColData[0]->value) . '</span>';
            echo '<span style="text-align: right; min-width: 100px;">' . (isset($row->Header->ColData[1]->value) ? htmlspecialchars($row->Header->ColData[1]->value) : '') . '</span>';
            echo '</div>';
        }

        // Render the nested rows
        if (isset($row->Rows->Row)) {
            renderRows($row->Rows->Row, $indent + 1);
        }

        // Render data rows (like BOC, Cash on Hand, etc.)
        if (isset($row->ColData)) {
            echo '<div style="display: flex; justify-content: space-between; margin-left: ' . ($indent * 20) . 'px; position: relative; margin-bottom: 5px;">';
            echo '<span>' . htmlspecialchars($row->ColData[0]->value) . '</span>';
            echo '<span style="text-align: right; min-width: 100px;">' . htmlspecialchars($row->ColData[1]->value) . '</span>';
            echo '</div>';
        }

        // Render summary rows if available
        if (isset($row->Summary)) {
            // Add a faded gray horizontal rule before "Total" rows
            echo '<hr style="border: none; border-top: 1px solid rgba(0, 0, 0, 0.2); margin: 5px 0;">';
            echo '<div style="display: flex; justify-content: space-between; margin-left: ' . ($indent * 20) . 'px; font-weight: bold; position: relative; margin-bottom: 5px;">';
            echo '<span>' . htmlspecialchars($row->Summary->ColData[0]->value) . '</span>';
            echo '<span style="text-align: right; min-width: 100px;">' . htmlspecialchars($row->Summary->ColData[1]->value) . '</span>';
            echo '</div>';
        }
    }
}

// Function to render a section (Assets, Liabilities, etc.)
function renderSection($title, $rows, $summary = null) {
    echo '<div style="margin-bottom: 20px; width: 500px; margin-left:auto; margin-right:auto;">';
    echo '<h3 style="border-bottom: 2px solid #000;">' . htmlspecialchars($title) . '</h3>';

    if (!isset($rows->Row) || empty($rows->Row)) {
        echo '<p style="text-align: center; color: #888;">No records available</p>';
        echo '</div>';
        return; // Exit if no rows to process
    }

    renderRows($rows->Row);

    if ($summary) {
        // Render the summary row
        echo '<div style="display: flex; justify-content: space-between; font-weight: bold;">';
        echo '<span>' . htmlspecialchars($summary->ColData[0]->value) . '</span>';
        echo '<span style="text-align: right; min-width: 100px;">' . htmlspecialchars($summary->ColData[1]->value) . '</span>';
        echo '</div>';
        // Add a double horizontal rule AFTER "Total" rows
        echo '<hr style="border: none; border-top: 2px solid rgba(0, 0, 0, 0.5); margin: 2px 0;">';
        echo '<hr style="border: none; border-top: 2px solid rgba(0, 0, 0, 0.5); margin: 0 0 2px;">';
    }
    echo '</div>';
}

// Check if the dataset is valid
if (!isset($balanceSheetReport) || !isset($balanceSheetReport->Rows) || empty($balanceSheetReport->Rows->Row)) {
    echo '<div style="text-align: center; color: red; margin-top: 20px;">No data available in the report</div>';
} else {
    // Render the Assets section
    if (isset($balanceSheetReport->Rows->Row[0])) {
        $assets = $balanceSheetReport->Rows->Row[0];
        renderSection('Assets', $assets->Rows, $assets->Summary);
    }

    // Render the Liabilities and Equity section
    if (isset($balanceSheetReport->Rows->Row[1])) {
        $liabilitiesEquity = $balanceSheetReport->Rows->Row[1];
        renderSection('Liabilities and Equity', $liabilitiesEquity->Rows, $liabilitiesEquity->Summary);
    }
}
?>


<?php
include_once 'template/footer.php';
?>