<?php
function dateDiff($dt1, $dt2)
{
	$dt1 = new DateTime($dt1);
	$dt2 = new DateTime($dt2);
	$ts1 = $dt1->format('Y-m-d');
	$ts2 = $dt2->format('Y-m-d');
	$diff = abs(strtotime($ts1) - strtotime($ts2));
	$diff /= 3600 * 24;
	return $diff;
}

function loanInstallment($PV, $r, $n)
{
	if ($r == 0) {
		$P = $PV / $n;
	} else {
		$r = $r / 100 / 12;
		$P = ($r * $PV) / (1 - (pow((1 + $r), -$n)));
		$P = round($P, 2);
	}
	return $P;
}

// update by nirmal 16_11_23
function getExpenseFormData()
{
	global $st_id, $st_name, $ac_id, $ac_name, $ac_type, $fromac_id, $fromac_name, $py_id, $py_name, $cu_id, $cu_name, $su_id, $su_name, $up_id, $up_name, $method_id, $method_name, $conn;

	$st_id = $ac_id = $fromac_id = $py_id = $py_name = $cu_id = $su_id = $up_id = $method_id = array();
	$user = $_COOKIE['user_id'];
	$components = $_REQUEST['components'];
	$filter_accounts = $action = $table = '';
	$systemid = inf_systemid(1);
	if (isset($_GET['action']))
		$action = $_GET['action'];
	include('config.php');

	if ($components == 'accounts') {
		$table = ", account_managers am";
		$filter_accounts = " AND am.`account` = ac.`id` AND am.`user` = '$user'";
	}

	$query = "SELECT id,name FROM stores WHERE `status`=1";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$st_id[] = $row[0];
		$st_name[] = $row[1];
	}

	$odr_order = "odr.`order`='1'";
	if ($action != 'expense') {
		$odr_order = "odr.`order` IN(1,2,3,4,5)";
	}

	$query = "SELECT ac.`id`,ac.`name`,acat.`category_level1` FROM accounts ac, account_category acat, account_category_order odr WHERE ac.`category`=acat.`id` AND acat.`category_level1`=odr.`categoryL1` AND ac.`status`='1' AND $odr_order ORDER BY odr.`order`, ac.`name`";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$count = strlen($row[1]);
		$ac_id[] = $row[0];
		$ac_name[] = str_pad($row[1], 35, "-", STR_PAD_RIGHT) . ' ' . $row[2];
		$ac_type[] = $row[2];
	}

	$query = "SELECT ac.`id`, ac.`name` FROM accounts ac $table WHERE ac.`payment_ac`='1' $filter_accounts AND ac.`status`='1'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$fromac_id[] = $row[0];
		$fromac_name[] = $row[1];
	}

	$query = "SELECT id,name FROM other_payee WHERE `status`=1";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$py_id[] = $row[0];
		$py_name[] = $row[1];
	}

	$query = "SELECT id,name FROM cust WHERE `status`=1";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$cu_id[] = $row[0];
		$cu_name[] = $row[1];
	}

	$query = "SELECT id,name FROM supplier WHERE `status`=1";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$su_id[] = $row[0];
		$su_name[] = $row[1];
	}

	$query = "SELECT id,username FROM userprofile WHERE `status`=0";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$up_id[] = $row[0];
		$up_name[] = $row[1];
	}

	$query = "SELECT id,name FROM expense_paymethod WHERE `status`=1";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$method_id[] = $row[0];
		$method_name[] = $row[1];
	}

}

function getPayAccounts()
{
	global $fromac_id, $fromac_name;
	include('config.php');

	$query = "SELECT id,name FROM accounts WHERE payment_ac=1 AND `status`=1";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$fromac_id[] = $row[0];
		$fromac_name[] = $row[1];
	}
}

// update by nirmal 21_11_19
// update by nirmal 23_08_2023 (added quickbooks expense add)
function addExpense()
{
	global $message, $expense_id;
	$date = $_POST['date'];
	$store = $_POST['store'];
	$ref = $_POST['ref'];
	$payee_type = $_POST['payee_type'];
	$payee = $_POST['payee'];
	$from_account = $_POST['from_account'];
	$method = $_POST['method'];
	$memo = $_POST['memo'];
	$placed_by = $_COOKIE['user_id'];
	$today = timeNow();
	$out = true;
	$message = "Expense was added successfully.";
	$components = $_REQUEST['components'];
	$qb_msg = $qb_payee_type = $payee_ref = '';
	$expense_array = [];
	include('config.php');

	if ($components == 'accounts') {
		$store = $_COOKIE['store'];
	}

	try {
		// Start MySQL transaction
		mysqli_begin_transaction($conn);

		if (trim($payee) == "") {
			$out = false;
			$message = "Error: Payee cannot be a empty value!";
			throw new Exception($message);
		}

		if ($payee_type == 'customer') {
			$query = "SELECT id FROM cust WHERE name='$payee'";
			$qb_payee_type = $payee_type;
		}
		if ($payee_type == 'supplier') {
			$query = "SELECT id FROM supplier WHERE name='$payee'";
			$qb_payee_type = 'vendor';
		}
		if ($payee_type == 'employee') {
			$query = "SELECT id FROM userprofile WHERE username='$payee'";
			$qb_payee_type = 'employee';
		}
		if ($payee_type == 'other') {
			$query = "SELECT id FROM other_payee WHERE name='$payee'";
		}
		if ($out) {
			if (isQuickBooksActive(1)) {
				if (($payee_type != 'customer') && ($payee_type != 'supplier') && ($payee_type != 'employee')) {
					$out = false;
					$message = "Error: Quickbooks only allow customer, supplier and employee expenses insert only";
					throw new Exception($message);
				}
			}
		}
		if ($out) {
			$result = mysqli_query($conn2, $query);
			$row = mysqli_fetch_assoc($result);
			$payee_id = $row['id'];

			if ($payee_id == '') {
				if ($payee_type == 'other') {
					$query = "INSERT INTO `other_payee` (`name`,`status`) VALUES ('$payee','1')";
					$result = mysqli_query($conn, $query);
					$payee_id = mysqli_insert_id($conn);
					if (!$result) {
						$out = false;
						$message = "Error: Other payee is not inserted!";
						throw new Exception($message);
					}
				} else {
					$out = false;
					$message = "Error: This Payee is not in our system!";
					throw new Exception($message);
				}
			}
		}
		if ($out) {
			$result = mysqli_query($conn, "SELECT MAX(expense_id) as `maxid` FROM expense_main");
			if (!$result) {
				$out = false;
				$message = "Error: Expenses id is not selecting";
				throw new Exception($message);
			} else {
				$row = mysqli_fetch_assoc($result);
				$expense_id = $row['maxid'];
				if ($expense_id == '') {
					$expense_id = 1;
				} else {
					$expense_id++;
				}
			}
		}

		if ($out) {
			$query = "INSERT INTO `expense_main` (`expense_id`,`placed_by`,`placed_date`,`expense_date`,`store`,`ref_no`,`payee_type`,`payee`,`from_account`,`payment_method`,`memo`,`status`)
			VALUES ('$expense_id','$placed_by','$today','$date','$store','$ref','$payee_type','$payee_id','$from_account','$method','$memo','1')";
			$result = mysqli_query($conn, $query);

			if (!$result) {
				$out = false;
				$message = "Error: Expenses main table is not updated";
				throw new Exception($message);
			}

			if ($out) {
				for ($i = 1; $i <= 10; $i++) {
					$exp = $_POST['exp' . $i];
					$des = $_POST['des' . $i];
					$amo = $_POST['amo' . $i];
					if ($exp != '') {
						$account_name = substr($exp, 0, strpos($exp, '-'));
						$result1 = mysqli_query($conn, "SELECT id FROM accounts WHERE name='$account_name'");
						$row = mysqli_fetch_assoc($result1);
						$account_id = $row['id'];

						$query2 = "INSERT INTO `expense_item` (`expense_id`,`account`,`description`,`amount`) VALUES ('$expense_id','$account_id','$des','$amo')";
						$result2 = mysqli_query($conn, $query2);
						if (!$result2) {
							$out = false;
							$message = "Error: expenses item table is not updated";
							throw new Exception($message);
						} else {
							$out++;
							if (isQuickBooksActive(1)) {
								$result1 = mysqli_query($conn, "SELECT `qb_account_id` FROM accounts WHERE `id`='$account_id'");
								$row = mysqli_fetch_assoc($result1);
								$expense_account_ref = $row['qb_account_id'];

								$expense_array[] = [
									'description' => $des,
									'amount' => abs($amo),
									'expense_account_ref' => $expense_account_ref
								];
							}
						}
					}
				}
			}
		}

		if ($out) {
			if (count($expense_array) > 0) {
				$result2 = mysqli_query($conn, "SELECT `name` FROM expense_paymethod WHERE `id`='$method'");
				$row2 = mysqli_fetch_assoc($result2);
				$payment_method = $row2['name'];

				$result1 = mysqli_query($conn, "SELECT `qb_account_id` FROM accounts WHERE `id`='$from_account'");
				$row = mysqli_fetch_assoc($result1);
				$account_ref = $row['qb_account_id'];

				if ($payment_method == 'CASH')
					$payment_method = 'Cash';
				if ($payment_method == 'CARD')
					$payment_method = 'CreditCard';
				if ($payment_method == 'CHEQUE')
					$payment_method = 'Check';

				if ($qb_payee_type == 'vendor') {
					$result1 = mysqli_query($conn, "SELECT `qb_account_id` FROM accounts WHERE `name`='$payee'");
					$row = mysqli_fetch_assoc($result1);
					$payee_ref = $row['qb_account_id'];
				}

				if ($qb_payee_type == 'customer') {
					$result1 = mysqli_query($conn, "SELECT `qb_cust_id` FROM cust WHERE `name`='$payee'");
					$row = mysqli_fetch_assoc($result1);
					$payee_ref = $row['qb_cust_id'];
				}

				if ($qb_payee_type == 'employee') {
					$result1 = mysqli_query($conn, "SELECT `qb_id` FROM userprofile WHERE `username`='$payee'");
					$row = mysqli_fetch_assoc($result1);
					$payee_ref = $row['qb_id'];
				}
				if ($payee_ref != '') {
					try {
						$qb_result = QBAddExpenses($expense_array, $payment_method, $account_ref, $qb_payee_type, $payee_ref, $ref);
						$qb_msg = $qb_result['message'];
						if ((isset($qb_result['status'])) && ($qb_result['status'] == 'success')) {
							$qb_expense_id = $qb_result['qb_expense_id'];
							$qb_msg = $qb_result['message'];
							if ($qb_expense_id != '') {
								$query = "UPDATE `expense_main` SET `qb_id`='$qb_expense_id' WHERE `expense_id`='$expense_id'";
								$result = mysqli_query($conn, $query);
								if (!$result) {
									$out = false;
									$message = "Error: expenses_main qb_id is not updated";
									throw new Exception($message);
								}
							}
						} else {
							$out = false;
							throw new Exception($qb_msg);
						}
					} catch (Exception $e) {
						$out = false;
						$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
						$qb_result['status'] = 'error';
						throw new Exception($qb_msg);
					}
				} else {
					$out = false;
					$qb_msg = "Quickbooks Error : This payee not saved in quickbooks yet! Please contact Negoit";
					throw new Exception($qb_msg);
				}
			}
		}
		// Commit the transaction
		mysqli_commit($conn);
	} catch (Exception $e) {
		// Rollback transaction in case of any error
		mysqli_rollback($conn);

		$message = $e->getMessage();
	}
	$message = $message . ' ' . $qb_msg;
	return $out;
}

// update by nirmal 21_11_19
function deleteExpense()
{
	global $message, $expense_id;
	$id = $expense_id = $_GET['id'];
	$out = true;
	$result = false;
	$today = dateNow();
	$message = "Expense was Deleted Successfully";
	$components = $_REQUEST['components'];
	$user = $_COOKIE['user_id'];
	include('config.php');

	if ($components == 'accounts') {
		$query = "SELECT `placed_by`,`placed_date` FROM expense_main WHERE `expense_id`='$id'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$out = false;
			$message = "Error: Please check your expense again!";
		}
		if ($out) {
			$row = mysqli_fetch_row($result);
			$placed_by = $row[0];
			$date = substr($row[1], 0, 10);

			if ($user != $placed_by) {
				$out = false;
				$message = "Error: You cannot delete someone else's expense!";
			}

			if ($out) {
				if ($date != $today) {
					$out = false;
					$message = "Error: You cannot delete a expense older than today!";
				}
			}

			if ($out) {
				$query = "UPDATE expense_main SET `status`='0' WHERE expense_id='$id'";
				$result = mysqli_query($conn, $query);
				if (!$result) {
					$out = false;
					$message = "Expense could not be Deleted!";
				}
			}
		}
	} else {
		$query = "UPDATE expense_main SET `status`='0' WHERE `expense_id`='$id'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$out = false;
			$message = "Expense could not be Deleted!";
		}
	}

	return $out;
}

function listExpenseYears()
{
	global $year_list;
	$year_list = array();
	include('config.php');
	$query = "SELECT year(expense_date) FROM expense_main WHERE `status`=1 GROUP BY year(expense_date)";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$year_list[] = $row[0];
	}
}

function listJournalYears()
{
	global $year_list;
	$year_list = array();
	include('config.php');
	$query = "SELECT year(journal_date) FROM journal_main WHERE `status`=1 GROUP BY year(journal_date)";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$year_list[] = $row[0];
	}
}

// update by nirmal 21_11_17
function listExpense()
{
	global $em_id, $st_name, $em_expense_date, $up_username, $em_amount, $em_payee_type, $payee_name;
	$year = $_GET['year'];
	$components = $_REQUEST['components'];
	$store_filter = '';

	if ($components == 'accounts') {
		$store = $_COOKIE['store'];
		$store_filter = 'AND st.id="' . $store . '"';
	}

	include('config.php');
	$query = "SELECT em.expense_id,st.name,em.expense_date,up.username,SUM(ei.amount),em.payee_type,em.payee FROM expense_main em, expense_item ei, userprofile up, stores st WHERE em.expense_id=ei.expense_id AND em.placed_by=up.id AND em.store=st.id $store_filter AND year(em.expense_date)='$year' AND em.`status`=1 GROUP BY em.expense_id";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$em_id[] = $row[0];
		$st_name[] = $row[1];
		$em_expense_date[] = $row[2];
		$up_username[] = $row[3];
		$em_amount[] = $row[4];
		$payee_type = $row[5];
		$em_payee_type[] = $row[5];
		$payee_id = $row[6];

		if ($payee_type == 'customer')
			$query1 = "SELECT name FROM cust WHERE id='$payee_id'";
		if ($payee_type == 'supplier')
			$query1 = "SELECT name FROM supplier WHERE id='$payee_id'";
		if ($payee_type == 'employee')
			$query1 = "SELECT username as `name` FROM userprofile WHERE id='$payee_id'";
		if ($payee_type == 'other')
			$query1 = "SELECT name FROM other_payee WHERE id='$payee_id'";
		$result1 = mysqli_query($conn, $query1);
		$row1 = mysqli_fetch_assoc($result1);
		$payee_name[] = $row1['name'];
	}
}

// update by nirmal 21_11_24
function getOneExpense()
{
	global $placed_by, $placed_date, $expense_date, $store, $ref_no, $payee_type, $payee_name, $from_account, $payment_method, $memo, $status, $ei_account, $ei_description, $ei_amount;
	$id = $_REQUEST['id'];
	$components = $_REQUEST['components'];
	$store = $_COOKIE['store'];
	include('config.php');
	$out = true;

	if ($components == 'accounts') {
		$query = "SELECT em.`expense_id` FROM expense_main em WHERE em.`expense_id` = '$id' AND em.`store` = '$store'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_num_rows($result);
		if ($row == 0)
			$out = false;
	}

	if ($out) {
		$query = "SELECT up.username,em.placed_date,em.expense_date,st.name,em.ref_no,em.payee_type,em.payee,ac.name,meth.name,em.memo,em.`status` FROM expense_main em, accounts ac, expense_paymethod meth, stores st, userprofile up WHERE em.placed_by=up.id AND em.store=st.id AND em.from_account=ac.id AND em.payment_method=meth.id AND em.expense_id='$id'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$placed_by = $row[0];
			$placed_date = $row[1];
			$expense_date = $row[2];
			$store = $row[3];
			$ref_no = $row[4];
			$payee_type = $row[5];
			$payee_id = $row[6];
			$from_account = $row[7];
			$payment_method = $row[8];
			$memo = $row[9];
			$status = $row[10];

			if ($payee_type == 'customer')
				$query1 = "SELECT name FROM cust WHERE id='$payee_id'";
			if ($payee_type == 'supplier')
				$query1 = "SELECT name FROM supplier WHERE id='$payee_id'";
			if ($payee_type == 'employee')
				$query1 = "SELECT username as `name` FROM userprofile WHERE id='$payee_id'";
			if ($payee_type == 'other')
				$query1 = "SELECT name FROM other_payee WHERE id='$payee_id'";
			$result1 = mysqli_query($conn, $query1);
			$row1 = mysqli_fetch_assoc($result1);
			$payee_name = $row1['name'];

			$query1 = "SELECT ac.name,ei.description,ei.amount FROM expense_item ei, accounts ac WHERE ei.account=ac.id AND ei.expense_id='$id'";
			$result1 = mysqli_query($conn, $query1);
			while ($row1 = mysqli_fetch_array($result1)) {
				$ei_account[] = $row1[0];
				$ei_description[] = $row1[1];
				$ei_amount[] = $row1[2];
			}
		}
	}
	return $out;
}

// updated by nirmal 02_08_2024 (add quickbooks journal entry add)
function addJournal()
{
	global $message, $journal_id;
	$date = $_POST['date'];
	$store = $_POST['store'];
	$ref = $_POST['ref'];
	$memo = $_POST['memo'];
	$placed_by = $_COOKIE['user_id'];
	$today = timeNow();
	$out = $payee_id = 0;
	$flag = true;
	$qb_msg = $last_id = $qb_cust_id = $qb_vendor_id = '';
	$journal_entries_array = [];
	$message = 'Journal was added successfully. ' . $qb_msg;
	include('config.php');

	try {
		// Start MySQL transaction
		mysqli_begin_transaction($conn);

		// Get the next journal_id
		$result = mysqli_query($conn, "SELECT MAX(journal_id) as `maxid` FROM journal_main");
		$row = mysqli_fetch_assoc($result);
		$journal_id = $row['maxid'];
		if ($journal_id == '') {
			$journal_id = 1;
		} else {
			$journal_id++;
		}

		// Prepare for credit and debit totals
		$total_cr = 0;
		$total_dr = 0;
		$has_cr = false;
		$has_dr = false;

		// First pass: calculate totals and validate
		for ($i = 1; $i <= 10; $i++) {
			$exp = $_POST['exp' . $i];
			$des = $_POST['des' . $i];
			$cr = $_POST['cr' . $i];
			$dr = $_POST['dr' . $i];
			$payee_type = $_POST['payee_type' . $i];
			if ($cr != '') {
				$has_cr = true;
				$total_cr += $cr;
			}
			if ($dr != '') {
				$has_dr = true;
				$total_dr += $dr;
			}
		}

		// Check if there are both credit and debit records and if totals match
		if (!$has_cr || !$has_dr || $total_cr != $total_dr) {
			$message = 'Journal must have at least one credit and one debit record, and the totals must match.';
			throw new Exception($message);
		}

		// Insert the journal entry
		$query = "INSERT INTO `journal_main` (`journal_id`, `placed_by`, `placed_date`, `journal_date`, `store`, `ref_no`, `memo`, `status`) VALUES ('$journal_id', '$placed_by', '$today', '$date', '$store', '$ref', '$memo', '1')";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$message = 'Error: Error adding journal entry.';
			$flag = false;
			throw new Exception($message);
		}

		if ($flag) {
			for ($i = 1; $i <= 10; $i++) {
				$exp = $_POST['exp' . $i];
				$des = $_POST['des' . $i];
				$cr = $_POST['cr' . $i];
				$dr = $_POST['dr' . $i];
				$payee_type = $_POST['payee_type' . $i];
				if ($cr != '') {
					$cr_dr = 'cr';
					$amount = -$cr;
				}
				if ($dr != '') {
					$cr_dr = 'dr';
					$amount = $dr;
				}

				if ($exp != '') {
					$payee = $_POST['payee' . $i];
					$account_name = substr($exp, 0, strpos($exp, '-'));
					$result1 = mysqli_query($conn, "SELECT id FROM accounts WHERE name='$account_name'");
					$row = mysqli_fetch_assoc($result1);
					$account_id = $row['id'];

					if (!$account_id) {
						throw new Exception("Error: Account '$account_name' does not exist in the accounts table.");
					}

					if (($flag) && ($account_name == 'Accounts Receivable (A/R)')) {
						if ($payee_type != 'customer') {
							$flag = false;
							$message = 'Error : you can only select Customer with Accounts Receivable (A/R)';
							throw new Exception($message);
						}
					}

					if (($flag) && ($account_name == 'Accounts Payable (A/P)')) {
						if ($payee_type != 'supplier') {
							$flag = false;
							$message = 'Error : you can only select Supplier with Accounts Payable (A/P)';
							throw new Exception($message);
						}
					}

					if ($flag) {
						if ($payee_type != '') {
							if ($payee_type == 'customer') {
								$query = "SELECT id FROM cust WHERE `name`='$payee'";
								if (isQuickBooksActive(1)) {
									$query = "SELECT id, qb_cust_id FROM cust WHERE `name`='$payee'";
								}
							}
							if ($payee_type == 'supplier') {
								$query = "SELECT id FROM supplier WHERE name='$payee'";
								if (isQuickBooksActive(1)) {
									$query = "SELECT id, qb_account_id FROM accounts WHERE `name`='$payee'";
								}
							}
							if ($payee_type == 'employee') {
								$query = "SELECT id FROM userprofile WHERE `username`='$payee'";
								if (isQuickBooksActive(1)) {
									$query = "SELECT id, qb_id FROM userprofile WHERE `username`='$payee'";
								}
							}
							if ($payee_type == 'other') {
								$query = "SELECT id FROM other_payee WHERE `name`='$payee'";
							}
							$result = mysqli_query($conn, $query);
							$row = mysqli_fetch_assoc($result);

							if ($payee_type == 'customer' && isQuickBooksActive(1)) {
								$qb_cust_id = $row['qb_cust_id'];
							}
							if ($payee_type == 'supplier' && isQuickBooksActive(1)) {
								$qb_vendor_id = $row['qb_account_id'];
							}
							if ($payee_type == 'employee' && isQuickBooksActive(1)) {
								$qb_employee_id = $row['qb_id'];
							}
							$payee_id = $row['id'];

							if ($payee_id == '') {
								$query = "INSERT INTO `other_payee` (`name`,`status`) VALUES ('$payee','1')";
								$result = mysqli_query($conn, $query);
								$payee_id = mysqli_insert_id($conn);
							}
						}
						$query2 = "INSERT INTO `journal_item` (`journal_id`,`account`,`description`,`cr_dr`,`amount`,`stakeholder_type`,`stakeholder_id`) VALUES ('$journal_id','$account_id','$des','$cr_dr','$amount','$payee_type','$payee_id')";
						$result2 = mysqli_query($conn, $query2);
						if ($result2) {
							$out++;
						} else {
							$message = 'Error: Journal could not be added. ' . mysqli_error($conn);
							throw new Exception($message);
						}

						if (($out > 0) && (isQuickBooksActive(1))) {
							$storeQBID = $classID = null;

							$result = mysqli_query($conn, "SELECT `qb_id` FROM `stores` WHERE `id`='$store'");
							if (!$result) {
								throw new Exception("Database query failed: " . mysqli_error($conn));
							} else {
								$row = mysqli_fetch_assoc($result);
								if (!empty($row)) {
									if ($row['qb_id'] == '') {
										$out = false;
										$qb_msg = 'Error: this store is not registered in qb';
										throw new Exception($qb_msg);
									} else {
										$storeQBID = $row['qb_id'];
									}
								} else {
									$out = false;
									$qb_msg = 'Error: this store is not registered in qb';
									throw new Exception($qb_msg);
								}
							}

							if ($storeQBID === null || $storeQBID === '') {
								throw new Exception("Error: Store QuickBooks ID not found");
							}
							$departmentID = $storeQBID;

							$result1 = mysqli_query($conn, "SELECT qb_account_id FROM accounts WHERE `id`='$account_id'");
							$row = mysqli_fetch_assoc($result1);
							$qb_account_id = $row['qb_account_id'];
							$cr_dr = $cr != '' ? 'Credit' : 'Debit';
							$amount = $cr_dr === 'Debit' ? abs(floatval($amount)) : abs(floatval($amount));
							$description = $des;

							if ($account_name == 'Accounts Receivable (A/R)') {
								if ($payee_type == 'customer') {
									$journal_entries_array[] = [
										'description' => $des,
										'amount' => $amount,
										'posting_type' => $cr_dr,
										'account_id' => $qb_account_id,
										'account_name' => $account_name,
										'entity_type' => 'Customer',
										'entity_id' => $qb_cust_id,
										'class_id' => $classID,
										'department_id' => $departmentID
									];
								}
							} else if ($account_name == 'Accounts Payable (A/P)') {
								if ($payee_type == 'supplier') {
									$journal_entries_array[] = [
										'description' => $des,
										'amount' => $amount,
										'posting_type' => $cr_dr,
										'account_id' => $qb_account_id,
										'account_name' => $account_name,
										'entity_type' => 'Vendor',
										'entity_id' => $qb_vendor_id,
										'class_id' => $classID,
										'department_id' => $departmentID
									];
								}
							} else {
								if ($payee_type == 'employee') {
									$journal_entries_array[] = [
										'description' => $des,
										'amount' => $amount,
										'posting_type' => $cr_dr,
										'account_id' => $qb_account_id,
										'account_name' => $account_name,
										'entity_type' => 'Employee',
										'entity_id' => $qb_employee_id,
										'class_id' => $classID,
										'department_id' => $departmentID
									];
								} else {
									$journal_entries_array[] = [
										'description' => $des,
										'amount' => $amount,
										'posting_type' => $cr_dr,
										'account_id' => $qb_account_id,
										'account_name' => $account_name,
										'class_id' => $classID,
										'department_id' => $departmentID
									];
								}
							}
						}
					}
				}
			}

			if ($flag) {
				if (count($journal_entries_array) > 0) {
					$action_name = "journal_entry_insert";
					$batch_id = generateBatchID();
					foreach ($journal_entries_array as $entry) {
						$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
						$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
						$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
						$amount = mysqli_real_escape_string($conn, $entry['amount']);
						$description = mysqli_real_escape_string($conn, $entry['description']);
						$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
						$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;
						$class_id = isset($entry['class_id']) ? mysqli_real_escape_string($conn, $entry['class_id']) : null;
						$department_id = isset($entry['department_id']) ? mysqli_real_escape_string($conn, $entry['department_id']) : null;

						$query = "INSERT INTO qb_queue (`action`,`batch_id`, `journal_id`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `created_at`, `class_id`, `department_id`, `entity_type`, `entity_id`)
								VALUES ('$action_name','$batch_id','$journal_id', '$posting_type', '$account_id', '$account_name', '$amount', '$description','$today',
									" . ($class_id !== null ? "'$class_id'" : "NULL") . ",
									" . ($department_id !== null ? "'$department_id'" : "NULL") . ",
									" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
									" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
						if (!mysqli_query($conn, $query)) {
							$message = "MySQL Error: " . mysqli_error($conn);
							throw new Exception($message);
						}
					}
				}
			}
		}

		// Commit the transaction
		mysqli_commit($conn);
		$message = $message . ' ' . (isset($qb_msg) ? $qb_msg : '');
		return true;
	} catch (Exception $e) {
		$conn->rollback(); // Roll back the transaction
		$message = $e->getMessage();
		$message = $message . ' ' . (isset($qb_msg) ? $qb_msg : '');
		return false;
	}
}

function listJournal()
{
	global $jm_id, $st_name, $jm_journal_date, $up_username, $jm_amount;
	$year = $_GET['year'];

	$jm_id = array();
	include('config.php');
	$query = "SELECT jm.journal_id,st.name,jm.journal_date,up.username,SUM(ji.amount) FROM journal_main jm, journal_item ji, userprofile up, stores st WHERE jm.journal_id=ji.journal_id AND jm.placed_by=up.id AND jm.store=st.id AND year(jm.journal_date)='$year' AND jm.`status`=1 GROUP BY jm.journal_id";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$jm_id[] = $row[0];
		$st_name[] = $row[1];
		$jm_journal_date[] = $row[2];
		$up_username[] = $row[3];
		$jm_amount[] = $row[4];
	}
}

function getOneJournal()
{
	global $id, $placed_by, $placed_date, $journal_date, $store, $ref_no, $memo, $no_delete, $status, $ji_account, $ji_description, $ji_amount, $ji_cr_dr, $ji_payee_type, $ji_payee_name;
	$id = $_REQUEST['id'];

	include('config.php');
	$query = "SELECT id,name FROM cust";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$cust_id[] = $row[0];
		$cust_name[] = $row[1];
	}
	$query = "SELECT id,name FROM supplier";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$sup_id[] = $row[0];
		$sup_name[] = $row[1];
	}
	$query = "SELECT id,username FROM userprofile";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$user_id[] = $row[0];
		$user_name[] = $row[1];
	}
	$query = "SELECT id,name FROM other_payee";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$other_id[] = $row[0];
		$other_name[] = $row[1];
	}

	$query = "SELECT up.username,jm.placed_date,jm.journal_date,st.name,jm.ref_no,jm.memo,jm.no_delete,jm.`status` FROM journal_main jm, stores st, userprofile up WHERE jm.placed_by=up.id AND jm.store=st.id AND jm.journal_id='$id'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$placed_by = $row[0];
		$placed_date = $row[1];
		$journal_date = $row[2];
		$store = $row[3];
		$ref_no = $row[4];
		$memo = $row[5];
		$no_delete = $row[6];
		$status = $row[7];

		$query1 = "SELECT ac.name,ji.description,ji.amount,ji.cr_dr,ji.stakeholder_type,ji.stakeholder_id FROM journal_item ji, accounts ac WHERE ji.account=ac.id AND ji.journal_id='$id'";
		$result1 = mysqli_query($conn, $query1);
		while ($row1 = mysqli_fetch_array($result1)) {
			$ji_account[] = $row1[0];
			$ji_description[] = $row1[1];
			$ji_amount[] = $row1[2];
			$ji_cr_dr[] = $row1[3];
			$ji_payee_type[] = $row1[4];
			$payee_type = $row1[4];
			$ji_payee_id = $row1[5];

			if ($payee_type == 'customer') {
				$payee_array_id = $cust_id;
				$payee_array_name = $cust_name;
			}
			if ($payee_type == 'supplier') {
				$payee_array_id = $sup_id;
				$payee_array_name = $sup_name;
			}
			if ($payee_type == 'employee') {
				$payee_array_id = $user_id;
				$payee_array_name = $user_name;
			}
			if ($payee_type == 'other') {
				$payee_array_id = $other_id;
				$payee_array_name = $other_name;
			}
			if ($payee_type != '') {
				$key = array_search($ji_payee_id, $payee_array_id);
				$ji_payee_name[] = $payee_array_name[$key];
			} else {
				$ji_payee_name[] = '';
			}
		}
	}
}

// updated by nirmal 18_02_2025 (prevent journal entries delete if qb active)
function deleteJournal()
{
	global $message;
	if (!isQuickBooksActive(1)) {
		$id = $_GET['id'];
		include('config.php');
		$result1 = mysqli_query($conn, "DELETE FROM journal_item WHERE journal_id='$id'");
		if ($result1)
			$result2 = mysqli_query($conn, "DELETE FROM journal_main WHERE journal_id='$id'");
		if ($result2) {
			$message = "Journal was deleted successfully";
			return true;
		} else {
			$message = "Journal could not be deleted!";
			return false;
		}
	} else {
		$message = "Journal cannot be deleted, when Quickbooks is enabled.";
		return false;
	}
}

function getAccountFormData()
{
	global $category_l1, $category_l2, $category_l3, $category_l2_list;
	$category_l1 = $category_l2 = $category_l3 = array();

	include('config.php');
	$query1 = "SELECT category_level1 FROM account_category GROUP BY category_level1 ORDER BY category_level1";
	$result1 = mysqli_query($conn, $query1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$category_l1_tmp = $row1[0];
		$category_l1[] = $row1[0];
		$query2 = "SELECT category_level2 FROM account_category WHERE category_level1='$category_l1_tmp' GROUP BY category_level2 ORDER BY category_level2";
		$result2 = mysqli_query($conn, $query2);
		while ($row2 = mysqli_fetch_array($result2)) {
			$category_l2_tmp = $row2[0];
			$category_l2[$category_l1_tmp][] = $row2[0];
			if ($category_l2_tmp != '') {
				$category_l2_list[] = $category_l1_tmp . '_' . str_replace(" ", "_", $category_l2_tmp);
				$query3 = "SELECT ac.category_level3 FROM account_category ac WHERE ac.category_level1='$category_l1_tmp' AND ac.category_level2='$category_l2_tmp' ORDER BY ac.category_level3";
				$result3 = mysqli_query($conn, $query3);
				while ($row3 = mysqli_fetch_array($result3)) {
					$category_l3[$category_l1_tmp . '_' . str_replace(" ", "_", $category_l2_tmp)][] = $row3[0];
				}
			}
		}
	}
}

function getParentAccountsAjax()
{
	include('config.php');
	$category = $_POST['category'];

	// Step 1: Collect all IDs from the first query
	$ids = $jsonArray = array();
	$query = "SELECT id FROM account_category WHERE category_level2 = '$category'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$ids[] = $row[0]; // Store the IDs in an array
	}
	// Step 2: Use the IDs in the IN() clause
	if (!empty($ids)) {
		// Convert the array to a comma-separated string for the IN() clause
		$ids_list = implode(",", $ids);
		// Run the second query using the ID list
		$query = "SELECT `id`, `name` FROM accounts WHERE `category` IN ($ids_list) AND `parent_account_id` = ''";
		$result = mysqli_query($conn, $query);
		// Process the result
		while ($row = mysqli_fetch_assoc($result)) {
			$jsonArray[] = array( // Append each item as an array to $jsonArray
				'parent_account_id' => $row['id'],
				'parent_account_name' => $row['name'],
			);
		}
	}
	$myJSON = json_encode($jsonArray);
	return $myJSON;
}


// update by nirmal 21_11_22
function getCahrtOfAccounts()
{
	global $ac_id, $ac_name, $ac_system, $ac_status, $category_L1, $category_L2, $category_L3, $ac_parent_id, $conn;
	$component = $_REQUEST['components'];
	$user = $_COOKIE['user_id'];
	$filter_accounts = $table = "";
	include('config.php');

	if ($component == 'accounts') {
		$filter_accounts = "AND am.`account`=ac.`id` AND am.`user`='$user'";
		$table = ", account_managers am";
	}


	// $query = "SELECT ac.`id`, ac.`name`, ac.`system_ac`, ac.`status`, acat.`category_level1`, acat.`category_level2`, acat.`category_level3`, ac.`parent_account_id`
	//		 FROM accounts ac, account_category acat $table
	//		 WHERE ac.`category`=acat.`id` $filter_accounts
	//		 ORDER BY acat.`category_level1`, acat.`category_level2`, acat.`category_level3`, ac.`parent_account_id`";

	// -- hide supplier from the query
	$query = "SELECT ac.`id`, ac.`name`, ac.`system_ac`, ac.`status`, acat.`category_level1`, acat.`category_level2`, acat.`category_level3`, ac.`parent_account_id`
	FROM accounts AS ac
	JOIN account_category AS acat ON ac.`category` = acat.`id`
	LEFT JOIN supplier AS s ON ac.`name` = s.`name`
	WHERE s.`name` IS NULL $filter_accounts
	ORDER BY acat.`category_level1`, acat.`category_level2`, acat.`category_level3`, ac.`parent_account_id`;";
	$result = mysqli_query($conn, $query);
	if (!$result) {
		die("Database Error: " . mysqli_error($conn));
	}
	while ($row = mysqli_fetch_array($result)) {
		$ac_id[] = $row[0];
		$ac_name[] = $row[1];
		$ac_system[] = $row[2];
		$ac_status[] = $row[3];
		$category_L1[] = $row[4];
		$category_L2[] = $row[5];
		$category_L3[] = $row[6];
		$ac_parent_id[] = $row[7];
	}
}

// updated by nirmal 21_12_8
function getOneAccount()
{
	global $one_name, $one_bank, $one_pay, $one_status, $one_L1, $one_L2, $one_L3, $one_bank_fee;
	$id = $_GET['id'];
	include('config.php');
	$query = "SELECT ac.name,ac.bank_ac,ac.payment_ac,ac.`status`,acat.category_level1,acat.category_level2,acat.category_level3,ac.`processing_fee` FROM accounts ac, account_category acat WHERE ac.category=acat.id AND ac.id='$id'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$one_name = $row[0];
		$one_bank = $row[1];
		$one_pay = $row[2];
		$one_status = $row[3];
		$one_L1 = $row[4];
		$one_L2 = $row[5];
		$one_L3 = $row[6];
		$one_bank_fee = $row[7];
	}
}

// update by nirmal 21_12_8
// update by nirmal 05_03_2024 add quickbooks account add
function addCahrtOfAccounts()
{
	global $message;
	$category_l1 = $_POST['category_l1'];
	$ac_name = $_POST['ac_name'];
	$sub_ac = isset($_POST['sub_ac']) ? $_POST['sub_ac'] : '';
	$parent_account = isset($_POST['parent_account']) ? $_POST['parent_account'] : '';
	$out = true;
	$message = 'Account was created successfully.';
	$qb_account_id = $last_id = $qb_msg = $qb_result = '';
	include('config.php');

	if (isset($_POST['payment_ac']))
		$payment_ac = 1;
	else
		$payment_ac = 0;
	if (isset($_POST['bank_ac']))
		$bank_ac = 1;
	else
		$bank_ac = 0;
	if (isset($_POST['category_l2']))
		$category_l2 = $_POST['category_l2'];
	else
		$category_l2 = '';
	if (isset($_POST['category_l3']))
		$category_l3 = $_POST['category_l3'];
	else
		$category_l3 = '';
	if ($_POST['bank_fee'] == '')
		$bank_fee = 0;
	else
		$bank_fee = $_POST['bank_fee'];

	try {
		// Start the transaction
		mysqli_begin_transaction($conn);

		if ($out) {
			// Sanitize and normalize the account name
			$ac_name = trim($ac_name); // Remove leading and trailing whitespace
			$ac_name = preg_replace('/[^a-zA-Z0-9\s\(\)\/\-]/', '', $ac_name); // Allow letters, numbers, spaces, (), /, and -
			$ac_name = preg_replace('/\s+/', ' ', $ac_name); // Replace multiple spaces with a single space
			$ac_name = mysqli_real_escape_string($conn, $ac_name); // Escape for SQL

			// Check if the sanitized account name already exists (case-insensitive)
			$query = "SELECT * FROM accounts WHERE LOWER(name) = LOWER('$ac_name')";
			$check_result = mysqli_query($conn, $query);

			if (mysqli_num_rows($check_result) > 0) {
				$out = false;
				$message = 'Account name already exists.';
				throw new Exception($message);
			}
		}

		if (!is_numeric($bank_fee)) {
			$out = false;
			$message = 'Error: Bank processing fee must be a numeric value.';
			throw new Exception($message);
		}

		if (($bank_fee != 0) && ($bank_fee < 0)) {
			$out = false;
			$message = 'Error: Bank processing fee cannot be a negative value.';
			throw new Exception($message);
		}

		if (($sub_ac === 'on') && (empty($parent_account))) {
			$out = false;
			$message = 'Error: Please select a parent account when the Sub Account checkbox is checked.';
			throw new Exception($message);
		}

		if (($sub_ac !== 'on') && (!empty($parent_account))) {
			$out = false;
			$message = 'Error: Sub Account checkbox must be checked if a parent account is selected.';
			throw new Exception($message);
		}

		if ($out) {
			$query = "SELECT id FROM account_category WHERE category_level1='$category_l1' AND category_level2='$category_l2' AND category_level3='$category_l3'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
				$message = 'Error: Category selection issue.';
			} else {
				$row = mysqli_fetch_assoc($result);
				$account_category_id = $row['id'];
				if (($category_l1 == 'ASSET' && $category_l2 == 'Bank') && (!empty($parent_account)) && ($sub_ac == 'on') && ($category_l3 == '')) {
					$out = false;
					$message = 'Error: Please select all category levels';
					throw new Exception($message);
				}
			}
		}

		if ($out) {
			if ($account_category_id == '') {
				$message = 'Error: Category selection issue.';
				$out = false;
				throw new Exception($message);
			}
		}

		if ($out) {
			// Insert new account if name does not exist
			$query = "INSERT INTO accounts (`name`, `category`, bank_ac, payment_ac, system_ac, processing_fee, `status`, shows_in_payments_bank_list, parent_account_id)
						VALUES ('$ac_name', '$account_category_id', '$bank_ac', '$payment_ac', '0', '$bank_fee', '1', '$bank_ac', '$parent_account')";
			$result = mysqli_query($conn, $query);
			$last_id = mysqli_insert_id($conn);

			if (!$result) {
				$out = false;
				$message = 'Error: Account could not be created. ' . mysqli_error($conn);
				throw new Exception($message);
			}
		}

		if ($out) {

			if (isQuickBooksActive(1)) {
				$parentAccountQbId = '';
				// Query to fetch the QB account ID for the parent account
				if ($parent_account != '') {
					$query = "SELECT `qb_account_id` FROM `accounts` WHERE `id` = '$parent_account'";
					$result = mysqli_query($conn, $query);
					if (!$result) {
						$out = false;
						$qb_msg = 'Error: Could not execute query to fetch parent account details.';
						throw new Exception($qb_msg);
					} else {
						// Successfully retrieved the parent account QB ID
						$parentAccountRow = mysqli_fetch_assoc($result);
						$parentAccountQbId = $parentAccountRow['qb_account_id'];
						if (empty($parentAccountQbId)) {
							$out = false;
							$qb_msg = 'Error: Parent account does not have a QuickBooks ID.';
							throw new Exception($qb_msg);
						}
					}
				}
				if ($out) {
					$query2 = "SELECT `category_level1`, `category_level2`, `category_level3` FROM account_category WHERE `id`='$account_category_id'";
					$result2 = mysqli_query($conn, $query2);
					if (!$result2) {
						$out = false;
						$qb_msg = 'Error: Category selection issue.';
						throw new Exception($qb_msg);
					}
					if ($out) {
						$row2 = mysqli_fetch_array($result2);
						$account_classification = $row2[0];
						$account_type = $row2[1];
						$account_sub_type = $row2[2];

						$account_array = array(
							'account_name' => $ac_name,
							'account_type' => $account_type,
							'account_sub_type' => $account_sub_type,
							'account_classification' => $account_classification,
							'parent_account_id' => $parentAccountQbId
						);

						try {
							$qb_result = QBAddAccount($account_array);
							$qb_msg = $qb_result['message'];
							if ((isset($qb_result['status'])) && ($qb_result['status'] == 'success')) {
								$qb_account_id = $qb_result['qb_account_id'];
								if ($qb_account_id != '') {
									if ($last_id != '') {
										$query = "UPDATE `accounts` SET `qb_account_id`='$qb_account_id', `qb_status`=1  WHERE `id`='$last_id'";
										$result = mysqli_query($conn, $query);
										if (!$result) {
											$out = false;
											$qb_msg = "Error: QuickBooks account ID update error";
											throw new Exception($qb_msg);
										}
									} else {
										$out = false;
										$qb_msg = "Error: No last account ID found";
										throw new Exception($qb_msg);
									}
								} else {
									$out = false;
									throw new Exception("Error: Quickbooks account ID is null");
								}
							} else {
								$out = false;
								throw new Exception($qb_msg);
							}
						} catch (Exception $e) {
							$out = false;
							$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
							$qb_result['status'] = 'error';
							throw new Exception($qb_msg);
						}
					}
				}
			}
		}
		// Commit the transaction
		mysqli_commit($conn);
	} catch (Exception $e) {
		mysqli_rollback($conn);
		$message = $e->getMessage();
	}
	$message = $message . ' ' . $qb_msg;
	return $out;
}

// updated by nirmal 21_12_8
function editCahrtOfAccounts()
{
	global $message, $account_id;
	$id = $account_id = $_POST['id'];
	$category_l1 = $_POST['category_l1'];
	$ac_name = $_POST['ac_name'];
	$out = true;
	$qb_msg = '';
	$message = 'Account was updated successfully. ';
	include('config.php');

	if (isset($_POST['payment_ac']))
		$payment_ac = 1;
	else
		$payment_ac = 0;
	if (isset($_POST['bank_ac']))
		$bank_ac = 1;
	else
		$bank_ac = 0;
	if (isset($_POST['category_l2']))
		$category_l2 = $_POST['category_l2'];
	else
		$category_l2 = '';
	if (isset($_POST['category_l3']))
		$category_l3 = $_POST['category_l3'];
	else
		$category_l3 = '';
	if ($_POST['bank_fee'] == '')
		$bank_fee = 0;
	else
		$bank_fee = $_POST['bank_fee'];

	try {
		// Start the transaction
		mysqli_begin_transaction($conn);

		if ($out) {
			// Sanitize and normalize the account name
			$ac_name = trim($ac_name); // Remove leading and trailing whitespace
			$ac_name = str_replace('-', '', $ac_name); // Remove hyphens
			$ac_name = preg_replace('/[^a-zA-Z0-9\s\(\)\/]/', '', $ac_name); // Allow letters, numbers, spaces, (), and /
			$ac_name = preg_replace('/\s+/', ' ', $ac_name); // Replace multiple spaces with a single space
			$ac_name = mysqli_real_escape_string($conn, $ac_name); // Escape for SQL

			// Check if the sanitized account name already exists (excluding the current account)
			$query = "SELECT * FROM accounts WHERE LOWER(name) = LOWER('$ac_name') AND id != '$id'";
			$check_result = mysqli_query($conn, $query);

			if (mysqli_num_rows($check_result) > 0) {
				$out = false;
				$message = 'Error: Account name already exists.';
				throw new Exception($message);
			}
		}


		if (!is_numeric($bank_fee)) {
			$out = false;
			$message = 'Error: Bank processing fee must be a numeric value';
			throw new Exception($message);
		}

		if (($bank_fee != 0) && ($bank_fee < 0)) {
			$out = false;
			$message = 'Error: Bank processing fee cannot be a negative value';
			throw new Exception($message);
		}

		if ($out) {
			$query = "SELECT id FROM account_category WHERE category_level1='$category_l1' AND category_level2='$category_l2' AND category_level3='$category_l3'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$message = 'Error: Category selection issue';
				$out = false;
				throw new Exception($message);
			} else {
				$row = mysqli_fetch_assoc($result);
				$account_category_id = $row['id'];
			}
		}

		if ($out) {
			if ($account_category_id == '') {
				$message = 'Error: Category selection issue';
				$out = false;
				throw new Exception($message);
			}
		}

		if ($out) {
			if ($bank_ac == 0) {
				$bank_fee = 0;
			}
			$query = "UPDATE `accounts` SET `name`='$ac_name',`category`='$account_category_id',`payment_ac`='$payment_ac',`bank_ac`='$bank_ac',`processing_fee`='$bank_fee', `shows_in_payments_bank_list` = '$bank_ac' WHERE id='$id'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$message = 'Account could not be updated';
				$out = false;
				throw new Exception($message);
			}
		}

		if ($out) {
			if ((isQuickBooksActive(1))) {
				$account_id = '';
				$query2 = "SELECT `category_level1`, `category_level2`, `category_level3` FROM account_category WHERE `id`='$account_category_id'";
				$result2 = mysqli_query($conn, $query2);
				if (!$result2) {
					$out = false;
					$message = 'Error: Category selection issue.';
					throw new Exception($message);
				} else {
					$row2 = mysqli_fetch_array($result2);
					$account_classification = $row2[0];
					$account_type = $row2[1];
					$account_sub_type = $row2[2];

					$query3 = "SELECT `qb_account_id` FROM accounts WHERE `id` = '$id'";
					$result3 = mysqli_query($conn, $query3);
					if ($result3) {
						$row3 = mysqli_fetch_array($result3);
						$account_id = $row3[0];
					}

					if ($account_id != '') {
						$account_array = array('account_name' => $ac_name, 'account_type' => $account_type, 'account_sub_type' => $account_sub_type, 'account_classification' => $account_classification, 'account_id' => $account_id);
						$qb_result = QBAccountUpdate($account_array);
						$qb_account_id = $qb_result['qb_account_id'];
						$qb_msg = $qb_result['message'];

						if ((isset($qb_result['status'])) && ($qb_result['status'] == 'success')) {
							if ($qb_account_id != '') {
								$query = "UPDATE `accounts` SET `qb_account_id`='$qb_account_id', `qb_status`=1  WHERE `id`='$id'";
								$result = mysqli_query($conn, $query);
								if (!$result) {
									$out = false;
									$qb_msg = "Error: QuickBooks account ID update error";
									throw new Exception($qb_msg);
								}
							} else {
								$out = false;
								throw new Exception("Error: Quickbooks account ID is null");
							}
						} else {
							$out = false;
							throw new Exception($qb_msg);
						}
					} else {
						$out = false;
						throw new Exception("Error: Quickbooks account ID is null");
					}
				}
			}
		}
		// Commit the transaction
		mysqli_commit($conn);
	} catch (Exception $e) {
		mysqli_rollback($conn);
		$message = $e->getMessage();
	}
	$message = $message . ' ' . $qb_msg;
	return $out;
}

// update by nirmal 05_03_2024 quickbooks account activate deactivate
function setStCahrtOfAccounts()
{
	global $message;
	$id = $_GET['id'];
	$status = $_GET['status'];
	$qb_msg = $qb_account_id = '';

	if ($status == 0) {
		$msg = 'disabled';
		$account_status = false;
	} else {
		$msg = 'enabled';
		$account_status = true;
	}
	$out = true;
	include('config.php');

	try {
		// Start the transaction
		mysqli_begin_transaction($conn);

		$result = mysqli_query($conn, "UPDATE accounts SET `status`='$status' WHERE system_ac=0 AND id='$id'");
		if ($result) {
			$message = "Account was $msg successfully.";
		} else {
			$message = "Account could not be $msg! due to a database error: " . mysqli_error($conn);
			$out = false;
			throw new Exception($message);
		}
		if ($out) {
			if (isQuickBooksActive(1)) {
				$query1 = "SELECT `qb_account_id` FROM accounts WHERE `qb_account_id` IS NOT NULL AND id='$id'";
				$result1 = mysqli_query($conn2, $query1);
				if (($result1) && (mysqli_num_rows($result1) > 0)) {
					$row1 = mysqli_fetch_assoc($result1);
					$account_id = $row1['qb_account_id'];
					$account_array = array('account_id' => $account_id, 'account_status' => $account_status);
					try {
						$qb_result = QBAccountStatusChange($account_array);
						$qb_msg = $qb_result['message'];
						$qb_account_id = $qb_result['qb_account_id'];

						if (isset($qb_result['status']) && ($qb_result['status'] == 'success')) {
							if ($qb_account_id != '') {
								$query2 = "UPDATE `accounts` SET `qb_status`='$status' WHERE `qb_account_id` = '$qb_account_id'";
								$result2 = mysqli_query($conn, $query2);
								if (!$result2) {
									$out = false;
									$qb_msg = "Error: QuickBooks account status update error";
									throw new Exception($qb_msg);
								}
							} else {
								$out = false;
								throw new Exception("Error: Quickbooks account ID is null");
							}
						} else {
							$out = false;
							throw new Exception($qb_msg);
						}
					} catch (Exception $e) {
						$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
						$qb_result['status'] = 'error';
						$out = false;
					}
				}
			}
		}
		// Commit the transaction
		mysqli_commit($conn);
	} catch (Exception $e) {
		mysqli_rollback($conn);
		$message = $e->getMessage();
	}
	$message = $message . ' ' . $qb_msg;
	return $out;
}

function date_sort($a, $b)
{
	return strtotime($a) - strtotime($b);
}

// update by nirmal 21_12_16
// update by nirmal 06_06_2024 (added cust group filter)
// update by nirmal 23_07_2024 (add account sub system)
// update by nirmal 15_10_2024 (fixed sql error (selecting wrong table of transfer)
function getAccountHistory()
{
	global $account_name, $date, $id_list, $type, $payee, $dr, $cr, $from_date, $to_date, $statting_balance, $group, $conn;
	$id = $_GET['id'];
	if (isset($_GET['from_date']))
		$from_date = $_GET['from_date'];
	else
		$from_date = date("Y-m-d", time() - (60 * 60 * 20 * 30));
	if (isset($_GET['to_date']))
		$to_date = $_GET['to_date'];
	else
		$to_date = dateNow();
	$jo_dr = $jo_cr = $ex_dr = $ex_cr = $oo_date = $jo_id = $ex_id = $date = array();
	$statting_balance = 0;
	if (isset($_GET['group'])) {
		$group = $_REQUEST['group'];
	} else {
		$group = '';
	}

	if ($group == 'all') {
		$groupsearch = '';
	} else {
		$groupsearch = "AND cu.associated_group='" . $group . "'";
	}

	if (isset($_GET['sub_system'])) {
		$sub_system = $_REQUEST['sub_system'];
		if ($sub_system == 'all') {
			$sub_system_search = '';
		} else {
			$sub_system_search = "AND cu.sub_system='" . $sub_system . "'";
		}
	} else {
		$sub_system_search = '';
	}

	include('config.php');

	$query = "SELECT name,id,bank_ac FROM accounts WHERE id='$id'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$account_name = $row[0];
	$ac_id_tmp = $row[1];
	$account_bank_ac = $row[2];

	$query = "SELECT SUM(ji.amount) FROM accounts ac, journal_main jm, journal_item ji WHERE jm.journal_id=ji.journal_id AND ji.account=ac.id AND jm.`status`=1 AND ac.id='$id' AND jm.journal_date < '$from_date'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$statting_balance = $row[0];

	$query = "SELECT jm.journal_date,jm.journal_id,ji.cr_dr,ji.amount FROM accounts ac, journal_main jm, journal_item ji WHERE jm.journal_id=ji.journal_id AND ji.account=ac.id AND jm.`status`=1 AND ac.id='$id' AND jm.journal_date BETWEEN '$from_date' AND '$to_date'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$jo_id_tmp = $row[1];
		$oo_date[] = $row[0];
		$oo_id[] = $row[1];
		$oo_type[] = 'Journal';
		if ($row[2] == 'dr') {
			$oo_dr[] = $row[3];
			$oo_cr[] = 0;
			$query1 = "SELECT ac.name FROM accounts ac, journal_item ji WHERE ac.id=ji.account AND ji.journal_id='$jo_id_tmp' AND ji.cr_dr='cr'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			if ($row1[0] != '')
				$oo_payee[] = $row1[0];
			else
				$oo_payee[] = '';
		}
		if ($row[2] == 'cr') {
			$oo_dr[] = null;
			$oo_cr[] = -$row[3];
			$query1 = "SELECT ac.name FROM accounts ac, journal_item ji WHERE ac.id=ji.account AND ji.journal_id='$jo_id_tmp' AND ji.cr_dr='dr'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			if ($row1[0] != '')
				$oo_payee[] = $row1[0];
			else
				$oo_payee[] = '';
		}
	}

	$query = "SELECT SUM(ei.amount) FROM accounts ac, expense_main em, expense_item ei WHERE em.expense_id=ei.expense_id AND ac.id=ei.account AND em.`status`=1 AND em.from_account='$id' AND em.expense_date < '$from_date'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$statting_balance -= $row[0];

	$query = "SELECT em.expense_date,em.expense_id,ac.name,ei.amount FROM accounts ac, expense_main em, expense_item ei WHERE em.expense_id=ei.expense_id AND ac.id=ei.account AND em.`status`=1 AND em.from_account='$id' AND em.expense_date BETWEEN '$from_date' AND '$to_date'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$oo_date[] = $row[0];
		$oo_id[] = $row[1];
		$oo_type[] = 'Expense';
		$oo_payee[] = $row[2];
		$oo_cr[] = $row[3];
		$oo_dr[] = 0;
	}

	$query = "SELECT SUM(ei.amount) FROM accounts ac, expense_main em, expense_item ei WHERE em.expense_id=ei.expense_id AND ac.id=em.from_account AND em.`status`=1 AND ei.account='$id' AND em.expense_date < '$from_date'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$statting_balance += $row[0];

	$query = "SELECT em.expense_date,em.expense_id,ac.name,ei.amount FROM accounts ac, expense_main em, expense_item ei WHERE em.expense_id=ei.expense_id AND ac.id=em.from_account AND em.`status`=1 AND ei.account='$id' AND em.expense_date BETWEEN '$from_date' AND '$to_date'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$oo_date[] = $row[0];
		$oo_id[] = $row[1];
		$oo_type[] = 'Expense';
		$oo_payee[] = $row[2];
		$oo_cr[] = 0;
		$oo_dr[] = $row[3];
	}
	//--------------Check for Loan CR--------------------//
	$query = "SELECT SUM(amount) FROM loan_main WHERE from_account='$id' AND start_date < '$from_date'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$statting_balance -= $row[0];

	$query = "SELECT start_date,id,amount FROM loan_main WHERE from_account='$id' AND start_date BETWEEN '$from_date' AND '$to_date'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$oo_date[] = $row[0];
		$oo_id[] = $row[1];
		$oo_type[] = 'EMP Loan';
		$oo_payee[] = 'EMP Loan';
		$oo_cr[] = $row[2];
		$oo_dr[] = 0;
	}
	//--------------Bank Accounts--------------------//
	if ($account_bank_ac == 1) {
		$query = "SELECT SUM(py.amount) FROM payment py, cust cu WHERE py.`cust`=cu.id AND py.`status`=0 AND py.payment_type=3 AND py.bank_trans='$ac_id_tmp' AND date(py.payment_date) < '$from_date' $groupsearch $sub_system_search";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance += $row[0];

		$query = "SELECT date(py.payment_date),py.id,cu.name,py.amount FROM payment py, cust cu WHERE py.`cust`=cu.id AND py.`status`=0 AND py.payment_type=3 AND py.bank_trans='$ac_id_tmp' AND date(py.payment_date) BETWEEN '$from_date' AND '$to_date' $groupsearch $sub_system_search";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'Cust Payment (Bank)';
			$oo_payee[] = $row[2];
			$oo_cr[] = 0;
			$oo_dr[] = $row[3];
		}
	}

	if ($account_name == 'Cash in Hand') {
		$query = "SELECT SUM(py.amount) FROM payment py, cust cu WHERE py.`cust`=cu.id AND py.`status`=0 AND py.payment_type=1 AND date(py.payment_date) < '$from_date' $groupsearch $sub_system_search";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance += $row[0];
		$query = "SELECT SUM(lp.capital_pay+lp.interest_pay) FROM loan_main lm, loan_pay lp WHERE lm.id=lp.loan_id AND lp.payroll_id=0 AND lm.`status` IN (0,4) AND `date` < '$from_date'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance += $row[0];

		$query = "SELECT date(py.payment_date),py.id,cu.name,py.amount FROM payment py, cust cu WHERE py.`cust`=cu.id AND py.`status`=0 AND py.payment_type=1 AND date(py.payment_date) BETWEEN '$from_date' AND '$to_date' $groupsearch $sub_system_search";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'Cust Payment (Cash)';
			$oo_payee[] = $row[2];
			$oo_cr[] = 0;
			$oo_dr[] = $row[3];
		}

		$query = "SELECT `date`,lm.id,up.username,SUM(lp.capital_pay+lp.interest_pay) FROM loan_main lm, loan_pay lp, userprofile up WHERE lm.id=lp.loan_id AND lm.emp_id=up.id AND lp.payroll_id=0 AND lm.`status` IN (0,4) AND `date` BETWEEN '$from_date' AND '$to_date'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'Direct Settlement';
			$oo_payee[] = ucfirst($row[2]);
			$oo_cr[] = 0;
			$oo_dr[] = $row[3];
		}
	}

	if ($account_name == 'Undeposited Cheques') {
		$query = "SELECT SUM(py.amount) FROM payment py, cust cu WHERE py.`cust`=cu.id AND py.`status`=0 AND py.payment_type=2 AND py.chque_clear=0 AND date(py.payment_date) < '$from_date' $groupsearch $sub_system_search";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance += $row[0];

		$query = "SELECT date(py.payment_date),py.id,cu.name,py.amount FROM payment py, cust cu WHERE py.`cust`=cu.id AND py.`status`=0 AND py.payment_type=2 AND py.chque_clear=0 AND date(py.payment_date) BETWEEN '$from_date' AND '$to_date' $groupsearch $sub_system_search";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'Cust Payment (Cheque)';
			$oo_payee[] = $row[2];
			$oo_cr[] = 0;
			$oo_dr[] = $row[3];
		}
	}

	if ($account_name == 'Account Receivable') {
		$tttt = 0;
		$pay_cust1 = $pay_amount1 = array();
		$query = "SELECT `cust`, SUM(amount) FROM payment WHERE `status`=0 AND chque_return=0 AND (payment_type=1 OR chque_clear=1 ) GROUP BY `cust`";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$pay_cust1[] = $row[0];
			$pay_amount1[] = $row[1];
		}
		$query = "SELECT bm.`cust`, SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, cust cu WHERE bm.exclude=0 AND bm.`lock`=1 AND bm.`status` NOT IN (0,7) AND bm.`cust`=cu.`id` $groupsearch $sub_system_search GROUP BY bm.`cust`";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$key = array_search($row[0], $pay_cust1);
			if ($key > -1)
				$balance = $row[1] - $pay_amount1[$key];
			else
				$balance = $row[1];
			if ($balance > 0) {
				$tttt += $balance;
				$query1 = "SELECT SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm WHERE bm.exclude=0 AND bm.`lock`=1 AND bm.`status` NOT IN (0,7) AND bm.`cust`='$row[0]' AND date(bm.billed_timestamp) < '$from_date'";
				$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
				$statting_balance += $row1[0];
				$query1 = "SELECT SUM(amount) FROM payment WHERE `status`=0 AND payment_type=1 AND `cust`='$row[0]' AND date(payment_date) < '$from_date'";
				$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
				$statting_balance -= $row1[0];
				$query1 = "SELECT SUM(amount) FROM payment WHERE `status`=0 AND payment_type=2 AND chque_return=0 AND chque_clear=1 AND `cust`='$row[0]' AND chque_deposit_date < '$from_date'";
				$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
				$statting_balance -= $row1[0];

				$query1 = "SELECT SUM(amount) FROM payment WHERE `status`=0 AND chque_return=0 AND ((payment_type=1 AND date(payment_date) BETWEEN '$from_date' AND '$to_date' ) OR (chque_clear=1 AND chque_deposit_date BETWEEN '$from_date' AND '$to_date' )) AND `cust`='$row[0]'";
				$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
				$balance2 = $row1[0];
				$query1 = "SELECT MAX(date(bm.billed_timestamp)),cu.name,SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, cust cu WHERE bm.`cust`=cu.id AND bm.exclude=0 AND bm.`lock`=1 AND bm.`status` NOT IN (0,7) AND date(bm.billed_timestamp) BETWEEN '$from_date' AND '$to_date' AND bm.`cust`='$row[0]'";
				$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
				$balance2 = $row1[2] - $balance2;
				$oo_date[] = $row1[0];
				$oo_id[] = '';
				$oo_type[] = 'Account Receivable';
				$oo_payee[] = $row1[1];
				if ($balance2 > 0) {
					$oo_dr[] = $balance2;
					$oo_cr[] = 0;
				} else {
					$oo_cr[] = -$balance2;
					$oo_dr[] = 0;
				}
			}
		}
	}

	if ($account_name == 'Unearned Revenue') {
		$pay_cust1 = $pay_amount1 = array();
		$query = "SELECT `cust`,SUM(amount) FROM payment WHERE `status`=0 AND chque_return=0 AND (payment_type=1 OR chque_clear=1 ) GROUP BY `cust`";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$pay_cust1[] = $row[0];
			$pay_amount1[] = $row[1];
		}
		$query = "SELECT bm.`cust`,SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, cust cu WHERE bm.exclude=0 AND bm.`lock`=1 AND bm.`status` NOT IN (0,7) AND bm.`cust`=cu.`id` $groupsearch $sub_system_search GROUP BY bm.`cust`";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$key = array_search($row[0], $pay_cust1);
			if ($key > -1)
				$balance = $row[1] - $pay_amount1[$key];
			else
				$balance = $row[1];
			if ($balance < 0) {
				$balance2 = 0;
				$query1 = "SELECT SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm WHERE bm.exclude=0 AND bm.`lock`=1 AND bm.`status` NOT IN (0,7) AND bm.`cust`='$row[0]' AND date(bm.billed_timestamp) < '$from_date'";
				$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
				$statting_balance += $row1[0];
				$query1 = "SELECT SUM(amount) FROM payment WHERE `status`=0 AND payment_type=1 AND `cust`='$row[0]' AND date(payment_date) < '$from_date'";
				$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
				$statting_balance -= $row1[0];
				$query1 = "SELECT SUM(amount) FROM payment WHERE `status`=0 AND payment_type=2 AND chque_return=0 AND chque_clear=1 AND `cust`='$row[0]' AND chque_deposit_date < '$from_date'";
				$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
				$statting_balance -= $row1[0];

				$query1 = "SELECT SUM(amount) FROM payment WHERE `status`=0 AND chque_return=0 AND ((payment_type=1 AND date(payment_date) BETWEEN '$from_date' AND '$to_date' ) OR (chque_clear=1 AND chque_deposit_date BETWEEN '$from_date' AND '$to_date' )) AND `cust`='$row[0]'";
				$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
				$balance2 = $row1[0];
				$query1 = "SELECT MAX(date(bm.billed_timestamp)),cu.name,SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, cust cu WHERE bm.`cust`=cu.id AND bm.exclude=0 AND bm.`lock`=1 AND bm.`status` NOT IN (0,7) AND date(bm.billed_timestamp) BETWEEN '$from_date' AND '$to_date' AND bm.`cust`='$row[0]'";
				$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
				$balance2 = $balance2 - $row1[2];
				$oo_date[] = $row1[0];
				$oo_id[] = '';
				$oo_type[] = 'Unearned Revenue';
				$oo_payee[] = $row1[1];
				if ($balance2 > 0) {
					$oo_cr[] = $balance2;
					$oo_dr[] = 0;
				} else {
					$oo_dr[] = -$balance2;
					$oo_cr[] = 0;
				}
			}
		}
	}

	if ($account_name == 'Sales') {
		$query = "SELECT SUM(bi.qty*bi.unit_price) FROM bill_main bm, bill bi, cust cu WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`=cu.id AND bm.exclude=0 AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND date(bm.billed_timestamp) < '$from_date' $groupsearch $sub_system_search";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance -= $row[0];

		$query = "SELECT date(bm.billed_timestamp),bm.invoice_no,cu.name,SUM(bi.qty*bi.unit_price) FROM bill_main bm, bill bi, cust cu WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`=cu.id AND bm.exclude=0 AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND date(bm.billed_timestamp) BETWEEN '$from_date' AND '$to_date' $groupsearch $sub_system_search GROUP BY bm.invoice_no";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'Bill';
			$oo_payee[] = $row[2];
			if ($row[3] > 0) {
				$oo_cr[] = $row[3];
				$oo_dr[] = 0;
			} else {
				$oo_cr[] = 0;
				$oo_dr[] = -$row[3];
			}
		}
	}

	if ($account_name == 'Payroll Expenses') {
		$query = "SELECT SUM(pd.amount) FROM payroll_main pm, payroll_data pd WHERE pm.payroll_no=pd.payroll_no AND pd.amount>0 AND date(pm.generated_date) < '$from_date'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance += $row[0];

		$query = "SELECT date(pm.generated_date),pm.payroll_no,SUM(pd.amount) FROM payroll_main pm, payroll_data pd WHERE pm.payroll_no=pd.payroll_no AND pd.amount>0 AND date(pm.generated_date) BETWEEN '$from_date' AND '$to_date' GROUP BY pm.payroll_no ";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'Payroll Expenses';
			$oo_payee[] = 'Payroll No: ' . str_pad($row[1], 7, "0", STR_PAD_LEFT);
			$oo_cr[] = 0;
			$oo_dr[] = $row[2];
		}
	}
	if ($account_name == 'Payroll Payble') {
		$query = "SELECT SUM(pd.amount) FROM payroll_main pm, payroll_data pd WHERE pm.payroll_no=pd.payroll_no AND date(generated_date) < '$from_date'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance -= round($row[0], 2);

		$query = "SELECT date(pm.generated_date),pm.payroll_no,SUM(pd.amount) FROM payroll_main pm, payroll_data pd WHERE pm.payroll_no=pd.payroll_no AND date(generated_date) BETWEEN '$from_date' AND '$to_date' GROUP BY pm.payroll_no";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'Payroll Payble';
			$oo_payee[] = 'Payroll Expenses';
			$oo_cr[] = round($row[2], 2);
			$oo_dr[] = 0;
		}
	}
	if ($account_name == 'ETF Expense (Employer)') {
		$query = "SELECT SUM(employer_etf) FROM payroll_main WHERE date(generated_date) < '$from_date'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance = $row[0];

		$query = "SELECT date(generated_date),payroll_no,employer_etf FROM payroll_main WHERE date(generated_date) BETWEEN '$from_date' AND '$to_date'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'ETF Expense (Employer)';
			$oo_payee[] = 'Payroll No: ' . str_pad($row[1], 7, "0", STR_PAD_LEFT);
			$oo_cr[] = 0;
			$oo_dr[] = $row[2];
		}
	}
	if ($account_name == 'EPF Expense (Employer)') {
		$query = "SELECT SUM(employer_epf) FROM payroll_main WHERE date(generated_date) < '$from_date'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance = $row[0];

		$query = "SELECT date(generated_date),payroll_no,employer_epf FROM payroll_main WHERE date(generated_date) BETWEEN '$from_date' AND '$to_date'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'EPF Expense (Employer)';
			$oo_payee[] = 'Payroll No: ' . str_pad($row[1], 7, "0", STR_PAD_LEFT);
			$oo_cr[] = 0;
			$oo_dr[] = $row[2];
		}
	}
	if ($account_name == 'EPF Payble') {
		$query = "SELECT SUM(pm.employer_epf) FROM payroll_main pm WHERE date(pm.generated_date) < '$from_date'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance -= $row[0];
		$query = "SELECT SUM(-pd.amount) FROM payroll_main pm, payroll_data pd WHERE pm.payroll_no=pd.payroll_no AND pd.`type`='EPF Emp' AND date(pm.generated_date) < '$from_date'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance -= $row[0];

		$query = "SELECT date(pm.generated_date),pm.payroll_no,pm.employer_epf+SUM(-pd.amount) FROM payroll_main pm, payroll_data pd WHERE pm.payroll_no=pd.payroll_no AND pd.`type`='EPF Emp' AND date(pm.generated_date) BETWEEN '$from_date' AND '$to_date' GROUP BY pm.payroll_no";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'EPF Payble';
			$oo_payee[] = 'EPF Expense (Employer) + Payroll Expenses';
			$oo_cr[] = $row[2];
			$oo_dr[] = 0;
		}
	}
	if ($account_name == 'ETF Payble') {
		$query = "SELECT SUM(employer_etf) FROM payroll_main WHERE date(generated_date) < '$from_date'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance -= $row[0];

		$query = "SELECT date(generated_date),payroll_no,employer_etf FROM payroll_main WHERE date(generated_date) BETWEEN '$from_date' AND '$to_date'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'ETF Expense (Employer)';
			$oo_payee[] = 'ETF Expense (Employer)';
			$oo_cr[] = $row[2];
			$oo_dr[] = 0;
		}
	}
	if ($account_name == 'Payroll Tax Payble') {
		$query = "SELECT SUM(-pd.amount) FROM payroll_main pm, payroll_data pd WHERE pm.payroll_no=pd.payroll_no AND pd.`type`='Tax' AND date(pm.generated_date) < '$from_date'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance -= $row[0];

		$query = "SELECT date(pm.generated_date),pm.payroll_no,SUM(-pd.amount) FROM payroll_main pm, payroll_data pd WHERE pm.payroll_no=pd.payroll_no AND pd.`type`='Tax' AND date(pm.generated_date) BETWEEN '$from_date' AND '$to_date' GROUP BY pm.payroll_no";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			if ($row[2] != 0) {
				$oo_date[] = $row[0];
				$oo_id[] = $row[1];
				$oo_type[] = 'Payroll Tax Payble';
				$oo_payee[] = 'Payroll Expenses';
				$oo_cr[] = $row[2];
				$oo_dr[] = 0;
			}
		}
	}
	if ($account_name == 'Payroll Withholdings') {
		$query = "SELECT date(pm.generated_date),pm.payroll_no,SUM(-pd.amount) FROM payroll_main pm, payroll_data pd WHERE pm.payroll_no=pd.payroll_no AND pd.`type`='Deductions' AND date(pm.generated_date) BETWEEN '$from_date' AND '$to_date' GROUP BY pm.payroll_no";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			if ($row[2] != 0) {
				$oo_date[] = $row[0];
				$oo_id[] = $row[1];
				$oo_type[] = 'Payroll Withholdings';
				$oo_payee[] = 'Payroll Expenses';
				$oo_cr[] = $row[2];
				$oo_dr[] = 0;
				//-------------------//
				$oo_date[] = $row[0];
				$oo_id[] = $row[1];
				$oo_type[] = 'Payroll Withholdings';
				$oo_payee[] = 'EMP Loan';
				$oo_cr[] = 0;
				$oo_dr[] = $row[2];
			}
		}
	}
	if ($account_name == 'EMP Loan') {
		$query = "SELECT lm.amount,lm.duration,lm.rate FROM loan_main lm WHERE lm.`status` IN (0,4) AND lm.start_date < '$from_date'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$statting_balance += round((loanInstallment($row[0], $row[2], $row[1]) * $row[1]), 2);
		}

		$query = "SELECT SUM(capital_pay+interest_pay) FROM loan_pay WHERE `date` < '$from_date'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance -= $row[0];

		$query = "SELECT lm.start_date,lm.id,lm.amount,lm.duration,lm.rate,ac.name FROM loan_main lm, accounts ac WHERE lm.from_account=ac.id AND lm.`status` IN (0,4) AND lm.start_date BETWEEN '$from_date' AND '$to_date'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'EMP Loan';
			$oo_payee[] = $row[5] . ' + EMP Loan Interest in Suspense';
			$oo_cr[] = 0;
			$oo_dr[] = round((loanInstallment($row[2], $row[4], $row[3]) * $row[3]), 2);
		}

		$query = "SELECT `date`,loan_id,SUM(capital_pay+interest_pay) FROM loan_pay WHERE payroll_id!=0 AND `date` BETWEEN '$from_date' AND '$to_date' GROUP BY payroll_id";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'Payroll Settlement';
			$oo_payee[] = 'Payroll Payble';
			$oo_cr[] = $row[2];
			$oo_dr[] = 0;
		}
		$query = "SELECT lp.`date`,lp.loan_id,up.username,(lp.capital_pay+lp.interest_pay) FROM loan_main lm, loan_pay lp, userprofile up WHERE lm.id=lp.loan_id AND lm.emp_id=up.id AND lp.payroll_id=0 AND lp.`date` BETWEEN '$from_date' AND '$to_date'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'Direct Settlement';
			$oo_payee[] = ucfirst($row[2]);
			$oo_cr[] = $row[3];
			$oo_dr[] = 0;
		}
	}

	if ($account_name == 'EMP Loan Interest in Suspense') {
		$query = "SELECT lm.amount,lm.duration,lm.rate FROM loan_main lm WHERE lm.`status` IN (0,4) AND lm.start_date < '$from_date'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$statting_balance -= round((loanInstallment($row[0], $row[2], $row[1]) * $row[1]), 2) - $row[0];
		}
		$query = "SELECT lm.start_date,lm.id,lm.amount,lm.duration,lm.rate FROM loan_main lm WHERE lm.`status` IN (0,4) AND lm.start_date BETWEEN '$from_date' AND '$to_date'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			if ($row[4] != 0) {
				$oo_date[] = $row[0];
				$oo_id[] = $row[1];
				$oo_type[] = 'EMP Loan';
				$oo_payee[] = 'EMP Loan';
				$oo_cr[] = round((loanInstallment($row[2], $row[4], $row[3]) * $row[3]), 2) - $row[2];
				$oo_dr[] = 0;
			}
		}
		$query = "SELECT SUM(interest_pay) FROM loan_pay WHERE `date` < '$from_date'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance += $row[0];

		$query = "SELECT `date`,loan_id,interest_pay FROM loan_pay WHERE `date` BETWEEN '$from_date' AND '$to_date'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			if ($row[2] != 0) {
				$oo_date[] = $row[0];
				$oo_id[] = $row[1];
				$oo_type[] = 'EMP Loan';
				$oo_payee[] = 'EMP Loan Interest income';
				$oo_cr[] = 0;
				$oo_dr[] = $row[2];
			}
		}
	}
	if ($account_name == 'EMP Loan Interest income') {
		$query = "SELECT SUM(interest_pay) FROM loan_pay WHERE `date` < '$from_date'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance -= $row[0];

		$query = "SELECT `date`,loan_id,interest_pay FROM loan_pay WHERE `date` BETWEEN '$from_date' AND '$to_date'";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			if ($row[2] != 0) {
				$oo_date[] = $row[0];
				$oo_id[] = $row[1];
				$oo_type[] = 'EMP Loan';
				$oo_payee[] = 'EMP Loan Interest in Suspense';
				$oo_cr[] = $row[2];
				$oo_dr[] = 0;
			}
		}
	}
	if ($account_name == 'Inventory Asset') {
		$query1 = "SELECT SUM(c_price*qty) FROM inventory_qty";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$inventory_total = $row1[0];
		$query1 = "SELECT SUM(c_price*qty) FROM inventory_new";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$inventory_total += $row1[0];
		//$query1="SELECT SUM(c_price*qty) FROM transfer tr WHERE `status` IN (0,4)";
		$query1 = "SELECT SUM(tr.`c_price`*tr.`qty`) FROM `transfer` tr, `transfer_main` tm WHERE tm.`status` IN (0,4)";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$inventory_total += $row1[0];
		$query1 = "SELECT SUM(bi.qty*bi.cost) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.exclude=0 AND bm.`status` NOT IN (0,7) AND bm.`lock`=0";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$inventory_total += $row1[0];
		$oo_date[] = dateNow();
		$oo_id[] = 'show_all_item&category=all&store=1';
		$oo_type[] = 'Inventory Asset';
		$oo_payee[] = 'Shipment';
		$oo_cr[] = 0;
		$oo_dr[] = $inventory_total;

	}
	$query = "SELECT SUM(py.amount) FROM payment py, accounts ac WHERE py.chque_deposit_bank=ac.id AND py.`status`=0 AND py.payment_type=2 AND py.chque_clear=1 AND ac.id='$id' AND date(py.chque_deposit_date) < '$from_date'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$statting_balance += $row[0];

	// removed sum function py.amount
	// $query="SELECT date(py.chque_deposit_date),py.id,cu.name,SUM(py.amount) FROM payment py, accounts ac, cust cu WHERE py.chque_deposit_bank=ac.id AND py.`cust`=cu.id AND py.`status`=0 AND py.payment_type=2 AND py.chque_clear=1 AND ac.id='$id' AND date(py.chque_deposit_date) BETWEEN '$from_date' AND '$to_date'";
	$query = "SELECT date(py.chque_deposit_date),py.id,cu.name,py.amount FROM payment py, accounts ac, cust cu WHERE py.chque_deposit_bank=ac.id AND py.`cust`=cu.id AND py.`status`=0 AND py.payment_type=2 AND py.chque_clear=1 AND ac.id='$id' AND date(py.chque_deposit_date) BETWEEN '$from_date' AND '$to_date' $groupsearch $sub_system_search";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		if ($row[0] != NULL) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'Cust Payment (Cheque)';
			$oo_payee[] = $row[2];
			$oo_cr[] = 0;
			$oo_dr[] = $row[3];
		}
	}

	// Card Payment Calculations (added by nirmal 21_12_16)
	$query = "SELECT SUM(py.amount) FROM payment py, accounts ac WHERE py.bank_trans=ac.id AND py.`status`='0' AND py.payment_type='4' AND ac.id='$id' AND date(py.payment_date) < '$from_date'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$statting_balance += $row[0];

	$query = "SELECT date(py.payment_date),py.id,cu.name,py.amount FROM payment py, accounts ac, cust cu WHERE py.bank_trans=ac.id AND py.`cust`=cu.id AND py.`status`='0' AND py.payment_type='4' AND ac.id='$id' AND date(py.payment_date) BETWEEN '$from_date' AND '$to_date' $groupsearch $sub_system_search";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		if ($row[0] != NULL) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'Cust Payment (Card)';
			$oo_payee[] = $row[2];
			$oo_cr[] = 0;
			$oo_dr[] = $row[3];
		}
	}

	$query = "SELECT count(id) FROM supplier WHERE `name`='$account_name'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$sup_acc_count = $row[0];
	if ($sup_acc_count == 1) {
		$query = "SELECT SUM(ins.cost*ins.added_qty) FROM shipment_main sm, inventory_shipment ins, supplier su WHERE sm.id=ins.shipment_no AND sm.`supplier`=su.id AND sm.`status`!='3' AND su.name='$account_name' AND sm.shipment_date < '$from_date'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$statting_balance -= $row[0];

		$query = "SELECT sm.shipment_date,sm.id,SUM(ins.cost*ins.added_qty) FROM shipment_main sm, inventory_shipment ins, supplier su WHERE sm.id=ins.shipment_no AND sm.`supplier`=su.id AND sm.`status`!='3' AND su.name='$account_name' AND sm.shipment_date BETWEEN '$from_date' AND '$to_date' GROUP BY sm.id";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$oo_date[] = $row[0];
			$oo_id[] = $row[1];
			$oo_type[] = 'Shipment';
			$oo_payee[] = 'Shipment No : ' . $row[1];
			$oo_cr[] = $row[2];
			$oo_dr[] = 0;
		}
	}

	$date1 = $oo_date;
	usort($date1, "date_sort");
	$date1 = array_values(array_unique($date1));
	for ($i = 0; $i < sizeof($date1); $i++) {
		for ($j = 0; $j < sizeof($oo_date); $j++) {
			if ($date1[$i] == $oo_date[$j]) {
				$date[] = $oo_date[$j];
				$id_list[] = $oo_id[$j];
				$type[] = $oo_type[$j];
				$payee[] = $oo_payee[$j];
				$dr[] = $oo_dr[$j];
				$cr[] = $oo_cr[$j];
			}
		}
	}
	// for from account dropdown (account, fin)
	if (isset($_GET['method'])) {
		return number_format($statting_balance, 2);
	}

}
//-----------------------------------REPORT-------------------------------------------------//
function generateBalanceSheet()
{
	global $initial_ship, $ass_ac_id, $ass_ac_name, $ass_ac_catL2, $ass_ac_catL3, $ass_ac_amount, $lia_ac_id, $lia_ac_name, $lia_ac_catL2, $lia_ac_catL3, $lia_ac_amount, $equ_ac_id, $equ_ac_name, $equ_ac_catL2, $equ_ac_catL3, $equ_ac_amount;
	$today = dateNow();
	$chque_deposited_bank = array();
	$to_date = $_GET['to_date'];
	include('config.php');
	$bill_cash = $bill_chque_deposited = 0;
	$suplier_list = [];

	$query = "SELECT name FROM supplier";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$suplier_list[] = $row[0];
	}
	$query = "SELECT ac.name,SUM(py.amount) FROM payment py, accounts ac WHERE py.chque_deposit_bank=ac.id AND py.`status`=0 AND py.payment_type=2 AND py.chque_clear=1 AND py.chque_deposit_date<='$to_date' GROUP BY py.chque_deposit_bank";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$chque_deposited_bank[] = $row[0];
		$chque_deposited_amount[] = $row[1];
	}

	$query = "SELECT ac.id,ac.name,acat.category_level2,acat.category_level3,ac.bank_ac FROM accounts ac, account_category acat WHERE ac.category=acat.id AND ac.`status`=1 AND acat.category_level1='Asset' ORDER BY acat.category_level2,acat.category_level3,ac.name";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$jo = $dr = $cr = 0;
		$ac_id_tmp = $row[0];
		$ac_name_tmp = $row[1];
		$ass_ac_id[] = $row[0];
		$ass_ac_name[] = $row[1];
		$ass_ac_catL2[] = $row[2];
		$ass_ac_catL3[] = $row[3];
		$ac_bank_tmp = $row[4];


		$query1 = "SELECT SUM(amount) FROM loan_main WHERE from_account='$ac_id_tmp' AND start_date<='$to_date'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$cr += $row1[0];

		if ($ac_bank_tmp == 1) {
			$query1 = "SELECT SUM(amount) FROM payment WHERE `status`=0 AND payment_type=3 AND bank_trans='$ac_id_tmp' AND date(payment_date)<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$dr = $row1[0];
		}
		if ($ac_bank_tmp == 1) {
			$query1 = "SELECT SUM(amount) FROM payment WHERE `status`=0 AND payment_type=4 AND bank_trans='$ac_id_tmp' AND date(payment_date)<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$dr += $row1[0];
		}
		if ($ac_name_tmp == 'Inventory Asset') {
			if ($to_date == $today) {
				$query1 = "SELECT SUM(c_price*qty) FROM inventory_qty";
				$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
				$dr += $row1[0];
				$query1 = "SELECT SUM(c_price*qty) FROM inventory_new";
				$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
				$dr += $row1[0];
				$query1 = "SELECT SUM(tr.c_price * tr.qty) FROM transfer_main tm, transfer tr WHERE tm.gtn_no=tr.gtn_no AND tm.`status` IN (0,4)";
				$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
				$dr += $row1[0];
				$query1 = "SELECT SUM(bi.qty*bi.cost) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.exclude=0 AND bm.`status` NOT IN (0,7) AND bm.`lock`=0";
				$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
				$dr += $row1[0];
			} else {
				$query1 = "SELECT total FROM inventory_history WHERE `date`='$to_date'";
				$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
				$dr = $row1[0];
			}
		}
		if ($ac_name_tmp == 'Cash in Hand') {
			$query1 = "SELECT SUM(amount) FROM payment WHERE `status`=0 AND payment_type=1 AND date(payment_date)<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$dr = $row1[0];
			$query1 = "SELECT SUM(lp.capital_pay+lp.interest_pay) FROM loan_main lm, loan_pay lp WHERE lm.id=lp.loan_id AND lp.payroll_id=0 AND lm.`status` IN (0,4) AND lp.`date`<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$dr += $row1[0];
		}
		if ($ac_name_tmp == 'Undeposited Cheques') {
			if ($to_date == $today)
				$query1 = "SELECT SUM(amount) FROM payment WHERE `status`=0 AND payment_type=2 AND chque_clear=0";
			else
				$query1 = "SELECT SUM(amount) FROM payment WHERE `status`=0 AND payment_type=2 AND chque_date>'$to_date' AND date(payment_date)<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$dr = $row1[0];
		}
		if ($ac_name_tmp == 'Account Receivable') {
			$dr = 0;
			if ($to_date == $today)
				$query1 = "SELECT `cust`,SUM(amount) FROM payment WHERE `status`=0 AND chque_return=0 AND (payment_type=1 OR chque_clear=1 ) GROUP BY `cust`";
			else
				$query1 = "SELECT `cust`,SUM(amount) FROM payment WHERE `status`=0 AND chque_return=0 AND (payment_type=1 OR (payment_type=2 AND chque_date<='$to_date' AND chque_clear=1) ) GROUP BY `cust`";
			$result1 = mysqli_query($conn, $query1);
			while ($row1 = mysqli_fetch_array($result1)) {
				$pay_cust1[] = $row1[0];
				$pay_amount1[] = $row1[1];
			}
			$query1 = "SELECT bm.`cust`,SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm WHERE bm.exclude=0 AND bm.`lock`=1 AND bm.`status` NOT IN (0,7) AND date(bm.billed_timestamp)<='$to_date' GROUP BY bm.`cust`";
			$result1 = mysqli_query($conn, $query1);
			while ($row1 = mysqli_fetch_array($result1)) {
				$key = array_search($row1[0], $pay_cust1);
				if ($key > -1)
					$balance = $row1[1] - $pay_amount1[$key];
				else
					$balance = $row1[1];
				if ($balance > 0)
					$dr += $balance;
			}
		}
		if ($ac_name_tmp == 'EMP Loan') {
			$query1 = "SELECT lm.amount,lm.duration,lm.rate FROM loan_main lm WHERE lm.`status` IN (0,4) AND lm.start_date<='$to_date'";
			$result1 = mysqli_query($conn, $query1);
			while ($row1 = mysqli_fetch_array($result1)) {
				$dr += round((loanInstallment($row1[0], $row1[2], $row1[1]) * $row1[1]), 2);
			}
			$query1 = "SELECT SUM(lp.capital_pay+lp.interest_pay) FROM loan_main lm, loan_pay lp WHERE lm.id=lp.loan_id AND lm.`status` IN (0,4) AND lp.`date`<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$cr = $row1[0];
		}
		$key = array_search($ac_name_tmp, $chque_deposited_bank);
		if ($key > -1) {
			$dr = $chque_deposited_amount[$key];
		}
		$query1 = "SELECT SUM(ei.amount) FROM expense_main em, expense_item ei WHERE em.expense_id=ei.expense_id AND em.from_account='$ac_id_tmp' AND expense_date<='$to_date'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$cr += $row1[0];
		$query1 = "SELECT SUM(ei.amount) FROM expense_main em, expense_item ei WHERE em.expense_id=ei.expense_id AND ei.account='$ac_id_tmp' AND expense_date<='$to_date'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$dr += $row1[0];
		$query1 = "SELECT SUM(ji.amount) FROM journal_main jm, journal_item ji WHERE jm.journal_id=ji.journal_id AND ji.account='$ac_id_tmp' AND journal_date<='$to_date'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$jo += $row1[0];
		$ass_ac_amount[] = ($jo + $dr - $cr);
	}
	//---------------Initial Shipments--------------------------//
	$query1 = "SELECT value FROM settings WHERE setting='initial_shipments'";
	$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
	$initial_ship = $row1[0];
	//---------------------------------------------------------//

	$query = "SELECT ac.id,ac.name,acat.category_level2,acat.category_level3 FROM accounts ac, account_category acat WHERE ac.category=acat.id AND ac.`status`=1 AND acat.category_level1='Liability' ORDER BY acat.category_level2,acat.category_level3,ac.name";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$jo = $dr = $cr = 0;
		$ac_id_tmp = $row[0];
		$ac_name_tmp = $row[1];
		$lia_ac_id[] = $row[0];
		$lia_ac_name[] = $row[1];
		$lia_ac_catL2[] = $row[2];
		$lia_ac_catL3[] = $row[3];

		$key = array_search($ac_name_tmp, $suplier_list);
		if ($key > -1) {
			$query1 = "SELECT SUM(ins.cost*ins.added_qty) FROM shipment_main sm, inventory_shipment ins, supplier su WHERE sm.id=ins.shipment_no AND sm.`supplier`=su.id AND sm.`status`!='3' AND su.name='$ac_name_tmp' AND sm.shipment_date<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$cr = $row1[0];
		}
		if ($ac_name_tmp == 'Payroll Liabilities') {
			$query1 = "SELECT SUM(payroll_total) FROM payroll_main WHERE date(generated_date)<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$cr = $row1[0];
		}
		if ($ac_name_tmp == 'Unearned Revenue') {
			$dr = 0;
			if ($to_date == $today)
				$query1 = "SELECT `cust`,SUM(amount) FROM payment WHERE `status`=0 AND chque_return=0 AND (payment_type=1 OR chque_clear=1 ) GROUP BY `cust`";
			else
				$query1 = "SELECT `cust`,SUM(amount) FROM payment WHERE `status`=0 AND chque_return=0 AND (payment_type=1 OR (payment_type=2 AND chque_date<='$to_date' AND chque_clear=1) ) GROUP BY `cust`";
			$result1 = mysqli_query($conn, $query1);
			while ($row1 = mysqli_fetch_array($result1)) {
				$pay_cust1[] = $row1[0];
				$pay_amount1[] = $row1[1];
			}
			$query1 = "SELECT bm.`cust`,SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm WHERE bm.exclude=0 AND bm.`lock`=1 AND bm.`status` NOT IN (0,7) AND date(bm.billed_timestamp)<='$to_date' GROUP BY bm.`cust`";
			$result1 = mysqli_query($conn, $query1);
			while ($row1 = mysqli_fetch_array($result1)) {
				$key = array_search($row1[0], $pay_cust1);
				if ($key > -1)
					$balance = $row1[1] - $pay_amount1[$key];
				else
					$balance = $row1[1];
				if ($balance < 0)
					$dr += $balance;
			}
		}
		if ($ac_name_tmp == 'EPF Payble') {
			$query1 = "SELECT SUM(pm.employer_epf) FROM payroll_main pm WHERE date(pm.generated_date)<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$cr = $row1[0];
			$query1 = "SELECT SUM(-pd.amount) FROM payroll_main pm ,payroll_data pd WHERE pm.payroll_no=pd.payroll_no AND pd.`type`='EPF Emp' AND date(pm.generated_date)<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$cr += $row1[0];
		}
		if ($ac_name_tmp == 'ETF Payble') {
			$query1 = "SELECT SUM(employer_etf) FROM payroll_main WHERE date(generated_date)<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$cr = $row1[0];
		}
		if ($ac_name_tmp == 'Payroll Payble') {
			$query = "SELECT SUM(pd.amount) FROM payroll_main pm ,payroll_data pd WHERE pm.payroll_no=pd.payroll_no AND date(pm.generated_date)<='$to_date'";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$cr = round($row[0], 2);
		}
		if ($ac_name_tmp == 'Payroll Tax Payble') {
			$query1 = "SELECT SUM(-pd.amount) FROM payroll_main pm ,payroll_data pd WHERE pm.payroll_no=pd.payroll_no AND `type`='Tax' AND date(pm.generated_date)<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$cr = $row1[0];
		}
		if ($ac_name_tmp == 'EMP Loan Interest in Suspense') {
			$query1 = "SELECT lm.amount,lm.duration,lm.rate FROM loan_main lm WHERE lm.`status` IN (0,4) AND lm.start_date<='$to_date'";
			$result1 = mysqli_query($conn, $query1);
			while ($row1 = mysqli_fetch_array($result1)) {
				$cr += round((loanInstallment($row1[0], $row1[2], $row1[1]) * $row1[1]), 2) - $row1[0];
			}
			$query1 = "SELECT SUM(interest_pay) FROM loan_pay WHERE `date`<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$dr += $row1[0];
		}

		$query1 = "SELECT SUM(ei.amount) FROM expense_main em, expense_item ei WHERE em.expense_id=ei.expense_id AND em.from_account='$ac_id_tmp' AND em.expense_date<='$to_date'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$cr += $row1[0];
		$query1 = "SELECT SUM(ei.amount) FROM expense_main em, expense_item ei WHERE em.expense_id=ei.expense_id AND ei.account='$ac_id_tmp' AND em.expense_date<='$to_date'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$dr += $row1[0];
		$query1 = "SELECT SUM(ji.amount) FROM journal_main jm, journal_item ji WHERE jm.journal_id=ji.journal_id AND ji.account='$ac_id_tmp' AND jm.journal_date<='$to_date'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$jo += $row1[0];
		$lia_ac_amount[] = -($jo + $dr - $cr);
	}

	$query = "SELECT ac.id,ac.name,acat.category_level2,acat.category_level3 FROM accounts ac, account_category acat WHERE ac.category=acat.id AND ac.`status`=1 AND acat.category_level1='Equity' ORDER BY acat.category_level2,acat.category_level3,ac.name";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$jo = $dr = $cr = 0;
		$ac_id_tmp = $row[0];
		$equ_ac_id[] = $row[0];
		$equ_ac_name[] = $row[1];
		$equ_ac_catL2[] = $row[2];
		$equ_ac_catL3[] = $row[3];

		$query1 = "SELECT SUM(ei.amount) FROM expense_main em, expense_item ei WHERE em.expense_id=ei.expense_id AND em.from_account='$ac_id_tmp' AND em.expense_date<='$to_date'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$cr = $row1[0];
		$query1 = "SELECT SUM(ei.amount) FROM expense_main em, expense_item ei WHERE em.expense_id=ei.expense_id AND ei.account='$ac_id_tmp' AND em.expense_date<='$to_date'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$dr = $row1[0];
		$query1 = "SELECT SUM(ji.amount) FROM journal_main jm, journal_item ji WHERE jm.journal_id=ji.journal_id AND ji.account='$ac_id_tmp' AND jm.journal_date<='$to_date'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$jo = $row1[0];
		$equ_ac_amount[] = -($jo + $dr - $cr);
	}
}

function generateProfitandLoss()
{
	global $cogs, $inc_ac_id, $inc_ac_name, $inc_ac_catL2, $inc_ac_catL3, $inc_ac_amount, $exp_ac_id, $exp_ac_name, $exp_ac_catL2, $exp_ac_catL3, $exp_ac_amount, $equ_ac_id, $equ_ac_name, $equ_ac_catL2, $equ_ac_catL3, $equ_ac_amount;
	$today = dateNow();
	$to_date = $_GET['to_date'];
	include('config.php');

	$query1 = "SELECT SUM(bi.qty*bi.cost) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.exclude=0 AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$to_date'";
	$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
	$cogs = $row1[0];

	$query = "SELECT ac.id,ac.name,acat.category_level2,acat.category_level3 FROM accounts ac, account_category acat WHERE ac.category=acat.id AND ac.`status`=1 AND acat.category_level1='Income' ORDER BY acat.category_level2,acat.category_level3,ac.name";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$bills = $jo = $dr = $cr = 0;
		$ac_id_tmp = $row[0];
		$ac_name_tmp = $row[1];
		$inc_ac_id[] = $row[0];
		$inc_ac_name[] = $row[1];
		$inc_ac_catL2[] = $row[2];
		$inc_ac_catL3[] = $row[3];

		if ($ac_name_tmp == 'Sales') {
			$query1 = "SELECT SUM(bi.qty*bi.unit_price) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.exclude=0 AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$cr += $row1[0];
		}
		if ($ac_name_tmp == 'EMP Loan Interest income') {
			$query1 = "SELECT SUM(lp.interest_pay) FROM loan_pay lp, loan_main lm WHERE lm.id=lp.loan_id AND lm.start_date<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$cr += $row1[0];
		}

		$query1 = "SELECT SUM(ei.amount) FROM expense_main em, expense_item ei WHERE em.expense_id=ei.expense_id AND em.from_account='$ac_id_tmp' AND em.expense_date<='$to_date'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$cr += $row1[0];
		$query1 = "SELECT SUM(ei.amount) FROM expense_main em, expense_item ei WHERE em.expense_id=ei.expense_id AND ei.account='$ac_id_tmp' AND em.expense_date<='$to_date'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$dr += $row1[0];
		$query1 = "SELECT SUM(ji.amount) FROM journal_main jm, journal_item ji WHERE jm.journal_id=ji.journal_id AND ji.account='$ac_id_tmp' AND jm.journal_date<='$to_date'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$jo += $row1[0];
		$inc_ac_amount[] = ($jo + $dr - $cr);
	}

	$query = "SELECT ac.id,ac.name,acat.category_level2,acat.category_level3 FROM accounts ac, account_category acat WHERE ac.category=acat.id AND ac.`status`=1 AND acat.category_level1='Expense' ORDER BY acat.category_level2,acat.category_level3,ac.name";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$payroll = $jo = $dr = $cr = 0;
		$ac_id_tmp = $row[0];
		$ac_name_tmp = $row[1];
		$exp_ac_id[] = $row[0];
		$exp_ac_name[] = $row[1];
		$exp_ac_catL2[] = $row[2];
		$exp_ac_catL3[] = $row[3];

		if ($ac_name_tmp == 'Payroll Expenses') {
			$query1 = "SELECT SUM(pd.amount) FROM payroll_data pd, payroll_main pm WHERE pm.payroll_no=pd.payroll_no AND pd.amount>0 AND pm.generated_date<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$payroll = $row1[0];
		}
		if ($ac_name_tmp == 'EPF Expense (Employer)') {
			$query1 = "SELECT SUM(employer_epf) FROM payroll_main WHERE generated_date<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$payroll = $row1[0];
		}
		if ($ac_name_tmp == 'ETF Expense (Employer)') {
			$query1 = "SELECT SUM(employer_etf) FROM payroll_main WHERE generated_date<='$to_date'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$payroll = $row1[0];
		}

		$query1 = "SELECT SUM(ei.amount) FROM expense_main em, expense_item ei WHERE em.expense_id=ei.expense_id AND em.from_account='$ac_id_tmp' AND em.expense_date<='$to_date'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$cr = $row1[0];
		$query1 = "SELECT SUM(ei.amount) FROM expense_main em, expense_item ei WHERE em.expense_id=ei.expense_id AND ei.account='$ac_id_tmp' AND em.expense_date<='$to_date'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$dr = $row1[0];
		$query1 = "SELECT SUM(ji.amount) FROM journal_main jm, journal_item ji WHERE jm.journal_id=ji.journal_id AND ji.account='$ac_id_tmp' AND jm.journal_date<='$to_date'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$jo = $row1[0];
		$exp_ac_amount[] = $jo + $dr + $payroll - $cr;
	}
}

//----------------------------------Salary & Payroll---------------------------------------------------//

function getSalaryType()
{
	global $sa_type_id, $sa_type_name;
	include('config.php');
	$query = "SELECT id,name FROM salary_type";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$sa_type_id[] = $row[0];
		$sa_type_name[] = $row[1];
	}
}

function getEmp()
{
	global $emp_id, $emp_name;
	include('config.php');
	$query = "SELECT id,username FROM userprofile WHERE `status`=0";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$emp_id[] = $row[0];
		$emp_name[] = $row[1];
	}
}

function oneSalary()
{
	global $one_emp_name, $one_ot_rate, $one_st_id, $one_st_name, $one_sa_id, $one_sa_amount;
	$one_sa_id = array();
	$id = $_GET['id'];
	include('config.php');

	$query = "SELECT username,ot_rate FROM userprofile WHERE id='$id'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$one_emp_name = $row[0];
	$one_ot_rate = $row[1];

	$query = "SELECT st.id,st.name,sa.id,sa.amount FROM salary_type st, salary_amount sa WHERE sa.`type`=st.id AND sa.employee='$id'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$one_st_id[] = $row[0];
		$one_st_name[] = $row[1];
		$one_sa_id[] = $row[2];
		$one_sa_amount[] = $row[3];
	}
}

function updateSalary()
{
	global $message, $emp_id;
	$emp_id = $_POST['emp_id'];
	$ot_rate = $_POST['ot_rate'];
	$satype_new = $_POST['satype_new'];
	$amount_new = $_POST['amount_new'];

	include('config.php');
	$query = "UPDATE userprofile SET `ot_rate`='$ot_rate' WHERE id='$emp_id'";
	mysqli_query($conn, $query);
	$query = "SELECT st.id,st.name,sa.id,sa.amount FROM salary_type st, salary_amount sa WHERE sa.`type`=st.id AND sa.employee='$emp_id'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$one_st_id = $row[0];
		$one_st_name = $row[1];
		$one_sa_id = $row[2];
		$one_sa_amount = $row[3];
		$update_type = $_POST["satype_$one_sa_id"];
		$update_amount = $_POST["amount_$one_sa_id"];
		if (($update_type != $one_st_id) || ($update_amount != $one_sa_amount)) {
			$query = "UPDATE salary_amount SET `type`='$update_type',`amount`='$update_amount' WHERE id='$one_sa_id'";
			mysqli_query($conn, $query);
		}
	}
	if (($satype_new != '') && ($amount_new != '')) {
		$query = "INSERT INTO `salary_amount` (`employee`,`type`,`amount`) VALUES ('$emp_id','$satype_new','$amount_new')";
		$result = mysqli_query($conn, $query);
	}

	if ($result) {
		$message = 'Salary Sheet was Updated Successfully';
		return true;
	} else {
		$message = 'Salary Sheet Could Not be Updated!';
		return false;
	}
}

function getPayrollForm()
{
	global $emp_id, $emp_name, $emp_ot_rate, $type_id, $type_name, $payroll_arr, $etf_rate, $sa_epf_employee, $epf_employer_rate, $sa_tax_employee, $payroll_loan;
	$loan_emp2 = '';
	$k = -1;
	$loan_emp_py = $loan_id_py = $loan_installment_py = array();

	include('config.php');
	$query = "SELECT value FROM settings WHERE setting='ETF'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$etf_rate = $row[0];
	$query = "SELECT value FROM settings WHERE setting='EPF_Employee'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$epf_employee_rate = $row[0];
	$query = "SELECT value FROM settings WHERE setting='EPF_Employer'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$epf_employer_rate = $row[0];
	$query = "SELECT value FROM settings WHERE setting='Salary_Tax'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$sa_tax_rate = $row[0];

	$query = "SELECT lm.id,lm.emp_id,lm.amount,lm.rate,lm.duration,sum(lp.capital_pay+lp.interest_pay) FROM loan_main lm LEFT JOIN loan_pay lp ON lm.id=lp.loan_id WHERE lm.`status`=4 GROUP BY lm.id ORDER BY lm.emp_id";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$loan_id = $row[0];
		$loan_emp = $row[1];
		$loan_amount = $row[2];
		$loan_rate = $row[3];
		$loan_duration = $row[4];
		$loan_paid = $row[5];
		$loan_installment = round(loanInstallment($loan_amount, $loan_rate, $loan_duration), 2);
		$loan_totalreturn = $loan_installment * $loan_duration;
		$remaining = $loan_totalreturn - $loan_paid;
		if ($remaining < $loan_installment) {
			$payroll_installment = $remaining;
		} else {
			if ($remaining < ($loan_installment + 10))
				$payroll_installment = $loan_installment + $remaining;
			else
				$payroll_installment = $loan_installment;
		}
		if ($loan_emp2 != $loan_emp) {
			$k++;
			$payroll_installment_total = 0;
		}
		$payroll_installment_total += $payroll_installment;
		$loan_emp_py[$k] = $loan_emp;
		$loan_installment_py[$k] = $payroll_installment_total;
		$loan_emp2 = $loan_emp;
	}

	$query = "SELECT DISTINCT up.id,up.username,up.ot_rate FROM userprofile up, salary_amount sa WHERE sa.employee=up.id";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$emp_id[] = $row[0];
		$emp_name[] = $row[1];
		$emp_ot_rate[] = $row[2];
	}
	$query = "SELECT id,name FROM salary_type";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$type_id[] = $row[0];
		$type_name[] = $row[1];
	}
	$query = "SELECT employee,`type`,amount FROM salary_amount";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$sa_emp[] = $row[0];
		$sa_type[] = $row[1];
		$sa_amount[] = $row[2];
		if ($row[1] == 1)
			$sa_epf_employee[$row[0]] = $row[2] * $epf_employee_rate;
		if ($row[1] == 1)
			$sa_tax_employee[$row[0]] = $row[2] * $sa_tax_rate;
	}

	for ($i = 0; $i < sizeof($emp_id); $i++) {
		for ($j = 0; $j < sizeof($type_id); $j++) {
			for ($k = 0; $k < sizeof($sa_emp); $k++) {
				if (($emp_id[$i] == $sa_emp[$k]) && ($type_id[$j] == $sa_type[$k])) {
					$payroll_arr[$emp_id[$i]][$type_id[$j]] = $sa_amount[$k];
				}
			}
		}
		$key = array_search($emp_id[$i], $loan_emp_py);
		if ($key > -1) {
			$payroll_loan[$emp_id[$i]] = $loan_installment_py[$key];
		} else {
			$payroll_loan[$emp_id[$i]] = 0;
		}
	}
}

function generatePayroll()
{
	global $message, $payroll_no;
	$paytoll_total = 0;
	include('config.php');
	$payroll_month = $_POST['month'] . '-15';
	$today = timeNow();
	$generated_by = $_COOKIE['user_id'];
	$etf_employer = $_POST['etf_employer'];
	$epf_employer = $_POST['epf_employer'];
	$paytoll_total += $etf_employer + $epf_employer;

	$query = "SELECT MAX(payroll_no) FROM payroll_main";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$payroll_no = $row[0];
	if ($payroll_no == '') {
		$payroll_no = 0;
	}
	$payroll_no++;

	$query = "SELECT DISTINCT up.id,up.username,up.ot_rate FROM userprofile up, salary_amount sa WHERE sa.employee=up.id";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$emp_id[] = $row[0];
		$emp_name[] = $row[1];
		$emp_ot_rate[] = $row[2];
	}
	$query = "SELECT id,name FROM salary_type";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$type_id[] = $row[0];
		$type_name[] = $row[1];
	}
	$query = "SELECT employee,`type`,amount FROM salary_amount";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$sa_emp[] = $row[0];
		$sa_type[] = $row[1];
		$sa_amount[] = $row[2];
	}

	$query = "INSERT INTO `payroll_main` (`payroll_no`,`month`,`generated_date`,`generated_by`,`employer_etf`,`employer_epf`) VALUES ('$payroll_no','$payroll_month','$today','$generated_by','$etf_employer','$epf_employer')";
	$result1 = mysqli_query($conn, $query);

	for ($i = 0; $i < sizeof($emp_id); $i++) {
		$employee = $emp_id[$i];
		//$emp_salary=0;
		if ($result1) {
			for ($j = 0; $j < sizeof($type_id); $j++) {
				for ($k = 0; $k < sizeof($sa_emp); $k++) {
					if (($emp_id[$i] == $sa_emp[$k]) && ($type_id[$j] == $sa_type[$k])) {
						//						$emp_salary+=$sa_amount[$k];
						$payroll_item_type = $type_name[$j];
						$payroll_item_amount = $sa_amount[$k];
						$query2 = "INSERT INTO `payroll_data` (`payroll_no`,`emp_id`,`type`,`amount`) VALUES ('$payroll_no','$employee','$payroll_item_type','$payroll_item_amount')";
						$result2 = mysqli_query($conn, $query2);
					}
				}
			}
		}
		$payroll_comission = $_POST["commission_$emp_id[$i]"];
		$payroll_special = $_POST["special_$emp_id[$i]"];
		$payroll_OT = $_POST["ot_$emp_id[$i]"] * $emp_ot_rate[$i];
		$payroll_gross = $_POST["basic_gross_cal_$emp_id[$i]"];
		$payroll_epf_emp = -$_POST["epf_emp_$emp_id[$i]"];
		$payroll_tax = -$_POST["tax_$emp_id[$i]"];
		$payroll_loan = $_POST["loan_$emp_id[$i]"];
		//		$payroll_net=$_POST["basic_net_cal_$emp_id[$i]"];
		$payroll_deductions = -$payroll_loan;
		$paytoll_total += $payroll_gross;

		//$emp_salary=$emp_salary+$payroll_comission+$payroll_special;

		$query3 = "INSERT INTO `payroll_data` (`payroll_no`,`emp_id`,`type`,`amount`) VALUES ('$payroll_no','$employee','Commission','$payroll_comission')";
		$result3 = mysqli_query($conn, $query3);
		$query3 = "INSERT INTO `payroll_data` (`payroll_no`,`emp_id`,`type`,`amount`) VALUES ('$payroll_no','$employee','Special','$payroll_special')";
		$result3 = mysqli_query($conn, $query3);
		$query3 = "INSERT INTO `payroll_data` (`payroll_no`,`emp_id`,`type`,`amount`) VALUES ('$payroll_no','$employee','OT','$payroll_OT')";
		$result3 = mysqli_query($conn, $query3);
		$query3 = "INSERT INTO `payroll_data` (`payroll_no`,`emp_id`,`type`,`amount`) VALUES ('$payroll_no','$employee','EPF Emp','$payroll_epf_emp')";
		$result3 = mysqli_query($conn, $query3);
		$query3 = "INSERT INTO `payroll_data` (`payroll_no`,`emp_id`,`type`,`amount`) VALUES ('$payroll_no','$employee','Tax','$payroll_tax')";
		$result3 = mysqli_query($conn, $query3);
		$query3 = "INSERT INTO `payroll_data` (`payroll_no`,`emp_id`,`type`,`amount`) VALUES ('$payroll_no','$employee','Deductions','$payroll_deductions')";
		$result3 = mysqli_query($conn, $query3);

		$query4 = "SELECT lm.id,lm.amount,lm.rate,lm.duration,sum(lp.capital_pay+lp.interest_pay),sum(lp.capital_pay) FROM loan_main lm LEFT JOIN loan_pay lp ON lm.id=lp.loan_id WHERE lm.`status`=4 AND lm.emp_id='$emp_id[$i]' GROUP BY lm.id";
		$result4 = mysqli_query($conn, $query4);
		while ($row4 = mysqli_fetch_array($result4)) {
			if ($row4[0] != '') {
				$loan_id = $row4[0];
				$loan_amount = $row4[1];
				$loan_rate = $row4[2];
				$loan_duration = $row4[3];
				$loan_paid = $row4[4];
				$loan_paid_capital = $row4[5];


				$loan_installment = round(loanInstallment($loan_amount, $loan_rate, $loan_duration), 2);
				$loan_totalreturn = $loan_installment * $loan_duration;
				$remaining = $loan_totalreturn - $loan_paid;
				$interest_pay = round((($loan_amount - $loan_paid_capital) * ($loan_rate / 100 / 12)), 2);
				$capital_pay = $loan_installment - $interest_pay;

				if ($remaining < $loan_installment) {
					$payroll_installment = $remaining;
				} else {
					if ($remaining < ($loan_installment + 10))
						$payroll_installment = $loan_installment + $remaining;
					else
						$payroll_installment = $loan_installment;
				}
				$query = "INSERT INTO `loan_pay` (`loan_id`,`payroll_id`,`date`,`capital_pay`,`interest_pay`) VALUES ('$loan_id','$payroll_no','$today','$capital_pay','$interest_pay')";
				$result5 = mysqli_query($conn, $query);
				if (($result5) && ($payroll_installment == $remaining)) {
					$query = "UPDATE `loan_main` SET `status`='0' WHERE id='$loan_id'";
					mysqli_query($conn, $query);
				}
			}
		}
	}

	if ($result1 && $result2 && $result3) {
		$query3 = "UPDATE `payroll_main` SET payroll_total='$paytoll_total' WHERE payroll_no='$payroll_no'";
		$result3 = mysqli_query($conn, $query3);
		$message = 'Payroll was Generated Successfully';
		return true;
	} else {
		$message = 'Payroll Could Not be Generated!';
		return false;
	}
}

function getPayrollList()
{
	global $payroll_no, $payroll_month, $payroll_amount;
	include('config.php');

	$query = "SELECT pm.payroll_no,year(pm.`month`),monthname(pm.`month`),SUM(pd.amount),pm.employer_etf,pm.employer_epf FROM payroll_main pm, payroll_data pd WHERE pm.payroll_no=pd.payroll_no AND pd.amount>0 GROUP BY pm.payroll_no";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$payroll_no[] = $row[0];
		$payroll_month[] = $row[1] . ' - ' . $row[2];
		$payroll_amount[] = $row[3] + $row[4] + $row[5];
	}
}

function getPayrollOne()
{
	global $emp_id, $emp_name, $type_id, $type_name, $payroll_month, $payroll_arr, $generated_date, $etf_employer_payble, $epf_employer_payble;
	$id = $_GET['id'];
	$usr2 = '';
	include('config.php');

	$query = "SELECT id,name FROM salary_type";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$type_id[] = $row[0];
		$type_name[] = $row[1];
	}
	$type_id[] = 101;
	$type_name[] = 'Commission';
	$type_id[] = 102;
	$type_name[] = 'Special';
	$type_id[] = 103;
	$type_name[] = 'OT';
	$type_id[] = 111;
	$type_name[] = 'EPF Emp';
	$type_id[] = 112;
	$type_name[] = 'Tax';
	$type_id[] = 113;
	$type_name[] = 'Deductions';

	$query = "SELECT year(pm.`month`),monthname(pm.`month`),up.id,up.username,pd.`type`,pd.amount,date(pm.generated_date),pm.generated_by,pm.employer_etf,pm.employer_epf FROM payroll_main pm, payroll_data pd, userprofile up WHERE pm.payroll_no=pd.payroll_no AND pd.emp_id=up.id AND pm.payroll_no='$id' ORDER BY up.username";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$payroll_month = $row[0] . ' - ' . $row[1];
		$generated_date = $row[6];
		$generated_by = $row[7];
		$etf_employer_payble = $row[8];
		$epf_employer_payble = $row[9];
		$usr1 = $row[2];
		if ($usr1 != $usr2) {
			$emp_id[] = $row[2];
			$emp_name[] = $row[3];
		}
		for ($i = 0; $i < sizeof($type_name); $i++) {
			if ($type_name[$i] == $row[4]) {
				$payroll_arr[$usr1][$type_id[$i]] = $row[5];
			}
		}
		$usr2 = $usr1;
	}
}

// update by nirmal 31_01_2025
function getPayrollUserView()
{
	global $etf_rate, $epf_employer_rate, $emp_id, $emp_name, $type_id, $type_name, $payroll_month, $payroll_arr, $generated_date, $epf_employee_rate,
	$etf_employer_payble, $epf_employer_payble, $emp_fullname, $emp_nic, $emp_bank, $emp_bankbranch, $emp_bankac, $emp_store, $payroll_year,
	$store_address, $emp_no, $emp_designation, $emp_bank_code, $emp_bank_branch_name, $emp_basic_salary, $salary_type, $salary_amount,
	$salary_name, $payroll_data_id, $emp_branch_code;

	$payroll_no = $_GET['payroll_no'];
	$emp = $_GET['emp'];
	$emp_st = 0;
	include('config.php');

	$query = "SELECT `value` FROM settings WHERE setting='ETF'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$etf_rate = $row[0];

	$query = "SELECT `value` FROM settings WHERE setting='EPF_Employer'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$epf_employer_rate = $row[0];

	$query = "SELECT `value` FROM settings WHERE setting='EPF_Employee'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$epf_employee_rate = $row[0];

	$query = "SELECT `id`,`name` FROM salary_type";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$type_id[] = $row[0];
		$type_name[] = $row[1];
	}
	$type_id[] = 101;
	$type_name[] = 'Commission';
	$type_id[] = 102;
	$type_name[] = 'Special';
	$type_id[] = 103;
	$type_name[] = 'OT';
	$type_id[] = 111;
	$type_name[] = 'EPF Emp';
	$type_id[] = 112;
	$type_name[] = 'Tax';
	$type_id[] = 113;
	$type_name[] = 'Deductions';

	$query = "SELECT YEAR(pm.`month`),MONTHNAME(pm.`month`),up.id,up.username,pd.`type`,pd.amount,date(pm.generated_date),pm.generated_by,pm.employer_etf,pm.employer_epf,
	up.emp_name,up.nic,bk.name,up.bank_branch,up.bank_ac,up.store,up.`employee_no`,up.`designation`,bk.`bank_code`,st.`type`,st.`name`,pd.`id`,up.`branch_code`
	FROM payroll_main pm
	JOIN payroll_data pd ON pm.payroll_no = pd.payroll_no
	JOIN userprofile up ON pd.emp_id = up.id
	JOIN bank bk ON up.bank_id = bk.id
	LEFT JOIN salary_type st ON st.`name` = pd.`type`
	WHERE pm.payroll_no='$payroll_no' AND pd.emp_id='$emp'";

	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$payroll_year = $row[0];
		$payroll_month = $row[1];
		$emp_id[] = $row[2];
		$emp_name = $row[3];
		$generated_date = $row[6];
		$generated_by = $row[7];
		$etf_employer_payble = $row[8];
		$epf_employer_payble = $row[9];
		$emp_fullname = $row[10];
		$emp_nic = $row[11];
		$emp_bank = $row[12];
		$emp_bankbranch = $row[13];
		$emp_bankac = $row[14];
		$emp_st = $row[15];
		$emp_no = $row[16];
		$emp_designation = $row[17];
		$emp_bank_code = $row[18];
		$salary_type[] = $row[19];
		$salary_name[] = $row[20];
		$salary_amount[] = $row[5];
		$payroll_data_id[] = $row[21];
		$emp_branch_code = $row[22];
		for ($i = 0; $i < sizeof($type_name); $i++) {
			if ($type_name[$i] == $row[4]) {
				$payroll_arr[$type_id[$i]] = $row[5];
			}
		}
	}
	$query = "SELECT `shop_name`, `address` FROM stores WHERE id='$emp_st'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$emp_store = $row[0];
	$store_address = $row[1];
}

function deleteAuthurization()
{
	$id = $_GET['id'];
	$user_id = $_COOKIE['user_id'];
	$today = dateNow();
	include('config.php');
	$query = "SELECT date(generated_date),generated_by FROM payroll_main WHERE payroll_no='$id'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$generated_date = $row[0];
	$generated_by = $row[1];
	if (($today == $generated_date) && ($user_id == $generated_by))
		return true;
	else
		return false;
}

function deletePayroll()
{
	global $message;
	$id = $_GET['id'];
	if (deleteAuthurization()) {
		include('config.php');
		$query1 = "DELETE FROM payroll_data WHERE `payroll_no`='$id'";
		$result1 = mysqli_query($conn, $query1);

		if ($result1) {
			$query2 = "DELETE FROM payroll_main WHERE `payroll_no`='$id'";
			$result2 = mysqli_query($conn, $query2);
		}

		if ($result2) {
			$query3 = "DELETE FROM loan_pay WHERE `payroll_id`='$id'";
			$result3 = mysqli_query($conn, $query3);
		}
		if ($result2) {
			$message = 'Payroll was Deleted Successfully';
			return true;
		} else {
			$message = 'Payroll Could Not be Deleted!';
			return false;
		}
	} else {
		$message = 'Error: UnAuthorized attempt !';
		return false;
	}
}
//----------------------------------------LOAN--------------------------------------------//
function loanStatus($status_id)
{
	$status_name = '';
	switch ($status_id) {
		case "0":
			$status_name = 'Inactive';
			break;
		case "1":
			$status_name = 'Pending';
			break;
		case "2":
			$status_name = 'Approved';
			break;
		case "3":
			$status_name = 'Rejected';
			break;
		case "4":
			$status_name = 'Granted';
			break;
	}
	return $status_name;
}

function getLoanList()
{
	global $loan_id, $loan_amount, $loan_emp, $loan_balance, $loan_start, $loan_end, $loan_status;
	include('config.php');

	$query = "SELECT lm.id,lm.amount,lm.rate,lm.duration,up.username,SUM(lp.capital_pay+lp.interest_pay),date(lm.start_date),lm.`status` FROM userprofile up, loan_main lm LEFT JOIN loan_pay lp ON lm.id=lp.loan_id WHERE lm.emp_id=up.id AND lm.`status` IN (1,2,3,4) GROUP BY lm.id ORDER BY lm.`status` DESC, lm.id ASC";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$loan_id[] = $row[0];
		$loan_amount[] = $row[1];
		$loan_amount_tmp = $row[1];
		$loan_rate = $row[2];
		$loan_duration = $row[3];
		$loan_emp[] = $row[4];
		$loan_paid = $row[5];
		$loan_balance[] = round(((loanInstallment($loan_amount_tmp, $loan_rate, $loan_duration) * $loan_duration) - $loan_paid), 2);
		$loan_start[] = $row[6];
		$date = new DateTime($row[6]);
		$date->modify("+$row[3] month");
		$loan_end[] = $date->format('Y-m-d');
		$loan_status[] = loanStatus($row[7]);
	}
	$loan_id[] = 'newstatus';
	$loan_amount[] = 'newstatus';
	$loan_emp[] = 'newstatus';
	$loan_balance[] = 'newstatus';
	$loan_start[] = 'newstatus';
	$loan_end[] = 'newstatus';
	$loan_status[] = 'newstatus';
	$query = "SELECT lm.id,lm.amount,lm.rate,lm.duration,up.username,SUM(lp.capital_pay+lp.interest_pay),date(lm.start_date),lm.`status`,MAX(lp.`date`) FROM userprofile up, loan_main lm LEFT JOIN loan_pay lp ON lm.id=lp.loan_id WHERE lm.emp_id=up.id AND lm.`status`=0 GROUP BY lm.id ORDER BY lm.id LIMIT 30";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$loan_id[] = $row[0];
		$loan_amount[] = $row[1];
		$loan_amount_tmp = $row[1];
		$loan_rate = $row[2];
		$loan_duration = $row[3];
		$loan_emp[] = $row[4];
		$loan_paid = $row[5];
		$loan_balance[] = 0;
		$loan_start[] = $row[6];
		$loan_end[] = $row[8];
		$loan_status[] = loanStatus($row[7]);
	}
}

function getLoanOne()
{
	global $payoff_value, $one_amount, $one_rate, $one_duration, $one_emp, $one_start, $one_end, $one_pay_payroll, $one_pay_date, $one_pay_amount, $one_total_return, $one_status, $one_pay_capital, $one_installment, $one_from_account;
	$id = $_GET['id'];
	$today = dateNow();
	$one_pay_capital = 0;
	$one_lastpaydate = '';
	$one_pay_payroll = $one_pay_date = $one_pay_amount = array();
	include('config.php');

	$query = "SELECT lm.amount,lm.rate,lm.duration,up.username,lm.start_date,lm.`status`,ac.name FROM userprofile up, loan_main lm LEFT JOIN accounts ac ON lm.from_account=ac.id WHERE lm.emp_id=up.id AND lm.id='$id'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$one_amount = $row[0];
		$one_rate = $row[1];
		$one_duration = $row[2];
		$one_emp = $row[3];
		$one_start = $row[4];
		$one_status = loanStatus($row[5]);
		$one_from_account = $row[6];
		$date = new DateTime($one_start);
		$date->modify("+$one_duration month");
		$one_end = $date->format('Y-m-d');

		$query1 = "SELECT payroll_id,`date`,(capital_pay+interest_pay),capital_pay FROM loan_pay WHERE loan_id='$id'";
		$result1 = mysqli_query($conn, $query1);
		while ($row1 = mysqli_fetch_array($result1)) {
			$one_pay_payroll[] = $row1[0];
			$one_pay_date[] = $row1[1];
			$one_pay_amount[] = $row1[2];
			$one_pay_capital += $row1[3];
			$one_lastpaydate = $row1[1];
		}

	}
	$one_installment = loanInstallment($one_amount, $one_rate, $one_duration);
	$one_total_return = $one_installment * $one_duration;
	if ($one_lastpaydate != '')
		$date1 = $one_lastpaydate;
	else
		$date1 = $one_start;
	$payoff_duration = dateDiff($date1, $today) / 30;
	$payoff_duration = ceil($payoff_duration);
	$remaining_capital = $one_amount - $one_pay_capital;
	if ($payoff_duration != 0) {
		$interest_pay = round(($remaining_capital * $one_rate / 100 / 12 * $payoff_duration), 2);
		$payoff_value = round(($remaining_capital + $interest_pay), 2);
	} else {
		$payoff_value = $remaining_capital;
	}
	if ($one_duration < 1)
		$one_installment = 0;
	if ($one_status == 'Inactive') {
		$one_total_return = array_sum($one_pay_amount);
	}
}

function createLoan()
{
	global $message;
	$emp_name = $_POST['emp_name'];
	$amount = $_POST['amount'];
	$rate = $_POST['rate'];
	$start_date = $_POST['start_date'];
	$duration = $_POST['duration'];
	$submit_by = $_COOKIE['user_id'];
	$datetime = timeNow();

	include('config.php');
	$query = "SELECT id FROM userprofile WHERE username='$emp_name'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$emp_id = $row[0];

	$query = "INSERT INTO `loan_main` (`emp_id`,`start_date`,`duration`,`amount`,`rate`,`submited_by`,`submited_date`,`status`) VALUES ('$emp_id','$start_date','$duration','$amount','$rate','$submit_by','$datetime','1')";
	$result1 = mysqli_query($conn, $query);

	if ($result1) {
		$message = 'Loan was Created Successfully';
		return true;
	} else {
		$message = 'Loan Could Not be Created!';
	}
}

function editLoan()
{
	global $message, $id;
	$id = $_POST['loan_id'];
	$duration = $_POST['duration'];
	$out = true;
	$msg = '';
	include('config.php');
	$query = "SELECT `status` FROM loan_main WHERE id='$id'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$loan_st = $row[0];
	if ($loan_st == 1) {
		$query = "UPDATE `loan_main` SET `duration`='$duration',`status`='1' WHERE id='$id'";
		$result1 = mysqli_query($conn, $query);
		if ($result1) {
			$message = 'Loan was Updated Successfully';
		} else {
			$out = false;
			$message = 'Error: Loan Could Not Be Updated !';
		}
	} else {
		$out = false;
		$message = 'Error: Unauthorize request';
	}

	if ($out)
		return true;
	else
		return false;
}

function payLoan()
{
	global $message, $id;
	$id = $_POST['loan_id'];
	$amount = $_POST['custom_pay'];
	$loan_lastpaydate = '';
	$today = dateNow();
	$out = true;

	include('config.php');
	$query = "SELECT lm.amount,lm.rate,lm.start_date,SUM(lp.capital_pay+lp.interest_pay),SUM(lp.capital_pay),lm.`status`,MAX(lp.`date`) FROM loan_main lm LEFT JOIN loan_pay lp ON lm.id=lp.loan_id WHERE lm.id='$id' GROUP BY lm.id";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$loan_amount = $row[0];
	$loan_rate = $row[1];
	$loan_start = $row[2];
	$loan_paid = $row[3];
	$loan_pay_capital = $row[4];
	$loan_status = $row[5];
	$loan_lastpaydate = $row[6];

	if ($loan_lastpaydate != '')
		$date1 = $loan_lastpaydate;
	else
		$date1 = $loan_start;
	$payoff_duration = dateDiff($date1, $today) / 30;
	$payoff_duration = ceil($payoff_duration);

	$remaining_capital = $loan_amount - $loan_pay_capital;
	$interest_pay = round(($remaining_capital * $loan_rate / 100 / 12 * $payoff_duration), 2);
	$payoff_value = round(($remaining_capital + $interest_pay), 2);
	/*
																																																												 if($payoff_duration!=0){
																																																												 }else{
																																																													 $payoff_value=$loan_amount;
																																																												 }
																																																												 */

	$loan_balance = $payoff_value;

	if ($loan_status == 0) {
		$out = false;
		$message = 'Error: This Loan is a inactive Loan';
	}
	if ($amount != $loan_balance) {
		$out = false;
		$message = 'Error: Invalid Amount';
	}
	if ($amount == $loan_balance) {
		$message = 'The Loan is Fully Settled';
	}

	if ($out) {
		$query = "INSERT INTO `loan_pay` (`loan_id`,`date`,`capital_pay`,`interest_pay`) VALUES ('$id','$today','$remaining_capital','$interest_pay')";
		print $query;
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$out = false;
			$message = 'Error: Amount could not be Added!';
		}
	}
	if ($out) {
		$query = "UPDATE `loan_main` SET `status`='0' WHERE id='$id'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$out = false;
			$message = 'Error: Loan status could not be updated!';
		}
	}

	return $out;
}

function grantLoan()
{
	global $message, $id;
	$id = $_POST['id'];
	$from_account = $_POST['from_account'];
	$user = $_COOKIE['user_id'];
	$today1 = timeNow();

	include('config.php');
	$query = "UPDATE `loan_main` SET `status`='4',`granted_by`='$user',`granted_date`='$today1',`from_account`='$from_account' WHERE `status`='2' AND id='$id'";
	$result3 = mysqli_query($conn, $query);
	if ($result3) {
		$message = "The Loan was Granted Successfully";
		return true;
	} else {
		$message = "Error: The Loan could not be Granted !";
		return false;
	}
}

function deleteLoan()
{
	global $message, $id;
	$id = $_GET['id'];
	$result1 = false;

	include('config.php');

	$query1 = "DELETE FROM loan_main WHERE `status`='1' AND `id`='$id'";
	$result1 = mysqli_query($conn, $query1);

	if ($result1) {
		$message = "The Loan was Deleted Successfully";
		return true;
	} else {
		$message = "Error: The Loan could not be Deleted !";
		return false;
	}
}

//---------------------------------------Dashboard--------------------------------------------//
function dashboard()
{
	global $backdate30, $today, $supplier_name, $supplier_remaining, $expense_account, $expense_amount, $supinv_name, $supinv_shipno, $supinv_invno, $supinv_invdate, $supinv_duedate, $supinv_amount, $conn;

	$backdate30 = date("Y-m-d", time() - 2592000);
	$today = dateNow();
	$year_start = date("Y", time()) . '-01-01';
	$supplier_name = $supplier_remaining = $expense_account = $expense_amount = array();

	include('config.php');
	$query = "SELECT sm.`supplier`,su.name,SUM(ins.cost * ins.added_qty) FROM shipment_main sm, inventory_shipment ins, supplier su WHERE sm.id=ins.shipment_no AND sm.`supplier`=su.id AND sm.`status`!='3' GROUP BY sm.`supplier`";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$supplier_paid = 0;
		$supplier_name[] = $row[1];
		$supplier_name_tmp = $row[1];
		$supplier_id = $row[0];
		$supplier_total = $row[2];
		$query1 = "SELECT SUM(ji.amount) FROM journal_item ji, accounts ac WHERE ji.account=ac.id AND ji.cr_dr='dr' AND ac.name='$supplier_name_tmp'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$supplier_paid += $row1[0];
		$query1 = "SELECT SUM(ei.amount) FROM expense_item ei, accounts ac WHERE ei.account=ac.id AND ac.name='$supplier_name_tmp'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		$supplier_paid += $row1[0];
		$supplier_remaining[] = $supplier_total - $supplier_paid;

		$query1 = "SELECT sm.id,sm.invoice_no,sm.invoice_date,sm.invoice_due,SUM(ins.cost * ins.added_qty) FROM shipment_main sm, inventory_shipment ins WHERE sm.id=ins.shipment_no AND sm.`status`!='3' AND sm.`supplier`='$supplier_id' GROUP BY sm.id LIMIT 5";
		$result1 = mysqli_query($conn, $query1);
		while ($row1 = mysqli_fetch_array($result1)) {
			$supinv_name[] = $supplier_name_tmp;
			$supinv_shipno[] = $row1[0];
			$supinv_invno[] = $row1[1];
			$supinv_invdate[] = $row1[2];
			$supinv_duedate[] = $row1[3];
			$supinv_amount[] = $row1[4];
		}

	}
	$query = "SELECT ac.name,SUM(ei.amount) FROM expense_main em, expense_item ei, accounts ac WHERE em.expense_id=ei.expense_id AND ei.account=ac.id AND em.expense_date>='$year_start' GROUP BY ac.id";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$expense_account[] = $row[0];
		$expense_amount[] = $row[1];
	}

}

?>