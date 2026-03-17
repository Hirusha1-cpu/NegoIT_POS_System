<?php

function isMobile()
{
	return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function getUnlockBills()
{
	global $bm_bill, $bm_time, $bm_user;
	$today = dateNow();
	$bm_bill = array();
	include('../config.php');
	$query = "SELECT bm.invoice_no,time(bm.billed_timestamp),up.username FROM bill bi, bill_main bm, userprofile up WHERE bi.invoice_no=bm.invoice_no AND bm.billed_by=up.id AND bm.`status`!='0' AND bm.`lock`=0 AND date(bm.billed_timestamp)='$today' GROUP BY bm.invoice_no ORDER BY bm.invoice_no DESC";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$bm_bill[] = $row[0];
		$bm_time[] = $row[1];
		$bm_user[] = $row[2];
	}
}

function getOneLockSt()
{
	global $lockstatus, $py_inv, $py_type, $py_amount;
	if (isset($_REQUEST['lockinvid'])) {
		include('../config.php');
		$bill_id = $_REQUEST['lockinvid'];
		$result = mysqli_query($conn, "SELECT `lock` FROM bill_main WHERE invoice_no='$bill_id'");
		$row = mysqli_fetch_row($result);
		$lockstatus = $row[0];

		$query = "SELECT id,payment_type,amount FROM payment WHERE `status`=0 AND invoice_no='$bill_id'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$py_inv[] = $row[0];
			if ($row[1] == 1)
				$py_type[] = 'Cash';
			if ($row[1] == 2)
				$py_type[] = 'Chque';
			$py_amount[] = $row[2];
		}
	}
}

function changeLock()
{
	global $message, $bill_id;

	include('../config.php');
	$bill_id = $_REQUEST['lockinvid'];
	$result = mysqli_query($conn, "SELECT `lock` FROM bill_main WHERE invoice_no='$bill_id'");
	$row = mysqli_fetch_row($result);
	$lockstatus = $row[0];
	if ($lockstatus == 0)
		$newlock = 1;
	if ($lockstatus == 1)
		$newlock = 0;

	$result = mysqli_query($conn, "UPDATE bill_main SET `lock`='$newlock' WHERE invoice_no='$bill_id'");

	if ($result) {
		$message = 'Lock was Updated Successfully!';
		return true;
	} else {
		$message = 'Lock could not be Updated!';
		return false;
	}
}

function searchDelete()
{
	global $message, $type, $id;
	$type = $_GET['type'];
	include('../config.php');
	if ($type == 'bill') {
		$message = 'Invalid Invoice Number';
		$id = ltrim($_POST['search1'], '0');
		$query = "SELECT count(invoice_no) FROM bill_main WHERE invoice_no='$id'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		if ($row[0] == 1)
			return true;
		else
			return false;
	} else
		if ($type == 'pay') {
			$message = 'Invalid Payment Number';
			$id = ltrim($_POST['search2'], '0');
			$query = "SELECT count(id) FROM payment WHERE id='$id'";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			if ($row[0] == 1)
				return true;
			else
				return false;
		} else
			if ($type == 'commission') {
				$message = 'Invalid Commission Number';
				$id = ltrim($_POST['search3'], '0');
				$query = "SELECT count(id) FROM hp_commission_main WHERE id='$id'";
				$row = mysqli_fetch_row(mysqli_query($conn, $query));
				if ($row[0] == 1)
					return true;
				else
					return false;
			} else {
				$message = 'Invalid Operation';
				return false;
			}
}

function searchInv()
{
	global $inv_found, $id, $inv_billed_by, $inv_type_id, $inv_store, $inv_sms, $inv_date, $inv_total, $status_out, $inv_status, $status_color;
	$inv_found = false;
	include('../config.php');
	if (isset($_GET['bill_no'])) {
		$id = $_GET['bill_no'];
		$query = "SELECT bm.billed_by,st.name,date(bm.order_timestamp),(bm.`invoice_+total` + bm.`invoice_-total`),bm.`type`,bm.sms,bm.`status` FROM bill_main bm, stores st WHERE bm.store=st.id AND bm.invoice_no='$id'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$inv_billed_by = $row[0];
		$inv_store = $row[1];
		$inv_date = $row[2];
		$inv_total = $row[3];
		$inv_type = $row[4];
		$inv_type_id = $row[4];
		$inv_sms = $row[5];
		$inv_status = $row[6];
		if ($row[0] != '') {
			switch ($inv_status) {
				case 0:
					$status_out = 'Deleted';
					$status_color = '#FF3300';
					break;
				case 1:
					$status_out = 'Billed (Pending)';
					$status_color = 'yellow';
					break;
				case 2:
					$status_out = 'Billed (Picked)';
					$status_color = 'yellow';
					break;
				case 3:
					if ($inv_type == 3) {
						$status_out = 'Billed (Picked)';
					} else {
						$status_out = 'Billed (Packed)';
					}
					$status_color = 'yellow';
					break;
				case 4:
					if ($inv_type == 3) {
						$status_out = 'Repaired';
					} else {
						$status_out = 'Billed (Shipped)';
					}
					$status_color = 'yellow';
					break;
				case 5:
					if ($inv_type == 3) {
						$status_out = 'Repaired | Delivered';
					} else {
						$status_out = 'Billed (Delivered)';
					}
					$status_color = 'white';
					break;
				case 6:
					$status_out = 'Rejected';
					$status_color = 'orange';
					break;
				case 7:
					$status_out = 'Rejected | Delivered';
					$status_color = 'orange';
					break;
			}
		}
		if ($row[0] != '')
			$inv_found = true;
	}
}

function searchCustOrderItem()
{
	global $inv_found, $id, $inv_billed_by, $inv_type_id, $inv_store, $inv_sms, $inv_date, $inv_total, $status_out, $inv_status, $status_color;
	$inv_found = false;
	include('../config.php');
	if (isset($_GET['bill_no'])) {
		$id = $_GET['bill_no'];
		$query = "SELECT bm.billed_by,st.name,date(bm.order_timestamp),(bm.`invoice_+total` + bm.`invoice_-total`),bm.`type`,bm.sms,bm.`status` FROM bill_main bm, stores st WHERE bm.store=st.id AND bm.invoice_no='$id' AND bm.type = '4'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$inv_billed_by = $row[0];
		$inv_store = $row[1];
		$inv_date = $row[2];
		$inv_total = $row[3];
		$inv_type = $row[4];
		$inv_type_id = $row[4];
		$inv_sms = $row[5];
		$inv_status = $row[6];
		if ($row[0] != '') {
			switch ($inv_status) {
				case 0:
					$status_out = 'Deleted';
					$status_color = '#FF3300';
					break;
				case 1:
					$status_out = 'Billed (Pending)';
					$status_color = 'yellow';
					break;
				case 2:
					$status_out = 'Billed (Picked)';
					$status_color = 'yellow';
					break;
				case 3:
					if ($inv_type == 3) {
						$status_out = 'Billed (Picked)';
					} else {
						$status_out = 'Billed (Packed)';
					}
					$status_color = 'yellow';
					break;
				case 4:
					if ($inv_type == 3) {
						$status_out = 'Repaired';
					} else {
						$status_out = 'Billed (Shipped)';
					}
					$status_color = 'yellow';
					break;
				case 5:
					if ($inv_type == 3) {
						$status_out = 'Repaired | Delivered';
					} else {
						$status_out = 'Billed (Delivered)';
					}
					$status_color = 'white';
					break;
				case 6:
					$status_out = 'Rejected';
					$status_color = 'orange';
					break;
				case 7:
					$status_out = 'Rejected | Delivered';
					$status_color = 'orange';
					break;
			}
		}
		if ($row[0] != '')
			$inv_found = true;
	}
}

function setCustOrderMain()
{
	global $message, $bill_no;
	$invoice_no = $bill_no = $_POST['bill_no'];
	$status = $_POST['status'];
	$message = 'Nothing is changed';
	include('../config.php');
	$time_now = timeNow();

	mysqli_begin_transaction($conn);
	try {
		$billMainQuery = "SELECT `type`,`mapped_inventory` FROM bill_main WHERE invoice_no='$invoice_no'";
		$billMainQueryResult = mysqli_query($conn, $billMainQuery);
		$billMainQueryResultRow = mysqli_fetch_assoc($billMainQueryResult);
		$type = $billMainQueryResultRow['type'];
		$bm_mapped_inventory = $billMainQueryResultRow['mapped_inventory'];

		// Invoice bill item (-qty) update to inventory
		$queryBillItems = "SELECT bi.item, bi.qty, itm.pr_sr, itm.unic
		FROM bill bi
		JOIN inventory_items itm ON bi.item = itm.id
		WHERE bi.invoice_no = '$invoice_no'";
		$resultBillItems = mysqli_query($conn, $queryBillItems);
		$uniqueCalculation = unicCal();
		$queryUpdateInventory = $queryUpdateInventory1 = '';

		while ($billItem = mysqli_fetch_array($resultBillItems)) {
			$inventoryId = '';
			$itemId = $billItem[0];
			$itemQuantity = $billItem[1];
			$itemPriceStatus = $billItem[2];
			$itemUniqueStatus = $billItem[3];

			if ($itemPriceStatus == 1) {
				if ((($itemUniqueStatus == 1) && (!$uniqueCalculation)) || ($itemUniqueStatus == 0)) {
					$queryInventoryId = "SELECT id FROM inventory_qty WHERE item = '$itemId' AND `location` = '$bm_mapped_inventory'";
					$inventoryRow = mysqli_fetch_row(mysqli_query($conn, $queryInventoryId));
					$inventoryId = $inventoryRow[0];

					if ($itemQuantity < 0) {
						$queryUpdateInventory = "UPDATE `inventory_qty` SET `qty` = `qty` + $itemQuantity WHERE `id` = '$inventoryId'";
						$resultUpdateInventory = mysqli_query($conn, $queryUpdateInventory);
						if (!$resultUpdateInventory) {
							$errorMessage = "Error: Inventory could not be updated. " . mysqli_error($conn);
							$out = false;
							throw new Exception($errorMessage);
						}
					}
				}
			}
		}

		// Return items with bill (-qty) update to inventory
		$queryReturnItems = "SELECT rt.`replace_item`, SUM(rt.`qty`), itm.`pr_sr`, itm.`unic` FROM `return` rt
												JOIN inventory_items itm ON itm.`id` = rt.`replace_item`
												WHERE rt.`odr_no` = '$invoice_no' AND rt.`odr_packed` = 1
												GROUP BY rt.`replace_item`";
		$resultReturnItems = mysqli_query($conn, $queryReturnItems);

		while ($returnItem = mysqli_fetch_array($resultReturnItems)) {
			$inventoryId = '';
			$replaceItemId = $returnItem[0];
			$replaceItemQty = $returnItem[1];
			$itm_pr_sr = $returnItem[2];
			$itemUniqueStatus = $returnItem[3];

			if ($itm_pr_sr == 1) {
				if ((($itemUniqueStatus == 1) && (!$uniqueCalculation)) || ($itemUniqueStatus == 0)) {
					$queryInventoryId = "SELECT `id` FROM inventory_qty WHERE `item`='$replaceItemId' AND `location`='$bm_mapped_inventory'";
					$inventoryRow = mysqli_fetch_row(mysqli_query($conn, $queryInventoryId));
					$inventoryId = $inventoryRow[0];

					$queryBillItems = "SELECT bi.`item`, bi.`qty` AS total_qty, itm.`pr_sr`, itm.`unic`
														FROM bill bi
														JOIN inventory_items itm ON bi.`item` = itm.`id`
														WHERE bi.`invoice_no` = '$invoice_no' AND bi.`item` = '$replaceItemId'";
					$resultBillItems = mysqli_query($conn, $queryBillItems);
					while ($billItem = mysqli_fetch_array($resultBillItems)) {
						$billItemId = $billItem[0];
						$billItemQty = $billItem[1];
						if (($replaceItemQty > 0) && ($billItemQty < 0)) {
							$queryUpdateInventory1 = "UPDATE `inventory_qty` SET `qty` = `qty` + $replaceItemQty WHERE `id` = '$inventoryId'";
							$resultUpdateInventory = mysqli_query($conn, $queryUpdateInventory1);
							if (!$resultUpdateInventory) {
								$errorMessage = "Error: Inventory could not be updated. " . mysqli_error($conn);
								$out = false;
								throw new Exception($errorMessage);
							}
						}
					}
				}
			}
		}

		$query = "UPDATE bill_main SET `status`='$status' WHERE invoice_no='$invoice_no'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$message = 'Error: Invoice status could not be updated!';
			throw new Exception($message);
		}

		if (isQuickBooksActive(1)) {
			$custQuery = "SELECT c.qb_cust_id, c.name FROM cust c JOIN bill_main bm ON bm.cust = c.id WHERE bm.invoice_no = '$invoice_no' LIMIT 1";
			$custResult = mysqli_query($conn, $custQuery);
			if (!$custResult || mysqli_num_rows($custResult) == 0) {
				throw new Exception("Failed to fetch customer details for QB reversal.");
			}
			$custRow = mysqli_fetch_assoc($custResult);
			$qb_cust_id = $custRow['qb_cust_id'];
			if (empty($qb_cust_id)) {
				throw new Exception("This customer is not registered in QB");
			}
			$custName = $custRow['name'];

			// Delete associated invoice
			$journalEntryForDeleteInvoiceValue = $journalEntryForDeleteInvoiceCost = $journal_entry_result = $journal_entry_result1 = [];
			$invoiceQuery = "SELECT SUM(b.`qty` * b.`cost`) as cost, SUM(bm.`invoice_+total`+bm.`invoice_-total`) as total, bm.`qb_value_id`, bm.`qb_cost_id`, c.`name`, c.`qb_cust_id`
							FROM `bill_main` bm, bill b, `cust` c
							WHERE bm.`invoice_no` = b.`invoice_no` AND bm.`qb_status` IS NOT NULL AND c.`id` = bm.`cust` AND bm.`invoice_no`='$invoice_no'";
			$invoiceResult = mysqli_query($conn, $invoiceQuery);

			if ($invoiceResult && mysqli_num_rows($invoiceResult) > 0) {
				$invoiceRow = mysqli_fetch_assoc($invoiceResult);
				$custName = $invoiceRow['name'];
				$invoiceCost = $invoiceRow['cost'];
				$invoiceTotal = $invoiceRow['total'];
				$qb_cust_id = $invoiceRow['qb_cust_id'];

				$debitAccountName = "Sales";
				$creditAccountName = "Accounts Receivable (A/R)";
				$description = "[INVOICE STATUS CHANGE BACK TO BILLED] - Invoice No: $invoice_no, Customer : $custName";
				$debitEntityType = "";
				$debitEntityID = "";
				$creditEntityType = "Customer";
				$creditEntityID = $qb_cust_id;

				$journalEntryForDeleteInvoiceValue = buildJournalEntry($conn, abs($invoiceTotal), $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
				if (isset($journalEntryForDeleteInvoiceValue['error'])) {
					$flag = false;
					$qb_msg = $journalEntryForDeleteInvoiceValue['error'];
					throw new Exception(mysqli_real_escape_string($conn, $qb_msg));
				} else {
					$batch_id = generateBatchID();
					$action_name = 'delete_invoice_total';
					foreach ($journalEntryForDeleteInvoiceValue as $entry) {
						$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
						$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
						$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
						$amount = mysqli_real_escape_string($conn, $entry['amount']);
						$description = mysqli_real_escape_string($conn, $entry['description']);
						$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
						$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

						$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `invoice_total`, `description`, `created_at`, `entity_type`, `entity_id`)
										VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description','$time_now',
											" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
											" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
						$result = mysqli_query($conn, $query);
						if (!$result) {
							$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
							throw new Exception($message);
						}
					}
				}

				// Retun items (packaged items journal entires)
				$query = "SELECT rt.replace_item, rt.qty, rm.mapped_inventory, rt.extra_pay, rt.id
									FROM return_main rm
									JOIN `return` rt ON rm.invoice_no = rt.invoice_no
									JOIN cust c ON c.id = rm.cust
									WHERE rt.odr_no = '$invoice_no' AND rt.odr_packed = '1'";
				$result = mysqli_query($conn, $query);
				if (mysqli_num_rows($result) > 0) {
					$item_cost = 0;
					while ($returnItemRow = mysqli_fetch_array($result)) {
						$replace_item = $returnItemRow[0];
						$rt_qty = $returnItemRow[1];
						$mapped_inventory = $returnItemRow[2];
						$extra_pay = $returnItemRow[3];
						$return_id = $returnItemRow[4];

						// Get the cost of the item from the inventory
						$cost_query = "SELECT c_price FROM inventory_qty WHERE item='$replace_item' AND `location`='$mapped_inventory'";
						$row = mysqli_fetch_row(mysqli_query($conn, $cost_query));

						// Ensure the cost query returns a result
						if ($row) {
							$cost = $row[0];
							// Add the cost of this returned item to the total cost
							$item_cost += (($cost * $rt_qty) + $extra_pay);
						}
					}

					// Proceed only if return items are found
					$debitAccountName = "Inventory Asset";
					$creditAccountName = "Return Item";
					$description = "[REVERT BACK RETURN ITEMS] - Invoice No: $invoice_no, Customer: $custName";
					$debitEntityType = "";
					$debitEntityID = "";
					$creditEntityType = "";
					$creditEntityID = "";

					$journalEntryForReturnItems = buildJournalEntry($conn, $item_cost, $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
					if (isset($journalEntryForReturnItems['error'])) {
						$qb_msg = $journalEntryForReturnItems['error'];
						throw new Exception($qb_msg);
					} else {
						$batch_id = generateBatchID();
						$action_name = 'return_item_invoice_insert_through_odr_insert';

						foreach ($journalEntryForReturnItems as $entry) {
							$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
							$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
							$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
							$amount = mysqli_real_escape_string($conn, $entry['amount']);
							$description = mysqli_real_escape_string($conn, $entry['description']);
							$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
							$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

							$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `created_at`, `entity_type`, `entity_id`)
																VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description','$time_now',
																	" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
																	" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
							$result = mysqli_query($conn, $query);
							if (!$result) {
								$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
								throw new Exception($message);
							}
						}
					}
				}
			}
		}
		// Commit moved AFTER all operations including QB
		mysqli_commit($conn);

		$message = 'Invoice status updated successfully';
		return true;
	} catch (Exception $ex) {
		mysqli_rollback($conn);
		$message = $ex->getMessage();
		return false;
	}
}

function getLock($type, $status)
{
	$lock = 0;
	switch ($status) {
		case 1:
			if ($type == 1)
				$lock = 1;
			if ($type == 4)
				$lock = 2;
			break;
		case 2:
			if ($type == 1)
				$lock = 1;
			if ($type == 4)
				$lock = 2;
			break;
		case 3:
			if ($type == 1)
				$lock = 1;
			if ($type == 4)
				$lock = 1;
			break;
		case 4:
			if ($type == 1)
				$lock = 1;
			if ($type == 4)
				$lock = 1;
			break;
		case 5:
			if ($type == 1)
				$lock = 1;
			if ($type == 4)
				$lock = 1;
			break;
	}
	return $lock;
}

// function setInvMain()
// {
// 	global $message, $bill_no;
// 	$bill_no = $_POST['bill_no'];
// 	$type = $_POST['type'];
// 	$status = $_POST['status'];
// 	$sms = $_POST['sms'];
// 	$lock = getLock($type, $status);
// 	$out = true;
// 	$message = 'Nothing is Changed';
// 	include('../config.php');

// 	$query = "SELECT `type`,sms,`status` FROM bill_main WHERE invoice_no='$bill_no'";
// 	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
// 	$old_type = $row[0];
// 	$old_sms = $row[1];
// 	$old_status = $row[2];

// 	if ($out && ($old_type != $type)) {
// 		$query = "UPDATE bill_main SET `type`='$type',`lock`='$lock' WHERE invoice_no='$bill_no'";
// 		$result = mysqli_query($conn, $query);
// 		if ($result)
// 			$message = 'Invoice Details were Changed Successfully';
// 		else {
// 			$message = 'Error: Invoice Details could not be Changed !';
// 			$out = false;
// 		}
// 	}
// 	if ($out && ($old_status != $status)) {
// 		$query = "UPDATE bill_main SET `status`='$status',`lock`='$lock' WHERE invoice_no='$bill_no'";
// 		$result = mysqli_query($conn, $query);
// 		if ($result)
// 			$message = 'Invoice Details were Changed Successfully';
// 		else {
// 			$message = 'Error: Invoice Details could not be Changed !';
// 			$out = false;
// 		}
// 	}
// 	if ($out && ($old_sms != $sms)) {
// 		$query = "UPDATE bill_main SET `sms`='$sms' WHERE invoice_no='$bill_no'";
// 		$result = mysqli_query($conn, $query);
// 		if ($result)
// 			$message = 'Invoice Details were Changed Successfully';
// 		else {
// 			$message = 'Error: Invoice Details could not be Changed !';
// 			$out = false;
// 		}
// 	}

// 	if (isQuickBooksActive(1)) {
// 		if (($old_type != $type)) {
// 			if ($old_type == 1 && $type == 4) { // sales bill to cust order
// 				// need to revert back account Receivable and sales
// 				$invoiceQuery = "SELECT SUM(b.`qty` * b.`cost`) as cost, SUM(bm.`invoice_+total`+bm.`invoice_-total`) as total, bm.`qb_value_id`,
// 				bm.`qb_cost_id`, c.`name`, c.`qb_cust_id`
// 				FROM `bill_main` bm, bill b, `cust` c
// 				WHERE bm.`invoice_no` = b.`invoice_no` AND bm.`qb_status` IS NOT NULL AND c.`qb_cust_id` IS NOT NULL AND c.`id` = bm.`cust` AND bm.`invoice_no`='$bill_no'";
// 				$invoiceResult = mysqli_query($conn, $invoiceQuery);

// 				if ($invoiceResult && mysqli_num_rows($invoiceResult) > 0) {
// 					$invoiceRow = mysqli_fetch_assoc($invoiceResult);

// 					$custName = $invoiceRow['name'];
// 					$invoiceCost = $invoiceRow['cost'];
// 					$invoiceTotal = $invoiceRow['total'];
// 					$qb_cust_id = $invoiceRow['qb_cust_id'];

// 					$debitAccountName = "Sales";
// 					$creditAccountName = "Accounts Receivable (A/R)";
// 					$description = "[SALES BILL TO CUST ORDER] - Invoice No: $bill_no, Customer : $custName";
// 					$debitEntityType = "";
// 					$debitEntityID = "";
// 					$creditEntityType = "Customer";
// 					$creditEntityID = $qb_cust_id;

// 					$journalEntryForDeleteInvoiceValue = buildJournalEntry($conn, abs($invoiceTotal), $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
// 					if (isset($journalEntryForDeleteInvoiceValue['error'])) {
// 						$flag = false;
// 						$qb_msg = $journalEntryForDeleteInvoiceValue['error'];
// 						throw new Exception(mysqli_real_escape_string($conn, $qb_msg));
// 					} else {
// 						$batch_id = generateBatchID();
// 						$action_name = 'sales_bill_to_cust_order';
// 						foreach ($journalEntryForDeleteInvoiceValue as $entry) {
// 							$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
// 							$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
// 							$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
// 							$amount = mysqli_real_escape_string($conn, $entry['amount']);
// 							$description = mysqli_real_escape_string($conn, $entry['description']);
// 							$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
// 							$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

// 							$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `invoice_total`, `description`, `created_at`, `entity_type`, `entity_id`)
// 							VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description','$time_now',
// 							" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
// 							" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
// 							$result = mysqli_query($conn, $query);
// 							if (!$result) {
// 								$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
// 								throw new Exception($message);
// 							}
// 						}
// 					}
// 				}
// 			}
// 			if ($old_type == 4 && $type == 1) { // cust order to sales bill
// 				$query = "SELECT bm.`invoice_+total`, bm.`invoice_-total`, c.`qb_cust_id`, c.`name`
// 				FROM bill_main bm, `cust` c
// 				WHERE c.`id` = bm.`cust` AND bm.`qb_status` IS NOT NULL AND c.`qb_cust_id` IS NOT NULL AND bm.`invoice_no`='$bill_no'";
// 				$row = mysqli_fetch_row(mysqli_query($conn, $query));
// 				$bill_total_plus = $row[0];
// 				$bill_total_minus = $row[1];
// 				$qb_cust_id = $row[2];
// 				$custName = $row[3];
// 				$bill_total = $bill_total_plus + $bill_total_minus;

// 				// invoice total journal entry
// 				$debitAccountName = "Accounts Receivable (A/R)";
// 				$creditAccountName = "Sales";
// 				$description = "[CUST ORDER TO SALES BILL] - Invoice No: $bill_no, Customer : $custName";
// 				$debitEntityType = "Customer";
// 				$debitEntityID = $qb_cust_id;
// 				$creditEntityType = "";
// 				$creditEntityID = "";

// 				$journalEntryForInvoiceTotal = buildJournalEntry($conn, $bill_total, $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
// 				if (isset($journalEntryForInvoiceTotal['error'])) {
// 					$qb_msg = $journalEntryForInvoiceTotal['error'];
// 					throw new Exception("QuickBooks error: " . $qb_msg);
// 				} else {
// 					$batch_id = generateBatchID();
// 					$action_name = 'cust_order_to_sales_bill';
// 					foreach ($journalEntryForInvoiceTotal as $entry) {
// 						$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
// 						$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
// 						$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
// 						$amount = mysqli_real_escape_string($conn, $entry['amount']);
// 						$description = mysqli_real_escape_string($conn, $entry['description']);
// 						$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
// 						$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

// 						$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `created_at`, `entity_type`, `entity_id`)
// 				VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description','$time_now',
// 					" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
// 					" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
// 						$result = mysqli_query($conn, $query);
// 						if (!$result) {
// 							$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
// 							throw new Exception($message);
// 						}
// 					}
// 				}
// 			}
// 		}
// 	}

// 	if ($out)
// 		return true;
// 	else
// 		return false;
// }

function setInvMain()
{
	global $message, $bill_no;
	$bill_no = $_POST['bill_no'];
	$type = $_POST['type'];
	$status = $_POST['status'];
	$sms = $_POST['sms'];
	$lock = getLock($type, $status);
	$message = 'Nothing is changed';
	include('../config.php');
	$time_now = timeNow();

	// Retrieve current invoice record (using $conn2 as before)
	$query = "SELECT `type`, sms, `status` FROM bill_main WHERE invoice_no='$bill_no'";
	$result = mysqli_query($conn2, $query);
	if (!$result) {
		$message = "Error retrieving invoice: " . mysqli_error($conn2);
		return false;
	}
	$row = mysqli_fetch_row($result);
	$old_type = $row[0];
	$old_sms = $row[1];
	$old_status = $row[2];

	// Begin transaction on $conn
	mysqli_begin_transaction($conn);

	try {
		// Update type if changed
		if ($old_type != $type) {
			$query = "UPDATE bill_main SET `type`='$type', `lock`='$lock' WHERE invoice_no='$bill_no'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				throw new Exception("Error: Invoice Details could not be Changed (Type Update)! " .
					mysqli_error($conn));
			}
			$message = 'Invoice Details were Changed Successfully';
		}

		if (($old_type == 4) && ($type == 4) && ($old_status != $status)) {
			throw new Exception("Error: You cannot change cust order status in here!");
		}

		if ($old_type == 1 && $type == 4) {
			$status = 1; // Force status to "Billed"
		}

		// Update status if changed
		if ($old_status != $status) {
			$query = "UPDATE bill_main SET `status`='$status', `lock`='$lock' WHERE invoice_no='$bill_no'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				throw new Exception("Error: Invoice Details could not be Changed (Status Update)! " .
					mysqli_error($conn));
			}
			$message = 'Invoice Details were Changed Successfully';
		}

		// Update SMS if changed
		if ($old_sms != $sms) {
			$query = "UPDATE bill_main SET `sms`='$sms' WHERE invoice_no='$bill_no'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				throw new Exception("Error: Invoice Details could not be Changed (SMS Update)! " .
					mysqli_error($conn));
			}
			$message = 'Invoice Details were Changed Successfully';
		}

		// QuickBooks related updates, if active
		if (isQuickBooksActive(1)) {
			if ($old_type != $type) {
				// Sales bill to customer order: need to revert accounts receivable and sales
				if ($old_type == 1 && $type == 4) {
					$invoiceQuery = "SELECT SUM(b.`qty` * b.`cost`) as cost, SUM(bm.`invoice_+total` + bm.`invoice_-total`) as total, bm.`qb_value_id`, bm.`qb_cost_id`, c.`name`, c.`qb_cust_id`
                        FROM `bill_main` bm, bill b, `cust` c
                        WHERE bm.`invoice_no` = b.`invoice_no` AND bm.`qb_status` IS NOT NULL AND c.`qb_cust_id` IS NOT NULL AND c.`id` = bm.`cust` AND bm.`invoice_no`='$bill_no'";
					$invoiceResult = mysqli_query($conn, $invoiceQuery);
					if (!$invoiceResult || mysqli_num_rows($invoiceResult) === 0) {
						throw new Exception("QuickBooks invoice query error: " . mysqli_error($conn));
					}
					$invoiceRow = mysqli_fetch_assoc($invoiceResult);
					$custName = $invoiceRow['name'];
					$invoiceTotal = $invoiceRow['total'];
					$qb_cust_id = $invoiceRow['qb_cust_id'];

					$debitAccountName = "Sales";
					$creditAccountName = "Accounts Receivable (A/R)";
					$description = "[SALES BILL TO CUST ORDER] - Invoice No: $bill_no, Customer : $custName";
					$debitEntityType = "";
					$debitEntityID = "";
					$creditEntityType = "Customer";
					$creditEntityID = $qb_cust_id;

					$journalEntryForDeleteInvoiceValue = buildJournalEntry(
						$conn,
						abs($invoiceTotal),
						$debitAccountName,
						$creditAccountName,
						$description,
						$debitEntityType,
						$debitEntityID,
						$creditEntityType,
						$creditEntityID
					);

					if (isset($journalEntryForDeleteInvoiceValue['error'])) {
						$qb_msg = mysqli_real_escape_string(
							$conn,
							$journalEntryForDeleteInvoiceValue['error']
						);
						throw new Exception($qb_msg);
					} else {
						$batch_id = generateBatchID();
						$action_name = 'sales_bill_to_cust_order';
						foreach ($journalEntryForDeleteInvoiceValue as $entry) {
							$posting_type = mysqli_real_escape_string(
								$conn,
								$entry['posting_type']
							);
							$account_id = mysqli_real_escape_string(
								$conn,
								$entry['account_id']
							);
							$account_name = mysqli_real_escape_string(
								$conn,
								$entry['account_name']
							);
							$amount = mysqli_real_escape_string(
								$conn,
								$entry['amount']
							);
							$desc = mysqli_real_escape_string(
								$conn,
								$entry['description']
							);
							$entity_type = isset($entry['entity_type']) ?
								mysqli_real_escape_string($conn, $entry['entity_type']) : null;
							$entity_id = isset($entry['entity_id']) ?
								mysqli_real_escape_string($conn, $entry['entity_id']) : null;

							$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`,`posting_type`, `account_id`, `account_name`, `amount`,`description`, `created_at`, `entity_type`, `entity_id`)
                        VALUES ('$batch_id','$action_name','$bill_no','$posting_type','$account_id','$account_name','$amount','$desc','$time_now',
                        " . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
                        " . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
							$result = mysqli_query($conn, $query);
							if (!$result) {
								throw new Exception("MySQL Error while inserting into qb_queue: " .
									mysqli_error($conn));
							}
						}
					}
				}
				// Customer order to sales bill
				if ($old_type == 4 && $type == 1) {
					$query = "SELECT bm.`invoice_+total`, bm.`invoice_-total`,c.`qb_cust_id`, c.`name`FROM bill_main bm, `cust` c
                    WHERE c.`id` = bm.`cust` AND bm.`qb_status` IS NOT NULL AND c.`qb_cust_id` IS NOT NULL AND bm.`invoice_no`='$bill_no'";
					$result = mysqli_query($conn, $query);
					if (!$result) {
						throw new Exception("QuickBooks error: " .
							mysqli_error($conn));
					}
					$row = mysqli_fetch_row($result);
					if (!$row) {
						throw new Exception("No record found for QuickBooks conversion.");
					}
					$bill_total_plus = $row[0];
					$bill_total_minus = $row[1];
					$qb_cust_id = $row[2];
					$custName = $row[3];
					$bill_total = $bill_total_plus + $bill_total_minus;

					$debitAccountName = "Accounts Receivable (A/R)";
					$creditAccountName = "Sales";
					$description = "[CUST ORDER TO SALES BILL] - Invoice No: $bill_no, Customer : $custName";
					$debitEntityType = "Customer";
					$debitEntityID = $qb_cust_id;
					$creditEntityType = "";
					$creditEntityID = "";

					$journalEntryForInvoiceTotal = buildJournalEntry(
						$conn,
						$bill_total,
						$debitAccountName,
						$creditAccountName,
						$description,
						$debitEntityType,
						$debitEntityID,
						$creditEntityType,
						$creditEntityID
					);

					if (isset($journalEntryForInvoiceTotal['error'])) {
						$qb_msg = $journalEntryForInvoiceTotal['error'];
						throw new Exception("QuickBooks error: " . $qb_msg);
					} else {
						$batch_id = generateBatchID();
						$action_name = 'cust_order_to_sales_bill';
						foreach ($journalEntryForInvoiceTotal as $entry) {
							$posting_type = mysqli_real_escape_string(
								$conn,
								$entry['posting_type']
							);
							$account_id = mysqli_real_escape_string(
								$conn,
								$entry['account_id']
							);
							$account_name = mysqli_real_escape_string(
								$conn,
								$entry['account_name']
							);
							$amount = mysqli_real_escape_string(
								$conn,
								$entry['amount']
							);
							$desc = mysqli_real_escape_string(
								$conn,
								$entry['description']
							);
							$entity_type = isset($entry['entity_type']) ?
								mysqli_real_escape_string($conn, $entry['entity_type']) : null;
							$entity_id = isset($entry['entity_id']) ?
								mysqli_real_escape_string($conn, $entry['entity_id']) : null;

							$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`,`posting_type`, `account_id`, `account_name`, `amount`,`description`, `created_at`, `entity_type`, `entity_id`)
                        VALUES ('$batch_id','$action_name','$bill_no','$posting_type','$account_id','$account_name','$amount','$desc','$time_now',
                        " . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
                        " . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
							$result = mysqli_query($conn, $query);
							if (!$result) {
								throw new Exception("MySQL Error while inserting into qb_queue: " .
									mysqli_error($conn));
							}
						}
					}
				}
			}
		}

		mysqli_commit($conn);
		return true;
	} catch (Exception $ex) {
		mysqli_rollback($conn);
		$message = $ex->getMessage();
		return false;
	}
}

function billStatus()
{
	global $bm_status, $bm_lock, $bm_type, $bm_module, $bm_cust, $status_out, $status_color;
	$invoice_no = $_REQUEST['id'];
	$today = dateNow();
	include('../config.php');
	$query = "SELECT billed_by,date(`billed_timestamp`),`status`,`lock`,`store`,`type`,`module`,`cust` FROM bill_main WHERE invoice_no='$invoice_no'";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_row($result);
	$salesman = $row[0];
	$date = $row[1];
	$bm_status = $row[2];
	$bm_lock = $row[3];
	$bm_store = $row[4];
	$bm_type = $row[5];
	$bm_module = $row[6];
	$bm_cust = $row[7];

	switch ($bm_status) {
		case 0:
			$status_out = 'Deleted';
			$status_color = '#FF3300';
			break;
		case 1:
			$status_out = 'Billed (Pending)';
			$status_color = 'yellow';
			break;
		case 2:
			$status_out = 'Billed (Picked)';
			$status_color = 'yellow';
			break;
		case 3:
			if ($bm_type == 3) {
				$status_out = 'Billed (Picked)';
			} else {
				$status_out = 'Billed (Packed)';
			}
			$status_color = 'yellow';
			break;
		case 4:
			if ($bm_type == 3) {
				$status_out = 'Repaired';
			} else {
				$status_out = 'Billed (Shipped)';
			}
			$status_color = 'yellow';
			break;
		case 5:
			if ($bm_type == 3) {
				$status_out = 'Repaired | Delivered';
			} else {
				$status_out = 'Billed (Delivered)';
			}
			$status_color = 'white';
			break;
		case 6:
			$status_out = 'Rejected';
			$status_color = 'orange';
			break;
		case 7:
			$status_out = 'Rejected | Delivered';
			$status_color = 'orange';
			break;
	}
	if ($bm_lock == 0 && $bm_status != 0) {
		$status_out = 'Unlocked Bill';
		$status_color = 'yellow';
	}
}

function generateInvoice()
{
	global $print_time, $tm_company, $tm_address, $tm_tel, $tm_web, $tm_email, $chq0_fullNo, $bill_id, $bi_desc, $bi_code, $bi_discount, $bi_qty, $bi_price, $total, $ledc2, $bi_drawer, $bi_type, $pay_id, $cash_amount, $chque_amount, $chq0_date, $bi_cust0, $bi_cust, $bi_salesman_id, $up_salesman, $bi_date, $bi_time, $cu_id, $cu_details, $up_mobile, $bm_status, $bm_quotation_no, $qm_warranty, $qm_terms, $qm_po, $bm_packed_by;
	$invoice_no = $_REQUEST['id'];
	$chq0_no = $chq0_bnk = $chq0_branch = $bm_packed_by = '';
	$cash_amount = $chque_amount = 0;
	$sn_list = array();
	include('../config.php');
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='timezone'");
	$row = mysqli_fetch_assoc($result);
	$timezone = $row['value'];
	$print_time = date("Y-m-d H:i:s", time() + (60 * 60 * $timezone));

	$break_point = 1;

	$query = "SELECT bm.`type`,cu.name,bm.billed_by,up.username,date(bm.billed_timestamp),time(bm.billed_timestamp),bm.`store`,cu.id,cu.nic,cu.mobile,cu.`status`,up.mobile,bm.`status`,bm.mapped_inventory,bm.quotation_no,bm.packed_by FROM bill_main bm, cust cu, userprofile up WHERE  up.id=bm.billed_by AND bm.`cust`=cu.id AND bm.invoice_no='$invoice_no'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$bi_type = $row[0];
	if ($row[10] == 2)
		$bi_cust = 'Customer : ' . $row[1] . '<br />NIC: ' . $row[8] . ' &nbsp;&nbsp; Mobile: ' . $row[9];
	else
		$bi_cust = 'Customer : ' . $row[1];
	$bi_cust0 = $row[1];
	$bi_salesman_id = $row[2];
	$up_salesman = $row[3];
	$bi_date = $row[4];
	$bi_time = $row[5];
	$store = $row[6];
	$cu_id = $row[7];
	$cu_details = 'NIC        : ' . $row[8] . '&#13;Mobile  : ' . $row[9];
	$up_mobile = $row[11];
	$bm_status = $row[12];
	$bm_mapped_inventory = $row[13];
	$bm_quotation_no = $row[14];
	$bm_packed_by0 = $row[15];

	//		$query="SELECT bi.id,inv.description,bi.qty,bi.unit_price,inv.id,bm.`type`,bi.`comment`,cu.name,bm.billed_by,up.username, date(bm.billed_timestamp),time(bm.billed_timestamp),inv.code,bi.discount,bm.`store`,cu.id,cu.nic,cu.mobile,cu.`status`,up.mobile,inv.unic,bm.`status` FROM bill_main bm ,bill bi, inventory_items inv, cust cu, userprofile up WHERE bm.invoice_no=bi.invoice_no AND up.id=bm.billed_by AND bm.`cust`=cu.id AND bi.item=inv.id AND bi.invoice_no='$invoice_no' ORDER BY bi.id";
	$query = "SELECT bi.id,inv.description,bi.qty,bi.unit_price,inv.id,bi.`comment`,inv.code,bi.discount,inv.unic FROM bill_main bm ,bill bi, inventory_items inv, cust cu, userprofile up WHERE bm.invoice_no=bi.invoice_no AND up.id=bm.billed_by AND bm.`cust`=cu.id AND bi.item=inv.id AND bi.invoice_no='$invoice_no' ORDER BY bi.id";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$bill_id_tmp = $row[0];
		$bill_id[] = $row[0];
		if (($bi_type == 1) && ($row[8] == 1)) {
			$unic_sn = '';
			$k = 1;
			$sn_list = explode(",", $row[5]);
			for ($i = 0; $i < sizeof($sn_list); $i++) {
				if ($k == $break_point) {
					$break_unic = '<br />';
					$k = 0;
				} else {
					$break_unic = '&nbsp;&nbsp;';
				}
				$unic_sn = $unic_sn . '[' . $sn_list[$i] . ']' . $break_unic;
				$k++;
			}
			if ($unic_sn != '') {
				$bi_desc[] = $row[1] . '<br />' . $unic_sn;
			} else {
				$bi_desc[] = $row[1] . '<br /><br />';
			}
		} else if (($bi_type == 2) || ($bi_type == 3) || ($bi_type == 5))
			$bi_desc[] = $row[5] . '<br />&nbsp;&nbsp;&nbsp;&nbsp;[ ' . $row[1] . ' ]' . '<br />';
		else if ((($bi_type == 1) || ($bi_type == 4)) && ($row[8] == 0))
			$bi_desc[] = $row[1] . '<br />';
		$bi_qty[] = $row[2];
		$bi_price[] = $row[3];
		$item_id = $row[4];
		$total += $row[2] * $row[3];
		$ledc2[] = str_repeat('_', (12 - strlen(number_format($row[2] * $row[3]))));
		$bi_code[] = $row[6];
		$bi_discount[] = $row[7];
		$result1 = mysqli_query($conn, "SELECT drawer_no FROM inventory_qty WHERE item='$item_id' AND location='$store'");
		$row1 = mysqli_fetch_assoc($result1);
		if (($bi_type == 1) || ($bi_type == 4))
			$bi_drawer[] = $row1['drawer_no'];
		else
			$bi_drawer[] = '<br /><br /><br />';

	}
	$query1 = "SELECT id,payment_type,SUM(amount),chque_no,chque_bank,chque_branch,chque_date FROM payment WHERE bill_pay=1 AND invoice_no='$invoice_no' AND `status`=0 GROUP BY payment_type";
	$result1 = mysqli_query($conn, $query1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$pay_id[] = $row1[0];
		if ($row1[1] == 1)
			$cash_amount = $row1[2];
		if ($row1[1] == 2)
			$chque_amount = $row1[2];
		$chq0_no = $row1[3];
		$chq0_bnk = $row1[4];
		$chq0_branch = $row1[5];
		$chq0_date = $row1[6];
	}
	if ($chq0_bnk > 0) {
		$query2 = "SELECT bank_code FROM bank WHERE id=$chq0_bnk";
		$result2 = mysqli_query($conn, $query2);
		while ($row2 = mysqli_fetch_array($result2)) {
			$chq0_bnk = $row2[0];
		}
		$chq0_fullNo = '[ Cheque No: ' . $chq0_no . '-' . $chq0_bnk . '-' . $chq0_branch . ' ]';
	} else
		$chq0_fullNo = '';

	if ($bm_quotation_no != 0) {
		$result = mysqli_query($conn, "SELECT warranty,terms2,cust_po FROM quotation_main WHERE id='$bm_quotation_no'");
		$row = mysqli_fetch_assoc($result);
		$qm_warranty = $row['warranty'];
		$qm_terms = $row['terms2'];
		$qm_po = $row['cust_po'];
	}
	if ($bm_packed_by0 != '') {
		$result = mysqli_query($conn, "SELECT username FROM userprofile WHERE id='$bm_packed_by0'");
		$row = mysqli_fetch_assoc($result);
		$bm_packed_by = $row['username'];
	}

	$result = mysqli_query($conn, "SELECT shop_name,address,tel FROM stores WHERE id='$bm_mapped_inventory'");
	$row = mysqli_fetch_assoc($result);
	$tm_company = $row['shop_name'];
	$tm_address = $row['address'];
	$tm_tel = $row['tel'];
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='web'");
	$row = mysqli_fetch_assoc($result);
	$tm_web = $row['value'];
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='email'");
	$row = mysqli_fetch_assoc($result);
	$tm_email = $row['value'];
}

function generatePayment()
{
	global $tm_company, $tm_address, $tm_tel, $payment_id, $cust_name, $payment_type, $amount, $chque_no, $chque_bank, $chque_branch, $chque_date, $salesman, $payment_date, $invoice_no;
	$payment_id = $_REQUEST['id'];

	include('../config.php');
	$query = "SELECT cu.name,py.payment_type,py.amount,py.chque_no,py.chque_bank,py.chque_branch,py.chque_date,up.username,date(py.payment_date),py.invoice_no,py.store FROM payment py, cust cu, userprofile up WHERE py.cust=cu.id AND py.salesman=up.id AND py.id='$payment_id'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$cust_name = $row[0];
		$payment_type = $row[1];
		$amount = $row[2];
		$chque_no = $row[3];
		$chque_bank_id = $row[4];
		$chque_branch = $row[5];
		$chque_date = $row[6];
		$salesman = $row[7];
		$payment_date = $row[8];
		$invoice_no = $row[9];
		$store = $row[10];
	}

	$query = "SELECT name FROM bank WHERE id='$chque_bank_id'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$chque_bank = $row[0];
	}

	$result = mysqli_query($conn, "SELECT address,tel FROM stores WHERE id='$store'");
	$row = mysqli_fetch_assoc($result);
	$tm_address = $row['address'];
	$tm_tel = $row['tel'];

	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='company_name'");
	$row = mysqli_fetch_assoc($result);
	$tm_company = $row['value'];
}

// update by nirmal 04_03_2024 (fixed config include bug)
function payStatus()
{
	global $paymentpermission, $py_status, $status_out, $status_color;
	$payment_no = $_REQUEST['id'];
	$user = $_COOKIE['user_id'];
	$today = date("Y-m-d", time());
	include('../config.php');
	$query = "SELECT DISTINCT salesman,date(`payment_date`),`status` FROM payment WHERE id='$payment_no'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$salesman = $row[0];
		$date = $row[1];
		$py_status = $row[2];
	}

	switch ($py_status) {
		case 0:
			$status_out = 'Paid';
			$status_color = 'white';
			break;
		case 1:
			$status_out = 'Deleted';
			$status_color = '#FF3300';
			break;
	}
}

function comStatus()
{
	global $com_status, $status_out, $status_color;
	$com_no = $_REQUEST['id'];
	include('../config.php');
	$query = "SELECT count(id) FROM hp_commission_main WHERE id='$com_no'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$count = $row[0];

	if ($count == 1) {
		$status_out = 'Active';
		$status_color = 'white';
	} else {
		$status_out = 'Deleted';
		$status_color = '#FF3300';
	}
}

function getCategory()
{
	global $cat_id, $cat_name;
	include('../config.php');
	$query = "SELECT id,name FROM item_category";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$cat_id[] = $row[0];
		$cat_name[] = $row[1];
	}
}

function getStore()
{
	global $st_id, $st_name;
	include('../config.php');
	$query = "SELECT id,name FROM stores WHERE `status`=1";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$st_id[] = $row[0];
		$st_name[] = $row[1];
	}
}

function setClear()
{
	global $message;
	$category = $_REQUEST['category'];
	$store = $_REQUEST['store'];
	if (md5($_REQUEST['password']) == 'c3f1396a463205b149f7559b01fea607') {
		include('../config.php');

		$result = mysqli_query($conn, "SELECT name FROM item_category WHERE `id`='$category'");
		$row = mysqli_fetch_row($result);
		$cat_name = $row[0];
		$result = mysqli_query($conn, "SELECT name FROM stores WHERE `id`='$store'");
		$row = mysqli_fetch_row($result);
		$st_name = $row[0];

		$result = mysqli_query($conn, "SELECT MAX(job_id) FROM backup_cat_qty1");
		$row = mysqli_fetch_row($result);
		$max_job_id = $row[0];
		if ($max_job_id == '')
			$max_job_id = 0;

		$next_job_id = $max_job_id + 1;

		$query = "SELECT itq.item,itq.id,itq.qty,itq.drawer_no FROM inventory_qty itq, inventory_items itm WHERE itm.id=itq.item AND itm.category='$category' AND itq.location='$store'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$itq_item = $row[0];
			$itq_id = $row[1];
			$itq_qty = $row[2];
			$itq_drawer_no = $row[3];
			mysqli_query($conn, "INSERT INTO `backup_cat_qty1` (`job_id`,`category`,`store`,`itq_id`,`qty`,`drawer_no`) VALUES ('$next_job_id','$cat_name','$st_name','$itq_id','$itq_qty','$itq_drawer_no')");
		}

		$query = "SELECT itn.item,itn.id,itn.w_price,itn.r_price,itn.c_price,itn.qty,itn.shipment_no FROM inventory_new itn, inventory_items itm WHERE itm.id=itn.item AND itm.category='$category' AND itn.store='$store'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$itn_item = $row[0];
			$itn_id = $row[1];
			$itn_w_price = $row[2];
			$itn_r_price = $row[3];
			$itn_c_price = $row[4];
			$itn_qty = $row[5];
			$itn_shipment_no = $row[6];
			mysqli_query($conn, "INSERT INTO `backup_cat_qty2` (`job_id`,`category`,`store`,`itn_id`,`itn_item`,`itn_w_price`,`itn_r_price`,`itn_c_price`,`itn_qty`,`shipment_no`) VALUES ('$next_job_id','$cat_name','$st_name','$itn_id','$itn_item','$itn_w_price','$itn_r_price','$itn_c_price','$itn_qty','$itn_shipment_no')");
			mysqli_query($conn, "DELETE FROM inventory_new WHERE id='$itn_id'");
		}
		$result = mysqli_query($conn, "UPDATE inventory_qty itq, inventory_items itm SET itq.qty=0 WHERE itm.id=itq.item AND itm.category='$category' AND itq.location='$store'");

		if ($result) {
			$message = 'Item Qty of ' . $cat_name . ' in ' . $st_name . ' set to 0 Successfully!';
			return true;
		} else {
			$message = 'Category could not be Cleared!';
			return false;
		}
	} else {
		$message = 'Invalid Password!';
		return false;
	}
}

function getJobId()
{
	global $last_job_id;
	include('../config.php');
	$result = mysqli_query($conn, "SELECT MAX(job_id) FROM backup_cat_qty1");
	$row = mysqli_fetch_row($result);
	$last_job_id = $row[0];
}

function restoreClearCat()
{
	global $message;
	$last_job_id = $_REQUEST['last_job_id'];
	if (md5($_REQUEST['password']) == 'c3f1396a463205b149f7559b01fea607') {
		include('../config.php');

		$result = mysqli_query($conn, "SELECT DISTINCT category,store FROM backup_cat_qty1 WHERE job_id='$last_job_id'");
		$row = mysqli_fetch_row($result);
		$cat_name = $row[0];
		$st_name = $row[1];

		$query = "SELECT itq_id,qty,drawer_no FROM backup_cat_qty1 WHERE job_id='$last_job_id'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$itq_id = $row[0];
			$itq_qty = $row[1];
			$itq_drawer = $row[2];
			mysqli_query($conn, "UPDATE inventory_qty SET `qty`='$itq_qty',`drawer_no`='$itq_drawer' WHERE id='$itq_id'");
		}
		$query = "SELECT bc2.itn_id,bc2.itn_item,bc2.itn_w_price,bc2.itn_r_price,bc2.itn_c_price,bc2.itn_qty,st.id,bc2.shipment_no FROM backup_cat_qty2 bc2, stores st WHERE bc2.store=st.name AND bc2.job_id='$last_job_id'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$itn_id = $row[0];
			$itn_item = $row[1];
			$itn_w_price = $row[2];
			$itn_r_price = $row[3];
			$itn_c_price = $row[4];
			$itn_qty = $row[5];
			$itn_store = $row[6];
			$itn_shipment_no = $row[7];
			print $itn_id . '<br>';
			if ($itn_id != '') {
				$query2 = "INSERT INTO `inventory_new` (`item`,`w_price`,`r_price`,`c_price`,`qty`,`store`,`shipment_no`) VALUES ('$itn_item','$itn_w_price','$itn_r_price','$itn_c_price','$itn_qty','$itn_store','$itn_shipment_no')";
				$result2 = mysqli_query($conn, $query2);
				print $query2 . '<br>';
			}
		}

		if ($result) {
			$message = 'Item Qty of ' . $cat_name . ' in ' . $st_name . ' was Restored!';
			return true;
		} else {
			$message = 'Job could not be Restored!';
			return false;
		}
	} else {
		$message = 'Invalid Password!';
		return false;
	}
}

function invSetOrder()
{
	global $message;
	$result2 = true;
	include('../config.php');
	$query = "SELECT id,drawer_no FROM inventory_qty";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$itq_id = $row[0];
		$itq_drawer = $row[1];
		if (strlen($itq_drawer) > 3) {
			$itq_drawer2 = substr($itq_drawer, 0, strpos($itq_drawer, ','));
		} else
			$itq_drawer2 = $itq_drawer;
		$query2 = "UPDATE inventory_qty SET `drawer_no_odr`='$itq_drawer2' WHERE id='$itq_id'";
		$result1 = mysqli_query($conn, $query2);
		if (!$result1)
			$result2 = false;
	}

	if ($result2) {
		$message = 'Inventory Drawer Order was created Successfully';
		return true;
	} else {
		$message = 'Order Could be Arranged!';
		return false;
	}
}
//---------------------------Debug---------------------------------------------------------//
function getDebug()
{
	global $debug_id, $debug_itq, $debug_store, $debug_item, $debug_action, $debug_actionresult, $debug_start_qty, $debug_action_qty, $debug_end_qty, $debug_itq_qty;
	include('../config.php');
	$query = "SELECT dg.id,dg.itq_id,st.name,itm.description,dg.`action`,dg.action_result,dg.start_qty,dg.action_qty,dg.end_qty,itq.qty FROM debug dg, inventory_items itm, inventory_qty itq, stores st WHERE dg.item=itm.id AND dg.itq_id=itq.id AND itq.location=st.id AND dg.ack=0 AND dg.end_qty!=(dg.start_qty + dg.action_qty)";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$debug_id[] = $row[0];
		$debug_itq[] = $row[1];
		$debug_store[] = $row[2];
		$debug_item[] = $row[3];
		$debug_action[] = $row[4];
		$debug_actionresult[] = $row[5];
		$debug_start_qty[] = $row[6];
		$debug_action_qty[] = $row[7];
		$debug_end_qty[] = $row[8];
		$debug_itq_qty[] = '';
	}
	$query = "SELECT dg.id,dg.itq_id,st.name,itm.description,dg.`action`,dg.action_result,dg.start_qty,dg.action_qty,dg.end_qty,itq.qty,itq.item,itq.location FROM debug dg, inventory_items itm, inventory_qty itq, stores st WHERE dg.item=itm.id AND dg.itq_id=itq.id AND itq.location=st.id AND dg.id IN ( SELECT MAX(id) FROM debug GROUP BY itq_id )";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$query1 = "SELECT SUM(qty) as `total` FROM inventory_new WHERE item='$row[10]' AND store='$row[11]'";
		$result1 = mysqli_query($conn, $query1);
		$row1 = mysqli_fetch_assoc($result1);
		if ($row[8] != $row1['total'] + $row[9]) {
			$debug_id[] = $row[0];
			$debug_itq[] = $row[1];
			$debug_store[] = $row[2];
			$debug_item[] = $row[3];
			$debug_action[] = $row[4];
			$debug_actionresult[] = $row[5];
			$debug_start_qty[] = $row[6];
			$debug_action_qty[] = $row[7];
			$debug_end_qty[] = $row[8];
			$debug_itq_qty[] = $row1['total'] + $row[9];
		}
	}
}

function debugAck()
{
	global $message;
	$id = $_GET['id'];
	include('../config.php');
	$query = "UPDATE debug SET `ack`='1' WHERE id='$id'";
	$result = mysqli_query($conn, $query);

	if ($result) {
		$message = 'Debug was Acknowledged';
		return true;
	} else {
		$message = 'Debug Could be Acknowledged!';
		return false;
	}
}

//---------------------------Debug---------------------------------------------------------//
/*
function getInvMismatch(){
	global $itm_id,$itu_item,$store_id,$store_name,$itu_qty,$itu_itq,$itq_qty_arr,$issue;
	include('../config.php');
	$query1="SELECT itq_id,count(id) FROM inventory_unic_item WHERE `status`=0 GROUP BY itq_id";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$itq_qty=0;
		$itu_itq_tmp=$row1[0];
		$itu_qty_tmp=$row1[1];
		$itu_itq[]=$row1[0];
		$itu_qty[]=$row1[1];

		$result2 = mysqli_query($conn,"SELECT itm.id,itm.description,itq.location,st.name FROM inventory_items itm, inventory_qty itq, stores st WHERE itm.id=itq.item AND itq.location=st.id AND itq.id='$itu_itq_tmp'");
		$row2 = mysqli_fetch_assoc($result2);
		$itm_id_tmp=$row2['id'];
		$store_id_tmp=$row2['location'];
		$itm_id[]=$row2['id'];
		$itu_item[]=$row2['description'];
		$store_id[]=$row2['location'];
		$store_name[]=$row2['name'];

		$query2="SELECT qty FROM inventory_qty WHERE id='$itu_itq_tmp'";
		$result2 = mysqli_query($conn,$query2);
		$row2 = mysqli_fetch_assoc($result2);
		$itq_qty=$row2['qty'];

		$query2="SELECT SUM(qty) AS `qty` FROM inventory_new WHERE item='$itm_id_tmp' AND store='$store_id_tmp'";
		$result2 = mysqli_query($conn,$query2);
		$row2 = mysqli_fetch_assoc($result2);
		$itq_qty+=$row2['qty'];

		$itq_qty_arr[]=$itq_qty;

		if($itu_qty_tmp!=$itq_qty) $issue[]='Error'; else $issue[]='';
	//	print '<tr><td><a title="'.$itm_id.'">'.$itu_item.'</a></td><td><a title="'.$store_id.'">'.$store_name.'</a></td><td>'.$itu_qty.'</td><td><a title="ITQ ID = '.$itu_itq.'">'.$itq_qty.'</a></td><td>'.$issue.'</td></tr>';
	}
}
*/
function getInvMismatch()
{
	global $litq_itm_id, $litq_itm_desc, $litq_itq_id, $litq_itq_qty, $litq_st_id, $litq_st_name, $litq_itu_qty, $issue;
	$litu_itq_id = $litu_qty = $litq_itm_id = $litq_itm_desc = $litu_qty = $litq_itu_qty = array();
	include('../config.php');

	$query1 = "SELECT itq_id,count(id) FROM inventory_unic_item WHERE `status`=0 GROUP BY itq_id";
	$result1 = mysqli_query($conn, $query1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$litu_itq_id[] = $row1[0];
		$litu_qty[] = $row1[1];
	}


	$query1 = "SELECT itm.id,itm.description,itq.id,itq.qty,itq.location,st.name FROM inventory_items itm, inventory_qty itq, stores st WHERE itm.id=itq.item AND itq.location=st.id AND itm.unic=1 AND itm.`status`=1";
	$result1 = mysqli_query($conn, $query1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$itm_id_tmp = $row1[0];
		$litq_itm_id[] = $row1[0];
		$litq_itm_desc[] = $row1[1];
		$itq_id = $row1[2];
		$litq_itq_id[] = $row1[2];
		$itq_qty = $row1[3];
		$st_id_tmp = $row1[4];
		$litq_st_id[] = $row1[4];
		$litq_st_name[] = $row1[5];

		$itn_qty = 0;
		$query2 = "SELECT id,SUM(qty) as `qty` FROM inventory_new WHERE item='$itm_id_tmp' AND store='$st_id_tmp'";
		$result2 = mysqli_query($conn, $query2);
		$row2 = mysqli_fetch_assoc($result2);
		$itn_qty = $row2['qty'];

		$total_itq_qty = $itq_qty + $itn_qty;
		$litq_itq_qty[] = $total_itq_qty;

		$arrsearch_itu_id = array_search($itq_id, $litu_itq_id);
		if ($arrsearch_itu_id === false) {
			$itu_qty = 0;
			$litq_itu_qty[] = $itu_qty;
		} else {
			$itu_qty = $litu_qty[$arrsearch_itu_id];
			$litq_itu_qty[] = $itu_qty;
		}

		if ($itu_qty == $total_itq_qty) {
			$issue[] = '';
		} else {
			$issue[] = 'Error';
		}
	}
}

function cashBackInvCheck($sn)
{
	include('../config.php');
	$i = 1;
	$val1 = $val2 = $cust1 = $error = 0;
	$query = "SELECT bm.`invoice_+total`,bm.`invoice_-total`,bm.`cust` FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bi.`comment` LIKE '%$sn%' GROUP BY bm.invoice_no";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$bm_total1 = $row[0];
		$bm_total2 = $row[1];
		$bm_cust = $row[2];
		if (($i % 2) != 0) {
			$val1 = $bm_total1;
			$val2 = $bm_total2;
			$cust1 = $bm_cust;
		} else {
			if ((($bm_total1 + $val2) != 0) || (($bm_total2 + $val1) != 0) || ($bm_cust != $cust1))
				$error++;
		}
		$i++;
	}
	//	if($error>0)print $sn.'<br />';
	if ($error == 0)
		return false;
	else
		return true;
}

function validateError()
{
	$itq_id = $_GET['itq_id'];
	$history_date = '2017-10-12';
	$error = 0;
	$error_code = '';
	include('../config.php');
	$result = mysqli_query($conn, "SELECT item,location FROM inventory_qty WHERE id='$itq_id'");
	$row = mysqli_fetch_assoc($result);
	$item_id = $row['item'];
	$store_id = $row['location'];

	//---------------------Test Case 1--------------------------------------------//
	$query = "SELECT sn FROM inventory_unic_item WHERE itq_id='$itq_id' AND `status`=0";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$itu_sn = $row[0];

		$query1 = "SELECT count(bi.id) as `count` FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bi.`comment` LIKE '%$itu_sn%'";
		$result1 = mysqli_query($conn, $query1);
		$row1 = mysqli_fetch_assoc($result1);
		$bill_found = $row1['count'];
		if ($bill_found > 0) {
			if (cashBackInvCheck($itu_sn)) {
				$error++;
				print $itu_sn . '<br />';
			}
		}
	}
	if ($error > 0)
		$error_code = '1';
	//---------------------Test Case 2--------------------------------------------//
	$query = "SELECT count(bi.id) as `count` FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bi.`comment`='' AND bi.item='$item_id' AND date(bm.billed_timestamp)>'$history_date'";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);
	$empty_found = $row['count'];
	if ($empty_found > 0) {
		$error++;
		$error_code = $error_code . ',2';
	}
	if ($error > 0)
		print 'Case : ' . $error_code;
	else
		print 'Good';
}

function getOneMismatch()
{
	global $itq_id, $itu_item, $store_id, $store_name, $itu_qty, $itu_itq, $itq_qty, $issue;
	$itq_id = $_GET['itq_id'];
	$itq_qty = 0;
	include('../config.php');

	$result = mysqli_query($conn, "SELECT itm.id,itm.description,itq.location,st.name,itq.qty FROM inventory_items itm, inventory_qty itq, stores st WHERE itm.id=itq.item AND itq.location=st.id AND itq.id='$itq_id'");
	$row = mysqli_fetch_assoc($result);
	$itm_id_tmp = $row['id'];
	$itu_item = $row['description'];
	$store_id = $row['location'];
	$store_name = $row['name'];
	$itq_qty = $row['qty'];

	$query = "SELECT SUM(qty) AS `qty` FROM inventory_new WHERE item='$itm_id_tmp' AND store='$store_id'";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);
	$itq_qty += $row['qty'];

	$query = "SELECT count(id) as `count` FROM inventory_unic_item WHERE itq_id='$itq_id' AND `status`=0";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);
	$itu_qty = $row['count'];

	if ($itu_qty != $itq_qty)
		$issue = 'Error';
	else
		$issue = '';
}

function updateItqQty($increment)
{
	global $message;
	$itq_id = $_GET['itq_id'];
	include('../config.php');
	$query = "UPDATE inventory_qty SET `qty`=qty+$increment WHERE id='$itq_id'";
	$result = mysqli_query($conn, $query);

	if ($result) {
		$message = 'ITQ QTY was Updated Succesfully';
		return true;
	} else {
		$message = 'ITQ QTY Could not be Updated!';
		return false;
	}
}

//---------------------------------------subscription----------------------------------------------------------------//
function getSubscription()
{
	global $subscription_end;
	include('../config.php');
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='subscription_start'");
	$row = mysqli_fetch_assoc($result);
	$subscription_start = $row['value'];
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='subscription_duration'");
	$row = mysqli_fetch_assoc($result);
	$subscription_duration = $row['value'];
	$timestamp_start = strtotime($subscription_start);
	$timestamp_nest = $timestamp_start + $subscription_duration * 24 * 60 * 60;
	$timestamp_gap = $timestamp_nest - time();
	$subscription_end = round($timestamp_gap / 60 / 60 / 24);
}


function incrementSub($increment)
{
	global $message;
	$itq_id = $_GET['itq_id'];
	include('../config.php');
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='subscription_duration'");
	$row = mysqli_fetch_assoc($result);
	$sub_duration = $row['value'];
	$new_sub_duration = $sub_duration + $increment;
	$query = "UPDATE settings SET `value`='$new_sub_duration' WHERE setting='subscription_duration'";
	$result1 = mysqli_query($conn, $query);
	if ($result1) {
		$message = 'Subscription was Updated Succesfully';
		return true;
	} else {
		$message = 'Subscription Could not be Updated!';
		return false;
	}
}

//--------------------------------------stores added by nirmal 23_01_2024--------------------------------------//
function getStores()
{
	global $store_id, $store_name, $store_theme_color, $store_theme_color_m1, $store_theme_color_m2, $store_workflow, $store_billing_template, $store_shop_name, $store_shop_name_sms, $store_sms_outstanding, $store_address, $store_tel, $store_retail, $store_sub_system, $store_district, $store_on_place_replace, $store_logo, $store_status, $store_email;
	include('../config.php');

	$query = "SELECT id, name, theme_color, theme_color_m1, theme_color_m2, workflow, billing_template, shop_name, shop_name_sms, sms_outstanding, address, tel, retail, sub_system, district, on_place_replace, logo, status, email FROM stores";
	$result = mysqli_query($conn, $query);

	while ($row = mysqli_fetch_array($result)) {
		$store_id[] = $row['id'];
		$store_name[] = $row['name'];
		$store_theme_color[] = $row['theme_color'];
		$store_theme_color_m1[] = $row['theme_color_m1'];
		$store_theme_color_m2[] = $row['theme_color_m2'];
		$store_workflow[] = $row['workflow'];
		$store_billing_template[] = $row['billing_template'];
		$store_shop_name[] = $row['shop_name'];
		$store_shop_name_sms[] = $row['shop_name_sms'];
		$store_sms_outstanding[] = $row['sms_outstanding'];
		$store_address[] = $row['address'];
		$store_tel[] = $row['tel'];
		$store_retail[] = $row['retail'];
		$store_sub_system[] = $row['sub_system'];
		$store_district[] = $row['district'];
		$store_on_place_replace[] = $row['on_place_replace'];
		$store_logo[] = $row['logo'];
		$store_status[] = $row['status'];
		$store_email[] = $row['email'];
	}
	mysqli_close($conn);
}

function getDistricts()
{
	global $district_id, $district_name;
	include('../config.php');

	$query = "SELECT id, name FROM district";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$district_id[] = $row['id'];
		$district_name[] = $row['name'];
	}
	mysqli_close($conn);
}

function updateStoreStatus()
{
	include('../config.php');
	global $message;
	$store_id = $_GET['store_id'];
	$case = $_GET['case'];
	if ($case == 0) {
		$status = 0;
	} else {
		$status = 1;
	}
	$query = "UPDATE stores SET `status`='$status' WHERE `id`='$store_id'";
	$result1 = mysqli_query($conn, $query);
	if ($result1) {
		$message = 'Success: Store was deactivated succesfully';
		return true;
	} else {
		$message = 'Error: Store deactivation error!';
		return false;
	}
}

function getOneStore()
{
	global $message, $store_id, $store_name, $store_theme_color, $store_theme_color_m1, $store_theme_color_m2, $store_workflow, $store_billing_template, $store_shop_name, $store_shop_name_sms, $store_sms_outstanding, $store_address, $store_tel, $store_retail, $store_sub_system, $store_district, $store_on_place_replace, $store_logo, $store_status, $store_email;
	$store_id = $_GET['store_id'];
	include('../config.php');

	$query = "SELECT id, name, theme_color, theme_color_m1, theme_color_m2, workflow, billing_template, shop_name, shop_name_sms, sms_outstanding, address, tel, retail, sub_system, district, on_place_replace, logo, status, email FROM stores WHERE `id`='$store_id'";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$store_id = $row['id']; //
		$store_name = $row['name']; //
		$store_theme_color = $row['theme_color']; //
		$store_theme_color_m1 = $row['theme_color_m1']; //
		$store_theme_color_m2 = $row['theme_color_m2']; //
		$store_workflow = $row['workflow']; //
		$store_billing_template = $row['billing_template']; //
		$store_shop_name = $row['shop_name']; //
		$store_shop_name_sms = $row['shop_name_sms']; //
		$store_sms_outstanding = $row['sms_outstanding']; //
		$store_address = $row['address']; //
		$store_tel = $row['tel']; //
		$store_retail = $row['retail']; //
		$store_sub_system = $row['sub_system']; //
		$store_district = $row['district']; //
		$store_on_place_replace = $row['on_place_replace']; //
		$store_logo = $row['logo']; //
		$store_status = $row['status']; //
		$store_email = $row['email']; //
	}
}

function addStore()
{
	global $message;
	$store_id = isset($_POST['store_id']) ? $_POST['store_id'] : '';
	$store_name = isset($_POST['store_name']) ? $_POST['store_name'] : '';
	$store_shop_name = isset($_POST['store_shop_name']) ? $_POST['store_shop_name'] : '';
	$store_shop_name_sms = isset($_POST['store_shop_name_sms']) ? $_POST['store_shop_name_sms'] : '';
	$store_sms_outstanding = isset($_POST['store_sms_outstanding']) ? $_POST['store_sms_outstanding'] : '';
	$store_address = isset($_POST['store_address']) ? $_POST['store_address'] : '';
	$store_district = isset($_POST['store_district']) ? $_POST['store_district'] : '';
	$store_email = isset($_POST['store_email']) ? $_POST['store_email'] : '';
	$store_tel = isset($_POST['store_tel']) ? $_POST['store_tel'] : '';
	$store_billing_template = isset($_POST['store_billing_template']) ? $_POST['store_billing_template'] : '';
	$store_logo = isset($_POST['store_logo']) ? $_POST['store_logo'] : '';
	$store_sub_system = isset($_POST['store_sub_system']) ? $_POST['store_sub_system'] : '';
	$store_retail = isset($_POST['store_retail']) ? $_POST['store_retail'] : '';
	$store_theme_color = isset($_POST['store_theme_color']) ? $_POST['store_theme_color'] : '';
	$store_theme_color_m1 = isset($_POST['store_theme_color_m1']) ? $_POST['store_theme_color_m1'] : '';
	$store_theme_color_m2 = isset($_POST['store_theme_color_m2']) ? $_POST['store_theme_color_m2'] : '';
	$store_workflow = isset($_POST['store_workflow']) ? $_POST['store_workflow'] : '';
	$store_on_place_replace = isset($_POST['store_on_place_replace']) ? $_POST['store_on_place_replace'] : '';
	$store_status = isset($_POST['store_status']) ? $_POST['store_status'] : '';
	include('../config.php');
	$out = true;

	if ($store_name == '') {
		$out = false;
		$message = 'Error: Store name cannot be empty!';
	}
	if ($store_billing_template == '') {
		$out = false;
		$message = 'Error: Store billing template cannot be empty!';
	}
	if ($store_shop_name == '') {
		$out = false;
		$message = 'Error: Store shop name cannot be empty!';
	}
	if ($store_shop_name_sms == '') {
		$out = false;
		$message = 'Error: Store sms shop name cannot be empty!';
	}
	if ($store_district == '') {
		$out = false;
		$message = 'Error: Store district cannot be empty!';
	}
	if ($store_address == '') {
		$out = false;
		$message = 'Error: Store address cannot be empty!';
	}
	if ($store_tel == '') {
		$out = false;
		$message = 'Error: Store tel cannot be empty!';
	}
	if ($store_logo == '') {
		$out = false;
		$message = 'Error: Store logo cannot be empty!';
	}
	if ($store_on_place_replace == '') {
		$out = false;
		$message = 'Error: Store on place replace cannot be empty!';
	}
	if ($store_email == '') {
		$out = false;
		$message = 'Error: Store email cannot be empty!';
	}
	if ($store_sms_outstanding == '') {
		$out = false;
		$message = 'Error: Store sms outstanding cannot be empty!';
	}
	if ($store_status == '') {
		$out = false;
		$message = 'Error: Store status cannot be empty!';
	}
	if ($store_sub_system == '') {
		$out = false;
		$message = 'Error: Store sub system cannot be empty!';
	}
	if ($out) {
		$query = "INSERT INTO stores (name, theme_color, theme_color_m1, theme_color_m2, workflow, billing_template, shop_name, shop_name_sms, sms_outstanding, address, tel, retail, sub_system, district, on_place_replace, logo, status, email) VALUES ('$store_name', '$store_theme_color', '$store_theme_color_m1', '$store_theme_color_m2', '$store_workflow','$store_billing_template', '$store_shop_name', '$store_shop_name_sms', '$store_sms_outstanding', '$store_address', '$store_tel', '$store_retail', '$store_sub_system', '$store_district', '$store_on_place_replace', '$store_logo', '$store_status', '$store_email')";
		$result = mysqli_query($conn, $query);
		if ($result) {
			$w_price = 1;
			$r_price = 1;
			$c_price = 1;
			$qty = 0;
			$store_id = mysqli_insert_id($conn);
			$query1 = "SELECT `id` FROM inventory_items WHERE sub_system='$store_sub_system' AND unic='0' AND pr_sr='1'";
			$result1 = mysqli_query($conn, $query1);
			while ($row1 = mysqli_fetch_array($result1)) {
				$item_id = $row1[0];
				$query2 = "INSERT INTO `inventory_qty` (`item`,`location`,`w_price`,`r_price`,`c_price`,`qty`) VALUES ('$item_id','$store_id','$w_price','$r_price','$c_price','$qty')";
				$result2 = mysqli_query($conn, $query2);
			}
			$message = "Success: Store " . $store_name . " added successfully";
		} else {
			$message = "Error: " . mysqli_error($conn);
		}
	}
	return $out;
}

function updateStore()
{
	global $message, $store_id;
	$out = true;
	// Initialize variables with default values or empty strings
	$store_id = isset($_POST['store_id']) ? $_POST['store_id'] : '';
	$store_name = isset($_POST['store_name']) ? $_POST['store_name'] : '';
	$store_shop_name = isset($_POST['store_shop_name']) ? $_POST['store_shop_name'] : '';
	$store_shop_name_sms = isset($_POST['store_shop_name_sms']) ? $_POST['store_shop_name_sms'] : '';
	$store_sms_outstanding = isset($_POST['store_sms_outstanding']) ? $_POST['store_sms_outstanding'] : '';
	$store_address = isset($_POST['store_address']) ? $_POST['store_address'] : '';
	$store_district = isset($_POST['store_district']) ? $_POST['store_district'] : '';
	$store_email = isset($_POST['store_email']) ? $_POST['store_email'] : '';
	$store_tel = isset($_POST['store_tel']) ? $_POST['store_tel'] : '';
	$store_billing_template = isset($_POST['store_billing_template']) ? $_POST['store_billing_template'] : '';
	$store_logo = isset($_POST['store_logo']) ? $_POST['store_logo'] : '';
	$store_sub_system = isset($_POST['store_sub_system']) ? $_POST['store_sub_system'] : '';
	$store_retail = isset($_POST['store_retail']) ? $_POST['store_retail'] : '';
	$store_theme_color = isset($_POST['store_theme_color']) ? $_POST['store_theme_color'] : '';
	$store_theme_color_m1 = isset($_POST['store_theme_color_m1']) ? $_POST['store_theme_color_m1'] : '';
	$store_theme_color_m2 = isset($_POST['store_theme_color_m2']) ? $_POST['store_theme_color_m2'] : '';
	$store_workflow = isset($_POST['store_workflow']) ? $_POST['store_workflow'] : '';
	$store_on_place_replace = isset($_POST['store_on_place_replace']) ? $_POST['store_on_place_replace'] : '';
	$store_status = isset($_POST['store_status']) ? $_POST['store_status'] : '';

	if ($store_id == '') {
		$out = false;
		$message = 'Error: Store cannot be empty!';
	}
	if ($store_name == '') {
		$out = false;
		$message = 'Error: Store name cannot be empty!';
	}
	if ($store_billing_template == '') {
		$out = false;
		$message = 'Error: Store billing template cannot be empty!';
	}
	if ($store_shop_name == '') {
		$out = false;
		$message = 'Error: Store shop name cannot be empty!';
	}
	if ($store_shop_name_sms == '') {
		$out = false;
		$message = 'Error: Store sms shop name cannot be empty!';
	}
	if ($store_district == '') {
		$out = false;
		$message = 'Error: Store district cannot be empty!';
	}
	if ($store_address == '') {
		$out = false;
		$message = 'Error: Store address cannot be empty!';
	}
	if ($store_tel == '') {
		$out = false;
		$message = 'Error: Store tel cannot be empty!';
	}
	if ($store_logo == '') {
		$out = false;
		$message = 'Error: Store logo cannot be empty!';
	}
	if ($store_on_place_replace == '') {
		$out = false;
		$message = 'Error: Store on place replace cannot be empty!';
	}
	if ($store_email == '') {
		$out = false;
		$message = 'Error: Store email cannot be empty!';
	}
	if ($store_sms_outstanding == '') {
		$out = false;
		$message = 'Error: Store sms outstanding cannot be empty!';
	}
	if ($store_status == '') {
		$out = false;
		$message = 'Error: Store status cannot be empty!';
	}
	if ($store_sub_system == '') {
		$out = false;
		$message = 'Error: Store sub system cannot be empty!';
	}
	include('../config.php');
	if ($out) {
		$query = "UPDATE stores SET name='$store_name', theme_color='$store_theme_color', theme_color_m1='$store_theme_color_m1', theme_color_m2='$store_theme_color_m2', workflow='$store_workflow', billing_template='$store_billing_template', shop_name='$store_shop_name', shop_name_sms='$store_shop_name_sms', sms_outstanding='$store_sms_outstanding', address='$store_address', tel='$store_tel', retail='$store_retail', sub_system='$store_sub_system', district='$store_district', on_place_replace='$store_on_place_replace', logo='$store_logo', status='$store_status', email='$store_email' WHERE id='$store_id'";

		$result = mysqli_query($conn, $query);
		if ($result) {
			$message = 'Success : Store updated succesfully';
			$out = true;
		} else {
			$message = 'Error : Store could not be updated!';
			$out = false;
		}
	}
	return $out;
}

function getSubSystems()
{
	global $sb_id, $sb_name;
	include('../config.php');

	$query = "SELECT `id`,`name` FROM sub_system WHERE `status`='1'";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$sb_id[] = $row[0];
		$sb_name[] = $row[1];
	}
}

//--------------------------------------shipment delete added by nirmal 26_01_2024--------------------------------------//
function getLastShipment()
{
	global $shipment_id, $shipment_date, $shipment_added_by, $shipment_supplier, $shipment_sub_system, $shipment_invoice_no, $shipment_invoice_due_date, $shipment_invoice_date, $shipment_type, $shipment_location;
	include('../config.php');
	$query = 'SELECT sm.id, sm.shipment_date, u.username, s.name AS supplier_name, ss.name AS sub_system_name, sm.invoice_no, st.name AS store_name, sm.invoice_due, sm.invoice_date, sm.unic FROM shipment_main sm, userprofile u, supplier s, sub_system ss, stores st, inventory_shipment `is` WHERE u.id = sm.added_by AND s.id = sm.supplier AND ss.id = sm.sub_system AND `is`.shipment_no = sm.id AND st.id = `is`.location AND sm.status = 0 ORDER BY sm.id DESC LIMIT 1';
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$shipment_id = $row[0];
		$shipment_date = $row[1];
		$shipment_added_by = $row[2];
		$shipment_supplier = $row[3];
		$shipment_sub_system = $row[4];
		$shipment_invoice_no = $row[5];
		$shipment_location = $row[6];
		$shipment_invoice_due_date = $row[7];
		$shipment_invoice_date = $row[8];
		if ($row[9] == 1)
			$shipment_type = 'Unic Items';
		else
			$shipment_type = 'QTY Items';
	}
}

//--------------------------------------Payment MGMT added by nirmal 15_03_2024--------------------------------------//
function getBanks()
{
	global $bank_id, $bank_code, $bank_name, $ac_bank_id, $ac_bank_name;
	include('../config.php');
	$query = "SELECT id,bank_code,name FROM bank WHERE `status`='1' ORDER BY bank_code";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$bank_id[] = $row[0];
		$bank_code[] = $row[1];
		$bank_name[] = $row[2];
	}
	$query = "SELECT id,name FROM accounts WHERE bank_ac=1 AND `status`=1";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$ac_bank_id[] = $row[0];
		$ac_bank_name[] = $row[1];
	}
}

function getSalesman()
{
	global $sm_id, $sm_name;
	include('../config.php');
	$query = "SELECT id,username FROM userprofile WHERE `status`=0 ORDER BY username";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$sm_id[] = $row[0];
		$sm_name[] = $row[1];
	}
}

// added by nirmal 19_03_2024
function searchPayment()
{
	global $payment_found, $is_cheque, $payments;
	$payment_found = false;
	$is_cheque = false;
	$cheque_number = $bank_code = $branch_code = "";
	include('../config.php');
	if (isset($_GET['payment_no'])) {
		$id = $_GET['payment_no'];
		$type = $_GET['type'];
		if ($type == 'cheque') {
			$query = "SELECT py.id, py.amount, py.invoice_no, py.chque_no, DATE(py.chque_date), py.chque_bank, py.chque_branch,
			cu.name, py.salesman, py.store, py.status, py.chque_deposit_bank, py.chque_deposit_date, py.chque_deposit_by
				FROM payment py, cust cu, bank bk WHERE bk.id = py.chque_bank AND cu.id = py.cust AND py.id='$id'";
			$result = mysqli_query($conn2, $query);
			while ($row = mysqli_fetch_row($result)) {
				$payment_found = true;
				$is_cheque = true;
				$payment = array(
					'id' => $row[0],
					'amount' => $row[1],
					'invoice_no' => $row[2],
					'cheque_no' => $row[3],
					'cheque_date' => $row[4],
					'cheque_bank' => $row[5],
					'cheque_branch' => $row[6],
					'customer' => $row[7],
					'salesman' => $row[8],
					'store' => $row[9],
					'status' => ($row[10] == 1) ? 'Deleted' : 'Active',
					'chque_deposit_bank' => $row[11],
					'chque_deposit_date' => $row[12],
					'chque_deposit_by' => $row[13]
				);
				$payments[] = $payment;
			}
		} else {
			$payment_found = true;
			$is_cheque = false;
			$query = "SELECT py.id, py.amount, py.invoice_no, cu.name, py.bank_trans, py.salesman, py.store, py.status, DATE(py.payment_date),
			py.payment_type, py.bank_trans FROM payment py, cust cu WHERE cu.id = py.cust AND py.id='$id'";
			$result = mysqli_query($conn2, $query);
			while ($row = mysqli_fetch_row($result)) {
				$payment = array(
					'id' => $row[0],
					'amount' => $row[1],
					'invoice_no' => $row[2],
					'customer' => $row[3],
					'bank' => $row[4],
					'salesman' => $row[5],
					'store' => $row[6],
					'status' => ($row[7] == 1) ? 'Deleted' : 'Active',
					'date' => $row[8],
					'payment_method' => $row[9],
					'bank_tr' => $row[10],
				);
				$payments[] = $payment;
			}
		}
	}
}

// added by nirmal 19_03_2024
function updatePayment()
{
	global $message, $payment_id, $type;
	$out = true;
	$qb_msg = '';
	$time_now = timeNow();
	include('../config.php');

	// Start a MySQL transaction
	mysqli_begin_transaction($conn);

	try {
		$type = $_REQUEST['type'];
		$payment_id = $_POST['payment_id'];
		$invoice_no = $_POST['invoice_no'];
		$salesman = $_POST['salesman'];
		$store = $_POST['store'];

		// Retrieve the original payment record including qb_status if available
		if (isQuickBooksActive(1)) {
			$origQuery = "SELECT payment_type, invoice_no, amount, qb_id, qb_status FROM payment WHERE id = '$payment_id'";
		} else {
			$origQuery = "SELECT payment_type, invoice_no, amount FROM payment WHERE id = '$payment_id'";
		}
		$origResult = mysqli_query($conn, $origQuery);
		if (!$origResult || mysqli_num_rows($origResult) == 0) {
			throw new Exception("Original payment record not found.");
		}
		$origRow = mysqli_fetch_assoc($origResult);
		$original_amount = $origRow['amount'];
		$original_payment_type = $origRow['payment_type'];
		$original_invoice_no = $origRow['invoice_no'];

		// For QB – if active, retrieve QB ID and QB status and ensure QB ID exists.
		$original_qb_id = '';
		$original_qb_status = '';
		if (isQuickBooksActive(1)) {
			$original_qb_id = isset($origRow['qb_id']) ? $origRow['qb_id'] : '';
			$original_qb_status = isset($origRow['qb_status']) ? $origRow['qb_status'] : '';
			if (empty($original_qb_id)) {
				throw new Exception('QB is active, to update this payment has to be saved in QB first.');
			}
		}

		// Determine new payment amount and new payment type.
		if ($type != 'cheque') {
			$new_payment_amount = $_POST['payment_amount'];
			// For non-cheque, assume new payment type is passed via payment_method (e.g. 1=cash, 3=bank)
			$new_payment_type = $_POST['payment_method'];
		} else {
			// For cheque updates, retrieve cheque-specific fields.
			$cheque_no = $_POST['cheque_no'];
			$cheque_parts = explode('-', $cheque_no);
			$bank = $_POST['bank'];
			$branch_no = $_POST['branch_no'];
			$cheque_date = $_POST['cheque_date'];
			$cheque_amount = $_POST['cheque_amount'];
			$chque_deposit_date;
			if (isset($_POST['chque_deposit_date'])) {
				$chque_deposit_date = $_POST['chque_deposit_date'];
			}
			$chque_deposit_bank = $_POST['chque_deposit_bank'];
			$chque_deposit_by = $_POST['chque_deposit_by'];
			$new_payment_amount = $cheque_amount;
			$new_payment_type = 'cheque';
		}

		// 1. Check if the payment type has changed.
		// For cheque updates, we assume no type change.
		$paymentTypeChanged = false;
		if ($type != 'cheque' && $new_payment_type != $original_payment_type) {
			$paymentTypeChanged = true;
		}

		// 2. Update the local payment record.
		// Process cheque deposit date
		if ($chque_deposit_date == '') {
			$sqlChqueDepositDate = "NULL";
		} else {
			// Remember to escape the value if needed
			$sqlChqueDepositDate = "'$chque_deposit_date'";
		}

		// Process cheque deposit by
		if ($chque_deposit_by == '') {
			$sqlChqueDepositBy = "NULL";
		} else {
			// Remember to escape the value if needed
			$sqlChqueDepositBy = "'$chque_deposit_by'";
		}

		if ($chque_deposit_bank == '') {
			$sqlChqueDepositBank = "NULL";
		} else {
			$sqlChqueDepositBank = "'$chque_deposit_bank'";
		}

		if ($type == 'cheque') {
			$query = "UPDATE payment SET
							chque_no = '$cheque_no',
							chque_date = '$cheque_date',
							chque_branch = '$branch_no',
							chque_bank = '$bank',
							invoice_no = '$invoice_no',
							amount = '$cheque_amount',
							salesman = '$salesman',
							store = '$store',
							chque_deposit_date = $sqlChqueDepositDate,
							chque_deposit_bank = $sqlChqueDepositBank,
							chque_deposit_by = $sqlChqueDepositBy
						WHERE id = '$payment_id'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				throw new Exception("Cheque payment could not be updated: " . mysqli_error($conn));
			}
			$message = 'Success: Cheque payment updated successfully.';
		} else { // cash & bank
			$date = $_POST['date'];
			$bank_tr = isset($_POST['bank_tr']) ? $_POST['bank_tr'] : '';
			if ($original_payment_type == 3 && $new_payment_type == 1) { // bank to cash
				$query = "UPDATE payment SET
                        payment_date = '$date',
                        invoice_no = '$invoice_no',
                        amount = '$new_payment_amount',
                        salesman = '$salesman',
                        store = '$store',
                        payment_type = '$new_payment_type',
                        bank_trans = NULL
                      WHERE id = '$payment_id'";
			} else { // cash to bank
				$status_query = ", status = 0";
				if (!isQuickBooksActive(1)) {
					if (isSalesmanPaymentDepositActive()) {
						$status_query = ", status = 2";
					}
				}
				$query = "UPDATE payment SET
                        payment_date = '$date',
                        invoice_no = '$invoice_no',
                        amount = '$new_payment_amount',
                        salesman = '$salesman',
                        store = '$store',
                        payment_type = '$new_payment_type',
                        bank_trans = '$bank_tr'
												$status_query
                      WHERE id = '$payment_id'";
			}
			$result = mysqli_query($conn, $query);
			if (!$result) {
				throw new Exception("Payment could not be updated: " . mysqli_error($conn));
			}
			$message = 'Success: Payment updated successfully.';
		}


		// 3. If payment was recorded in QB (i.e. qb_status is not empty) and payment type changed,
		// then revert the previous QB entries.
		if (!empty($original_qb_status) && $paymentTypeChanged && isQuickBooksActive(1)) {
			// Retrieve customer details for this payment.
			$custQuery = "SELECT c.qb_cust_id, c.name FROM cust c JOIN payment p ON p.cust = c.id WHERE p.id = '$payment_id' LIMIT 1";
			$custResult = mysqli_query($conn, $custQuery);
			if (!$custResult || mysqli_num_rows($custResult) == 0) {
				throw new Exception("Failed to fetch customer details for QB reversal.");
			}
			$custRow = mysqli_fetch_assoc($custResult);
			$qb_cust_id = $custRow['qb_cust_id'];
			if (empty($qb_cust_id)) {
				throw new Exception("This customer is not registered in QB");
			}
			$custName = $custRow['name'];

			// CASE A: Originally Cash (payment_type == 1) changed to Bank (payment_type == 3)
			if ($original_payment_type == 1 && $new_payment_type == 3) {
				// First, insert deletion entry to reverse the original cash payment.
				// For deletion entry, use the original amount.
				$reversal_amount = $original_amount;
				$debitAccountName = "Accounts Receivable (A/R)";
				$creditAccountName = "Cash on Hand";
				$description = "[DELETE PAYMENT] - Payment No: $payment_id, Method: Cash ($creditAccountName)";
				if ($original_invoice_no != 0) {
					$description .= ", Invoice No: $original_invoice_no";
				}
				$description .= ", Customer: $custName";
				$debitEntityType = "Customer";
				$debitEntityID = $qb_cust_id;
				$creditEntityType = "";
				$creditEntityID = "";
				$journalEntryForDeletedPayment = buildJournalEntry($conn, abs($reversal_amount), $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
				if (isset($journalEntryForDeletedPayment['error'])) {
					throw new Exception($journalEntryForDeletedPayment['error']);
				} else {
					$batch_id = generateBatchID();
					$action_name = 'payment_delete_bill2';
					foreach ($journalEntryForDeletedPayment as $entry) {
						$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
						$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
						$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
						$amt = mysqli_real_escape_string($conn, $entry['amount']);
						$desc = mysqli_real_escape_string($conn, $entry['description']);
						$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
						$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

						$query = "INSERT INTO qb_queue (`batch_id`, `action`, `payment_id`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `created_at`, `entity_type`, `entity_id`)
								  VALUES ('$batch_id','$action_name', '$payment_id', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amt', '$desc', '$time_now',
								  " . ($entity_type !== null ? "'$entity_type'" : "NULL") . ", " .
							($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
						$result = mysqli_query($conn, $query);
						if (!$result) {
							throw new Exception("MySQL Error while inserting into qb_queue: " . mysqli_error($conn));
						}
					}
				}

				// Then, add a new journal entry for the new bank payment.
				if (!isSalesmanPaymentDepositActive()) {
					// For new bank payment, use the new payment amount.
					$insertion_amount = $new_payment_amount;
					$accountNameQuery = "SELECT `name` FROM accounts WHERE id='$bank_tr'";
					$accountNameResult = mysqli_query($conn, $accountNameQuery);
					if ($accountNameResult && mysqli_num_rows($accountNameResult) > 0) {
						$accountNameRow = mysqli_fetch_row($accountNameResult);
						// For insertion, re-create the appropriate entry for the new bank payment.
						$creditAccountName = 'Accounts Receivable (A/R)';
						$debitAccountName = $accountNameRow[0];
						$description = "[PAYMENT] - Payment No: $payment_id, Method: Bank Payment ($debitAccountName)";
						if (!empty($invoice_no)) {
							$description .= ", Invoice No: $invoice_no";
						}
						$description .= ", Customer: $custName";
						$result_array = processQBPayment($creditAccountName, $debitAccountName, $conn, $insertion_amount, $qb_cust_id, $description);
						if (is_array($result_array)) {
							$batch_id = generateBatchID();
							$action_name = 'payment_insert_bill2';
							foreach ($result_array as $entry) {
								$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
								$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
								$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
								$amt = mysqli_real_escape_string($conn, $entry['amount']);
								$desc = mysqli_real_escape_string($conn, $entry['description']);
								$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
								$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

								$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `payment_id`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `created_at`, `entity_type`, `entity_id`)
											VALUES ('$batch_id','$action_name', '$invoice_no', '$payment_id', '$posting_type', '$account_id', '$account_name', '$amt', '$desc','$time_now',
											" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
											" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
								$result = mysqli_query($conn, $query);
								if (!$result) {
									throw new Exception("MySQL Error while inserting into qb_queue: " . mysqli_error($conn));
								}
							}
						} else {
							$qb_msg = $result_array;
							throw new Exception($qb_msg);
						}
					}
				} else {
					$paymentDepositQuery = "SELECT COUNT(`payment_id`) as payment_count FROM payment_deposit WHERE id='$payment_id'";
					$paymentDepositResult = mysqli_query($conn, $paymentDepositQuery);
					if ($paymentDepositResult && mysqli_num_rows($paymentDepositResult) > 0) {

						$paymentDepositRow = mysqli_fetch_assoc($paymentDepositResult);
						if ($paymentDepositRow['payment_count'] > 0) {
							throw new Exception("This payment cannot be changed back to cash because it has already been sent as a payment deposit and is currently under review.");
						} else {
							$query2 = "UPDATE payment SET status = 2 WHERE id = '$payment_id'";
							$result2 = mysqli_query($conn, $query2);
							if (!$result2) {
								throw new Exception('Error: failed to update qb status in payment');
							}
						}
					}
				}
			} elseif ($original_payment_type == 3 && $new_payment_type == 1) { // originally bank changed to cash
				// Revert bank deletion: Flip accounts for deletion.
				if (!isSalesmanPaymentDepositActive()) {
					$accountNameQuery = "SELECT `name` FROM accounts WHERE id='$bank_tr'";
					$accountNameResult = mysqli_query($conn, $accountNameQuery);
					if ($accountNameResult && mysqli_num_rows($accountNameResult) > 0) {
						$accountNameRow = mysqli_fetch_row($accountNameResult);
						$debitAccountName = "Accounts Receivable (A/R)";
						$creditAccountName = $accountNameRow[0]; // original bank becomes credit
						$description = "[DELETE PAYMENT] - Payment No: $payment_id, Method: Bank ($creditAccountName)";
						if ($original_invoice_no != 0) {
							$description .= ", Invoice No: $original_invoice_no";
						}
						$description .= ", Customer: $custName";
						$debitEntityType = "Customer";
						$debitEntityID = $qb_cust_id;
						$creditEntityType = "";
						$creditEntityID = "";
						$journalEntryForDeletedPayment = buildJournalEntry($conn, abs($original_amount), $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
						if (isset($journalEntryForDeletedPayment['error'])) {
							throw new Exception($journalEntryForDeletedPayment['error']);
						} else {
							$batch_id = generateBatchID();
							$action_name = 'payment_delete_bill2';
							foreach ($journalEntryForDeletedPayment as $entry) {
								$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
								$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
								$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
								$amt = mysqli_real_escape_string($conn, $entry['amount']);
								$desc = mysqli_real_escape_string($conn, $entry['description']);
								$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
								$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

								$query = "INSERT INTO qb_queue (`batch_id`, `action`, `payment_id`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `created_at`, `entity_type`, `entity_id`)
														VALUES ('$batch_id','$action_name', '$payment_id', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amt', '$desc','$time_now',
														" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
														" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
								$result = mysqli_query($conn, $query);
								if (!$result) {
									throw new Exception("MySQL Error while inserting into qb_queue: " . mysqli_error($conn));
								}
							}
						}
					}
				} else {
					$query2 = "UPDATE payment SET status = 0 WHERE id = '$payment_id'";
					$result2 = mysqli_query($conn, $query2);
					if (!$result2) {
						throw new Exception('Error: failed to update qb status in payment');
					}
				}

				// Add a new Cash deposit entry as new payment; use new payment amount.
				$creditAccountName = 'Accounts Receivable (A/R)';
				$debitAccountName = "Cash on Hand";
				$description = "[PAYMENT] - Payment No: $payment_id, Method: Cash ($debitAccountName)";
				if ($original_invoice_no != 0) {
					$description .= ", Invoice No: $original_invoice_no";
				}
				$description .= ", Customer: $custName";
				$result_array = processQBPayment($creditAccountName, $debitAccountName, $conn, $new_payment_amount, $qb_cust_id, $description);
				if (is_array($result_array)) {
					$batch_id = generateBatchID();
					$action_name = 'payment_insert_bill2';
					foreach ($result_array as $entry) {
						$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
						$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
						$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
						$amount = mysqli_real_escape_string($conn, $entry['amount']);
						$desc = mysqli_real_escape_string($conn, $entry['description']);
						$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
						$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

						$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `payment_id`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `created_at`, `entity_type`, `entity_id`)
													VALUES ('$batch_id','$action_name', '$invoice_no', '$payment_id', '$posting_type', '$account_id', '$account_name', '$amount', '$desc','$time_now',
													" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
													" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
						$result = mysqli_query($conn, $query);
						if (!$result) {
							throw new Exception("MySQL Error while inserting into qb_queue: " . mysqli_error($conn));
						}
					}
				} else {
					$qb_msg = $result_array;
					throw new Exception($qb_msg);
				}
			}
		} // End revert section for payment type change

		// 4. If amount changed and QB is active, update the amount in QB using new_payment_amount.
		if (isQuickBooksActive(1) && !$paymentTypeChanged && floatval($new_payment_amount) != floatval($original_amount)) {
			try {
				$qb_response = QBUpdateJournalEntryAmount([
					'id' => $original_qb_id,
					'new_amount' => $new_payment_amount
				]);
				if ($qb_response['status'] !== 'success') {
					throw new Exception($qb_response['message']);
				} else {
					$qb_msg = " QuickBooks: " . $qb_response['message'];
				}
			} catch (Exception $e) {
				throw new Exception("QuickBooks error: " . $e->getMessage());
			}
		}

		mysqli_commit($conn);
	} catch (Exception $e) {
		mysqli_rollback($conn);
		$message = "Error: " . $e->getMessage();
		$out = false;
	}

	$message = $message . ' ' . $qb_msg;
	return $out;
}

//--------------------------------------Change Customer (system and qb) added by nirmal 03_04_2025--------------------------------------//
function getCustomers()
{
	global $customers;
	include('../config.php'); // Include the database configuration file

	$customers = [];

	if (((isset($_GET['invoice_id']) && $_GET['invoice_id'] != '')) || (isset($_GET['payment_id']) && $_GET['payment_id'] != '')) {
		// Query to fetch active customers (excluding disabled or pending)
		$query = "SELECT id, name FROM cust WHERE `status` NOT IN (0, 3) ORDER BY name";
		$result = mysqli_query($conn, $query);

		// Check if the query was successful
		if (!$result) {
			die("Database query failed: " . mysqli_error($conn));
		}

		// Fetch data and populate the array
		while ($row = mysqli_fetch_assoc($result)) {
			$customers[] = $row; // Add each row as an associative array
		}
	}
}

function getInvoice()
{
	global $invoice_id, $message, $invoice_cust_name, $invoice_salesman, $invoice_date, $inv_found;
	$inv_found = false;
	include('../config.php'); // Include the database configuration file

	// Check if invoice_id is provided
	if (isset($_GET['invoice_id']) && $_GET['invoice_id'] != '') {
		$invoice_id = $_GET['invoice_id'];
		$inv_found = true;

		// Query to fetch the customer name and invoice date associated with the invoice
		$query = "SELECT cu.name, bm.billed_timestamp, up.username FROM bill_main bm, cust cu, userprofile up WHERE bm.cust = cu.id AND up.id = bm.billed_by AND bm.invoice_no = '$invoice_id'";
		$result = mysqli_query($conn, $query);

		// Check if the query was successful
		if (!$result) {
			$message = "Database query failed: " . mysqli_error($conn);
			return false;
		}

		// Fetch the customer name
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$invoice_cust_name = $row['name'];
			$invoice_date = $row['billed_timestamp'];
			$invoice_salesman = $row['username'];
		} else {
			$message = "No customer found for the provided invoice ID.";
			return false;
		}
	} else {
		$message = "Error: Invoice ID is empty.";
		return false;
	}
}

function getPayment()
{
	global $payment_id, $message, $payment_cust_name, $payment_salesman, $payment_date, $pay_found;
	$pay_found = false;
	include('../config.php'); // Include the database configuration file

	// Check if payment_id is provided
	if (isset($_GET['payment_id']) && $_GET['payment_id'] != '') {
		$payment_id = $_GET['payment_id'];
		$pay_found = true;

		// Query to fetch the customer name and payment date associated with the payment
		$query = "SELECT cu.name, p.payment_date, up.username FROM payment p, cust cu, userprofile up
		WHERE p.cust = cu.id AND up.id = p.salesman AND p.id = '$payment_id'";
		$result = mysqli_query($conn, $query);

		// Check if the query was successful
		if (!$result) {
			$message = "Database query failed: " . mysqli_error($conn);
			return false;
		}

		// Fetch the customer name
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$payment_cust_name = $row['name'];
			$payment_date = $row['payment_date'];
			$payment_salesman = $row['username'];
		} else {
			$message = "No customer found for the provided payment ID.";
			return false;
		}
	} else {
		$message = "Error: Payment ID is empty.";
		return false;
	}
}

function changeInvoiceCust()
{
	global $invoice_id, $message;
	include('../config.php'); // Include the database configuration file

	// Start a transaction
	mysqli_begin_transaction($conn);

	try {
		// Validate input: check if invoice_id and new_cust_id are provided
		if (
			!isset($_POST['invoice_no']) || empty($_POST['invoice_no']) ||
			!isset($_POST['new_invoice_cust_id']) || empty($_POST['new_invoice_cust_id'])
		) {
			throw new Exception("Error: Invoice ID or customer ID is empty.");
		}

		// Note: using invoice_no from POST here.
		$invoice_id = $_POST['invoice_no'];
		$new_cust_id = $_POST['new_invoice_cust_id'];

		// Check if the new customer exists in the cust table
		if (isQuickBooksActive(1)) {
			$check_query = "SELECT id, name, qb_cust_id FROM cust WHERE id = '$new_cust_id'";
		} else {
			$check_query = "SELECT id, name FROM cust WHERE id = '$new_cust_id'";
		}
		$check_result = mysqli_query($conn, $check_query);
		if (!$check_result) {
			throw new Exception("Database query failed: " . mysqli_error($conn));
		}
		if (mysqli_num_rows($check_result) == 0) {
			throw new Exception("Error: The selected customer does not exist.");
		}
		$new_customer = mysqli_fetch_assoc($check_result);
		$new_customer_name = $new_customer['name'];

		// Update the customer in the bill_main table
		$update_query = "UPDATE bill_main SET cust = '$new_cust_id' WHERE invoice_no = '$invoice_id'";
		$update_result = mysqli_query($conn, $update_query);
		if (!$update_result) {
			throw new Exception("Error updating invoice customer: " . mysqli_error($conn));
		}

		// Update the customer in the payment table for associated payments
		$payment_update_query = "UPDATE payment SET cust = '$new_cust_id' WHERE invoice_no = '$invoice_id'";
		$payment_update_result = mysqli_query($conn, $payment_update_query);
		if (!$payment_update_result) {
			throw new Exception("Error updating customer in payment table: " . mysqli_error($conn));
		}

		// QuickBooks Integration branch
		if (isQuickBooksActive(1)) {
			// Check that the new customer is saved in QB
			if (!isset($new_customer['qb_cust_id']) || empty($new_customer['qb_cust_id'])) {
				throw new Exception("Error: The new customer is not saved in QuickBooks. Please save the customer in QuickBooks first.");
			}
			$new_qb_cust_id = $new_customer['qb_cust_id'];

			// Query to fetch invoice details and current customer name
			$query = "SELECT bm.qb_value_id, bm.qb_cost_id, bm.type, cu.qb_cust_id, cu.name as customer_name
                      FROM bill_main bm, cust cu
                      WHERE cu.id = bm.cust AND bm.invoice_no = '$invoice_id' LIMIT 1";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				throw new Exception("Database query failed: " . mysqli_error($conn));
			}
			if (mysqli_num_rows($result) == 0) {
				throw new Exception("Error: Invoice details not found in the database");
			}
			$row = mysqli_fetch_assoc($result);
			$qb_value_id = $row['qb_value_id'];
			$qb_cost_id = $row['qb_cost_id'];
			$type = $row['type'];
			$qb_cust_id = $new_qb_cust_id; // Use the new customer's QB ID
			$current_customer_name = $row['customer_name'];

			if ($type == 1) {
				// Initialize arrays to track QB operations and messages.
				$qb_operations = [];
				$qb_success_count = 0;
				$qb_error_messages = [];
				$qb_success_messages = [];

				// 1. Invoice Journal Entries
				if (!empty($qb_value_id) && !empty($qb_cost_id)) {
					$qb_operations[] = [
						'type' => 'invoice_value',
						'id' => $qb_value_id,
						'description' => 'Invoice Value Journal Entry'
					];
					$qb_operations[] = [
						'type' => 'invoice_cost',
						'id' => $qb_cost_id,
						'description' => 'Invoice Cost Journal Entry'
					];
				} else {
					throw new Exception('Error: QB is active, to change customer this invoice has to be saved in QB first');
				}

				// 2. Payment Journal Entries
				$payment_query = "SELECT id, qb_id, qb_deleted_id FROM payment WHERE bill_pay = 1 AND invoice_no = '$invoice_id'";
				$payment_result = mysqli_query($conn, $payment_query);
				if (!$payment_result) {
					throw new Exception("Database query failed when fetching payments: " . mysqli_error($conn));
				}
				$payment_count = mysqli_num_rows($payment_result);
				if ($payment_count > 0) {
					while ($payment = mysqli_fetch_assoc($payment_result)) {
						$pay_id = $payment['id'];
						$payment_qb_id = $payment['qb_id'];
						$payment_qb_deleted_id = $payment['qb_deleted_id'];

						// If no QB record is linked, throw an exception.
						if (empty($payment_qb_id)) {
							throw new Exception("Error: Payment ID $pay_id is not saved in QuickBooks. All payments must be saved in QB before changing the customer.");
						}

						$qb_operations[] = [
							'type' => 'payment',
							'id' => $payment_qb_id,
							'description' => "Payment Journal Entry (Payment ID: $pay_id)"
						];

						if (!empty($payment_qb_deleted_id)) {
							$qb_operations[] = [
								'type' => 'deleted_payment',
								'id' => $payment_qb_deleted_id,
								'description' => "Deleted Payment Journal Entry (Payment ID: $pay_id)"
							];
						}
					}
				} // End Payment Journal Entries

				// 3. Execute all QB operations
				foreach ($qb_operations as $operation) {
					$journal_entry_array = [
						'id' => $operation['id'],
						'new_customer' => $qb_cust_id,
						'new_customer_name' => $new_customer_name,
						'old_customer_name' => $current_customer_name
					];

					$result_qb = QBUpdateJournalEntryCustomer($journal_entry_array);
					if ($result_qb['status'] === 'success') {
						$qb_success_count++;
						$qb_success_messages[] = "Successfully updated {$operation['description']} (ID: {$operation['id']})";
					} else {
						$qb_error_messages[] = "Failed to update {$operation['description']} (ID: {$operation['id']}): " . $result_qb['message'];
					}
				}

				$total_operations = count($qb_operations);
				if ($qb_success_count === $total_operations) {
					// Build a composite message for QB updates.
					$qb_success_message = "Updated $qb_success_count QuickBooks entries: " . implode(" | ", $qb_success_messages);
					// Append the QB successes to the base message.
					$message = "Invoice customer changed successfully. " . $qb_success_message;
				} else {
					$error_message = "Error: Not all QuickBooks operations succeeded.\n";
					$error_message .= "Completed $qb_success_count out of $total_operations operations.\n";
					if ($qb_success_count > 0) {
						$error_message .= "Successful operations:\n" . implode("\n", $qb_success_messages) . "\n";
					}
					$error_message .= "Failed operations:\n" . implode("\n", $qb_error_messages);
					$error_message .= "\nPlease manually update the failed entries in QuickBooks for customer '$new_customer_name' (QB ID: $qb_cust_id).";
					throw new Exception($error_message);
				}
			} else {
				throw new Exception("Error: Invoice details not found in the database");
			}
		} else {
			// If QuickBooks is not active
			$message = "Invoice customer changed successfully.";
		}

		// Commit the entire transaction only once at the end
		mysqli_commit($conn);
		return true;
	} catch (Exception $e) {
		mysqli_rollback($conn); // Rollback the transaction on error
		$message = $e->getMessage();
		return false;
	}
}

function changePaymentCust()
{
	global $payment_id, $message;
	include('../config.php'); // Include the database configuration file

	// Start a transaction
	mysqli_begin_transaction($conn);

	try {
		// Validate input: check if payment_id and new_payment_cust_id are provided
		if (
			!isset($_POST['payment_id']) || empty($_POST['payment_id']) ||
			!isset($_POST['new_payment_cust_id']) || empty($_POST['new_payment_cust_id'])
		) {
			throw new Exception("Error: Payment ID or customer ID is empty.");
		}

		$new_cust_id = $_POST['new_payment_cust_id'];
		$payment_id = $_POST['payment_id'];

		// Check if the new customer exists in the cust table.
		// If QuickBooks is active, also retrieve qb_cust_id.
		if (isQuickBooksActive(1)) {
			$check_query = "SELECT id, name, qb_cust_id FROM cust WHERE id = '$new_cust_id'";
		} else {
			$check_query = "SELECT id, name FROM cust WHERE id = '$new_cust_id'";
		}

		$check_result = mysqli_query($conn, $check_query);
		if (!$check_result) {
			throw new Exception("Database query failed: " . mysqli_error($conn));
		}
		if (mysqli_num_rows($check_result) == 0) {
			throw new Exception("Error: The selected customer does not exist.");
		}
		$new_customer = mysqli_fetch_assoc($check_result);
		$new_customer_name = $new_customer['name'];

		// Update the customer in the payment table for the payment record with bill_pay = 2
		$update_query = "UPDATE payment SET cust = '$new_cust_id' WHERE id = '$payment_id' AND bill_pay = 2";
		$update_result = mysqli_query($conn, $update_query);
		if (!$update_result) {
			throw new Exception("Error updating payment customer: " . mysqli_error($conn));
		}

		// If QuickBooks integration is active, proceed with QB operations.
		if (isQuickBooksActive(1)) {
			// Verify that the new customer is saved in QB.
			if (!isset($new_customer['qb_cust_id']) || empty($new_customer['qb_cust_id'])) {
				throw new Exception("Error: The new customer is not saved in QuickBooks. Please save the customer in QuickBooks first.");
			}
			$new_qb_cust_id = $new_customer['qb_cust_id'];

			// Fetch payment details along with the current (old) customer name.
			$query = "SELECT p.qb_id, p.qb_deleted_id, p.cust, c.name as customer_name
                      FROM payment p
                      LEFT JOIN cust c ON c.id = p.cust
                      WHERE p.id = '$payment_id' AND p.bill_pay = 2 LIMIT 1";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				throw new Exception("Database query failed: " . mysqli_error($conn));
			}
			if (mysqli_num_rows($result) == 0) {
				throw new Exception("Error: Payment details not found in the database");
			}
			$row = mysqli_fetch_assoc($result);
			$payment_qb_id = $row['qb_id'];
			$payment_qb_deleted_id = $row['qb_deleted_id'];
			$old_customer_name = $row['customer_name'];

			if (empty($payment_qb_id)) {
				throw new Exception('Error: QB is active, to change customer this payment has to be saved in QB first');
			}
			// Prepare an array of QB operations.
			$qb_operations = array();

			if (!empty($payment_qb_id)) {
				$qb_operations[] = array(
					'type' => 'payment',
					'id' => $payment_qb_id,
					'description' => "Payment Journal Entry (Payment ID: $payment_id)"
				);
			} else {
				throw new Exception("Error: Payment ID $payment_id has no QuickBooks record (qb_id). It must be saved in QB first.");
			}

			// If a deleted payment journal entry exists, include it.
			if (!empty($payment_qb_deleted_id)) {
				$qb_operations[] = array(
					'type' => 'deleted_payment',
					'id' => $payment_qb_deleted_id,
					'description' => "Deleted Payment Journal Entry (Payment ID: $payment_id)"
				);
			}

			// Execute each QB operation.
			$qb_success_count = 0;
			$qb_error_messages = array();
			$qb_success_messages = array();
			foreach ($qb_operations as $operation) {
				$journal_entry_array = array(
					'id' => $operation['id'],
					'new_customer' => $new_qb_cust_id,
					'new_customer_name' => $new_customer_name,
					'old_customer_name' => $old_customer_name
				);

				$result_qb = QBUpdateJournalEntryCustomer($journal_entry_array);
				if ($result_qb['status'] === 'success') {
					$qb_success_count++;
					$qb_success_messages[] = "Successfully updated {$operation['description']} (ID: {$operation['id']})";
				} else {
					$qb_error_messages[] = "Failed to update {$operation['description']} (ID: {$operation['id']}): " . $result_qb['message'];
				}
			}

			$total_operations = count($qb_operations);
			if ($qb_success_count !== $total_operations) {
				$error_message = "Error: Not all QuickBooks operations succeeded.\n";
				$error_message .= "Completed $qb_success_count out of $total_operations operations.\n";
				$error_message .= "Successful operations:\n" . implode("\n", $qb_success_messages) . "\n";
				$error_message .= "Failed operations:\n" . implode("\n", $qb_error_messages);
				$error_message .= "\nPlease manually update the failed entries in QuickBooks for customer '$new_customer_name' (QB ID: $new_qb_cust_id).";
				throw new Exception($error_message);
			}

			// Append QB success messages to the final message.
			if (!empty($qb_success_messages)) {
				$message .= " QuickBooks updates: " . implode(" | ", $qb_success_messages);
			}
		}

		// Commit the transaction if everything succeeded.
		mysqli_commit($conn);
		$message = "Payment customer changed successfully. " . $message;
		return true;
	} catch (Exception $e) {
		mysqli_rollback($conn);
		$message = $e->getMessage();
		return false;
	} finally {
		// Close the database connection
		if (isset($conn)) {
			mysqli_close($conn);
		}
	}
}

function getReturnItems()
{
	global $inventory_options;
	include('../config.php');

	$query_inventory = "SELECT `id`, `description` FROM inventory_items
                    WHERE `status` = 1 ORDER BY `description`";
	$result_inventory = mysqli_query($conn, $query_inventory);
	$inventory_options = array();
	if ($result_inventory) {
		while ($row = mysqli_fetch_assoc($result_inventory)) {
			$inventory_options[] = $row;
		}
	}
}
function searchReturnItem()
{
	global $invoice_id, $message, $invoice_cust_name,
	$invoice_salesman, $invoice_date, $inv_found,
	$return_item, $replace_item, $return_record_ids;
	$inv_found = false;
	$return_item = $replace_item = $return_record_ids = array();
	include('../config.php'); // Include the database configuration file

	// Check if invoice_id is provided
	if (isset($_GET['invoice_id']) && $_GET['invoice_id'] != '') {
		$invoice_id = $_GET['invoice_id'];

		$query = "SELECT
                        rt.id AS return_record_id,
                        c.`name` AS customer_name,
                        up.`username` AS salesman,
                        rm.`return_date`,
                        ii.`description` AS return_description,
                        iii.`description` AS replace_description
                  FROM `return_main` rm
                  JOIN `return` rt ON rm.invoice_no = rt.invoice_no
                  JOIN cust c ON rm.`cust` = c.`id`
                  JOIN `inventory_items` ii ON rt.return_item = ii.id
                  JOIN `inventory_items` iii ON rt.replace_item = iii.id
                  JOIN `userprofile` up ON up.id = rm.return_by
                  WHERE rm.invoice_no = '$invoice_id'
                    AND rm.status NOT IN (0)
                    AND rt.odr_no IS NULL";

		$result = mysqli_query($conn, $query);

		// Check if the query was successful
		if (!$result) {
			$message = "Database query failed: " . mysqli_error($conn);
			return false;
		}

		// Fetch the customer name and other details
		if (mysqli_num_rows($result) > 0) {
			$inv_found = true;
			while ($row = mysqli_fetch_assoc($result)) {
				$invoice_cust_name = $row['customer_name'];
				$invoice_salesman = $row['salesman'];
				$invoice_date = $row['return_date'];

				$return_item[] = $row['return_description'];
				$replace_item[] = $row['replace_description'];
				$return_record_ids[] = $row['return_record_id'];
			}
		} else {
			$message = "No customer found for the provided invoice ID.";
			return false;
		}
	} else {
		$message = "Error: Invoice ID is empty.";
		return false;
	}
}

function updateReturnItem()
{
	global $message, $invoice_id;
	include('../config.php');

	if (!isset($_POST['invoice_id'], $_POST['return_record_ids'], $_POST['return_item'], $_POST['replace_item'])) {
		$message = "Required POST parameters are missing.";
		return false;
	}

	$invoice_id = mysqli_real_escape_string($conn, $_POST['invoice_id']);
	$return_record_ids = $_POST['return_record_ids'];
	$new_return_items = $_POST['return_item'];
	$new_replace_items = $_POST['replace_item'];

	// Optional: if customer update is included, ensure it's present (or default it)
	$new_cust_id = null;
	if (isset($_POST['new_return_cust_id']) && $_POST['new_return_cust_id'] != '') {
		$new_cust_id = intval($_POST['new_return_cust_id']);
	}

	$allUpdated = true;
	$errorMessages = "";

	// First update the customer in the return_main table (if a new customer was selected)
	if ($new_cust_id !== null) {
		$update_cust_query = "UPDATE `return_main` SET `cust` = '$new_cust_id' WHERE `invoice_no` = '$invoice_id'";
		$cust_update_result = mysqli_query($conn, $update_cust_query);
		if (!$cust_update_result) {
			$allUpdated = false;
			$errorMessages .= "Error updating customer for invoice $invoice_id: "
				. mysqli_error($conn) . "<br>";
		}
	}

	// Validate the arrays have the same length before updating item details.
	if (count($return_record_ids) !== count($new_return_items) || count($return_record_ids) !== count($new_replace_items)) {
		$message = "Input arrays have mismatched lengths.";
		return false;
	}

	// Loop through each return record and update
	for ($i = 0, $n = count($return_record_ids); $i < $n; $i++) {
		// Use intval() for the record id for safety
		$record_id = intval($return_record_ids[$i]);

		// Get and sanitize the new values
		$new_return_raw = trim($new_return_items[$i]);
		$new_replace_raw = trim($new_replace_items[$i]);

		// Check if the selection is empty.
		if ($new_return_raw === "" || $new_replace_raw === "") {
			$allUpdated = false;
			$errorMessages .= "Return or replace item not selected for record ID $record_id.<br>";
			continue; // Skip updating this record
		}

		// Check current status of the record to ensure it can be updated:
		$status_query = "SELECT `odr_no`, `odr_packed` FROM `return` WHERE `id` = '$record_id' LIMIT 1";
		$status_result = mysqli_query($conn, $status_query);
		if ($status_result && mysqli_num_rows($status_result) > 0) {
			$status_row = mysqli_fetch_assoc($status_result);
			// If odr_no is not null or odr_packed is not 0, then do not allow update.
			if ($status_row['odr_no'] !== null || $status_row['odr_packed'] != 0) {
				$allUpdated = false;
				$errorMessages .= "Record ID $record_id cannot be updated because it is either packed (odr_packed not 0) or already has an order number (odr_no is set).<br>";
				continue; // Skip to the next record.
			}
		} else {
			$allUpdated = false;
			$errorMessages .= "Could not verify status for record ID $record_id.<br>";
			continue;
		}

		// Escape the new values
		$new_return = mysqli_real_escape_string($conn, $new_return_raw);
		$new_replace = mysqli_real_escape_string($conn, $new_replace_raw);

		// Build update query with conditions on odr_no and odr_packed.
		$update_query = "UPDATE `return` SET `return_item` = '$new_return', `replace_item` = '$new_replace'
											 WHERE `id` = '$record_id' AND `odr_no` IS NULL AND `odr_packed` = 0";
		$update_result = mysqli_query($conn, $update_query);

		if (!$update_result) {
			$allUpdated = false;
			$errorMessages .= "Error updating record ID $record_id: "
				. mysqli_error($conn) . "<br>";
		}
	}

	// Finalize the function
	if ($allUpdated) {
		$message = "Update successful!";
		return true;
	} else {
		$message = "Some records could not be updated. " . $errorMessages;
		return false;
	}
}

function QBDeleteJournalEntries()
{
	global $message;
	// Reset global message
	$message = "";

	// Check if qb_id array was submitted via POST
	if (!isset($_POST['qb_id']) || !is_array($_POST['qb_id'])) {
		$message = "No QuickBooks IDs provided.";
		return false;
	}

	$qb_ids = $_POST['qb_id'];
	$allSuccess = true;
	$messages = array();

	// Loop through the submitted qb_ids (ignoring empty inputs)
	foreach ($qb_ids as $index => $qb_id) {
		$qb_id = trim($qb_id);
		if ($qb_id === "") {
			continue; // Skip empty fields
		}

		$result = QBDeleteJournalEntry(array('id' => $qb_id));
		if ($result) {
			$messages[] = "Deletion succeeded for journal entry ID: {$qb_id}.";
		} else {
			$messages[] = "Deletion failed for journal entry ID: {$qb_id}. " . $message;
			$allSuccess = false;
		}
	}

	// Combine messages for display.
	$message = implode(" <br> ", $messages);
	return $allSuccess;
}

function searchCashPaymentDeposit()
{
	global $invoice_id, $message, $id, $transfer_source, $amount, $payment_placed_by, $bank_name, $transfer_to,
	$transfer_date, $status, $payment_found, $approved_by;
	$payment_found = false;
	include('../config.php'); // Include the database configuration file

	// Check if search criteria were submitted via POST
	if (!isset($_GET['id'])) {
		$message = "No id provided.";
		return false;
	}
	$id = $_GET['id'];

	// Build the query based on the search criteria
	$query = "SELECT pd.id, pd.transfer_source, pd.amount, up.username AS salesman, a.name AS bank_name, uppp.username AS transfer_to, pd.transfer_date,
					upp.username AS approved_by, pd.status
						FROM payment_deposit pd
						INNER JOIN userprofile up ON pd.placed_by = up.id
						LEFT JOIN accounts a ON a.id = pd.bank_id
						LEFT JOIN userprofile upp ON upp.id = pd.approved_by
						LEFT JOIN userprofile uppp ON uppp.id = pd.user_id
						WHERE pd.id='$id';";

	$result = mysqli_query($conn, $query);
	if (!$result) {
		$message = "Error searching cash payment deposits: " . mysqli_error($conn);
		return false;
	} else {
		if (mysqli_num_rows($result) > 0) {
			$payment_found = true;
			while ($row = mysqli_fetch_assoc($result)) {
				$id = $row['id'];
				$transfer_source = $row['transfer_source'];
				$amount = $row['amount'];
				$payment_placed_by = $row['salesman'];
				$bank_name = $row['bank_name'];
				$transfer_to = $row['transfer_to'];
				$transfer_date = $row['transfer_date'];
				$approved_by = $row['approved_by'];
				$status = $row['status'];
			}
		} else {
			$message = "No payment deposit found.";
			return false;
		}
	}
}

function deleteCashPaymentDeposit()
{
	global $message;
	$time_now = timeNow();
	include('../config.php'); // Include the database configuration file

	try {
		// Check if database connection is available
		if (!$conn) {
			throw new Exception('Database connection failed');
		}

		// Begin transaction
		if (!mysqli_begin_transaction($conn)) {
			throw new Exception('Failed to begin transaction');
		}

		// Check if id was submitted via POST
		if (!isset($_POST['id'])) {
			$message = "No ID provided.";
			throw new Exception($message);
		}
		$id = $_POST['id'];

		if (isQuickBooksActive(1)) {
			// Check payment id with status
			$query = "SELECT pd.`transfer_source`, pd.`bank_id`, up.`username`, pd.`amount` FROM payment_deposit pd, userprofile up
			WHERE up.`id` = pd.`placed_by` AND pd.`status` = 2 AND pd.`qb_id` IS NOT NULL AND pd.`id`='$id'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				throw new Exception("Error checking QuickBooks ID: " . mysqli_error($conn));
			}
			$row = mysqli_fetch_assoc($result);
			if ($row && !empty($row['transfer_source'])) {
				$transfer_source = $row['transfer_source'];
				$bank_id = $row['bank_id'];
				$placed_by = $row['username'];
				$deposit_amount = $row['amount'];

				// bank payment, revert back / delete bank payment
				if ($transfer_source == 2) {
					$bankAccountNameQuery = "SELECT `name` FROM accounts WHERE id='$bank_id' LIMIT 1";
					$bankAccountNameResult = mysqli_query($conn, $bankAccountNameQuery);
					if (!$bankAccountNameResult) {
						$errorMessage = "MySQL Error fetching bank account name: " . mysqli_error($conn);
						throw new Exception($errorMessage);
					}
					if (mysqli_num_rows($bankAccountNameResult) > 0) {
						$bankAccountNameRow = mysqli_fetch_row($bankAccountNameResult);
						$bankAccountName = $bankAccountNameRow[0]; // This is the Credit Account for reversal
					} else {
						// Handle case where bank account ID exists but account is not found
						throw new Exception("Error: Bank account with ID '$bank_id' not found for payment transfer '$id'.");
					}

					$debitAccountName = "Cash on Hand";
					$creditAccountName = "$bankAccountName";
					$description = "[REVERT BACK BANK DEPOSIT] - Bank : ($bankAccountName), User : $placed_by";
					$debitEntityType = "";
					$debitEntityID = "";
					$creditEntityType = "";
					$creditEntityID = "";

					// Call buildJournalEntry
					$journalEntryForBankDeposit = buildJournalEntry(
						$conn,
						$deposit_amount,
						$debitAccountName,
						$creditAccountName,
						$description,
						$debitEntityType,
						$debitEntityID,
						$creditEntityType,
						$creditEntityID
					);

					if (isset($journalEntryForBankDeposit['error'])) {
						$qb_msg = $journalEntryForBankDeposit['error'];
						throw new Exception("QB Journal Entry Error for Cash Deposit: " . $qb_msg);
					} else {
						$action_name = "revers_bank_payment_deposit";
						$batch_id = generateBatchID();
						if (!$batch_id) {
							throw new Exception("Error: Failed to generate a valid batch ID.");
						}

						if (!is_array($journalEntryForBankDeposit)) {
							throw new Exception("buildJournalEntry did not return a valid array for cash deposit $id.");
						}

						foreach ($journalEntryForBankDeposit as $entry) {
							if (
								!isset(
								$entry['posting_type'],
								$entry['account_id'],
								$entry['account_name'],
								$entry['amount'],
								$entry['description']
							)
							) {
								throw new Exception("Invalid structure in journal entry array from buildJournalEntry for cash deposit $id.");
							}

							$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
							$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
							$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
							$entry_amount = mysqli_real_escape_string($conn, $entry['amount']);
							$entry_description = mysqli_real_escape_string($conn, $entry['description']);

							$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
							$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;
							$entity_name = isset($entry['entity_name']) ? mysqli_real_escape_string($conn, $entry['entity_name']) : null;

							// Insert into qb_queue
							$insert_query = "INSERT INTO qb_queue (
                            `batch_id`,
                            `action`,
                            `payment_id`,
                            `posting_type`,
                            `account_id`,
                            `account_name`,
                            `amount`,
                            `description`,
                            `created_at`,
                            `entity_type`,
                            `entity_id`,
                            `entity_name`
                        ) VALUES (
                            '$batch_id',
                            '$action_name',
                            '$id',
                            '$posting_type',
                            '$account_id',
                            '$account_name',
                            '$entry_amount',
                            '$entry_description',
                            '$time_now',
                            " . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
                            " . ($entity_id !== null ? "'$entity_id'" : "NULL") . ",
                            " . ($entity_name !== null ? "'$entity_name'" : "NULL") . "
                        )";

							if (!mysqli_query($conn, $insert_query)) {
								$message = "MySQL Error while inserting cash deposit QB entry into qb_queue: " .
									mysqli_error($conn);
								throw new Exception($message);
							}
						}
					}
				}
			}
		}

		// Build the delete query
		$query = "DELETE FROM payment_deposit WHERE id='$id'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$message = "Error deleting cash payment deposit: " . mysqli_error($conn);
			throw new Exception($message);
		}

		// Commit transaction
		if (!mysqli_commit($conn)) {
			throw new Exception('Failed to commit transaction');
		}

		$message = "Cash payment deposit deleted successfully.";
		return true;
	} catch (Exception $e) {
		mysqli_rollback($conn);
		$message = $e->getMessage();
		return false;
	} finally {
		// Close the database connection
		if (isset($conn)) {
			mysqli_close($conn);
		}
	}
}
