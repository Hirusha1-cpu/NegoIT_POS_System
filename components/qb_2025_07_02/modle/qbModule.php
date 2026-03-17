<?php

// added by nirmal 07_08_2024
function getAccounts()
{
    global $decimal, $result, $message, $sortedAccounts, $out;
    $out = true;
    $decimal = getDecimalPlaces(1);
    $sortOrder = 'positive_first'; // Change this value to 'negative_first' or 'zero_first'

    $result = QBgetAccounts($sortOrder); // Get sorted accounts
    if ($result['status'] == 'error') {
        $message = htmlspecialchars($result['message'], ENT_QUOTES, 'UTF-8');
        $out = false;
    }
    if ($out) {
        $sortedAccounts = $result['data'];
    }
    return $out;
}

// added by nirmal 07_08_2024
function getProfitAndLossReport()
{
    global $decimal, $startDate, $endDate, $out, $message, $profitAndLossReport;
    $decimal = getDecimalPlaces(1);
    $out = true;

    if (isset($_REQUEST['start_date'])) {
        $startDate = $_REQUEST['start_date'] ?: date("Y-m-d", strtotime("-12 months"));
    } else {
        $startDate = date("Y-m-01");
    }

    if (isset($_REQUEST['end_date'])) {
        $endDate = $_REQUEST['end_date'] ?: date("Y-m-d");
    } else {
        $endDate = date("Y-m-d");
    }

    $result = QBgetProfitAndLoss($startDate, $endDate);
    if ($result['status'] == 'error') {
        $message = htmlspecialchars($result['message'], ENT_QUOTES, 'UTF-8');
        $out = false;
    }

    if ($out) {
        $profitAndLossReport = $result['data'];
    }

    return $out;
}

// added by nirmal 07_08_2024
function getTrialBalanceReport()
{
    global $decimal, $startDate, $endDate, $out, $message, $trialBalanceReport;
    $decimal = getDecimalPlaces(1);
    $out = true;

    if (isset($_REQUEST['start_date'])) {
        $startDate = $_REQUEST['start_date'] ?: date("Y-m-d", strtotime("-6 months"));
    } else {
        $startDate = date("Y-m-01");
    }

    if (isset($_REQUEST['end_date'])) {
        $endDate = $_REQUEST['end_date'] ?: date("Y-m-d");
    } else {
        $endDate = date("Y-m-d");
    }

    $result = QBgetTrialBalance($startDate, $endDate);
    if ($result['status'] == 'error') {
        $message = htmlspecialchars($result['message'], ENT_QUOTES, 'UTF-8');
        $out = false;
    }

    if ($out) {
        $trialBalanceReport = $result['data'];
    }

    return $out;
}

// added by nirmal 07_08_2024
function getBalanceSheetReport()
{
    global $decimal, $startDate, $endDate, $out, $message, $balanceSheetReport;
    $decimal = getDecimalPlaces(1);
    $out = true;

    if (isset($_REQUEST['start_date'])) {
        $startDate = $_REQUEST['start_date'] ?: date("Y-m-d", strtotime("-6 months"));
    } else {
        $startDate = date("Y-01-01"); // Start of the year
    }

    if (isset($_REQUEST['end_date'])) {
        $endDate = $_REQUEST['end_date'] ?: date("Y-m-d");
    } else {
        $endDate = date("Y-m-d");
    }

    $result = QBgetBalanceSheet($startDate, $endDate);
    if ($result['status'] == 'error') {
        $message = htmlspecialchars($result['message'], ENT_QUOTES, 'UTF-8');
        $out = false;
    }


    if ($out) {
        $balanceSheetReport = $result['data'];
    }

    return $out;
}

// added by nirmal 07_08_2024
function getJournalReport()
{
    global $decimal, $startDate, $endDate, $out, $message, $journalReport;
    $decimal = getDecimalPlaces(1);
    $out = true;

    if (isset($_REQUEST['start_date'])) {
        $startDate = $_REQUEST['start_date'] ?: date("Y-m-d", strtotime("-6 months"));
    } else {
        $startDate = date("Y-m-01"); // Start of the month
    }

    if (isset($_REQUEST['end_date'])) {
        $endDate = $_REQUEST['end_date'] ?: date("Y-m-d");
    } else {
        $endDate = date("Y-m-d");
    }

    $result = QBgetJournalReport($startDate, $endDate);
    if ($result['status'] == 'error') {
        $message = htmlspecialchars($result['message'], ENT_QUOTES, 'UTF-8');
        $out = false;
    }

    if ($out) {
        $journalReport = $result['data'];
    }

    return $out;
}

function getVendors()
{
    global $vendors, $message;
    $response = QBgetAllVendors();
    $vendors = [];

    if ($response['status'] == 'error') {
        $message = htmlspecialchars($response['message'], ENT_QUOTES, 'UTF-8');
    } else {
        $index = 1; // Initialize the index counter
        foreach ($response['data'] as $vendor) {
            $vendors[] = [
                'index' => $index++, // Auto-increment index
                'CompanyName' => isset($vendor->CompanyName) ? htmlspecialchars($vendor->CompanyName, ENT_QUOTES, 'UTF-8') : 'N/A',
                'PrimaryEmailAddr' => isset($vendor->PrimaryEmailAddr->Address) ? htmlspecialchars($vendor->PrimaryEmailAddr->Address, ENT_QUOTES, 'UTF-8') : 'N/A',
                'PrimaryPhone' => isset($vendor->PrimaryPhone->FreeFormNumber) ? htmlspecialchars($vendor->PrimaryPhone->FreeFormNumber, ENT_QUOTES, 'UTF-8') : 'N/A',
                'Mobile' => isset($vendor->Mobile->FreeFormNumber) ? htmlspecialchars($vendor->Mobile->FreeFormNumber, ENT_QUOTES, 'UTF-8') : 'N/A',
                'Balance' => isset($vendor->Balance) ? htmlspecialchars($vendor->Balance, ENT_QUOTES, 'UTF-8') : 'N/A',
                'Active' => isset($vendor->Active) && $vendor->Active ? 'Yes' : 'No',
                'CreateTime' => isset($vendor->MetaData->CreateTime) ? date('Y-m-d', strtotime($vendor->MetaData->CreateTime)) : 'N/A'
            ];
        }
    }
}

function createQbAccountsInSystem()
{
    global $message;
    $out = true;
    $message = "Success : Quickbooks accounts created in the system";
    $errorMessages = array();
    include('config.php');

    $result = QBgetAccounts();
    if ($result['status'] === 'error') {
        $message = $result['message'];
        $out = false;
    }

    if ($out) {
        $sortedAccounts = $result['data'];
        if (!$conn) {
            $message = "Connection failed: " . mysqli_connect_error();
            $out = false;
        }
    }

    if ($out) {
        foreach ($sortedAccounts as $account) {
            $qb_account_id = mysqli_real_escape_string($conn, $account['id']);
            $name = mysqli_real_escape_string($conn, $account['name']);
            $currentBalance = $account['current_balance'];
            $accountType = mysqli_real_escape_string($conn, $account['account_type']);
            $accountSubType = mysqli_real_escape_string($conn, $account['account_sub_type']);
            $active = ($account['active'] === 'Yes') ? 1 : 0;
            $subAccount = ($account['sub_account'] === 'Yes') ? 1 : 0;
            $parentAccount = ($account['parent_ref'] === 'None') ? "" : $account['parent_ref'];

            // Fetch category ID from account_category table
            $categoryQuery = "SELECT id FROM account_category WHERE category_level2 = '$accountType' AND category_level3 = '$accountSubType'";
            $categoryResult = mysqli_query($conn, $categoryQuery);

            if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
                $categoryRow = mysqli_fetch_assoc($categoryResult);
                $categoryId = $categoryRow['id'];
                $out = true;
            } else {
                $out = false;
                $errorMessages[] = "Failed to find category query $categoryQuery";
            }

            if ($out) {
                // Check if the account already exists
                $checkQuery = "SELECT id FROM accounts WHERE `name`='$name'";
                $checkResult = mysqli_query($conn, $checkQuery);

                if (mysqli_num_rows($checkResult) == 0) {
                    // Insert new account if it does not exist
                    if ($accountType == 'Bank')
                        $bank = 1;
                    else
                        $bank = 0;
                    if ($parentAccount == '') {
                        $insertQuery = "INSERT INTO `accounts` (`name`, `category`, `bank_ac`, `processing_fee`, `payment_ac`, `system_ac`, `status`, `qb_account_id`, `qb_status`,`qb_balance`,`shows_in_payments_bank_list`) VALUES
                        ('$name', $categoryId, $bank, 0, $bank, 0, $active, '$qb_account_id', 1, $currentBalance, $bank)";
                    } else {
                        $insertQuery = "INSERT INTO `accounts` (`name`, `category`, `bank_ac`, `processing_fee`, `payment_ac`, `system_ac`, `status`, `qb_account_id`, `qb_status`,`qb_balance`,`shows_in_payments_bank_list`, `parent_account_id`) VALUES
                        ('$name', $categoryId, $bank, 0, $bank, 0, $active, '$qb_account_id', 1, $currentBalance, $bank, $parentAccount)";
                    }
                    if (!mysqli_query($conn, $insertQuery)) {
                        $errorMessages[] = "Failed to insert account '$name': " . mysqli_error($conn);
                        $out = false;
                    }
                }
            }
        }

        if (!$out) {
            $message = implode("<br>", $errorMessages);
        }
    }

    mysqli_close($conn);
    print $message;
}


// added by nirmal 26_08_2024
function getAccountId($conn, $accountName)
{
    $query = "SELECT `qb_account_id` FROM `accounts` WHERE `name` = '$accountName' AND `status` = 1";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return isset($row['qb_account_id']) ? $row['qb_account_id'] : null;
    } else {
        return null;
    }
}

// added by nirmal 26_08_2024
function createSystemItemsInQb()
{
    global $message;
    $out = true;
    $errorMessages = [];
    $salesOfProductIncome = $costOfGoodsSold = $inventoryAsset = '';
    include('config.php');

    $salesOfProductIncome = getAccountId($conn2, 'Sales of Product Income');
    $costOfGoodsSold = getAccountId($conn2, 'Cost of Goods Sold');
    $inventoryAsset = getAccountId($conn2, 'Inventory Asset');

    // Validate account IDs
    if (empty($salesOfProductIncome)) {
        $errorMessages[] = "Error: The account 'Sales of Product Income' does not have a valid QuickBooks Account ID or it is not available in account table.";
        $out = false;
    }
    if (empty($costOfGoodsSold)) {
        $errorMessages[] = "Error: The account 'Cost of Goods Sold' does not have a valid QuickBooks Account ID or it is not available in account table.";
        $out = false;
    }
    if (empty($inventoryAsset)) {
        $errorMessages[] = "Error: The account 'Inventory Asset' does not have a valid QuickBooks Account ID or it is not available in account table.";
        $out = false;
    }

    if ($out) {
        $today = dateNow();
        $itemQuery = "SELECT `id`, `code`, `unic`, `default_cost`, `description` FROM `inventory_items` WHERE `status` = 1";
        $itemsResult = mysqli_query($conn2, $itemQuery);

        if (mysqli_num_rows($itemsResult) > 0) {
            while ($itemRow = mysqli_fetch_assoc($itemsResult)) {
                $itemId = $itemRow['id'];
                $itemCode = $itemRow['code'];
                $itemUnic = $itemRow['unic'];
                $itemDefaultCost = $itemRow['default_cost'];
                $itemDesc = $itemRow['default_cost'];
                $qty = 0;

                if (!$itemUnic) { // qty items
                    $itemQtyQuery = "SELECT `qty` FROM `inventory_qty` WHERE `item` = '$itemId'";
                    $itemsQTYResult = mysqli_query($conn2, $itemQtyQuery);
                    if (mysqli_num_rows($itemsQTYResult) > 0) {
                        while ($itemQtyRow = mysqli_fetch_assoc($itemsQTYResult)) {
                            $qty += $itemQtyRow['qty'];
                        }
                    }
                    $itemQtyNewQuery = "SELECT `qty` FROM `inventory_new` WHERE `item` = '$itemId'";
                    $itemsQTYNewResult = mysqli_query($conn2, $itemQtyNewQuery);
                    if (mysqli_num_rows($itemsQTYNewResult) > 0) {
                        while ($itemQtyNewRow = mysqli_fetch_assoc($itemsQTYNewResult)) {
                            $qty += $itemQtyNewRow['qty'];
                        }
                    }
                }
                try {
                    $item_array = [
                        'name' => $itemCode,
                        'desc' => $itemDesc,
                        'unit_price' => $itemDefaultCost,
                        'income_account_id' => $salesOfProductIncome,
                        'expense_account_id' => $costOfGoodsSold,
                        'asset_account_id' => $inventoryAsset,
                        'quantity_on_hand' => $qty,
                        'inv_start_date' => $today
                    ];
                    $qb_result = QBAddItem($item_array);
                    if ($qb_result['status'] == 'success') {
                        $message = $qb_result['message'];
                        $qb_item_id = $qb_result['qb_item_id'];
                        $query = "UPDATE `inventory_items` SET `qb_id`='$qb_item_id' WHERE `id`='$itemId'";
                        $result1 = mysqli_query($conn2, $query);
                        if (!$result1) {
                            $errorMessages[] = "Error: QuickBooks item ID update failed for item ID $itemId.";
                        }
                    } else {
                        $errorMessages[] = "QuickBooks error for item '$itemCode': " . $qb_result['message'];
                    }
                } catch (\Throwable $th) {
                    $errorMessages[] = "QuickBooks error for item '$itemCode': " . $th->getMessage();
                }
            }
        } else {
            $out = false;
            $message = "No items available to save in QuickBooks!";
        }
    }

    if (!empty($errorMessages)) {
        $message = implode("<br>", $errorMessages);
    }

    print $message;
    return $out;
}

// added by nirmal 28_08_2024
function createAServiceItem()
{
    global $message;
    $out = true;

    $salesOfProductIncome = '';
    $itemName = 'Service Item';
    $des = 'Service Item for invoices';
    include('config.php');

    $salesOfProductIncome = getAccountId($conn2, 'Services');

    if (empty($salesOfProductIncome)) {
        $errorMessages[] = "Error: The account 'Services' does not have a valid QuickBooks Account ID or it is not available in account table.";
        $out = false;
    }

    if ($out) {
        try {
            $item_array = [
                'name' => $itemName,
                'desc' => $des,
                'income_account_id' => $salesOfProductIncome
            ];
            $qb_result = QBCreateServiceItem($item_array);
            if ($qb_result['status'] == 'success') {
                $message = $qb_result['message'];
                $qb_item_id = $qb_result['qb_item_id'];
                $query = "UPDATE `settings` SET `value` = '$qb_item_id' WHERE `setting` = 'quickbooks_service_item_id'";
                $result1 = mysqli_query($conn2, $query);
                if (!$result1) {
                    $errorMessages[] = "Error: QuickBooks item ID update failed for item ID $qb_item_id.";
                }
            } else {
                $errorMessages[] = "QuickBooks error for item '$itemName': " . $qb_result['message'];
            }
        } catch (\Throwable $th) {
            $errorMessages[] = "QuickBooks error for item '$itemName': " . $th->getMessage();
        }
    }

    if (!empty($errorMessages)) {
        $message = implode("<br>", $errorMessages);
    }

    print $message;
    return $out;
}

// added by nirmal 09_09_2024
function createEmployeesInQB()
{
    include('config.php');
    $errorMessages = [];
    $successMessages = [];
    $out = true;

    try {
        // Query to select employees who need to be added to QuickBooks
        $employeeQuery = "SELECT `username`, `emp_name`, `id`, `mobile` FROM `userprofile` WHERE `status` = '0' AND qb_id IS NULL LIMIT 20";
        $employeeResult = mysqli_query($conn, $employeeQuery);

        if (mysqli_num_rows($employeeResult) > 0) {
            while ($employeeRow = mysqli_fetch_assoc($employeeResult)) {
                $username = $employeeRow['username'];
                $empName = $employeeRow['emp_name'];
                $empMobile = $employeeRow['mobile'];
                $employeeId = $employeeRow['id'];

                if ($username != '' && $empName != '') {
                    // Create an employee array for QuickBooks
                    $employeeArray = [
                        'given_name' => $username,
                        'family_name' => $empName,
                        'mobile' => $empMobile
                    ];

                    // Call function to add employee in QuickBooks
                    $qb_result = QBAddEmployee($employeeArray);

                    if ($qb_result['status'] == 'success') {
                        $qb_employee_id = $qb_result['qb_employee_id'];

                        // Update the employee in the local database with QuickBooks ID
                        $updateQuery = "UPDATE `userprofile` SET `qb_id`='$qb_employee_id' WHERE `id`='$employeeId'";
                        $updateResult = mysqli_query($conn, $updateQuery);

                        if ($updateResult) {
                            $successMessages[] = "Employee '$username' successfully added to QuickBooks.";
                        } else {
                            $out = false;
                            $errorMessages[] = "Error: Failed to update QuickBooks employee ID for employee ID $employeeId.";
                        }
                    } else {
                        $out = false;
                        $errorMessages[] = "QuickBooks error for employee '$username': " . $qb_result['message'];
                    }
                } else {
                    $out = false;
                    $errorMessages[] = "Error: Username or Employee name is empty for employee ID $employeeId.";
                }
            }
        } else {
            $successMessages[] = "No employees found to add to QuickBooks.";
        }
    } catch (Throwable $th) {
        $out = false;
        $errorMessages[] = "Error: " . $th->getMessage();
    }

    // Display success messages
    if (!empty($successMessages)) {
        foreach ($successMessages as $success) {
            echo $success . "<br>";
        }
    }

    // Display error messages
    if (!empty($errorMessages)) {
        foreach ($errorMessages as $error) {
            echo $error . "<br>";
        }
    }

    return $out;
}

function getAccountActivity()
{
    global $data, $message;
    $accountId = $_GET['id'];
    $response = QBGetAccountGeneralLedger($accountId);

    if ($response['status'] == 'error') {
        $message = htmlspecialchars($response['message'], ENT_QUOTES, 'UTF-8');
    } else {
        $data = $response['data'];
    }
}

function createCustomersInQB()
{
    include('config.php');
    $successfulCustomers = [];
    $failedCustomers = [];
    $out = true;
    $maxCustomers = 70; // Limit to 100 customers
    $totalProcessed = 0;

    try {
        // Fetch up to 70 customers who need to be added to QuickBooks
        $custQuery = "SELECT `id`,`name` FROM `cust` WHERE `qb_cust_id` IS NULL LIMIT $maxCustomers";
        $custResult = mysqli_query($conn2, $custQuery);

        if (mysqli_num_rows($custResult) > 0) {
            while ($custRow = mysqli_fetch_assoc($custResult)) {
                $custId = mysqli_real_escape_string($conn2, $custRow['id']);
                $custName = mysqli_real_escape_string($conn2, $custRow['name']);

                // Send customer to QuickBooks
                $qb_result = QBCustomerAdd($custId);

                if ($qb_result['status'] == 'success') {
                    $qb_cust_id = mysqli_real_escape_string($conn2, $qb_result['qb_cust_id']);

                    // Update the local database with QuickBooks ID
                    $updateQuery = "UPDATE `cust` SET `qb_cust_id`='$qb_cust_id' WHERE `id`='$custId'";
                    $updateResult = mysqli_query($conn2, $updateQuery);

                    if ($updateResult) {
                        $successfulCustomers[] = [
                            'name' => $custName,
                            'id' => $custId,
                            'qb_id' => $qb_cust_id
                        ];
                    } else {
                        $failedCustomers[] = [
                            'name' => $custName,
                            'id' => $custId,
                            'reason' => 'Database Update Failed'
                        ];
                    }
                } else {
                    $failedCustomers[] = [
                        'name' => $custName,
                        'id' => $custId,
                        'reason' => $qb_result['message']
                    ];
                }
                $totalProcessed++;

                // Stop processing after 70 customers
                if ($totalProcessed >= $maxCustomers) {
                    break;
                }
            }
        }

    } catch (Throwable $th) {
        $out = false;
        $failedCustomers[] = [
            'name' => 'System Error',
            'id' => 'N/A',
            'reason' => $th->getMessage()
        ];
    }

    // Summary Report
    echo "<h3>Customer Creation Report</h3>";
    echo "Total Customers Processed: $totalProcessed<br>";
    echo "Successful Customers: " . count($successfulCustomers) . "<br>";
    echo "Failed Customers: " . count($failedCustomers) . "<br>";

    return $out;
}

// added by nirmal 27_12_2024
function createVendorsInQB()
{
    include('config.php');
    $successfulVendors = array();
    $failedVendors = array();
    $out = true;
    $totalVendors = 0;

    try {
        // Query to select suppliers and join with accounts table by name
        $vendorQuery = "SELECT s.id AS supplier_id, a.id AS account_id, s.name, s.email, s.tel1, s.tel2, s.address, s.country
                        FROM supplier s JOIN accounts a ON s.name = a.name
                        WHERE s.status = '1' AND a.status = '1' AND a.qb_account_id IS NULL";

        $vendorResult = mysqli_query($conn, $vendorQuery);

        if (mysqli_num_rows($vendorResult) > 0) {
            $totalVendors = mysqli_num_rows($vendorResult);
            $vendorIndex = 1;

            while ($vendorRow = mysqli_fetch_assoc($vendorResult)) {
                $supplierId = mysqli_real_escape_string($conn, $vendorRow['supplier_id']);
                $accountId = mysqli_real_escape_string($conn, $vendorRow['account_id']);
                $vendorName = mysqli_real_escape_string($conn, $vendorRow['name']);
                $vendorArray = array(
                    'given_name' => $vendorName,
                    'company_name' => $vendorName,
                    'display_name' => $vendorName,
                    'address' => $vendorRow['address'],
                    'email' => $vendorRow['email'],
                    'primary_phone' => $vendorRow['tel1'],
                    'mobile' => $vendorRow['tel2']
                );

                $qb_result = QBAddVendor($vendorArray);

                if ($qb_result['status'] == 'success') {
                    $qb_vendor_id = mysqli_real_escape_string($conn, $qb_result['qb_vendor_id']);

                    // Update the accounts table with the QuickBooks vendor ID
                    $updateQuery = "UPDATE accounts SET qb_account_id = '$qb_vendor_id', qb_status = 1 WHERE id = '$accountId'";
                    $updateResult = mysqli_query($conn, $updateQuery);

                    if ($updateResult) {
                        $successfulVendors[] = array(
                            'index' => $vendorIndex,
                            'name' => $vendorName,
                            'supplier_id' => $supplierId,
                            'account_id' => $accountId,
                            'qb_id' => $qb_vendor_id
                        );
                    } else {
                        $failedVendors[] = array(
                            'index' => $vendorIndex,
                            'name' => $vendorName,
                            'supplier_id' => $supplierId,
                            'account_id' => $accountId,
                            'reason' => 'Failed to update accounts table'
                        );
                    }
                } else {
                    $failedVendors[] = array(
                        'index' => $vendorIndex,
                        'name' => $vendorName,
                        'supplier_id' => $supplierId,
                        'account_id' => $accountId,
                        'reason' => $qb_result['message']
                    );
                }

                $vendorIndex++;
            }
        } else {
            echo "No vendors found to add to QuickBooks.<br>";
        }
    } catch (Exception $e) {
        $out = false;
        $failedVendors[] = array(
            'index' => 'N/A',
            'name' => 'System Error',
            'supplier_id' => 'N/A',
            'account_id' => 'N/A',
            'reason' => $e->getMessage()
        );
    }

    // Detailed Reporting
    echo "<h3>Vendor Creation Report</h3>";
    echo "Total Vendors Processed: $totalVendors<br>";
    echo "Successful Vendors: " . count($successfulVendors) . "<br>";
    echo "Failed Vendors: " . count($failedVendors) . "<br>";

    // Successful Vendors Details
    if (!empty($successfulVendors)) {
        echo "<h4>Successful Vendors:</h4>";
        echo "<table border='1'>";
        echo "<tr><th>Index</th><th>Name</th><th>Supplier ID</th><th>Account ID</th><th>QuickBooks ID</th></tr>";
        foreach ($successfulVendors as $vendor) {
            echo "<tr>";
            echo "<td>{$vendor['index']}</td>";
            echo "<td>{$vendor['name']}</td>";
            echo "<td>{$vendor['supplier_id']}</td>";
            echo "<td>{$vendor['account_id']}</td>";
            echo "<td>{$vendor['qb_id']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    // Failed Vendors Details
    if (!empty($failedVendors)) {
        echo "<h4>Failed Vendors:</h4>";
        echo "<table border='1'>";
        echo "<tr><th>Index</th><th>Name</th><th>Supplier ID</th><th>Account ID</th><th>Reason for Failure</th></tr>";
        foreach ($failedVendors as $vendor) {
            echo "<tr>";
            echo "<td>{$vendor['index']}</td>";
            echo "<td>{$vendor['name']}</td>";
            echo "<td>{$vendor['supplier_id']}</td>";
            echo "<td>{$vendor['account_id']}</td>";
            echo "<td>{$vendor['reason']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    return $out;
}

function getCustomers()
{
    global $customers, $message;
    $response = QBGetAllCustomers();
    $customers = [];

    if ($response['status'] == 'error') {
        $message = htmlspecialchars($response['message'], ENT_QUOTES, 'UTF-8');
    } else {
        $customers = $response['data'];
    }
}

function createBasicAccountsInQB($conn)
{
    echo "Starting static QuickBooks account creation...\n";
    $results = array();
    $created_parent_qb_ids = array();

    $local_account_ids = array(
        'Accounts Receivable (A/R)' => 101,
        'Inventory Asset' => 102,
        'Cost of Goods Sold' => 103,
        'Cash on Hand' => 104,
        'Return Item' => 105,
        'Accounts Payable (A/P)' => 106,
        'Stock Written Off' => 107,
        'Disposal' => 108,
        'Cash and Cash Equivalence' => 109,
    );
    // --- End Local ID Definition ---


    $accounts_to_create = array(
        // 1. Create Parent Accounts First
        array('Cash and Cash Equivalence', 98, null, 'Cash and Cash Equivalence'),
        array('Disposal', 99, null, 'Disposal'),

        // 2. Create Standalone Accounts
        array('Accounts Receivable (A/R)', 1, null, 'Accounts Receivable (A/R)'),
        array('Inventory Asset', 3, null, 'Inventory Asset'),
        array('Cost of Goods Sold', 4, null, 'Cost of Goods Sold'),
        array('Accounts Payable (A/P)', 8, null, 'Accounts Payable (A/P)'),
        array('Stock Written Off', 9, null, 'Stock Written Off'),

        // 3. Create Child Accounts (after parents)
        array('Cash on Hand', 5, 'Cash and Cash Equivalence', 'Cash on Hand'),
        array('Return Item', 7, 'Disposal', 'Return Item'),
    );
    // --- End Account Definition ---


    // --- Process Account Creation --- AND THIS IS THE LOOP YOU ASKED ABOUT ---
    foreach ($accounts_to_create as $account_data) {
        // Use list() which is compatible with PHP 5
        list($ac_name, $cat_id, $parent_name, $local_id_key) = $account_data;

        // Check if local ID is defined for this account
        if (!isset($local_account_ids[$local_id_key])) {
            $message = "Skipping '{$ac_name}': No local ID defined in \$local_account_ids array for key '{$local_id_key}'. Please define it.";
            echo $message . "\n";
            $results[$ac_name] = array('status' => 'skipped', 'message' => $message); // Use array()
            continue; // Skip this account
        }
        $local_id = $local_account_ids[$local_id_key];

        // Determine Parent QB ID
        $parent_qb_id = null;
        if ($parent_name !== null) {
            if (isset($created_parent_qb_ids[$parent_name])) {
                $parent_qb_id = $created_parent_qb_ids[$parent_name];
            } else {
                // This means the parent account failed creation earlier or wasn't processed first.
                $message = "Skipping '{$ac_name}': Required parent account '{$parent_name}' was not successfully created or its QB ID was not found.";
                echo $message . "\n";
                $results[$ac_name] = array('status' => 'skipped', 'message' => $message); // Use array()
                continue; // Skip this account
            }
        }

        // Use concatenation and isset() check for PHP 5 compatibility
        echo "Attempting to create '{$ac_name}' (Local ID: {$local_id}, Cat ID: {$cat_id}, Parent: " . ($parent_name !== null ? $parent_name : 'None') . ")... ";

        try {
            // Call the single-account creation function (latest version)
            $result = createAccountInQB($conn, $cat_id, $ac_name, $parent_qb_id, $local_id);

            echo "Success! QB ID: {$result['qb_account_id']}\n";
            $results[$ac_name] = $result; // $result is already an array

            // If this account was a parent, store its QB ID for later use by children
            if ($parent_name === null && in_array($ac_name, array('Disposal', 'Cash and Cash Equivalence'))) {
                if (isset($result['qb_account_id'])) { // Ensure QB ID exists before storing
                    $created_parent_qb_ids[$ac_name] = $result['qb_account_id'];
                }
            }

        } catch (Exception $e) {
            $error_message = "Failed: " . $e->getMessage();
            echo $error_message . "\n";
            $results[$ac_name] = array('status' => 'error', 'message' => $e->getMessage()); // Use array()
            // Decide whether to stop or continue on error
            // break; // Uncomment to stop processing immediately on the first error
            continue; // Keep processing remaining accounts even if one fails
        }
    }
    // --- End Process Account Creation ---

    echo "Static account creation process finished.\n";
    echo "--- Summary --- \n";
    print_r($results); // Print a summary of what happened
    echo "---------------\n";

    return $results; // Return the results array
}

function createAccountInQB($conn, $account_category_id, $ac_name, $parentAccountQbId, $local_account_id)
{
    // Validate inputs (basic)
    if (!is_object($conn) || !($conn instanceof mysqli)) {
        throw new Exception("Invalid database connection provided.");
    }
    // Basic escaping for mysqli_query (still not fully secure like prepared statements)
    $safe_account_category_id = mysqli_real_escape_string($conn, (string) $account_category_id);
    $safe_local_account_id = mysqli_real_escape_string($conn, (string) $local_account_id);
    $safe_ac_name = mysqli_real_escape_string($conn, $ac_name); // Escape name for error messages

    if (empty($safe_account_category_id) || !is_numeric($account_category_id)) { // Check original numeric value too
        throw new Exception("Invalid Account Category ID provided for '{$safe_ac_name}'.");
    }
    if (empty($ac_name)) { // Check original name
        throw new Exception("Account Name cannot be empty.");
    }
    if (empty($safe_local_account_id) || !is_numeric($local_account_id)) { // Check original numeric value too
        throw new Exception("Invalid Local Account ID provided for '{$safe_ac_name}'.");
    }


    // 1. Fetch category details using mysqli_query
    $account_classification = null;
    $account_type = null;
    $account_sub_type = null;

    // Construct query string with escaped variable
    $query_cat = "SELECT `category_level1`, `category_level2`, `category_level3` FROM account_category WHERE `id`='{$safe_account_category_id}'";
    $result_cat = mysqli_query($conn, $query_cat);

    if (!$result_cat) {
        // Query failed
        throw new Exception("DB Query Error (Category): " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result_cat) > 0) {
        $row_cat = mysqli_fetch_array($result_cat); // Or mysqli_fetch_assoc($result_cat)
        $account_classification = $row_cat[0]; // Or $row_cat['category_level1']
        $account_type = $row_cat[1];           // Or $row_cat['category_level2']
        $account_sub_type = $row_cat[2];         // Or $row_cat['category_level3']
        mysqli_free_result($result_cat); // Free result set
    } else {
        // Category ID not found
        mysqli_free_result($result_cat); // Free result set even if empty
        throw new Exception("Error: Account category ID '{$safe_account_category_id}' not found for account '{$safe_ac_name}'.");
    }


    // --- Start: Logic matching the user's original snippet ---

    // 2. Prepare data for QBAddAccount - *Always* include parent_account_id as per original
    $account_array = array(
        'account_name' => $ac_name, // Use original name for QB
        'account_type' => $account_type,
        'account_sub_type' => $account_sub_type,
        'account_classification' => $account_classification,
        'parent_account_id' => $parentAccountQbId // Included even if null/empty
    );

    // Define $qb_msg here
    $qb_msg = 'An unknown QuickBooks error occurred.';
    // Define $qb_result here
    $qb_result = array('status' => 'error');

    try {
        // 3. Call the QuickBooks API function
        $qb_result = QBAddAccount($account_array); // Assume this function exists

        // 4. Process QB result
        $qb_msg = isset($qb_result['message']) ? $qb_result['message'] : 'QuickBooks status was not success, but no message was provided.';

        // 5. Check status
        if ((isset($qb_result['status'])) && ($qb_result['status'] == 'success')) {

            // 6. Get QB Account ID
            $qb_account_id = isset($qb_result['qb_account_id']) ? $qb_result['qb_account_id'] : null;

            // 7. Check if QB Account ID is valid
            if (!empty($qb_account_id)) {
                // 8. Return success details
                return array(
                    'status' => 'success',
                    'message' => $qb_msg,
                    'qb_account_id' => $qb_account_id, // Return original ID
                    'local_account_id' => $local_account_id, // Return original ID
                    'account_name' => $ac_name // Return original name
                );
            } else {
                // Original logic: throw exception if QB ID is null/empty after success status
                throw new Exception("Error: Quickbooks account ID is null or empty after success status for '{$safe_ac_name}'.");
            }
        } else {
            // Original logic: throw exception if status is not success
            throw new Exception($qb_msg);
        }
    } catch (Exception $e) {
        // Original logic: construct message, attempt to set status, re-throw
        $qb_msg = "<br>QuickBooks error: " . $e->getMessage();
        if (is_array($qb_result)) {
            $qb_result['status'] = 'error';
        }
        throw new Exception($qb_msg);
    }
}

function getDashboard()
{
    global $queue_total, $error_total, $message;
    include('config.php');

    $query = "SELECT COUNT(`id`) FROM `qb_queue`";
    $result = mysqli_query($conn2, $query);
    if ($result) {
        $row = mysqli_fetch_array($result);
        $queue_total = $row[0];
        mysqli_free_result($result);
    } else {
        $queue_total = 0;
    }

    $query = "SELECT COUNT(`id`) FROM `qb_queue_error_log`";
    $result = mysqli_query($conn2, $query);
    if ($result) {
        $row = mysqli_fetch_array($result);
        $error_total = $row[0];
        mysqli_free_result($result);
    } else {
        $error_total = 0;
    }
}
?>