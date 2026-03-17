<?php
require(__DIR__ . '/../../plugin/Quickbooks/vendor/autoload.php');
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Account;
use QuickBooksOnline\API\Facades\JournalEntry;
use QuickBooksOnline\API\ReportService\ReportService;
use QuickBooksOnline\API\ReportService\ReportName;
use QuickBooksOnline\API\Facades\Vendor;
use QuickBooksOnline\API\Facades\Bill;
use QuickBooksOnline\API\Facades\BillPayment;
use QuickBooksOnline\API\Facades\Purchase;

function timeNow()
{
	include('../config.php');
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='timezone'");
	$row = mysqli_fetch_assoc($result);
	$timezone = $row['value'];
	$time_now = date("Y-m-d H:i:s", time() + (60 * 60 * $timezone));
	return $time_now;
}

function dateNow()
{
	include('../config.php');
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='timezone'");
	$row = mysqli_fetch_assoc($result);
	$timezone = $row['value'];
	$date_now = date("Y-m-d", time() + (60 * 60 * $timezone));
	return $date_now;
}

function inf_company()
{
	include('../config.php');
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='company_name'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}
function inf_from_email()
{
	include('../config.php');
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='from_email'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}
function inf_to_email()
{
	include('../config.php');
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='to_email'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}
function inf_web()
{
	include('../config.php');
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='web'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}
function inf_url_primary()
{
	include('../config.php');
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='url_primary'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}
function inf_url_backup()
{
	include('../config.php');
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='url_backup'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}

function timeCheck($userid)
{
	include('../config.php');

	$result1 = mysqli_query($conn, "SELECT time_restrict FROM userprofile WHERE id='$userid'");
	$row = mysqli_fetch_assoc($result1);
	$timecheck = $row['time_restrict'];
	if ($timecheck == 1) {
		$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='timezone'");
		$row = mysqli_fetch_assoc($result);
		$timezone = $row['value'];

		$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='time_from'");
		$row = mysqli_fetch_assoc($result);
		$time_from = $row['value'];

		$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='time_to'");
		$row = mysqli_fetch_assoc($result);
		$time_to = $row['value'];

		$hour_now = date("H", time() + (60 * 60 * $timezone));
		if (($time_from < $hour_now) && ($hour_now < $time_to))
			$time_permit = true;
		else
			$time_permit = false;
	} else
		$time_permit = true;
	if (!$time_permit)
		header('Location: index.php?components=authenticate&action=logout&type=1');

}

function deviceCheck($userid)
{
	include('../config.php');
	$result1 = mysqli_query($conn, "SELECT device_restrict FROM userprofile WHERE id='$userid'");
	$row = mysqli_fetch_assoc($result1);
	$devicecheck = $row['device_restrict'];
	if ($devicecheck == 1) {
		$today = date("Y-m-d", time());
		if (isset($_COOKIE['rsaid']))
			$rsaid = $_COOKIE['rsaid'];
		else
			$rsaid = 'hhdjdhdaa44hd';
		$result = mysqli_query($conn, "SELECT count(dp.id) as `count` FROM devices dv, device_permission dp WHERE dp.device=dv.id AND dv.`key`='$rsaid' AND dv.expiration>'$today' AND dp.`user`='$userid'");
		$row = mysqli_fetch_assoc($result);
		$count = $row['count'];
		if ($count == 0)
			header('Location: index.php?components=authenticate&action=logout&type=2');
	}
}

// --------------------------- QUICKBOOKS FUNCTIONS START --------------------------- //

// added by nirmal 29_11_2023 needs because of bill2 module check this (when : deleteInvoice)

function isQuickBooksActive($method)
{
	if (isset($_SESSION['is_quickbooks_active'])) {
		return $_SESSION['is_quickbooks_active'];
	} else {
		include('../config.php');
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE setting='quickbooks'");
		$row = mysqli_fetch_assoc($result);
		if (!empty($row)) {
			if ($row['value'] == 1) {
				$_SESSION["is_quickbooks_active"] = 1;
			} else {
				$_SESSION["is_quickbooks_active"] = 0;
			}
		} else {
			$_SESSION["is_quickbooks_active"] = 0;
		}
		return $_SESSION['is_quickbooks_active'];
	}
}

// added by nirmal 29_11_2023 needs because of qb function need this in this file
function getQuickBooksRefreshToken($method)
{
	include('../config.php');

	if (isset($_SESSION['quickbooks_client_id'])) {
		$client_id = $_SESSION['quickbooks_client_id'];
	} else {
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_client_id'");
		$row = mysqli_fetch_assoc($result);
		$client_id = $row['value'];
		$_SESSION['quickbooks_client_id'] = $client_id;
	}

	if (isset($_SESSION['quickbooks_client_secret'])) {
		$client_secret = $_SESSION['quickbooks_client_secret'];
	} else {
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_client_secret'");
		$row = mysqli_fetch_assoc($result);
		$client_secret = $row['value'];
		$_SESSION['quickbooks_client_secret'] = $client_secret;
	}

	if (!isset($_SESSION['quickbooks_realmid'])) {
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_realmid'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION['quickbooks_realmid'] = $row['value'];
	}

	if (!isset($_SESSION['quickbooks_base_url'])) {
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_base_url'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION['quickbooks_base_url'] = $row['value'];
	}

	if (!isset($_SESSION['quickbooks_url'])) {
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_url'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION['quickbooks_url'] = $row['value'];
	}

	$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_refresh_token'");
	$row = mysqli_fetch_assoc($result);
	$refresh_token = $row['value'];

	$oauth2LoginHelper = new OAuth2LoginHelper($client_id, $client_secret);
	$accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($refresh_token);
	$accessTokenValue = $accessTokenObj->getAccessToken();
	$refreshTokenValue = $accessTokenObj->getRefreshToken();

	$_SESSION['quickbooks_access_token'] = $accessTokenValue;
	$_SESSION['quickbooks_refresh_token'] = $refreshTokenValue;
	$result = mysqli_query($conn, "UPDATE settings SET `value` = '$refreshTokenValue' WHERE `setting` = 'quickbooks_refresh_token'");
}

// added by nirmal 04_12_2023 needs because of qb function need this in this file
function getQuickBooksDataServiceObj()
{
	$dataService = DataService::Configure(array(
		'auth_mode' => 'oauth2',
		'ClientID' => $_SESSION['quickbooks_client_id'],
		'ClientSecret' => $_SESSION['quickbooks_client_secret'],
		'accessTokenKey' => $_SESSION['quickbooks_access_token'],
		'refreshTokenKey' => $_SESSION['quickbooks_refresh_token'],
		'QBORealmID' => $_SESSION['quickbooks_realmid'],
		'baseUrl' => $_SESSION['quickbooks_base_url']
	));
	return $dataService;
}

function fetchAccountID($conn, $accountName)
{
	$query = "SELECT `qb_account_id` FROM `accounts` WHERE `name` = '" . $accountName . "' AND `status` = 1";
	$result = mysqli_query($conn, $query);

	if ($result && ($row = mysqli_fetch_row($result))) {
		if ($row[0] !== null && $row[0] !== '') {
			return $row[0];
		} else {
			return "Error: `qb_account_id` is NULL for the account: $accountName";
		}
	} else {
		return "Error: Account '$accountName' not found or query failed.";
	}
}

function buildJournalEntry($conn, $amount, $debitAccountName, $creditAccountName, $description, $debitEntityType = "", $debitEntityID = null, $creditEntityType = "", $creditEntityID = null)
{
	// Fetch Debit Account ID
	$debitAccountID = fetchAccountID($conn, $debitAccountName);
	if (strpos($debitAccountID, "Error") !== false) {
		return array("error" => $debitAccountID);
	}

	// Fetch Credit Account ID
	$creditAccountID = fetchAccountID($conn, $creditAccountName);
	if (strpos($creditAccountID, "Error") !== false) {
		return array("error" => $creditAccountID);
	}

	// Build the journal entry array
	$journalEntry = array(
		array(
			"posting_type" => "Debit",
			"account_id" => $debitAccountID,
			"account_name" => $debitAccountName,
			"amount" => $amount,
			"description" => $description,
			"entity_type" => $debitEntityType,
			"entity_id" => $debitEntityID
		),
		array(
			"posting_type" => "Credit",
			"account_id" => $creditAccountID,
			"account_name" => $creditAccountName,
			"amount" => $amount,
			"description" => $description,
			"entity_type" => $creditEntityType,
			"entity_id" => $creditEntityID
		)
	);

	return $journalEntry;
}

function processQBPayment($creditAccountName, $debitAccountName, $conn, $paymentAmount, $qb_cust_id, $description)
{
	// Fetch the Credit Account ID using the fetchAccountID function
	$creditAccountID = fetchAccountID($conn, $creditAccountName);
	if (strpos($creditAccountID, "Error") !== false) {
		return $creditAccountID; // Return the error message directly
	}

	// Fetch the Debit Account ID using the fetchAccountID function
	$debitAccountID = fetchAccountID($conn, $debitAccountName);
	if (strpos($debitAccountID, "Error") !== false) {
		return $debitAccountID; // Return the error message directly
	}

	// Create the journal entry array
	$invoice_payment_journal_entry = [
		[
			"posting_type" => "Debit",
			"account_id" => $debitAccountID,
			"account_name" => $debitAccountName,
			"amount" => $paymentAmount,
			"description" => $description
		],
		[
			"posting_type" => "Credit",
			"account_id" => $creditAccountID,
			"account_name" => $creditAccountName,
			"amount" => $paymentAmount,
			"description" => $description,
			"entity_type" => "Customer",
			"entity_id" => $qb_cust_id
		]
	];

	return $invoice_payment_journal_entry;
}

function generateBatchID()
{
	return 'batch_' . substr(md5(microtime() . mt_rand()), 0, 16); // 16 characters
}

function isSalesmanPaymentDepositActive()
{
	if (isset($_SESSION['is_salesman_payment_deposits_active'])) {
		return $_SESSION['is_salesman_payment_deposits_active'];
	} else {
		include('../config.php');
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='salesman_payment_deposits'");
		$row = mysqli_fetch_assoc($result);
		if (!empty($row)) {
			if ($row['value'] == 1) {
				$_SESSION["is_salesman_payment_deposits_active"] = 1;
			} else {
				$_SESSION["is_salesman_payment_deposits_active"] = 0;
			}
		} else {
			$_SESSION["is_salesman_payment_deposits_active"] = 0;
		}
		return $_SESSION['is_salesman_payment_deposits_active'];
	}
}

// added by nirmal 12_12_2023 needs because of bill2 module use this (when : deleteInvoice)
function QBInvoiceDelete($qb_invoice_id)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$dataService->throwExceptionOnError(true);

	$invoice = $dataService->FindbyId('invoice', $qb_invoice_id);
	$resultingObj = $dataService->Delete($invoice);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Quickbooks updated.";
	}
	return array('status' => $status, 'message' => $message);
}

// added by nirmal 12_12_2023 needs because of bill2 module use this (when : deleteInvoice)
function QBPaymentDelete($qb_payment_id)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$qb_result_payment_id = '';
	$dataService->throwExceptionOnError(true);

	$payment = $dataService->FindbyId('payment', $qb_payment_id);
	$resultingObj = $dataService->Delete($payment);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Quickbooks updated.";
		$qb_result_payment_id = $resultingObj->Id;
	}
	return array('status' => $status, 'message' => $message, 'qb_result_payment_id' => $qb_result_payment_id);
}

// added by nirmal 19_03_2024
function QBPaymentUpdate($payment_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$qb_result_payment_id = '';
	$dataService->throwExceptionOnError(true);
	$qb_payment_id = $payment_array['qb_payment_id'];

	if (isset($payment_array['qb_cust_id'])) {
		$qb_cust_id = $payment_array['qb_cust_id'];
	} else {
		$qb_cust_id = '';
	}
	// Check and assign $amount
	if (isset($payment_array['amount'])) {
		$amount = $payment_array['amount'];
	} else {
		$amount = '';
	}

	// Check and assign $total_amount
	if (isset($payment_array['total_amount'])) {
		$total_amount = $payment_array['total_amount'];
	} else {
		$total_amount = '';
	}

	// Check and assign $qb_invoice_id
	if (isset($payment_array['qb_invoice_id'])) {
		$qb_invoice_id = $payment_array['qb_invoice_id'];
	} else {
		$qb_invoice_id = '';
	}

	// Check and assign $qb_bank_id
	if ((isset($payment_array['qb_bank_id'])) && ($payment_array['qb_bank_id'] != '')) {
		$qb_bank_id = $payment_array['qb_bank_id'];
	} else {
		$qb_bank_id = '';
	}

	// Check and assign $qb_payment_method_id
	if (isset($payment_array['qb_payment_method_id'])) {
		$qb_payment_method_id = $payment_array['qb_payment_method_id'];
	} else {
		$qb_payment_method_id = '';
	}

	// Check and assign $qb_payment_ref_number
	if (isset($payment_array['qb_payment_ref_number'])) {
		$qb_payment_ref_number = $payment_array['qb_payment_ref_number'];
	} else {
		$qb_payment_ref_number = '';
	}

	$paymentData = [
		"CustomerRef" => [
			"value" => $qb_cust_id
		],
		"TotalAmt" => $total_amount,
		"Line" => [
			[
				"Amount" => $amount,
				"LinkedTxn" => [
					[
						"TxnId" => $qb_invoice_id,
						"TxnType" => "Invoice"
					]
				]
			]
		],
		"DepositToAccountRef" => [
			"value" => $qb_bank_id
		],
		"PaymentMethodRef" => [
			"value" => $qb_payment_method_id
		],
		"PaymentRefNum" => $qb_payment_ref_number
	];

	// Remove parts if certain variables are empty
	if ($qb_cust_id === '') {
		unset($paymentData['CustomerRef']);
	}
	if ($total_amount === '') {
		unset($paymentData['TotalAmt']);
	}
	if ($amount === '') {
		unset($paymentData['Line']);
	}
	if ($qb_invoice_id === '') {
		unset($paymentData['Line']);
	}
	if ($qb_bank_id === '') {
		unset($paymentData['DepositToAccountRef']);
	}
	if ($qb_payment_method_id === '') {
		unset($paymentData['PaymentMethodRef']);
	}
	if ($qb_payment_ref_number === '') {
		unset($paymentData['PaymentRefNum']);
	}

	$payment = $dataService->FindbyId('payment', $qb_payment_id);
	$theResourceObj = Payment::update($payment, $paymentData);
	$resultingObj = $dataService->Update($theResourceObj);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Quickbooks updated.";
		$qb_result_payment_id = $resultingObj->Id;
	}
	return array('status' => $status, 'message' => $message, 'qb_result_payment_id' => $qb_result_payment_id);
}

// added by nirmal 05_09_2024
function QBUpdateJournalEntry($journal_entry_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj();

	$status = 'error';
	$message = 'Error: ';
	$qb_result_journal_entry_id = '';

	$id = $journal_entry_array['id'];
	$new_amount = $journal_entry_array['amount'];
	$new_description = $journal_entry_array['description'];

	// Fetch the existing journal entry by ID
	$journal_entry = $dataService->FindbyId('journalentry', $id);

	if ($journal_entry) {
		// Update the amount and description for each journal line (if applicable)
		if (isset($journal_entry->Line) && is_array($journal_entry->Line)) {
			foreach ($journal_entry->Line as $line) {
				if (isset($line->Amount)) {
					$line->Amount = $new_amount; // Update the amount
				}
				if (isset($line->Description)) {
					$line->Description = $new_description; // Update the description
				}
			}
		}
		$resultingObj = $dataService->Update($journal_entry);
		$error = $dataService->getLastError();
		if ($error) {
			$message .= $error->getResponseBody();
		} else {
			$status = 'success';
			$message = "Journal Entry updated successfully in QuickBooks.";
			$qb_result_journal_entry_id = $resultingObj->Id;
		}
	} else {
		$message .= "Journal Entry with ID $id not found.";
	}

	return array(
		'status' => $status,
		'message' => $message,
		'qb_result_journal_entry_id' => $qb_result_journal_entry_id
	);
}

// added by nirmal 06_09_2024
function QBDeleteJournalEntry($journal_entry_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$qb_result_journal_entry_id = '';

	$id = $journal_entry_array['id'];

	$journayentry = $dataService->FindbyId('journalentry', $id);
	$resultingObj = $dataService->Delete($journayentry);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Journal Entry delete recorded in QuickBooks.";
		$qb_result_journal_entry_id = $resultingObj->Id;
	}
	return array('status' => $status, 'message' => $message, 'qb_result_journal_entry_id' => $qb_result_journal_entry_id);
}


/**
 * Updates the Customer on all lines of a QuickBooks Journal Entry
 * where the Entity.Type is 'Customer'.
 *
 * @param array $journal_entry_array Contains:
 *   - 'id': The QuickBooks ID of the Journal Entry to update.
 *   - 'new_customer': The QuickBooks ID of the new Customer.
 *   - 'new_customer_name' (optional): The name of the new customer.
 * @return array Status array with 'status', 'message', 'qb_journal_entry_id'.
 */
function QBUpdateJournalEntryCustomer($journal_entry_array)
{
	getQuickBooksRefreshToken(1); // Assuming your auth functions
	$dataService = getQuickBooksDataServiceObj(); // Assuming your auth functions
	$dataService->throwExceptionOnError(true);

	$status = 'error';
	$message = 'Error: ';
	$qb_result_journal_entry_id = '';

	// Validate input
	if (!isset($journal_entry_array['id']) || !isset($journal_entry_array['new_customer'])) {
		$message .= "Missing required parameters: 'id' or 'new_customer'.";
		return [
			'status' => $status,
			'message' => $message,
			'qb_result_journal_entry_id' => $qb_result_journal_entry_id,
		];
	}

	$id = $journal_entry_array['id'];
	$newCustomerId = $journal_entry_array['new_customer'];
	$newCustomerName = isset($journal_entry_array['new_customer_name']) ? $journal_entry_array['new_customer_name'] : null;
	$oldCustomerName = isset($journal_entry_array['old_customer_name']) ? $journal_entry_array['old_customer_name'] : null;

	try {
		// 1. Find the Journal Entry by its ID
		$journal_entry = $dataService->FindbyId('journalentry', $id);

		if (!$journal_entry) {
			$message .= "Journal Entry with ID $id not found.";
			return [
				'status' => $status,
				'message' => $message,
				'qb_journal_entry_id' => $qb_result_journal_entry_id,
			];
		}

		$linesUpdated = false;
		$hasCustomerLines = false;
		$descriptionUpdated = false;

		// 2. Iterate through lines to find those with Entity.Type = 'Customer'
		if (isset($journal_entry->Line) && is_array($journal_entry->Line)) {
			foreach ($journal_entry->Line as $line) {
				// Check if the line has an Entity and its type is 'Customer'
				if (
					isset($line->JournalEntryLineDetail->Entity) &&
					$line->JournalEntryLineDetail->Entity->Type === 'Customer'
				) {
					$hasCustomerLines = true;

					// Directly update the EntityRef value
					$line->JournalEntryLineDetail->Entity->EntityRef = $newCustomerId;
					$linesUpdated = true;
				}

				if ($oldCustomerName && $newCustomerName && isset($line->Description)) {
					// This regex finds "Customer:" (or similar) and then captures the name until the next comma.
					$pattern = '/(\bCustomer\s*:?\s*)([^,]+)/i';
					// Replace the captured customer name with the new one.
					$newDescription = preg_replace($pattern, '$1' . $newCustomerName, $line->Description);

					if ($newDescription !== $line->Description) {
						$line->Description = $newDescription;
						$descriptionUpdated = true;
					} else {
						error_log("No customer substring was replaced in description: " . $line->Description);
					}
				}
			}
		}

		// If no customer lines found but we updated the description, consider it a success
		if (!$hasCustomerLines && $descriptionUpdated) {
			// No customer lines found, but we updated the description
			$linesUpdated = true;
		} else if (!$hasCustomerLines) {
			// No customer lines found and no description updated
			return [
				'status' => 'success',
				'message' => "Journal Entry $id does not have any customer lines to update, but the operation is considered successful.",
				'qb_result_journal_entry_id' => $id,
			];
		}

		if (!$linesUpdated) {
			$message .= "No lines with Entity.Type = 'Customer' found in Journal Entry $id and no description was updated.";
			return [
				'status' => 'error',
				'message' => $message,
				'qb_journal_entry_id' => $qb_result_journal_entry_id,
			];
		}

		// 3. Send the updated Journal Entry object back to QuickBooks
		$resultingObj = $dataService->Update($journal_entry);
		$error = $dataService->getLastError();

		if ($error) {
			$message .= "QBO API Error: " . $error->getOAuthHelperError() . " - ";
			$message .= $error->getResponseBody();
		} else {
			$status = 'success';
			$message = "Journal Entry customer updated successfully in QuickBooks.";
			if ($hasCustomerLines) {
				$message .= " Customer reference updated.";
			}
			if ($descriptionUpdated) {
				$message .= " Description updated.";
			}
			$qb_result_journal_entry_id = $resultingObj->Id;
		}
	} catch (\Exception $e) {
		$message .= " Exception: " . $e->getMessage();
	}

	return [
		'status' => $status,
		'message' => $message,
		'qb_result_journal_entry_id' => $qb_result_journal_entry_id,
	];
}

function QBUpdateJournalEntryAmount($journal_entry_array)
{
	// Refresh authentication and get the data service object.
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj();
	$dataService->throwExceptionOnError(true);

	$status = 'error';
	$message = 'Error: ';
	$qb_result_journal_entry_id = '';

	// Validate input: require both 'id' and 'new_amount'
	if (!isset($journal_entry_array['id']) || !isset($journal_entry_array['new_amount'])) {
		$message .= "Missing required parameters: 'id' or 'new_amount'.";
		return [
			'status' => $status,
			'message' => $message,
			'qb_result_journal_entry_id' => $qb_result_journal_entry_id,
		];
	}

	$id = $journal_entry_array['id'];
	$newAmount = $journal_entry_array['new_amount']; // Expected new amount as a numeric value

	try {
		// 1. Find the Journal Entry by its ID
		$journal_entry = $dataService->FindbyId('journalentry', $id);

		if (!$journal_entry) {
			$message .= "Journal Entry with ID $id not found.";
			return [
				'status' => $status,
				'message' => $message,
				'qb_result_journal_entry_id' => $qb_result_journal_entry_id,
			];
		}

		$linesUpdated = false;

		// 2. Iterate through lines to update the Amount property if present.
		if (isset($journal_entry->Line) && is_array($journal_entry->Line)) {
			foreach ($journal_entry->Line as $line) {
				// Check if this line has an Amount property.
				if (isset($line->Amount)) {
					// Update the amount of this line.
					$line->Amount = $newAmount;
					$linesUpdated = true;
				}
			}
		}

		if (!$linesUpdated) {
			$message .= "No line with an Amount property found in Journal Entry $id.";
			return [
				'status' => 'error',
				'message' => $message,
				'qb_result_journal_entry_id' => $qb_result_journal_entry_id,
			];
		}

		// Optionally update the TotalAmt property.
		// (Note: Depending on your QB configuration, TotalAmt may be calculated automatically.)
		$journal_entry->TotalAmt = $newAmount;

		// 3. Send the updated Journal Entry object back to QuickBooks.
		$resultingObj = $dataService->Update($journal_entry);
		$error = $dataService->getLastError();

		if ($error) {
			$message .= "QBO API Error: " . $error->getOAuthHelperError() . " - " .
				$error->getResponseBody();
		} else {
			$status = 'success';
			$message = "Journal Entry amount updated successfully in QuickBooks.";
			$qb_result_journal_entry_id = $resultingObj->Id;
		}
	} catch (\Exception $e) {
		$message .= " Exception: " . $e->getMessage();
	}

	return [
		'status' => $status,
		'message' => $message,
		'qb_result_journal_entry_id' => $qb_result_journal_entry_id,
	];
}

// --------------------------- QUICKBOOKS FUNCTIONS END --------------------------- //
?>