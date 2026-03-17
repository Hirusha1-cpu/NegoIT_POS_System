<?php
include('config.php');
include('template/common.php');
function handleDeleteInvoiceValue($conn, $batch_id, $action_name)
{
    // Fetch all records for the given batch
    $query = "SELECT `id`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `description`,
    `entity_type`, `entity_id`, `entity_name`, `invoice_total`
   FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    $invoice_no = null;
    $journal_entries = array();
    $queue_ids = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $invoice_no = $row['invoice_no'];
        $queue_ids[] = $row['id'];

        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['invoice_total'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null,
            "entity_name" => isset($row['entity_name']) ? $row['entity_name'] : null
        );
    }

    if (count($journal_entries) > 1) {
        try {
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message'])
                ? $qb_result['message'] : 'Unknown error');

            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if ($qb_journal_entry_id != '') {
                    $update_query = "UPDATE bill_main SET `qb_deleted_value_id` = '$qb_journal_entry_id'
                    WHERE `invoice_no` = '$invoice_no'";
                    $update_result = mysqli_query($conn, $update_query);

                    if (!$update_result) {
                        $failed_ids_str = implode(",", $queue_ids);
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2
                        WHERE `id` IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue);
                        $message = "QuickBooks bill_main qb_deleted_value_id update error. Journal Entry ID: " . $qb_journal_entry_id;
                        logQbError(
                            $conn,
                            $action_name,
                            $message,
                            $invoice_no
                        );
                        return false;
                    } else {
                        $failed_ids = array();
                        foreach ($queue_ids as $id) {
                            $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                            if (!mysqli_query($conn, $delete_query)) {
                                $failed_ids[] = $id;
                            }
                        }
                        if (count($failed_ids) > 0) {
                            $failed_ids_str = implode(",", $failed_ids);
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue);
                            logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $invoice_no);
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            } else {
                logQbError($conn, $action_name, $qb_msg, $invoice_no);
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $invoice_no);
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handlePaymentInsertThroughBillInsert($conn, $batch_id, $action_name)
{
    // try {
    // 	$journal_entry_result = QBAddJournalEntry($result_array);
    // 	$qb_msg = $journal_entry_result['message'];
    // 	if (isset($journal_entry_result['status']) && ($journal_entry_result['status'] == 'success')) {
    // 		$qb_journal_entry_id = $journal_entry_result['qb_journal_entry_id'];
    // 		$query = "UPDATE `payment` SET `qb_id`='$qb_journal_entry_id', `qb_status`=1 WHERE `id`='$payment_id'";
    // 		$result1 = mysqli_query($conn, $query);
    // 		if (!$result1) {
    // 			$qb_msg = 'QuickBooks payment ID update error.';
    // 			throw new Exception($qb_msg);
    // 		}
    // 	} else {
    // 		throw new Exception($qb_msg);
    // 	}
    // } catch (Exception $e) {
    // 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
    // 	throw new Exception($qb_msg);
    // }
    // Fetch all records for the given batch
    $query = "SELECT `id`, `payment_id`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`, `entity_name`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    $payment_id = $invoice_no = null;
    $journal_entries = array();
    $queue_ids = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $payment_id = $row['payment_id'];
        $invoice_no = $row['invoice_no'];
        $queue_ids[] = $row['id'];

        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null,
            "entity_name" => isset($row['entity_name']) ? $row['entity_name'] : null
        );
    }

    if (count($journal_entries) > 1) {
        try {
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if ($qb_journal_entry_id != '') {
                    $update_query = "UPDATE `payment` SET `qb_id`='$qb_journal_entry_id', `qb_status`=1 WHERE `id`='$payment_id'";
                    $update_result = mysqli_query($conn, $update_query);

                    if (!$update_result) {
                        $failed_ids_str = implode(",", $queue_ids);
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue);
                        logQbError($conn, $action_name, "QuickBooks payment qb_id update error. Journal Entry ID: " . $qb_journal_entry_id, $payment_id);
                        return false;
                    } else {
                        $failed_ids = array();
                        foreach ($queue_ids as $id) {
                            $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                            if (!mysqli_query($conn, $delete_query)) {
                                $failed_ids[] = $id;
                            }
                        }
                        if (count($failed_ids) > 0) {
                            $failed_ids_str = implode(",", $failed_ids);
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue);
                            logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $payment_id);
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            } else {
                logQbError($conn, $action_name, $qb_msg, $payment_id);
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $payment_id);
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handleInvoiceCostInsert($conn, $batch_id, $action_name)
{
    $query = "SELECT `id`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`, `entity_name`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    $invoice_no = null;
    $journal_entries = array();
    $queue_ids = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $invoice_no = $row['invoice_no'];
        $queue_ids[] = $row['id'];

        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null,
            "entity_name" => isset($row['entity_name']) ? $row['entity_name'] : null
        );
    }

    if (count($journal_entries) > 1) {
        try {
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if ($qb_journal_entry_id != '') {
                    $update_query = "UPDATE `bill_main` SET `qb_cost_id`='$qb_journal_entry_id' WHERE `invoice_no`='$invoice_no'";
                    $update_result = mysqli_query($conn, $update_query);

                    if (!$update_result) {
                        $failed_ids_str = implode(",", $queue_ids);
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue);
                        logQbError($conn, $action_name, "QuickBooks invoice qb_cost_id update error. Journal Entry ID: " . $qb_journal_entry_id, $invoice_no);
                        return false;
                    } else {
                        $failed_ids = array();
                        foreach ($queue_ids as $id) {
                            $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                            if (!mysqli_query($conn, $delete_query)) {
                                $failed_ids[] = $id;
                            }
                        }
                        if (count($failed_ids) > 0) {
                            $failed_ids_str = implode(",", $failed_ids);
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue);
                            logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $invoice_no);
                            return false;
                        } else {
                            $selectQBValueIdQuery = "SELECT `qb_value_id` FROM bill_main WHERE `invoice_no` = '$invoice_no'";
                            $qbValueResult = mysqli_query($conn, $selectQBValueIdQuery);
                            if ($qbValueResult && mysqli_num_rows($qbValueResult) > 0) {
                                $qbValueIdRow = mysqli_fetch_row($qbValueResult);
                                // It is preferable to use the empty() function here for better clarity
                                if (!empty($qbValueIdRow[0])) {
                                    $updateBillMainStatusQuery = "UPDATE bill_main SET `qb_status` = 1 WHERE `invoice_no` = '$invoice_no'";
                                    if (mysqli_query($conn, $updateBillMainStatusQuery)) {
                                        return true;
                                    } else {
                                        return false;
                                    }
                                }
                            }
                            return true;
                        }
                    }
                }
            } else {
                logQbError($conn, $action_name, $qb_msg, $invoice_no);
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $invoice_no);
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handleInvoiceTotalInsert($conn, $batch_id, $action_name)
{
    $query = "SELECT `id`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`, `entity_name`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    $invoice_no = null;
    $journal_entries = array();
    $queue_ids = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $invoice_no = $row['invoice_no'];
        $queue_ids[] = $row['id'];

        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null,
            "entity_name" => isset($row['entity_name']) ? $row['entity_name'] : null
        );
    }

    if (count($journal_entries) > 1) {
        try {
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if ($qb_journal_entry_id != '') {
                    $update_query = "UPDATE `bill_main` SET `qb_value_id`='$qb_journal_entry_id' WHERE `invoice_no`='$invoice_no'";
                    $update_result = mysqli_query($conn, $update_query);

                    if (!$update_result) {
                        $failed_ids_str = implode(",", $queue_ids);
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue);
                        logQbError($conn, $action_name, "QuickBooks invoice qb_value_id update error. Journal Entry ID: " . $qb_journal_entry_id, $invoice_no);
                        return false;
                    } else {
                        $failed_ids = array();
                        foreach ($queue_ids as $id) {
                            $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                            if (!mysqli_query($conn, $delete_query)) {
                                $failed_ids[] = $id;
                            }
                        }
                        if (count($failed_ids) > 0) {
                            $failed_ids_str = implode(",", $failed_ids);
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue);
                            logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $invoice_no);
                            return false;
                        } else {
                            $selectQBValueIdQuery = "SELECT `qb_cost_id` FROM bill_main WHERE `invoice_no` = '$invoice_no'";
                            $qbValueResult = mysqli_query($conn, $selectQBValueIdQuery);
                            if ($qbValueResult && mysqli_num_rows($qbValueResult) > 0) {
                                $qbValueIdRow = mysqli_fetch_row($qbValueResult);
                                // It is preferable to use the empty() function here for better clarity
                                if (!empty($qbValueIdRow[0])) {
                                    $updateBillMainStatusQuery = "UPDATE bill_main SET `qb_status` = 1 WHERE `invoice_no` = '$invoice_no'";
                                    if (mysqli_query($conn, $updateBillMainStatusQuery)) {
                                        return true;
                                    } else {
                                        return false;
                                    }
                                }
                            }
                            return true;
                        }
                    }
                }
            } else {
                logQbError($conn, $action_name, $qb_msg, $invoice_no);
                updateQueueStatus($conn, $batch_id, 1);
            }
        } catch (Exception $e) {
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $invoice_no);
            updateQueueStatus($conn, $batch_id, 1);
        }
    }
}

function handleDeletePaymentInsertThroughBill($conn, $batch_id, $action_name)
{
    // try {
    // 	$journal_payment_entry_result = QBAddJournalEntry($journalEntryForDeletedPayment);
    // 	$qb_msg = $journal_payment_entry_result['message'];

    // 	if ((isset($journal_payment_entry_result['status'])) && ($journal_payment_entry_result['status'] == 'success')) {
    // 		$qb_journal_entry_id = $journal_payment_entry_result['qb_journal_entry_id'];
    // 		$query = "UPDATE `payment` SET `qb_deleted_id`='$qb_journal_entry_id' WHERE `id`='$payment_id'";
    // 		$result1 = mysqli_query($conn, $query);
    // 		if (!$result1) {
    // 			$flag = false;
    // 			$qb_msg = 'QuickBooks payment deleted ID update error.';
    // 			throw new Exception($qb_msg);
    // 		}
    // 	} else {
    // 		$flag = false;
    // 		throw new Exception($qb_msg);
    // 	}
    // } catch (Exception $e) {
    // 	$flag = false;
    // 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
    // 	throw new Exception($qb_msg);
    // }
    // Fetch all records for the given batch
    $query = "SELECT `id`, `payment_id`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`, `entity_name`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    $payment_id = $invoice_no = null;
    $journal_entries = array();
    $queue_ids = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $payment_id = $row['payment_id'];
        $invoice_no = $row['invoice_no'];
        $queue_ids[] = $row['id'];

        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null,
            "entity_name" => isset($row['entity_name']) ? $row['entity_name'] : null
        );
    }

    if (count($journal_entries) > 1) {
        try {
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if ($qb_journal_entry_id != '') {

                    $update_query = "UPDATE `payment` SET `qb_deleted_id`='$qb_journal_entry_id' WHERE `id`='$payment_id'";
                    $update_result = mysqli_query($conn, $update_query);

                    if (!$update_result) {
                        $failed_ids_str = implode(",", $queue_ids);
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue);
                        logQbError($conn, $action_name, "QuickBooks payment qb_deleted_id update error. Journal Entry ID: " . $qb_journal_entry_id, $payment_id);
                        return false;
                    } else {
                        $failed_ids = array();
                        foreach ($queue_ids as $id) {
                            $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                            if (!mysqli_query($conn, $delete_query)) {
                                $failed_ids[] = $id;
                            }
                        }
                        if (count($failed_ids) > 0) {
                            $failed_ids_str = implode(",", $failed_ids);
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue);
                            logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $payment_id);
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            } else {
                logQbError($conn, $action_name, $qb_msg, $payment_id);
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $payment_id);
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handleReturnItemInvoiceInsertThroughODRInsert($conn, $batch_id, $action_name)
{
    // try {
    // 	$journal_entry_result = QBAddJournalEntry($journalEntryForReturnItems);
    // 	$qb_msg = $journal_entry_result['message'];

    // 	if (isset($journal_entry_result['status']) && ($journal_entry_result['status'] == 'success')) {
    // 		$qb_journal_entry_id = $journal_entry_result['qb_journal_entry_id'];
    // 		$query = "UPDATE `return` SET `qb_id`='$qb_journal_entry_id' WHERE `odr_no`='$invoice_no'";
    // 		$result1 = mysqli_query($conn, $query);
    // 		if (!$result1) {
    // 			$flag = false;
    // 			$qb_msg = 'QuickBooks return qb_id update error.';

    // 			$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 			$action = "UPDATE RETURN QB ID : Return Id : $return_id,  Invoice No: $invoice_no";
    // 			$functionName = "setStatus";

    // 			$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 			VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 			$result = mysqli_query($conn, $error_log_query);
    // 		}
    // 	} else {
    // 		$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 		$action = "CUST ODR RETURN ITEMS, JOURNAL ENTRY INSERT (Item Cost) : Return Id : $return_id, Invoice No: $invoice_no";
    // 		$functionName = "setStatus";

    // 		$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 		VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 		$result = mysqli_query($conn, $error_log_query);
    // 	}
    // } catch (Exception $e) {
    // 	$flag = false;
    // 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();

    // 	$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 	$action = "CUST ODR RETURN ITEMS, JOURNAL ENTRY INSERT (Item Cost) : Return Id : $return_id, Invoice No: $invoice_no";
    // 	$functionName = "setStatus";

    // 	$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 	VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 	$result = mysqli_query($conn, $error_log_query);
    // }
    $query = "SELECT `id`, `invoice_no`, `posting_type`, `account_id`, `amount`, `account_name`, `description`, `entity_type`, `entity_id`, `entity_name`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    $invoice_no = null;
    $journal_entries = array();
    $queue_ids = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $invoice_no = $row['invoice_no'];
        $queue_ids[] = $row['id'];

        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null,
            "entity_name" => isset($row['entity_name']) ? $row['entity_name'] : null
        );
    }

    if (count($journal_entries) > 1) {
        try {
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if ($qb_journal_entry_id != '') {
                    $update_query = "UPDATE `return` SET `qb_id`='$qb_journal_entry_id' WHERE `odr_no`='$invoice_no'";
                    $update_result = mysqli_query($conn, $update_query);

                    if (!$update_result) {
                        $failed_ids_str = implode(",", $queue_ids);
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue);
                        logQbError($conn, $action_name, "QuickBooks return qb_id update error. Journal Entry ID: " . $qb_journal_entry_id, $invoice_no);
                        return false;
                    } else {
                        $failed_ids = array();
                        foreach ($queue_ids as $id) {
                            $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                            if (!mysqli_query($conn, $delete_query)) {
                                $failed_ids[] = $id;
                            }
                        }
                        if (count($failed_ids) > 0) {
                            $failed_ids_str = implode(",", $failed_ids);
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue);
                            logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $invoice_no);
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            } else {
                logQbError($conn, $action_name, $qb_msg, $invoice_no);
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $invoice_no);
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handleInvoiceInsertThroughODRInsert($conn, $batch_id, $action_name)
{
    // invoice total journal entry inserting to qb
    // try {
    // 	$journal_entry_result = QBAddJournalEntry($journalEntryForInvoiceTotal);
    // 	$qb_msg = $journal_entry_result['message'];

    // 	if (isset($journal_entry_result['status']) && ($journal_entry_result['status'] == 'success')) {
    // 		$qb_journal_entry_id = $journal_entry_result['qb_journal_entry_id'];
    // 		$query = "UPDATE `bill_main` SET `qb_value_id`='$qb_journal_entry_id' WHERE `invoice_no`='$invoice_no'";
    // 		$result1 = mysqli_query($conn, $query);
    // 		if (!$result1) {
    // 			$flag = false;
    // 			$qb_msg = 'Quickbooks bill main id update error.';

    // 			$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 			$action = "UPDATE BILL MAIN INVOICE COST ID (Invoice Total) : Invoice No: $invoice_no";
    // 			$functionName = "setStatus";

    // 			$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 			VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 			$result = mysqli_query($conn, $error_log_query);
    // 		}
    // 	} else {
    // 		$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 		$action = "CUST ODR INVOICE, JOURNAL ENTRY INSERT (Invoice Total) : Invoice No: $invoice_no";
    // 		$functionName = "setStatus";
    // 		$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 		VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 		$result = mysqli_query($conn, $error_log_query);
    // 	}
    // } catch (Exception $e) {
    // 	$flag = false;
    // 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();

    // 	$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 	$action = "CUST ODR INVOICE, JOURNAL ENTRY INSERT (Invoice Total) : Invoice No: $invoice_no";
    // 	$functionName = "setStatus";

    // 	$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 	VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 	$result = mysqli_query($conn, $error_log_query);
    // }

    // invoice cost journal entry inserting to qb
    // try {
    // 	$journal_entry_result1 = QBAddJournalEntry($journalEntryForInvoiceCost);
    // 	$qb_msg = $journal_entry_result1['message'];

    // 	if (isset($journal_entry_result1['status']) && ($journal_entry_result1['status'] == 'success')) {
    // 		$qb_journal_entry_id = $journal_entry_result1['qb_journal_entry_id'];
    // 		$query = "UPDATE `bill_main` SET `qb_cost_id`='$qb_journal_entry_id' WHERE `invoice_no`='$invoice_no'";
    // 		$result1 = mysqli_query($conn, $query);
    // 		if (!$result1) {
    // 			$flag = false;
    // 			$qb_msg = 'Quickbooks shipment main id update error.';

    // 			$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 			$action = "UPDATE BILL MAIN INVOICE COST ID (Invoice Cost) : Invoice No: $invoice_no";
    // 			$functionName = "setStatus";

    // 			$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 			VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 			$result = mysqli_query($conn, $error_log_query);
    // 		}
    // 	} else {
    // 		$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 		$action = "CUST ODR INVOICE, JOURNAL ENTRY INSERT (Invoice Cost) : Invoice No: $invoice_no";
    // 		$functionName = "setStatus";
    // 		$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 		VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 		$result = mysqli_query($conn, $error_log_query);
    // 	}
    // } catch (Exception $e) {
    // 	$flag = false;
    // 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();

    // 	$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 	$action = "CUST ODR INVOICE, JOURNAL ENTRY INSERT (Invoice Cost) : Invoice No: $invoice_no";
    // 	$functionName = "setStatus";

    // 	$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 	VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 	$result = mysqli_query($conn, $error_log_query);
    // }
    $query = "SELECT `id`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `description`, `entity_type`, `entity_id`, `entity_name`, `invoice_total`, `invoice_cost`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    $invoice_no = null;
    $invoice_total = null;
    $invoice_cost = null;
    $journal_entries = array();
    $queue_ids = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $invoice_no = $row['invoice_no'];
        $invoice_total = $row['invoice_total'];
        $invoice_cost = $row['invoice_cost'];
        $queue_ids[] = $row['id'];

        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $invoice_total !== null && $invoice_total != '' ? $row['invoice_total'] : $row['invoice_cost'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null,
            "entity_name" => isset($row['entity_name']) ? $row['entity_name'] : null
        );
    }

    if (count($journal_entries) > 1) {
        try {
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if ($qb_journal_entry_id != '') {
                    if ($invoice_total != '') {
                        $update_query = "UPDATE bill_main SET `qb_value_id` = '$qb_journal_entry_id' WHERE `invoice_no` = '$invoice_no'";
                        $update_result = mysqli_query($conn, $update_query);

                        if (!$update_result) {
                            $failed_ids_str = implode(",", $queue_ids);
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue);
                            logQbError($conn, $action_name, "QuickBooks bill_main qb_value_id update error. Journal Entry ID: " . $qb_journal_entry_id, $invoice_no);
                            return false;
                        } else {
                            $failed_ids = array();
                            foreach ($queue_ids as $id) {
                                $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                                if (!mysqli_query($conn, $delete_query)) {
                                    $failed_ids[] = $id;
                                }
                            }
                            if (count($failed_ids) > 0) {
                                $failed_ids_str = implode(",", $failed_ids);
                                $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                                mysqli_query($conn, $update_deleted_queue);
                                logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $invoice_no);
                                return false;
                            } else {
                                $selectQBValueIdQuery = "SELECT `qb_cost_id` FROM bill_main WHERE `invoice_no` = '$invoice_no'";
                                $qbValueResult = mysqli_query($conn, $selectQBValueIdQuery);
                                if ($qbValueResult && mysqli_num_rows($qbValueResult) > 0) {
                                    $qbValueIdRow = mysqli_fetch_row($qbValueResult);
                                    if (!empty($qbValueIdRow[0])) {
                                        $updateBillMainStatusQuery = "UPDATE bill_main SET `qb_status` = 1 WHERE `invoice_no` = '$invoice_no'";
                                        if (mysqli_query($conn, $updateBillMainStatusQuery)) {
                                            return true;
                                        } else {
                                            logQbError($conn, $action_name, "Error updating bill_main qb_status to 1 ", $invoice_no);
                                            return false;
                                        }
                                    }
                                }
                                return true;
                            }

                        }
                    }

                    if ($invoice_cost != '') {
                        $update_query = "UPDATE bill_main SET `qb_cost_id` = '$qb_journal_entry_id' WHERE `invoice_no` = '$invoice_no'";
                        $update_result = mysqli_query($conn, $update_query);

                        if (!$update_result) {
                            $failed_ids_str = implode(",", $queue_ids);
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue);
                            logQbError($conn, $action_name, "QuickBooks bill_main qb_cost_id update error. Journal Entry ID: " . $qb_journal_entry_id, $invoice_no);
                            return false;
                        } else {
                            $failed_ids = array();
                            foreach ($queue_ids as $id) {
                                $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                                if (!mysqli_query($conn, $delete_query)) {
                                    $failed_ids[] = $id;
                                }
                            }
                            if (count($failed_ids) > 0) {
                                $failed_ids_str = implode(",", $failed_ids);
                                $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                                mysqli_query($conn, $update_deleted_queue);
                                logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $invoice_no);
                                return false;
                            } else {
                                $selectQBValueIdQuery = "SELECT `qb_value_id` FROM bill_main WHERE `invoice_no` = '$invoice_no'";
                                $qbValueResult = mysqli_query($conn, $selectQBValueIdQuery);
                                if ($qbValueResult && mysqli_num_rows($qbValueResult) > 0) {
                                    $qbValueIdRow = mysqli_fetch_row($qbValueResult);
                                    // It is preferable to use the empty() function here for better clarity
                                    if (!empty($qbValueIdRow[0])) {
                                        $updateBillMainStatusQuery = "UPDATE bill_main SET `qb_status` = 1 WHERE `invoice_no` = '$invoice_no'";
                                        if (mysqli_query($conn, $updateBillMainStatusQuery)) {
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    }
                                }
                                return true;
                            }

                        }
                    }
                }
            } else {
                logQbError($conn, $action_name, $qb_msg, $invoice_no);
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $invoice_no);
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handleReturnInvoiceInsertThroughODRInsert($conn, $batch_id, $action_name)
{
    // invoice total journal entry inserting to qb
    // try {
    // 	$journal_entry_result = QBAddJournalEntry($journalEntryForInvoiceTotal);
    // 	$qb_msg = $journal_entry_result['message'];
    // 	if ((isset($journal_entry_result['status'])) && ($journal_entry_result['status'] == 'success')) {
    // 		$qb_journal_entry_id = $journal_entry_result['qb_journal_entry_id'];
    // 		$query = "UPDATE `bill_main` SET `qb_value_id`='$qb_journal_entry_id' WHERE `invoice_no`='$invoice_no'";
    // 		$result1 = mysqli_query($conn, $query);
    // 		if (!$result1) {
    // 			$flag = false;
    // 			$qb_msg = 'Quickbooks bill main id update error.';

    // 			$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 			$action = "UPDATE BILL MAIN INVOICE COST ID (Invoice Total) : Invoice No: $invoice_no";
    // 			$functionName = "setStatus";

    // 			$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 			VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 			$result = mysqli_query($conn, $error_log_query);
    // 		}
    // 	} else {
    // 		$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 		$action = "CUST ODR RETURN INVOICE, JOURNAL ENTRY INSERT (Invoice Total) : Invoice No: $invoice_no";
    // 		$functionName = "setStatus";
    // 		$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 		VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 		$result = mysqli_query($conn, $error_log_query);
    // 	}
    // } catch (Exception $e) {
    // 	$flag = false;
    // 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();

    // 	$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 	$action = "CUST ODR RETURN INVOICE, JOURNAL ENTRY INSERT (Invoice Total) : Invoice No: $invoice_no";
    // 	$functionName = "setStatus";

    // 	$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 	VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 	$result = mysqli_query($conn, $error_log_query);
    // }

    // invoice cost journal entry inserting to qb
    // try {
    // 	$journal_entry_result1 = QBAddJournalEntry($journalEntryForInvoiceCost);
    // 	$qb_msg = $journal_entry_result1['message'];

    // 	if ((isset($journal_entry_result1['status'])) && ($journal_entry_result1['status'] == 'success')) {
    // 		$qb_journal_entry_id = $journal_entry_result1['qb_journal_entry_id'];
    // 		$query = "UPDATE `bill_main` SET `qb_cost_id`='$qb_journal_entry_id' WHERE `invoice_no`='$invoice_no'";
    // 		$result1 = mysqli_query($conn, $query);
    // 		if (!$result1) {
    // 			$qb_msg = 'Quickbooks bill main qb_cost_id update error.';

    // 			$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 			$action = "UPDATE BILL MAIN INVOICE COST ID (Invoice Cost) : Invoice No: $invoice_no";
    // 			$functionName = "setStatus";

    // 			$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 			VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 			$result = mysqli_query($conn, $error_log_query);
    // 		}
    // 		$query = "UPDATE `bill_main` SET `qb_status`=1 WHERE `invoice_no`='$invoice_no'";
    // 		$result1 = mysqli_query($conn, $query);
    // 		if (!$result1) {
    // 			$qb_msg = 'Quickbooks bill main qb status update error.';

    // 			$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 			$action = "UPDATE BILL MAIN INVOICE Status (Invoice Cost) : Invoice No: $invoice_no";
    // 			$functionName = "setStatus";

    // 			$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 			VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 			$result = mysqli_query($conn, $error_log_query);
    // 		}
    // 	} else {
    // 		$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 		$action = "CUST ODR RETURN INVOICE, JOURNAL ENTRY INSERT (Invoice Cost) : Invoice No: $invoice_no";
    // 		$functionName = "setStatus";
    // 		$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 		VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 		$result = mysqli_query($conn, $error_log_query);
    // 	}
    // } catch (Exception $e) {
    // 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
    // 	$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 	$action = "CUST ODR RETURN INVOICE, JOURNAL ENTRY INSERT (Invoice Cost) : Invoice No: $invoice_no";
    // 	$functionName = "setStatus";

    // 	$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 	VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 	$result = mysqli_query($conn, $error_log_query);
    // }
    $query = "SELECT `id`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `description`, `entity_type`, `entity_id`, `entity_name`, `invoice_total`, `invoice_cost`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    $invoice_no = null;
    $invoice_total = null;
    $invoice_cost = null;
    $journal_entries = array();
    $queue_ids = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $invoice_no = $row['invoice_no'];
        $invoice_total = $row['invoice_total'];
        $invoice_cost = $row['invoice_cost'];
        $queue_ids[] = $row['id'];

        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $invoice_total !== null && $invoice_total != '' ? $row['invoice_total'] : $row['invoice_cost'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null,
            "entity_name" => isset($row['entity_name']) ? $row['entity_name'] : null
        );
    }

    if (count($journal_entries) > 1) {
        try {
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if ($qb_journal_entry_id != '') {
                    // Update bill_main table only if invoice_total is not null
                    if ($invoice_total != null) {
                        $update_query = "UPDATE bill_main SET `qb_value_id` = '$qb_journal_entry_id' WHERE `invoice_no` = '$invoice_no'";
                        $update_result = mysqli_query($conn, $update_query);

                        if (!$update_result) {
                            $failed_ids_str = implode(",", $queue_ids);
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue);
                            logQbError($conn, $action_name, "QuickBooks bill_main qb_value_id update error. Journal Entry ID: " . $qb_journal_entry_id, $invoice_no);
                            return false;
                        } else {
                            $failed_ids = array();
                            foreach ($queue_ids as $id) {
                                $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                                if (!mysqli_query($conn, $delete_query)) {
                                    $failed_ids[] = $id;
                                }
                            }
                            if (count($failed_ids) > 0) {
                                $failed_ids_str = implode(",", $failed_ids);
                                $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                                mysqli_query($conn, $update_deleted_queue);
                                logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $invoice_no);
                                return false;
                            } else {
                                $selectQBValueIdQuery = "SELECT `qb_cost_id` FROM bill_main WHERE `invoice_no` = '$invoice_no'";
                                $qbValueResult = mysqli_query($conn, $selectQBValueIdQuery);
                                if ($qbValueResult && mysqli_num_rows($qbValueResult) > 0) {
                                    $qbValueIdRow = mysqli_fetch_row($qbValueResult);
                                    if (!empty($qbValueIdRow[0])) {
                                        $updateBillMainStatusQuery = "UPDATE bill_main SET `qb_status` = 1 WHERE `invoice_no` = '$invoice_no'";
                                        if (mysqli_query($conn, $updateBillMainStatusQuery)) {
                                            return true;
                                        } else {
                                            logQbError($conn, $action_name, "Error updating bill_main qb_status to 1 ", $invoice_no);
                                            return false;
                                        }
                                    }
                                }
                                return true;
                            }
                        }
                    }

                    if ($invoice_cost != null) {
                        $update_query = "UPDATE bill_main SET `qb_cost_id` = '$qb_journal_entry_id' WHERE `invoice_no` = '$invoice_no'";
                        $update_result = mysqli_query($conn, $update_query);

                        if (!$update_result) {
                            $failed_ids_str = implode(",", $queue_ids);
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue);
                            logQbError($conn, $action_name, "QuickBooks bill_main qb_cost_id update error. Journal Entry ID: " . $qb_journal_entry_id, $invoice_no);
                            return false;
                        } else {
                            $failed_ids = array();
                            foreach ($queue_ids as $id) {
                                $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                                if (!mysqli_query($conn, $delete_query)) {
                                    $failed_ids[] = $id;
                                }
                            }
                            if (count($failed_ids) > 0) {
                                $failed_ids_str = implode(",", $failed_ids);
                                $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                                mysqli_query($conn, $update_deleted_queue);
                                logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $invoice_no);
                                return false;
                            } else {
                                $selectQBValueIdQuery = "SELECT `qb_value_id` FROM bill_main WHERE `invoice_no` = '$invoice_no'";
                                $qbValueResult = mysqli_query($conn, $selectQBValueIdQuery);
                                if ($qbValueResult && mysqli_num_rows($qbValueResult) > 0) {
                                    $qbValueIdRow = mysqli_fetch_row($qbValueResult);
                                    // It is preferable to use the empty() function here for better clarity
                                    if (!empty($qbValueIdRow[0])) {
                                        $updateBillMainStatusQuery = "UPDATE bill_main SET `qb_status` = 1 WHERE `invoice_no` = '$invoice_no'";
                                        if (mysqli_query($conn, $updateBillMainStatusQuery)) {
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    }
                                }
                                return true;
                            }
                        }
                    }
                }
            } else {
                logQbError($conn, $action_name, $qb_msg, $invoice_no);
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $invoice_no);
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handleShipmentInsert($conn, $batch_id, $action_name)
{
    // Fetch all journal entries for the given batch from the qb_queue table
    $query = "SELECT `id`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`, `entity_name`, `shipment_no` FROM qb_queue
          WHERE `batch_id` = '$batch_id'";

    $result = mysqli_query($conn, $query);

    // Exit if no records are found
    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    // Initialize arrays for journal entries and queue IDs
    $journal_entries = array();
    $queue_ids = array();
    $shipment_no = null;

    // Process results if any rows are found
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $queue_ids[] = $row['id'];
            $shipment_no = $row['shipment_no'];

            $journal_entries[] = array(
                "posting_type" => $row['posting_type'],
                "account_id" => $row['account_id'],
                "account_name" => $row['account_name'],
                "amount" => $row['amount'],
                "description" => $row['description'],
                "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
                "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null,
                "entity_name" => isset($row['entity_name']) ? $row['entity_name'] : null
            );
        }

        // Only proceed if there are multiple journal entries
        if (count($journal_entries) > 1) {
            try {
                // Attempt to add journal entries to QuickBooks
                $journal_entry_result = QBAddJournalEntry($journal_entries);
                $qb_msg = mysqli_real_escape_string($conn, isset($journal_entry_result['message']) ? $journal_entry_result['message'] : 'Unknown error');

                // Check if QuickBooks returned success and a valid journal entry ID
                if (isset($journal_entry_result['status']) && $journal_entry_result['status'] == 'success') {
                    $qb_journal_entry_id = $journal_entry_result['qb_journal_entry_id'];

                    // Update shipment record with QuickBooks Journal Entry ID
                    if ($qb_journal_entry_id != '') {
                        $update_query = "UPDATE shipment_main SET `qb_id`='$qb_journal_entry_id', `qb_status` = 1 WHERE `id`='$shipment_no'";
                        $update_result = mysqli_query($conn, $update_query);

                        if (!$update_result) {
                            $failed_ids_str = implode(",", $queue_ids); // Convert failed IDs to a string for the SQL query
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue); // Update status for the failed entries

                            // Log error if update fails
                            logQbError($conn, $action_name, "QuickBooks shipment main id update error: Journal Entry ID: " . $qb_journal_entry_id, $shipment_no);
                            return false;
                        } else {
                            // Attempt to delete each entry individually and track successes and failures
                            $failed_ids = array(); // Array to store failed IDs
                            foreach ($queue_ids as $id) {
                                $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                                if (!mysqli_query($conn, $delete_query)) {
                                    // If delete fails, add to failed_ids array
                                    $failed_ids[] = $id;
                                }
                            }
                            // If there are failed entries, update their status
                            if (count($failed_ids) > 0) {
                                $failed_ids_str = implode(",", $failed_ids); // Convert failed IDs to a string for the SQL query
                                $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                                mysqli_query($conn, $update_deleted_queue); // Update status for the failed entries

                                // Log an error if there are any failed deletions
                                logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $shipment_no);
                                return false;
                            } else {
                                return true;
                            }
                        }
                    }
                } else {
                    // Log the error message if QuickBooks returns an error
                    logQbError($conn, $action_name, $qb_msg, $shipment_no);
                    updateQueueStatus($conn, $batch_id, 1); // Mark the batch as failed
                    return false;
                }
            } catch (Exception $e) {
                // If an exception occurs, log the error and update the queue status
                logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $shipment_no);
                updateQueueStatus($conn, $batch_id, 1); // Mark the batch as failed
                return false;
            }
        }
    }
}

function handleShipmentPayInsert($conn, $batch_id, $action_name)
{
    // Fetch all journal entries for this batch from the qb_queue table
    $query = "SELECT `id`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`, `entity_name`, `shipment_no`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    // Initialize arrays to store journal entries and queue IDs for cleanup
    $journal_entries = array();
    $queue_ids = array();
    $shipment_no = null;

    // Loop through each record and collect journal entries and queue IDs
    while ($row = mysqli_fetch_assoc($result)) {
        $queue_ids[] = $row['id'];
        $shipment_no = $row['shipment_no'];
        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null,
            "entity_name" => isset($row['entity_name']) ? $row['entity_name'] : null
        );
    }

    // Ensure we have multiple journal entries before proceeding
    if (count($journal_entries) > 1) {
        try {
            // Attempt to add journal entries to QuickBooks
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            // Check if the QuickBooks API call was successful
            if (!empty($qb_result['status']) && $qb_result['status'] == 'success' && !empty($qb_result['qb_journal_entry_id'])) {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                // Update shipment_pay table with QuickBooks Journal Entry ID
                if (!empty($qb_journal_entry_id)) {
                    $update_query = "UPDATE shipment_pay SET `qb_id` = '$qb_journal_entry_id', `qb_status` = 1 WHERE `shipment_no` = '$shipment_no'";
                    $update_result = mysqli_query($conn, $update_query);

                    if (!$update_result) {
                        $failed_ids_str = implode(",", $queue_ids); // Convert failed IDs to a string for the SQL query
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue); // Update status for the failed entries

                        // Log error if update fails
                        logQbError($conn, $action_name, "QuickBooks shipment main qb_id update error. Journal Entry ID: " . $qb_journal_entry_id, $shipment_no);
                        return false;
                    } else {
                        // Attempt to delete each entry individually and track successes and failures
                        $failed_ids = array(); // Array to store failed IDs

                        foreach ($queue_ids as $id) {
                            $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                            if (!mysqli_query($conn, $delete_query)) {
                                // If delete fails, add to failed_ids array
                                $failed_ids[] = $id;
                            }
                        }
                        // If there are failed entries, update their status
                        if (count($failed_ids) > 0) {
                            $failed_ids_str = implode(",", $failed_ids); // Convert failed IDs to a string for the SQL query
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue); // Update status for the failed entries

                            // Log an error if there are any failed deletions
                            logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $shipment_no);
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            } else {
                // Log the error message if QuickBooks returns an error
                logQbError($conn, $action_name, $qb_msg, $shipment_no);
                updateQueueStatus($conn, $batch_id, 1); // Mark the batch as failed
                return false;
            }
        } catch (Exception $e) {
            // Catch any exceptions, log the error, and update the queue status
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $shipment_no);
            updateQueueStatus($conn, $batch_id, 1); // Mark the batch as failed
            return false;
        }
    }
}

function handleShipmentPayDelete($conn, $batch_id, $action_name)
{
    // Fetch all journal entries for the given batch from qb_queue
    $query = "SELECT `id`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`, `entity_name`, `shipment_no`,`shipment_pay_id`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    // Initialize arrays for journal entries and queue IDs
    $journal_entries = array();
    $queue_ids = array();
    $shipment_no = null;
    $shipment_pay_id = null;

    // Loop through each row of the result set
    while ($row = mysqli_fetch_assoc($result)) {
        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null,
            "entity_name" => isset($row['entity_name']) ? $row['entity_name'] : null
        );
        $queue_ids[] = $row['id']; // Collect queue IDs for cleanup
        $shipment_no = $row['shipment_no'];
        $shipment_pay_id = $row['shipment_pay_id'];
    }

    // Ensure we have multiple journal entries before proceeding
    if (count($journal_entries) > 1) {
        try {
            // Attempt to add journal entries to QuickBooks
            $journal_entry_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($journal_entry_result['message']) ? $journal_entry_result['message'] : 'Unknown error');

            // If QuickBooks returns success, process the journal entry and update the journal ID
            if (!empty($journal_entry_result['status']) && $journal_entry_result['status'] == 'success' && !empty($journal_entry_result['qb_journal_entry_id'])) {
                $qb_journal_entry_id = $journal_entry_result['qb_journal_entry_id'];

                if ($qb_journal_entry_id != '') {
                    // Attempt to delete each entry individually and track successes and failures
                    $failed_ids = array(); // Array to store failed IDs

                    foreach ($queue_ids as $id) {
                        $delete_query = "DELETE FROM `qb_queue` WHERE `id` = $id";
                        if (!mysqli_query($conn, $delete_query)) {
                            // If delete fails, add to failed_ids array
                            $failed_ids[] = $id;
                        }
                    }
                    // If there are failed entries, update their status
                    if (count($failed_ids) > 0) {
                        $failed_ids_str = implode(",", $failed_ids); // Convert failed IDs to a string for the SQL query
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue); // Update status for the failed entries
                        $doc_no = "shipment_pay_no :" . $shipment_pay_id . " - shipment_no:" . $shipment_no;
                        // Log an error if there are any failed deletions
                        logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $doc_no);
                        return false;
                    } else {
                        return true;
                    }
                }
            } else {
                $doc_no = "shipment_pay_no :" . $shipment_pay_id . " - shipment_no:" . $shipment_no;
                // If QuickBooks returns an error, log the error and update the queue status
                logQbError($conn, $action_name, $qb_msg, $doc_no);
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            // If an exception occurs, log the error and update the queue status
            $doc_no = "shipment_pay_no :" . $shipment_pay_id . " - shipment_no:" . $shipment_no;
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $doc_no);
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handleClearChequeInsertThroughPaymentDeposit($conn, $batch_id, $action_name)
{
    // try {
    // 	$journal_entry_result = QBAddJournalEntry($journalEntryForCheque);
    // 	$qb_msg = $journal_entry_result['message'];
    // 	if (isset($journal_entry_result['status']) && ($journal_entry_result['status'] == 'success')) {
    // 		$qb_journal_entry_id = $journal_entry_result['qb_journal_entry_id'];
    // 		$query = "UPDATE `payment_deposit` SET `qb_id`='$qb_journal_entry_id' WHERE `payment_id`='$id'";
    // 		$result1 = mysqli_query($conn, $query);
    // 		if (!$result1) {
    // 			$out = false;
    // 			$qb_msg = "QuickBooks payment ID update error";
    // 			$responses[] = ['success' => $out, 'message' => $qb_msg, 'chequeId' => $id];
    // 			throw new Exception($qb_msg);
    // 		} else {
    // 			$qb_msg = "Journal Entry successfully recorded in QuickBooks. Cheque Number: $cheque_no";
    // 			$responses[] = ['success' => true, 'message' => "Status updated successfully. $qb_msg", 'chequeId' => $id];
    // 		}
    // 	} else {
    // 		$out = false;
    // 		$responses[] = ['success' => $out, 'message' => $qb_msg, 'chequeId' => $id];
    // 		throw new Exception($qb_msg);
    // 	}
    // } catch (Exception $e) {
    // 	$out = false;
    // 	$qb_msg = "<br> QuickBooks Error: Unable to record the cheque. Cheque Number: $cheque_no, Error: " . $error->getResponseBody();
    // 	$responses[] = ['success' => $out, 'message' => $qb_msg, 'chequeId' => $id];
    // 	$journal_entry_result['status'] = 'error';
    // 	throw new Exception($qb_msg);
    // }

    // Fetch all records for the given batch from the qb_queue
    $query = "SELECT `id`, `payment_id`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    // Initialize variables
    $payment_id = null;
    $journal_entries = array();
    $queue_ids = array();

    // Loop through each row from the result set
    while ($row = mysqli_fetch_assoc($result)) {
        $payment_id = $row['payment_id']; // Set payment ID
        $queue_ids[] = $row['id']; // Collect queue IDs for cleanup

        // Append journal entry details to journal_entries array
        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null
        );
    }

    // Ensure that we have multiple journal entries before proceeding
    if (count($journal_entries) > 1) {
        try {
            // Attempt to add journal entries to QuickBooks
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            // Check if the QuickBooks API call was successful
            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if (!empty($qb_journal_entry_id)) {
                    $update_query = "UPDATE `payment` SET `qb_cheque_clear_id` = '$qb_journal_entry_id' WHERE `id` = '$payment_id'";
                    $update_result = mysqli_query($conn, $update_query);

                    if (!$update_result) {
                        $failed_ids_str = implode(",", $queue_ids); // Convert failed IDs to a string for the SQL query
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue); // Update status for the failed entries
                        // Log error if update fails
                        logQbError($conn, $action_name, "QuickBooks payment qb_cheque_clear_id update error. Journal Entry ID: " . $qb_journal_entry_id, $payment_id);
                        return false;
                    } else {
                        // Attempt to delete each entry individually and track successes and failures
                        $failed_ids = array(); // Array to store failed IDs

                        foreach ($queue_ids as $id) {
                            $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                            if (!mysqli_query($conn, $delete_query)) {
                                // If delete fails, add to failed_ids array
                                $failed_ids[] = $id;
                            }
                        }
                        // If there are failed entries, update their status
                        if (count($failed_ids) > 0) {
                            $failed_ids_str = implode(",", $failed_ids);
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue);

                            // Log an error if there are any failed deletions
                            logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $payment_id);
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            } else {
                // If QuickBooks returned an error, log the error and update queue status
                logQbError($conn, $action_name, $qb_msg, $payment_id);
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            // Log the exception message and update queue status
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $payment_id);
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handleDeleteInvoiceCost($conn, $batch_id, $action_name)
{
    // $journal_entry_array = array('id' => $qb_cost_id);
    // try {
    // 	$qb_result = QBDeleteJournalEntry($journal_entry_array);
    // 	$qb_msg = $qb_result['message'];
    // 	if (isset($qb_result['status']) && ($qb_result['status'] != 'success')) {
    // 		$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
    // 		$action = "CUST ODR INVOICE, JOURNAL ENTRY DELETE (Invoice Cost) : Invoice No: $invoice_no";
    // 		$functionName = "setStatus";
    // 		$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 		VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 		$result = mysqli_query($conn, $error_log_query);
    // 	}
    // } catch (Exception $e) {
    // 	$flag = false;
    // 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
    // 	$qb_msg = mysqli_real_escape_string($conn, $qb_msg);

    // 	$action = "CUST ODR INVOICE, JOURNAL ENTRY DELETE (Invoice Cost) : Invoice No: $invoice_no";
    // 	$functionName = "setStatus";
    // 	$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
    // 	VALUES ('$action','$functionName','$qb_msg','$time_now')";
    // 	$result = mysqli_query($conn, $error_log_query);
    // }

    // Fetch all records for the given batch
    $query = "SELECT `id`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `description`, `entity_type`, `entity_id`, `entity_name`, `invoice_cost`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    $invoice_no = null;
    $journal_entries = array();
    $queue_ids = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $invoice_no = $row['invoice_no'];
        $queue_ids[] = $row['id'];

        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['invoice_cost'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null,
            "entity_name" => isset($row['entity_name']) ? $row['entity_name'] : null
        );
    }

    if (count($journal_entries) > 1) {
        try {
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if ($qb_journal_entry_id != '') {
                    $update_query = "UPDATE bill_main SET `qb_deleted_cost_id` = '$qb_journal_entry_id' WHERE `invoice_no` = '$invoice_no'";
                    $update_result = mysqli_query($conn, $update_query);

                    if (!$update_result) {
                        $failed_ids_str = implode(",", $queue_ids);
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue);
                        logQbError($conn, $action_name, "QuickBooks bill_main qb_deleted_cost_id update error. Journal Entry ID: " . $qb_journal_entry_id, $invoice_no);
                    } else {
                        $failed_ids = array();
                        foreach ($queue_ids as $id) {
                            $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                            if (!mysqli_query($conn, $delete_query)) {
                                $failed_ids[] = $id;
                            }
                        }
                        if (count($failed_ids) > 0) {
                            $failed_ids_str = implode(",", $failed_ids);
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue);
                            logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $invoice_no);
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            } else {
                logQbError($conn, $action_name, $qb_msg, $invoice_no);
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $invoice_no);
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handleBillConvert($conn, $batch_id, $action_name)
{
    // Fetch all records for the given batch
    $query = "SELECT `id`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `description`, `entity_type`, `entity_id`, `entity_name`, `amount`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    $invoice_no = null;
    $journal_entries = array();
    $queue_ids = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $invoice_no = $row['invoice_no'];
        $queue_ids[] = $row['id'];

        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null,
            "entity_name" => isset($row['entity_name']) ? $row['entity_name'] : null
        );
    }

    if (count($journal_entries) > 1) {
        try {
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if ($qb_journal_entry_id != '') {
                    $update_query = "UPDATE bill_main SET `qb_sales_cust_odr_id` = '$qb_journal_entry_id' WHERE `invoice_no` = '$invoice_no'";
                    $update_result = mysqli_query($conn, $update_query);

                    if (!$update_result) {
                        $failed_ids_str = implode(",", $queue_ids);
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue);
                        logQbError($conn, $action_name, "QuickBooks bill_main qb_sales_cust_odr_id update error. Journal Entry ID: " . $qb_journal_entry_id, $invoice_no);
                    } else {
                        $failed_ids = array();
                        foreach ($queue_ids as $id) {
                            $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                            if (!mysqli_query($conn, $delete_query)) {
                                $failed_ids[] = $id;
                            }
                        }
                        if (count($failed_ids) > 0) {
                            $failed_ids_str = implode(",", $failed_ids);
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue);
                            logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $invoice_no);
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            } else {
                logQbError($conn, $action_name, $qb_msg, $invoice_no);
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $invoice_no);
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handleQtyAdjustInsert($conn, $batch_id, $action_name)
{
    // Fetch all journal entries for this batch
    $query = "SELECT `id`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    $journal_entries = array();
    $queue_ids = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $queue_ids[] = $row['id'];

        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null
        );
    }

    if (count($journal_entries) > 1) {
        try {
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if ($qb_journal_entry_id != '') {
                    $failed_ids = array();
                    foreach ($queue_ids as $id) {
                        $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                        if (!mysqli_query($conn, $delete_query)) {
                            $failed_ids[] = $id;
                        }
                    }
                    if (count($failed_ids) > 0) {
                        $failed_ids_str = implode(",", $failed_ids);
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue);
                        logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", 'Adjust QTY');
                        return false;
                    } else {
                        return true;
                    }
                }
            } else {
                logQbError($conn, $action_name, $qb_msg, '');
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), 'Adjust QTY');
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handleBankDepositInsert($conn, $batch_id, $action_name)
{
    // try {
    // 	$journal_entry_result = QBAddJournalEntry($result_array);
    // 	$qb_msg = $journal_entry_result['message'];

    // 	if ((isset($journal_entry_result['status'])) && ($journal_entry_result['status'] == 'success')) {
    // 		$qb_journal_entry_id = $journal_entry_result['qb_journal_entry_id'];
    // 		$query = "UPDATE `payment` SET `qb_id`='$qb_journal_entry_id', `qb_status`=1, `status` =0 WHERE `id`='$payment_id'";
    // 		$result1 = mysqli_query($conn, $query);
    // 		if (!$result1) {
    // 			$qb_msg = 'QuickBooks payment ID update error.';
    // 			throw new Exception($qb_msg);
    // 		} else {
    // 			$bank_payment_updated = true;
    // 		}
    // 	} else {
    // 		throw new Exception($qb_msg);
    // 	}
    // } catch (Exception $e) {
    // 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
    // 	$journal_entry_result['status'] = 'error';
    // 	throw new Exception($qb_msg);
    // }

    // Fetch all records for the given batch from the qb_queue
    $query = "SELECT `id`, `payment_id`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    $payment_id = null;
    $journal_entries = array();
    $queue_ids = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $payment_id = $row['payment_id'];
        $queue_ids[] = $row['id'];

        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null
        );
    }

    if (count($journal_entries) > 1) {
        try {
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if ($qb_journal_entry_id != '') {
                    $update_query = "UPDATE `payment` SET `qb_id`='$qb_journal_entry_id', `qb_status`=1, `status`=0 WHERE `id`='$payment_id'";
                    $update_result = mysqli_query($conn, $update_query);

                    if (!$update_result) {
                        $failed_ids_str = implode(",", $queue_ids);
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue);
                        logQbError($conn, $action_name, "QuickBooks payment_deposit qb_id update error. Journal Entry ID: " . $qb_journal_entry_id, $payment_id);
                        return false;
                    } else {
                        $failed_ids = array();
                        foreach ($queue_ids as $id) {
                            $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                            if (!mysqli_query($conn, $delete_query)) {
                                $failed_ids[] = $id;
                            }
                        }
                        if (count($failed_ids) > 0) {
                            $failed_ids_str = implode(",", $failed_ids);
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue);
                            logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $payment_id);
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            } else {
                logQbError($conn, $action_name, $qb_msg, $payment_id);
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $payment_id);
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handleCashOnHandDepositInsert($conn, $batch_id, $action_name)
{
    // try {
    // 	$journal_entry_result = QBAddJournalEntry($journalEntryForBankDeposit);
    // 	$qb_msg = $journal_entry_result['message'];
    // 	if ((isset($journal_entry_result['status'])) && ($journal_entry_result['status'] == 'success')) {
    // 		$qb_journal_entry_id = $journal_entry_result['qb_journal_entry_id'];
    // 		$query = "UPDATE `payment_deposit` SET `qb_id`='$qb_journal_entry_id' WHERE `id`='$deposit_id'";
    // 		$result1 = mysqli_query($conn, $query);
    // 		if (!$result1) {
    // 			$qb_msg = 'Error: QuickBooks payment ID update error.';
    // 			throw new Exception($qb_msg);
    // 		}
    // 	} else {
    // 		throw new Exception($qb_msg);
    // 	}
    // } catch (Exception $e) {
    // 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
    // 	$journal_entry_result['status'] = 'error';
    // 	throw new Exception($qb_msg);
    // }

    // Fetch all records for the given batch from the qb_queue
    $query = "SELECT `id`, `payment_id`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    $payment_id = null;
    $journal_entries = array();
    $queue_ids = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $payment_id = $row['payment_id'];
        $queue_ids[] = $row['id'];

        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null
        );
    }

    if (count($journal_entries) > 1) {
        try {
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if ($qb_journal_entry_id != '') {
                    $update_query = "UPDATE `payment_deposit` SET `qb_id` = '$qb_journal_entry_id' WHERE id = '$payment_id'";
                    $update_result = mysqli_query($conn, $update_query);

                    if (!$update_result) {
                        $failed_ids_str = implode(",", $queue_ids); // Convert failed IDs to a string for the SQL query
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE id IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue); // Update status for the failed entries
                        // Log error if update fails
                        logQbError($conn, $action_name, "QuickBooks payment_deposit qb_id update error. Journal Entry ID: " . $qb_journal_entry_id, $payment_id);
                        return false;
                    } else {
                        $failed_ids = array();
                        foreach ($queue_ids as $id) {
                            $delete_query = "DELETE FROM qb_queue WHERE id = $id";
                            if (!mysqli_query($conn, $delete_query)) {
                                $failed_ids[] = $id;
                            }
                        }
                        if (count($failed_ids) > 0) {
                            $failed_ids_str = implode(",", $failed_ids);
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE id IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue);
                            logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $payment_id);
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            } else {
                logQbError($conn, $action_name, $qb_msg, $payment_id);
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $payment_id);
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handleClearChequeInsert($conn, $batch_id, $action_name)
{
    // Fetch all records for the given batch from the qb_queue
    $query = "SELECT `id`, `payment_id`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    // Initialize variables
    $payment_id = null;
    $journal_entries = array();
    $queue_ids = array();

    // Loop through each row from the result set
    while ($row = mysqli_fetch_assoc($result)) {
        $payment_id = $row['payment_id']; // Set payment ID
        $queue_ids[] = $row['id']; // Collect queue IDs for cleanup

        // Append journal entry details to journal_entries array
        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null
        );
    }

    // Ensure that we have multiple journal entries before proceeding
    if (count($journal_entries) > 1) {
        try {
            // Attempt to add journal entries to QuickBooks
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            // Check if the QuickBooks API call was successful
            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if (!empty($qb_journal_entry_id)) {
                    $update_query = "UPDATE payment SET `qb_cheque_clear_id` = '$qb_journal_entry_id' WHERE `id` = '$payment_id'";
                    $update_result = mysqli_query($conn, $update_query);

                    if (!$update_result) {
                        $failed_ids_str = implode(",", $queue_ids); // Convert failed IDs to a string for the SQL query
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue); // Update status for the failed entries

                        // Log error if update fails
                        logQbError($conn, $action_name, "QuickBooks payment qb_cheque_clear_id update error. Journal Entry ID: " . $qb_journal_entry_id, $payment_id);
                        return false;
                    } else {
                        // Attempt to delete each entry individually and track successes and failures
                        $failed_ids = array(); // Array to store failed IDs

                        foreach ($queue_ids as $id) {
                            $delete_query = "DELETE FROM qb_queue WHERE `id` = $id";
                            if (!mysqli_query($conn, $delete_query)) {
                                // If delete fails, add to failed_ids array
                                $failed_ids[] = $id;
                            }
                        }
                        // If there are failed entries, update their status
                        if (count($failed_ids) > 0) {
                            $failed_ids_str = implode(",", $failed_ids);
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue);

                            // Log an error if there are any failed deletions
                            logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $payment_id);
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            } else {
                // If QuickBooks returned an error, log the error and update queue status
                logQbError($conn, $action_name, $qb_msg, $payment_id);
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            // Log the exception message and update queue status
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $payment_id);
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handleReturnChequeInsert($conn, $batch_id, $action_name)
{
    // Fetch all records for the given batch from the qb_queue
    $query = "SELECT id, payment_id, posting_type, account_id, account_name, amount, description, entity_type, entity_id
          FROM qb_queue WHERE batch_id = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    // Initialize variables
    $payment_id = null;
    $journal_entries = array();
    $queue_ids = array();

    // Loop through each row from the result set
    while ($row = mysqli_fetch_assoc($result)) {
        $payment_id = $row['payment_id']; // Set payment ID
        $queue_ids[] = $row['id']; // Collect queue IDs for cleanup

        // Append journal entry details to journal_entries array
        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null
        );
    }

    // Ensure that we have multiple journal entries before proceeding
    if (count($journal_entries) > 1) {
        try {
            // Attempt to add journal entries to QuickBooks
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            // Check if the QuickBooks API call was successful
            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if (!empty($qb_journal_entry_id)) {
                    $update_query = "UPDATE payment SET `qb_cheque_return_id` = '$qb_journal_entry_id' WHERE `id` = '$payment_id'";
                    $update_result = mysqli_query($conn, $update_query);

                    if (!$update_result) {
                        $failed_ids_str = implode(",", $queue_ids); // Convert failed IDs to a string for the SQL query
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE `id` IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue); // Update status for the failed entries

                        // Log error if update fails
                        logQbError($conn, $action_name, "QuickBooks payment qb_cheque_return_id update error. Journal Entry ID: " . $qb_journal_entry_id, $payment_id);
                        return false;
                    } else {
                        // Attempt to delete each entry individually and track successes and failures
                        $failed_ids = array(); // Array to store failed IDs

                        foreach ($queue_ids as $id) {
                            $delete_query = "DELETE FROM qb_queue WHERE id = $id";
                            if (!mysqli_query($conn, $delete_query)) {
                                // If delete fails, add to failed_ids array
                                $failed_ids[] = $id;
                            }
                        }
                        // If there are failed entries, update their status
                        if (count($failed_ids) > 0) {
                            $failed_ids_str = implode(",", $failed_ids); // Convert failed IDs to a string for the SQL query
                            $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE id IN ($failed_ids_str)";
                            mysqli_query($conn, $update_deleted_queue); // Update status for the failed entries

                            // Log an error if there are any failed deletions
                            logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $payment_id);
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            } else {
                // If QuickBooks returned an error, log the error and update queue status
                logQbError($conn, $action_name, $qb_msg, $payment_id);
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            // Log the exception message and update queue status
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $payment_id);
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handleJournalEntryInsert($conn, $batch_id, $action_name)
{
    // Fetch all journal entries for the given batch from qb_queue
    $query = "SELECT `id`, `journal_id`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`, `entity_name`
          FROM qb_queue WHERE `batch_id` = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    // Initialize arrays for journal entries and queue IDs
    $journal_entries = array();
    $queue_ids = array();
    $journal_id = null;

    // Loop through each row of the result set
    while ($row = mysqli_fetch_assoc($result)) {
        $journal_id = $row['journal_id'];
        $queue_ids[] = $row['id']; // Collect queue IDs for cleanup

        // Collect journal entries data
        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null,
            "entity_name" => isset($row['entity_name']) ? $row['entity_name'] : null
        );
    }

    // Ensure we have multiple journal entries before proceeding
    if (count($journal_entries) > 1) {
        try {
            // Attempt to add journal entries to QuickBooks
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            // If QuickBooks returns success, update the journal entry
            if (!empty($qb_result['status']) && $qb_result['status'] == 'success' && !empty($qb_result['qb_journal_entry_id'])) {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                // Update journal_main table with QuickBooks Journal Entry ID
                $update_query = "UPDATE journal_main SET qb_id = '$qb_journal_entry_id' WHERE journal_id = '$journal_id'";
                $update_result = mysqli_query($conn, $update_query);

                if (!$update_result) {
                    $failed_ids_str = implode(",", $queue_ids); // Convert failed IDs to a string for the SQL query
                    $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE id IN ($failed_ids_str)";
                    mysqli_query($conn, $update_deleted_queue); // Update status for the failed entries
                    // Log error if updating journal_main fails
                    logQbError($conn, $action_name, "QuickBooks journal entry ID update error. Journal Entry ID: " . $qb_journal_entry_id, $journal_id);
                    return false;
                } else {
                    // Attempt to delete each entry individually and track successes and failures
                    $failed_ids = array(); // Array to store failed IDs

                    foreach ($queue_ids as $id) {
                        $delete_query = "DELETE FROM qb_queue WHERE id = $id";
                        if (!mysqli_query($conn, $delete_query)) {
                            // If delete fails, add to failed_ids array
                            $failed_ids[] = $id;
                        }
                    }
                    // If there are failed entries, update their status
                    if (count($failed_ids) > 0) {
                        $failed_ids_str = implode(",", $failed_ids); // Convert failed IDs to a string for the SQL query
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE id IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue); // Update status for the failed entries

                        // Log an error if there are any failed deletions
                        logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", $journal_id);
                        return false;
                    } else {
                        return true;
                    }
                }
            } else {
                // Log error if QuickBooks returns an error
                logQbError($conn, $action_name, $qb_msg, $journal_id);
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            // Log the exception message and update the queue status
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), $journal_id);
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function logQbError($conn, $action_name, $error_message, $doc_number)
{
    $time_now = timeNow();
    $escaped_message = mysqli_real_escape_string($conn, $error_message);
    $error_log_query = "INSERT INTO qb_queue_error_log (`action`, `error`, `doc_number`,`created_at`)
                        VALUES ('$action_name', '$escaped_message', '$doc_number','$time_now')";
    mysqli_query($conn, $error_log_query);
}

function updateQueueStatus($conn, $batch_id, $status)
{
    $update_query = "UPDATE `qb_queue` SET `status` = '$status' WHERE `batch_id` = '$batch_id'";
    mysqli_query($conn, $update_query);
}

function handleReturnItemMoveToInventoryInsert($conn, $batch_id, $action_name)
{
    // Fetch all records for the given batch from the qb_queue
    $query = "SELECT id, posting_type, account_id, account_name, amount, description, entity_type, entity_id
          FROM qb_queue WHERE batch_id = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    // Initialize variables
    $journal_entries = array();
    $queue_ids = array();

    // Loop through each row from the result set
    while ($row = mysqli_fetch_assoc($result)) {
        $queue_ids[] = $row['id']; // Collect queue IDs for cleanup

        // Append journal entry details to journal_entries array
        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null
        );
    }

    // Ensure that we have multiple journal entries before proceeding
    if (count($journal_entries) > 1) {
        try {
            // Attempt to add journal entries to QuickBooks
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            // Check if the QuickBooks API call was successful
            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if (!empty($qb_journal_entry_id)) {
                    // Attempt to delete each entry individually and track successes and failures
                    $failed_ids = array(); // Array to store failed IDs

                    foreach ($queue_ids as $id) {
                        $delete_query = "DELETE FROM qb_queue WHERE id = $id";
                        if (!mysqli_query($conn, $delete_query)) {
                            // If delete fails, add to failed_ids array
                            $failed_ids[] = $id;
                        }
                    }
                    // If there are failed entries, update their status
                    if (count($failed_ids) > 0) {
                        $failed_ids_str = implode(",", $failed_ids); // Convert failed IDs to a string for the SQL query
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE id IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue); // Update status for the failed entries

                        // Log an error if there are any failed deletions
                        logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", "");
                        return false;
                    } else {
                        return true;
                    }

                }
            } else {
                // If QuickBooks returned an error, log the error and update queue status
                logQbError($conn, $action_name, $qb_msg, "");
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            // Log the exception message and update queue status
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), "");
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function handleEditItemQty($conn, $batch_id, $action_name)
{
    // Fetch all records for the given batch from the qb_queue
    $query = "SELECT id, posting_type, account_id, account_name, amount, description, entity_type, entity_id
          FROM qb_queue WHERE batch_id = '$batch_id'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    // Initialize variables
    $journal_entries = array();
    $queue_ids = array();

    // Loop through each row from the result set
    while ($row = mysqli_fetch_assoc($result)) {
        $queue_ids[] = $row['id']; // Collect queue IDs for cleanup

        // Append journal entry details to journal_entries array
        $journal_entries[] = array(
            "posting_type" => $row['posting_type'],
            "account_id" => $row['account_id'],
            "account_name" => $row['account_name'],
            "amount" => $row['amount'],
            "description" => $row['description'],
            "entity_type" => isset($row['entity_type']) ? $row['entity_type'] : null,
            "entity_id" => isset($row['entity_id']) ? $row['entity_id'] : null
        );
    }

    // Ensure that we have multiple journal entries before proceeding
    if (count($journal_entries) > 1) {
        try {
            // Attempt to add journal entries to QuickBooks
            $qb_result = QBAddJournalEntry($journal_entries);
            $qb_msg = mysqli_real_escape_string($conn, isset($qb_result['message']) ? $qb_result['message'] : 'Unknown error');

            // Check if the QuickBooks API call was successful
            if (isset($qb_result['status']) && $qb_result['status'] == 'success') {
                $qb_journal_entry_id = $qb_result['qb_journal_entry_id'];

                if (!empty($qb_journal_entry_id)) {
                    // Attempt to delete each entry individually and track successes and failures
                    $failed_ids = array(); // Array to store failed IDs

                    foreach ($queue_ids as $id) {
                        $delete_query = "DELETE FROM qb_queue WHERE id = $id";
                        if (!mysqli_query($conn, $delete_query)) {
                            // If delete fails, add to failed_ids array
                            $failed_ids[] = $id;
                        }
                    }
                    // If there are failed entries, update their status
                    if (count($failed_ids) > 0) {
                        $failed_ids_str = implode(",", $failed_ids); // Convert failed IDs to a string for the SQL query
                        $update_deleted_queue = "UPDATE qb_queue SET `status` = 2 WHERE id IN ($failed_ids_str)";
                        mysqli_query($conn, $update_deleted_queue); // Update status for the failed entries

                        // Log an error if there are any failed deletions
                        logQbError($conn, $action_name, "Error deleting processed entries for IDs: $failed_ids_str", "");
                        return false;
                    } else {
                        return true;
                    }
                }
            } else {
                // If QuickBooks returned an error, log the error and update queue status
                logQbError($conn, $action_name, $qb_msg, "");
                updateQueueStatus($conn, $batch_id, 1);
                return false;
            }
        } catch (Exception $e) {
            // Log the exception message and update queue status
            logQbError($conn, $action_name, mysqli_real_escape_string($conn, $e->getMessage()), "");
            updateQueueStatus($conn, $batch_id, 1);
            return false;
        }
    }
}

function processQBQueue($conn)
{
    if (!isQuickBooksActive(1)) {
        return [
            'status' => 'error',
            'message' => 'QuickBooks integration is not active.',
            'batch_id' => null,
            'action' => null,
            'poll_interval' => 300
        ];
    }
    // Default response when nothing is processed
    $response = [
        'status' => 'empty', // Indicate that the queue is empty
        'message' => 'No pending batches found. Please check back after 5 minutes.',
        'batch_id' => null,
        'action' => null,
        'poll_interval' => 300 // e.g., 300 seconds (5 minutes)
    ];

    $batchQuery = "SELECT DISTINCT batch_id
    FROM qb_queue
    WHERE status IN (0, 1)
    ORDER BY status ASC, id ASC
    LIMIT 1 FOR UPDATE";

    $batchResult = mysqli_query($conn, $batchQuery);
    if ($batchResult && $batchRow = mysqli_fetch_assoc($batchResult)) {
        $batch_id = $batchRow['batch_id'];
        mysqli_free_result($batchResult);

        $response['batch_id'] = $batch_id;
        $response['message'] = "Attempting to process batch " . $batch_id . "...";

        $updateLockQuery = "UPDATE qb_queue
             SET status = 3
             WHERE batch_id = '$batch_id'
               AND status IN (0, 1)";
        $updateLockResult = mysqli_query($conn, $updateLockQuery);

        if ($updateLockResult && mysqli_affected_rows($conn) > 0) {
            // Fetch the action for this batch
            $query = "SELECT `action` FROM qb_queue WHERE batch_id = '$batch_id' LIMIT 1";
            $result = mysqli_query($conn, $query);

            if ($row = mysqli_fetch_assoc($result)) {
                $action_name = isset($row['action']) ? $row['action'] : '';
                $response['action'] = $action_name;

                $handlerSuccess = false; // Assume failure until proven otherwise

                switch ($action_name) {
                    case 'shipment_insert': // INV
                        $handlerSuccess = handleShipmentInsert($conn, $batch_id, $action_name);
                        break;
                    case 'journal_entry_insert': // FIN
                        $handlerSuccess = handleJournalEntryInsert($conn, $batch_id, $action_name);
                        break;
                    case 'shipment_pay_insert': // MGR
                        $handlerSuccess = handleShipmentPayInsert($conn, $batch_id, $action_name);
                        break;
                    case 'shipment_pay_delete': // MGR
                        $handlerSuccess = handleShipmentPayDelete($conn, $batch_id, $action_name);
                        break;
                    case 'clear_cheque_insert': // MGR
                        $handlerSuccess = handleClearChequeInsert($conn, $batch_id, $action_name);
                        break;
                    case 'clear_cheque_through_payment_deposit_insert': // MGR
                        $handlerSuccess = handleClearChequeInsertThroughPaymentDeposit($conn, $batch_id, $action_name);
                        break;
                    case 'cash_on_hand_deposit_insert': // MGR
                        $handlerSuccess = handleCashOnHandDepositInsert($conn, $batch_id, $action_name);
                        break;
                    case 'bank_deposit_insert': // MGR
                        $handlerSuccess = handleBankDepositInsert($conn, $batch_id, $action_name);
                        break;
                    case 'return_cheque_insert': // MGR
                        $handlerSuccess = handleReturnChequeInsert($conn, $batch_id, $action_name);
                        break;
                    case 'adjust_qty_insert': // MGR
                        $handlerSuccess = handleQtyAdjustInsert($conn, $batch_id, $action_name);
                        break;
                    case 'return_invoice_insert_through_odr_insert': // ODR
                        $handlerSuccess = handleReturnInvoiceInsertThroughODRInsert($conn, $batch_id, $action_name);
                        break;
                    case 'invoice_insert_through_odr_insert': // ODR // cost and total same update in one function depend on cost or total value
                        $handlerSuccess = handleInvoiceInsertThroughODRInsert($conn, $batch_id, $action_name);
                        break;
                    case 'delete_invoice_cost_through_odr_insert': // ODR
                        $handlerSuccess = handleDeleteInvoiceCost($conn, $batch_id, $action_name);
                        break;
                    case 'return_item_invoice_insert_through_odr_insert': // ODR
                        $handlerSuccess = handleReturnItemInvoiceInsertThroughODRInsert($conn, $batch_id, $action_name);
                        break;
                    case 'delete_payment_insert_through_bill':
                        $handlerSuccess = handleDeletePaymentInsertThroughBill($conn, $batch_id, $action_name);
                        break;
                    case 'delete_payment_insert_through_bill2_delete_invoice':
                        $handlerSuccess = handleDeletePaymentInsertThroughBill($conn, $batch_id, $action_name);
                        break;
                    case 'cash_back_invoice_total_insert':
                        $handlerSuccess = handleInvoiceTotalInsert($conn, $batch_id, $action_name);
                        break;
                    case 'cash_back_invoice_cost_insert':
                        $handlerSuccess = handleInvoiceCostInsert($conn, $batch_id, $action_name);
                        break;
                    case 'normal_invoice_total_insert': // = value
                        $handlerSuccess = handleInvoiceTotalInsert($conn, $batch_id, $action_name);
                        break;
                    case 'normal_invoice_cost_insert':
                        $handlerSuccess = handleInvoiceCostInsert($conn, $batch_id, $action_name);
                        break;
                    case 'payment_insert_through_bill_insert':
                        $handlerSuccess = handlePaymentInsertThroughBillInsert($conn, $batch_id, $action_name);
                        break;
                    case 'delete_invoice_cost':
                        $handlerSuccess = handleDeleteInvoiceCost($conn, $batch_id, $action_name);
                        break;
                    case 'delete_invoice_total': // = value
                        $handlerSuccess = handleDeleteInvoiceValue($conn, $batch_id, $action_name);
                        break;
                    case 'payment_insert_bill2':
                        $handlerSuccess = handlePaymentInsertThroughBillInsert($conn, $batch_id, $action_name);
                        break;
                    case 'payment_delete_bill2':
                        $handlerSuccess = handleDeletePaymentInsertThroughBill($conn, $batch_id, $action_name);
                        break;
                    case 'cash_on_hand_insert_through_payment_deposit_insert':
                        $handlerSuccess = handleCashOnHandDepositInsert($conn, $batch_id, $action_name);
                        break;
                    case 'bank_deposit_insert_through_payment_deposit_insert':
                        $handlerSuccess = handleBankDepositInsert($conn, $batch_id, $action_name);
                        break;
                    case 'cust_order_invoice_cost_insert':
                        $handlerSuccess = handleInvoiceCostInsert($conn, $batch_id, $action_name);
                        break;
                    case 'sales_bill_to_cust_order':
                        $handlerSuccess = handleBillConvert($conn, $batch_id, $action_name);
                        break;
                    case 'cust_order_to_sales_bill':
                        $handlerSuccess = handleBillConvert($conn, $batch_id, $action_name);
                        break;
                    case 'return_items_move_to_inventory_insert':
                        $handlerSuccess = handleReturnItemMoveToInventoryInsert($conn, $batch_id, $action_name);
                        break;
                    case 'edit_item_qty': // inventory
                        $handlerSuccess = handleEditItemQty($conn, $batch_id, $action_name);
                        break;
                    case 'revers_bank_payment_deposit': // back end
                        $handlerSuccess = handleCashOnHandDepositInsert($conn, $batch_id, $action_name);
                        break;
                    default:
                        $response['status'] = 'error';
                        $response['message'] = "Unknown action '$action_name' for batch $batch_id.";
                        logQbError($conn, 'processQBQueue', $response['message'], $batch_id);
                        $handlerSuccess = false;
                        break;
                }

                // Update response based on handler outcome
                if ($handlerSuccess) {
                    $response['status'] = 'success';
                    $response['message'] = "Batch $batch_id (Action: $action_name) processed successfully.";
                    // Optionally, you might want to suggest a shorter poll interval (e.g., 3 seconds)
                    $response['poll_interval'] = 3;
                } else {
                    $response['status'] = 'error';
                    $response['message'] = "Batch $batch_id (Action: $action_name) failed. Check qb_queue_error_log for details.";
                    // Here, we keep the default poll interval (e.g., 300 seconds)
                }
            } else {
                // Batch found but no valid action row exists – report an error.
                $response['status'] = 'error';
                $response['message'] = "Batch $batch_id found, but no action row exists in queue.";
                logQbError($conn, 'processQBQueue', $response['message'], $batch_id);
            }
        }
    }
    return $response;
}

// --- Main Execution Logic ---
$finalResponse = [
    'status' => 'error',
    'message' => 'QuickBooks processing not initiated.',
    'batch_id' => null,
    'action' => null
];

$finalResponse = processQBQueue($conn);
header('Content-Type: application/json');// Set the content type header to JSON
echo json_encode($finalResponse);// Encode the final response array as JSON and echo it
exit; // Ensure no other output interferes
?>