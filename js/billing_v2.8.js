function billingMenu2() {
	document.getElementById("myDropdown").classList.toggle("show");
}
function billingMenu3() {
	document.getElementById("myDropdown3").classList.toggle("show");
}
function billingMenu4() {
	document.getElementById("myDropdown4").classList.toggle("show");
}
function billingMenu5() {
	document.getElementById("myDropdown5").classList.toggle("show");
}
function billingMenu6() {
	document.getElementById("myDropdown6").classList.toggle("show");
}
function billingMenu7() {
	document.getElementById("myDropdown7").classList.toggle("show");
}
function billingMenu8() {
	document.getElementById("myDropdown8").classList.toggle("show");
}
function billingMenu9() {
	document.getElementById("myDropdown9").classList.toggle("show");
}
function billingMenu22() {
	var dis = document.getElementById("myDropdown");
	if (dis.style.display === "none") {
		dis.style.display = "block";
	} else {
		dis.style.display = "none";
	}
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function (event) {
	if (!event.target.matches('.dropbtn')) {

		var dropdowns = document.getElementsByClassName("dropdown-content");
		var i;
		for (i = 0; i < dropdowns.length; i++) {
			var openDropdown = dropdowns[i];
			if (openDropdown.classList.contains('show')) {
				openDropdown.classList.remove('show');
			}
		}
	}
}

function thousands_separators(num) {
	var num_parts = num.toString().split(".");
	num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	return num_parts.join(".");
}

/*--------------------------------------------------------------------------------------------------------------------------------*/

function setDistrict($components) {
	var id = document.getElementById('district').value;
	var cust_odr = document.getElementById('cust_odr').value;
	var salesman = document.getElementById('salesman').value;
	if (id != '')
		window.location = 'index.php?components=' + $components + '&action=set_district&id=' + id + '&s=' + salesman + '&cust_odr=' + cust_odr;
}

function setDistrict2($components) {
	var id = document.getElementById('district').value;
	var cust_odr = document.getElementById('cust_odr').value;
	var sm_id = document.getElementById('sm_id').value;
	if (id != '')
		window.location = 'index.php?components=' + $components + '&action=set_district&id=' + id + '&s=' + sm_id + '&cust_odr=' + cust_odr;
}

function setPayment($case) {
	document.getElementById('payment_type').value = 0;
	var bm_type = document.getElementById('bm_type').value;
	var invoicetotal = parseFloat(document.getElementById('invoicetotal').value);
	var crlimitbalance = parseFloat(document.getElementById('crlimitbalance').value);
	if (document.forms["payForm"]["amount_cash"].value != '') var amount_cash = (document.forms["payForm"]["amount_cash"].value); else { var amount_cash = 0; document.forms["payForm"]["amount_cash"].value = 0; }
	if (document.getElementById('amount_chque').value != '') var amount_chque = (document.getElementById('amount_chque').value); else { var amount_chque = 0; document.getElementById('amount_chque').value = 0; }
	if (document.getElementById('amount_credit').value != '') var amount_credit = (document.getElementById('amount_credit').value); else { var amount_credit = 0; document.getElementById('amount_credit').value = 0; }
	var balance = ((invoicetotal * 10) - (amount_cash * 10) - (amount_credit * 10) - (amount_chque * 10)) / 10;
	document.getElementById('payment_balance_div').innerHTML = thousands_separators(balance);
	document.getElementById('payment_cash_div').innerHTML = thousands_separators(amount_cash);
	document.getElementById('payment_chque_div').innerHTML = thousands_separators(amount_chque);
	document.getElementById('payment_credit_div').innerHTML = thousands_separators(amount_credit);
	document.getElementById('balance').value = balance;
	if ((amount_cash != 0) && (amount_chque != 0)) document.getElementById('payment_type').value = 3; else
		if (amount_cash != 0) document.getElementById('payment_type').value = 1; else
			if (amount_chque != 0) document.getElementById('payment_type').value = 2;
	var newcrlimitbalance = parseFloat(crlimitbalance) + parseFloat(amount_cash);
	document.getElementById('cr_balance_div').innerHTML = thousands_separators(newcrlimitbalance);
	if (bm_type != 3) document.getElementById('crlimitbalance2').value = newcrlimitbalance;
	if ($case = 'bill2') {
		if (balance == 0) progressBar(30);
	}
}

function deleteBill(id) {
	var check = confirm("Do you really want to Delete this Bill?");
	if (check == true)
		window.location = 'index.php?components=billing&action=delete&id=' + id;
}

function deleteGTN(id) {
	var check = confirm("Do you really want to Delete this GTN?");
	if (check == true) {
		document.getElementById('deletegtn').innerHTML = '';
		window.location = 'index.php?components=trans&action=delete&id=' + id;
	}
}

function updateGTN($id) {
	var itemid = "gtnitemid" + $id;
	var qty = document.getElementById(itemid).value;
	window.location = 'index.php?components=trans&action=gtn_item_gpdate&id=' + $id + '&qty=' + qty;
}

function removeGTN($id) {
	window.location = 'index.php?components=trans&action=gtn_item_remove&id=' + $id;
}

function deleteRepairComment($id) {
	var type = document.getElementById('repcom_type').value;
	if (type == 1 || type == 2) $components = 'repair'; else $components = 'billing';
	var check = confirm("Do you want to Delete this Comment?");
	if (check == true) {
		document.getElementById('repcom' + $id).innerHTML = '';
		window.location = 'index.php?components=' + $components + '&action=del_repair_comment&id=' + $id;
	}
}

function updateShipment($id) {
	var new_qty_id = "new_qty" + $id;
	var old_qty_id = "old_qty" + $id;
	var qty_new = document.getElementById(new_qty_id).value;
	var qty_old = document.getElementById(old_qty_id).value;
	window.location = 'index.php?components=inventory&action=shipment_item_gpdate&id=' + $id + '&qty_old=' + qty_old + '&qty_new=' + qty_new;
}

function removeShipment($id) {
	var old_qty_id = "old_qty" + $id;
	var qty_old = document.getElementById(old_qty_id).value;
	window.location = 'index.php?components=inventory&action=shipment_item_remove&id=' + $id + '&qty_old=' + qty_old;
}

function updateBill($id) {
	var itemid = "billitemid" + $id;
	var qty = document.getElementById(itemid).value;
	var cust = document.getElementById('cust').value;
	var salesman = document.getElementById('salesman').value;
	var cust_odr = document.getElementById('cust_odr').value;
	document.getElementById('itmdiv' + $id).innerHTML = document.getElementById('loading').innerHTML;
	window.location = 'index.php?components=billing&action=bill_item_gpdate&cust_odr=' + cust_odr + '&id=' + $id + '&qty=' + qty + '&s=' + salesman + '&cust=' + cust;
}

function removeBill($id) {
	var cust = document.getElementById('cust').value;
	var salesman = document.getElementById('salesman').value;
	var cust_odr = document.getElementById('cust_odr').value;
	document.getElementById('itmdiv' + $id).innerHTML = document.getElementById('loading').innerHTML;
	window.location = 'index.php?components=billing&action=bill_item_remove&cust_odr=' + cust_odr + '&id=' + $id + '&s=' + salesman + '&cust=' + cust;
}


function print_bill($id) {
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET", "index.php?components=billing&action=sms&id=" + $id, true);
	xmlhttp.send();
	document.getElementById("invoice_iframe").contentWindow.document.getElementById('print_top').style.display = "none";
	document.getElementById('invoice_iframe').focus();
	document.getElementById('invoice_iframe').contentWindow.print();
	document.getElementById("invoice_iframe").contentWindow.document.getElementById('print_top').style.display = "block";
}

function print_bill2(printpage, $id) {
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET", "index.php?components=billing&action=sms&id=" + $id, true);
	xmlhttp.send();
	var headstr = "<html><head><title></title></head><body>";
	var footstr = "</body></html>";
	var newstr = document.all.item(printpage).innerHTML;
	var oldstr = document.body.innerHTML;
	document.body.innerHTML = headstr + newstr + footstr;
	window.print();
	document.body.innerHTML = oldstr;
	return false;
}

function print_bill3() {
	document.getElementById('invoice_iframe').focus();
	document.getElementById('invoice_iframe').contentWindow.print();
}

function printdivBorder($table_id, $x, $y) {
	document.getElementById($table_id).border = "1"
	document.getElementById($table_id).cellSpacing = "0"
	printdiv($x, $y);
	document.getElementById($table_id).border = "0"
	document.getElementById($table_id).cellSpacing = "2"
}

function printdiv(printpage, header) {
	var headstr = "<html><head><title></title></head><body>";
	var footstr = "</body></html>";
	var headerstr = document.all.item(header).innerHTML;
	var newstr = document.all.item(printpage).innerHTML;
	var oldstr = document.body.innerHTML;
	document.body.innerHTML = headstr + headerstr + newstr + footstr;
	window.print();
	document.body.innerHTML = oldstr;
	return true;
}


function approveGTN(id) {
	var check = confirm("Do you want to Approve this GTN?");
	if (check == true) {
		document.getElementById('approvegtn').innerHTML = '';
		window.location = 'index.php?components=trans&action=approve&id=' + id;
	}
}

function rejectGTN(id) {
	var check = confirm("Do you really want to Reject this GTN?");
	if (check == true) {
		document.getElementById('rejectgtn').innerHTML = '';
		window.location = 'index.php?components=trans&action=reject&id=' + id;
	}
}

function crossSubmitGTN(id) {
	var check = confirm("Do you really want to Reject this GTN?");
	if (check == true) {
		document.getElementById('crossgtn').innerHTML = '';
		window.location = 'index.php?components=trans&action=cross_submit&id=' + id;
	}
}

function deleteCust(id) {
	var check = confirm("Do you really want Reject this Customer?");
	if (check == true)
		window.location = 'index.php?components=manager&action=delete_cust&id=' + id;
}

function disableCust(id) {
	var check = confirm("Do you really want Deactivate this Customer?");
	if (check == true)
		window.location = 'index.php?components=manager&action=disable_cust&id=' + id;
}

function enableCust(id) {
	var check = confirm("Do you really want Activate this Customer?");
	if (check == true)
		window.location = 'index.php?components=manager&action=enbale_cust&id=' + id;
}

function validateQuotation() {
	var $count = 0;
	if (document.getElementById('itemid').value == '') $count++;
	if (document.getElementById('qty1').value == '') $count++;
	if ($count != 0) {
		alert('Please fill Item Code and Quantity');
		return false;
	} else {
		document.getElementById('addtobill').innerHTML = document.getElementById('loading').innerHTML;
		return true;
	}
}

function validateBill() {
	var $count = 0;
	var $b = 0;
	var $fastprint = document.getElementById('fastprint').value;
	var $billitemcount = document.getElementById('billitemcount').value;
	var $unic_list_size = document.getElementById('unic_list_size').value;
	var $msg = "All fields must be filled out";
	document.getElementById('qty').value = document.getElementById('qty1').value;
	if (document.getElementById('av_qty_val').value != '') {
		if (document.getElementById('qty').value > parseInt(document.getElementById('av_qty_val').value)) {
			$count++;
			var $msg = "Insufficient Quantity";
		}
	}
	if (document.getElementById('tags1').value == '') $count++;
	if (document.getElementById('tags2').value == '') $count++;
	if (document.getElementById('qty').value == '') $count++;
	if (document.getElementById('tags4').value == '') $count++;
	if (document.getElementById('cust').value == 0) { $count++; if ($count == 1) $msg = "Customer must be Selected!"; }

	if ($unic_list_size > 0) {
		$uitem_limit = parseInt(document.getElementById('uitem_limit').value) + 3;
		if (document.getElementById('tags4').value == '') $count++;
		var $duplicate = 0;
		for ($i = 4; $i <= $uitem_limit; $i++) {
			$unic_item = document.getElementById('tags' + $i).value;
			if ($unic_item != '') {
				$b++;
				for ($j = 4; $j <= $uitem_limit; $j++) {
					if ($j != $i) if ($unic_item == document.getElementById('tags' + $j).value) $duplicate++;
				}
			}
		}
		if ($duplicate > 0) {
			$msg = ('Duplicate Detected');
			$count++;
		}
		if (document.getElementById('cashback').checked) {
			document.getElementById('qty').value = -1;
		} else {
			document.getElementById('qty').value = $b;
		}
	}

	if ($count != 0) {
		alert($msg);
		return false;
	} else {
		if (($fastprint == 'on') && ($billitemcount == 0)) {
			printdiv('print1', 'printheader');
			document.getElementById('addtobill').innerHTML = '';
			document.getElementById("billingForm").submit();
		}
		document.getElementById('addtobill').innerHTML = '';
		return true;
	}
}

function validateAppendCustOrder() {
	var $count = 0;
	var $msg = "All fields must be filled out";
	if (document.getElementById('item').value == '') $count++;
	if (document.getElementById('qty').value == '') $count++;
	if ($count != 0) {
		alert($msg);
		return false;
	} else {
		document.getElementById('div_submit').innerHTML = document.getElementById('loading').innerHTML;
		return true;
	}
}


function validateAddCategory() {
	var $count = 0;
	var $msg = "Category Name must be filled out";
	if (document.getElementById('category').value == '') { $count++; $msg = "Category Name must be filled out"; }
	if (document.getElementById('sub_sys').value == '') { $count++; $msg = "Sub System must be Selected"; }
	if ($count != 0) {
		alert($msg);
		return false;
	} else return true;
}


function validateCust() {
	var $count = 0;
	var $msg = "All fields must be filled out";
	if (document.getElementById('shop_name').value == '') $count++;
	if (document.getElementById('nic').value == '') $count++;
	if (document.getElementById('cu_group').value == '') $count++;
	if (document.getElementById('cu_town').value == '') $count++;
	if (document.getElementById('cr_limit').value == '') $count++;
	if (document.getElementById('store').value == '') $count++;
	if (document.getElementById('salesref').value == '') $count++;
	if (document.getElementById('customer').value == '') $count++;
	if (document.getElementById('shop_address').value == '') $count++;
	if (document.getElementById('shop_tel').value == '') $count++;
	if (document.getElementById('home_tel').value == '') $count++;
	if ($count == 0) {
		if (document.getElementById('status').value != 2) {
			var mobile = document.getElementById('mobile').value;
			if (document.getElementById('mobile').value == '') $count++;
			if (document.getElementById('home_address').value == '') $count++;
			if (mobile.indexOf("07") == -1) { $count++; $msg = "Invalid Mobile Number"; }
			if (mobile.length != 10) { $count++; $msg = "Error: Phone Number has " + mobile.length + " Letters"; }
		}
	}
	if ($count != 0) {
		alert($msg);
		return false;
	} else {
		document.getElementById('div_submit').innerHTML = document.getElementById('loading').innerHTML;
		return true;
	}
}

function validateUser() {
	var $count = 0;
	var pass1 = document.getElementById('user_pass1').value;
	var pass2 = document.getElementById('user_pass2').value;
	if (document.getElementById('emp_name').value == '') $count++;
	if (document.getElementById('user_name').value == '') $count++;
	if (document.getElementById('action').value == 'create') {
		var $msg = "Employee Name, Username, Password must be filled";
		if (pass1 == '') $count++;
		if (pass2 == '') $count++;
	} else {
		var $msg = "Employee Name and Username must be filled";
	}
	if (pass1 != pass2) {
		var $msg = "Password Does Not Match";
		document.getElementById('user_pass1').value = '';
		document.getElementById('user_pass2').value = '';
		$count++;
	}
	if ((pass1 != '') || (pass2 != '')) {
		if ($count == 0) {
			if ((document.getElementById('user_pass1').value.length) < 8) {
				var $msg = "Password Must Contain 8 Characters or Longer";
				$count++;
			}
		}
	}
	if ($count != 0) {
		document.getElementById('passhash').value = md5(pass1);
		alert($msg);
		return false;
	} else {
		document.getElementById('passhash').value = md5(pass1);
		return true;
	}
}

function generateLogIn() {
	var pass = md5(document.getElementById('passwd').value);
	var token = document.getElementById('token').value;
	var onetime_pass = md5(pass + token);
	document.getElementById('onetime_pass').value = onetime_pass;
	document.getElementById('div_login').innerHTML = document.getElementById('loading').innerHTML;
}

function validateGTN() {
	var $count = 0;
	var $msg = "All fields must be filled out";
	var $unic_list_size = document.getElementById('unic_list_size').value;
	document.getElementById('qty').value = document.getElementById('qty1').value;
	if (document.getElementById('tags1').value == '') $count++;
	if (document.getElementById('tags2').value == '') $count++;
	if (document.getElementById('qty').value == '') $count++;
	if (document.getElementById('remotestore').value == 0) { $count++; if ($count == 1) $msg = "Store must be Selected!"; }
	if ($unic_list_size > 0) {
		$uitem_limit = parseInt(document.getElementById('uitem_limit').value) + 3;
		if (document.getElementById('tags4').value == '') $count++;
		var $duplicate = 0;
		for ($i = 5; $i <= $uitem_limit; $i++) {
			$unic_item = document.getElementById('tags' + $i).value;
			if ($unic_item != '') {
				for ($j = 4; $j <= $uitem_limit; $j++) {
					if ($j != $i) if ($unic_item == document.getElementById('tags' + $j).value) $duplicate++;
				}
			}
		}
		if ($duplicate > 0) {
			$msg = ('Duplicate Detected');
			$count++;
		}
	}
	if ($count != 0) {
		alert($msg);
		return false;
	} else {
		document.getElementById('addtogtn').innerHTML = '';
		return true;
	}
}

function validateAddItem() {
	var $count = 0;
	var $warning = '<span style="color:red; font-weight:bold;">&nbsp;!<span>';
	var $msg = "Fields marked in ' ! ' must be filled";
	var $code = document.getElementById('code').value;
	if (document.getElementById('category').value == '') $count++;
	if ($code == '') $count++;
	if (document.getElementById('description').value == '') $count++;
	if (document.getElementById('w_price').value == '') $count++;
	if (document.getElementById('r_price').value == '') $count++;
	if (document.getElementById('cost').value == '') $count++;
	if (document.getElementById('min_w_rate').value == '') $count++;
	if (document.getElementById('max_w_rate').value == '') $count++;
	if (document.getElementById('max_r_rate').value == '') $count++;
	if (document.getElementById('unic').value == '') $count++;
	if ($count != 0) {
		for ($i = 1; $i < 12; $i++) {
			document.getElementById("id" + $i).innerHTML = $warning;
		}
		alert($msg);
		return false;
	} else {
		for ($i = 1; $i < 12; $i++) {
			document.getElementById("id" + $i).innerHTML = '';
		}
		if (parseInt(document.getElementById('min_w_rate').value) > 100) $count++;
		if (parseInt(document.getElementById('max_w_rate').value) > 100) $count++;
		if (parseInt(document.getElementById('max_r_rate').value) > 100) $count++;
		if ($count != 0) {
			document.getElementById("id7").innerHTML = $warning;
			document.getElementById("id8").innerHTML = $warning;
			document.getElementById("id9").innerHTML = $warning;
			alert("Max Discount % must be less than 100");
			return false;
		} else {
			if ($code.length > 30) {
				document.getElementById("id3").innerHTML = $warning;
				alert("Item CODE too long. Please limit the code to be less than 20 characters");
				return false;
			} else {
				document.getElementById('div_add_item').innerHTML = document.getElementById('loading').innerHTML;
				return true;
			}
		}
	}
}

function validateDate($date) {
	var $count = 0;
	var year = $date.substring(0, 4);
	var month = $date.substring(5, 7);
	var date = $date.substring(8, 10);
	var dash1 = $date.substring(4, 5);
	var dash2 = $date.substring(7, 8);
	if (isNaN(year)) $count++;
	if (isNaN(month)) $count++;
	if (isNaN(date)) $count++;
	if (month > 12) $count++;
	if (date > 31) $count++;
	if ((dash1 != '-') || (dash2 != '-')) $count++;
	if ($count != 0) return false; else return true;
}

function validateDateRange2() {
	var datefrom = document.getElementById('datefrom').value;
	var dateto = document.getElementById('dateto').value;
	var customer = document.getElementById('tags1').value;
	if ((validateDate(datefrom)) && (validateDate(dateto)) && (customer != '')) return true; else {
		alert('All fields must be filled and Date Must Be in 2010-11-28 Format');
		return false;
	}
}

function validatePayment($form) {
	var $count = 0;
	var txt = "";
	var i;
	if ($form == 1) {
		var payment_type = document.forms[0];
		for (i = 0; i < payment_type.length; i++) {
			if (payment_type[i].checked) {
				txt = txt + payment_type[i].value + " ";
			}
		}
	}
	if ($form == 2) {
		if (document.getElementById('balance').value != 0) { $count++; $msg = 'Balance Must Be 0. Please add Cash, Chque, Credit'; }
		if (document.getElementById('crlimitbalance2').value < 0) { $count++; $msg = 'Credit Limit Exceed'; }
		if (document.getElementById('amount_chque').value != 0) var txt = 2;
	}

	if (txt == 1) {
		if ($form == 1) { if (document.getElementById('amount_chque').value == '') { $count++; $msg = "Amount Shuldn't be Empty"; } }
		if ($form == 2) { if (document.getElementById('amount').value == '') { $count++; $msg = "Amount Shuldn't be Empty"; } }
	}
	if (txt == 3) {
		if ($form == 1) { if (document.getElementById('amount_chque').value == '') { $count++; $msg = "Amount Shuldn't be Empty"; } }
		if ($form == 2) { if (document.getElementById('amount').value == '') { $count++; $msg = "Amount Shuldn't be Empty"; } }
		if (document.forms["payForm"]["tr_bank"].value == '') { $count++; $msg = "Please Select the Money Transferred Bank"; }
	}
	if (($count == 0) && (txt == 2)) {
		$msg = 'Amount, Chque Bank, Branch, Chque No, Chque Date Must be filled';
		if (!(document.getElementById('amount_chque').value > 0)) $count++;
		if (document.forms["payForm"]["chque_no"].value == '') $count++;
		if (document.forms["payForm"]["chque_bank"].value == '') $count++;
		if (document.forms["payForm"]["chque_branch"].value == '') $count++;
		if (document.forms["payForm"]["chque_date"].value == '') $count++;
		if (validateDate(document.forms["payForm"]["chque_date"].value) == false) { $count++; $msg = 'Date Must Be in 2010-11-28 Format'; }
		if (isNaN(document.forms["payForm"]["chque_branch"].value)) { $count++; $msg = 'Branch must be a Number (Branch Code)'; }
	}
	if (($form == 1) && (txt == '')) { $count++; $msg = 'Payment Type Must be Selected'; }
	if (document.getElementById('payment_validity').value == 0) {
		if (document.getElementById('invoice_no').value != '') {
			$count++; $msg = 'Please Validate Invoice No';
		}
	}

	if ($count != 0) {
		alert($msg);
		return false;
	} else {
		if ($form == 1) document.getElementById('addpayment').innerHTML = '';
		if ($form == 2) document.getElementById('finalize').innerHTML = '';
		return true;
	}
}


function deletePayment(id, component) {
	var check = confirm("Do you really want Delete this Payment?");
	if (check == true)
		window.location = 'index.php?components=' + component + '&action=delete_payment&id=' + id;
}

function clearReturnChq(id, component) {
	var check = confirm("Do you really want Mark this Chque as Clear?");
	if (check == true)
		window.location = 'index.php?components=' + component + '&action=rtnchque_clear&id=' + id;
}

function validateSpecial() {
	var $count = 0;
	var $msg = "All fields must be filled out";
	if (document.getElementById('tags1').value == '') $count++;
	if (document.getElementById('district').value == '') $count++;
	if (document.getElementById('increment').value == '') $count++;
	if ($count != 0) {
		alert($msg);
		return false;
	}
}

function validateDisSpecial() {
	var $count = 0;
	var $msg = "Increment must be filled out";
	if (document.getElementById('increment').value == '') $count++;
	if ($count != 0) {
		alert($msg);
		return false;
	}
}

function deleteSpecial(id) {
	var check = confirm("Do you really want Remove this Special Price?");
	if (check == true)
		window.location = 'index.php?components=inventory&action=delete_specialprice&id=' + id;
}

//------------------------Return Item--------------------------------//

function validateReturn() {
	var $count = 0;
	var $msg = "All fields must be filled out";
	document.getElementById('qty').value = document.getElementById('qty1').value;
	if (document.getElementById('tags1').value == '') $count++;
	if (document.getElementById('tags2').value == '') $count++;
	if (document.getElementById('tags4').value == '') $count++;
	if (document.getElementById('tags6').value == '') $count++;
	if (document.getElementById('tags7').value == '') $count++;
	if (document.getElementById('tags8').value == '') $count++;
	if (document.getElementById('qty').value == '') $count++;
	if ($count != 0) {
		alert($msg);
		return false;
	} else {
		document.getElementById('addtoreturn').innerHTML = '';
		return true;
	}
}

function updateReturn($id, $component) {
	var itemid = "billitemid" + $id;
	var qty = document.getElementById(itemid).value;
	var cust = document.getElementById('cust').value;
	var invid = document.getElementById('id').value;
	window.location = 'index.php?components=' + $component + '&action=return_item_gpdate&return_invoice_no=' + invid + '&id=' + $id + '&qty=' + qty + '&cust=' + cust;
}

function removeReturn($id, $component) {
	var cust = document.getElementById('cust').value;
	var invid = document.getElementById('id').value;
	window.location = 'index.php?components=' + $component + '&action=return_item_remove&return_invoice_no=' + invid + '&id=' + $id + '&cust=' + cust;
}

function deleteReturn(id, $component) {
	var check = confirm("Do you really want Delete this Return Invoice?");
	if (check == true)
		window.location = 'index.php?components=' + $component + '&action=delete_return&id=' + id;
}

function processRtn(id) {
	var qtyrtn = document.getElementById('qty' + id).value;
	var invrtn = document.getElementById('inv' + id).value;
	var disrtn = document.getElementById('dis' + id).value;
	var newtotal = parseInt(invrtn) + parseInt(disrtn);
	if (newtotal == qtyrtn)
		window.location = 'index.php?components=order_process&action=process_return&item=' + id + '&invrtn=' + invrtn + '&disrtn=' + disrtn;
	else alert('SUM of Invontory and Disposal should be = to Qty');
}

function moveDisposal(id) {
	var check = confirm("Do you really want Move this Item from Disposal to Processing as Pending ?");
	var year = document.getElementById('year').value;
	if (check == true)
		window.location = 'index.php?components=manager&action=move_disposal&year=' + year + '&id=' + id;
}
//-----------------------------Inventory Different Item----------------------//
function validateQTY() {
	var $count = 0;
	var $msg = "All fields must be filled out";
	if (document.getElementById('tags1').value == '') $count++;
	if (document.getElementById('qty').value == '') $count++;
	if (document.getElementById('c_price1').value == '') $count++;
	if (document.getElementById('w_price1').value == '') $count++;
	if (document.getElementById('r_price1').value == '') $count++;
	if ($count != 0) {
		alert($msg);
		return false;
	}
}

function validateQTY2() {
	var $count = $count1 = $count2 = 0;
	var sn_arr = [];
	var $warning = '<span style="color:red; font-weight:bold; font-size:12pt">&nbsp;!<span>';
	var $msg = "All fields must be filled out";
	$c_price1 = document.getElementById('c_price1').value;
	$w_price1 = document.getElementById('w_price1').value;
	$r_price1 = document.getElementById('r_price1').value;
	if (document.getElementById('tags1').value == '') $count++;
	if (document.getElementById('qty').value == '') $count++;
	if ($c_price1 == '') $count++;
	if ($w_price1 == '') $count++;
	if ($r_price1 == '') $count++;
	if (($c_price1 > 1000000) || ($w_price1 > 1000000) || ($r_price1 > 1000000)) {
		$count++;
		$msg = "Invalid Price";
	}
	for ($i = 1; $i <= 10; $i++) {
		var sn_one = document.getElementById('sn' + $i).value;
		sn_arr.push(sn_one);
		document.getElementById('div_id' + $i).innerHTML = '';
		if (sn_one != '') $count2++;
	}
	if ($count2 == 0) $count++;
	for ($i = 0; $i < 10; $i++) {
		var id0 = $i;
		var value0 = sn_arr[$i];
		for ($j = 0; $j < 10; $j++) {
			if (id0 != $j) {
				if ((value0 == sn_arr[$j]) && (value0 != '')) {
					document.getElementById('div_id' + ($j + 1)).innerHTML = $warning;
					$count1++;
				}
			}
		}
	}
	if ($count1 != 0) { $count++; $msg = "Duplicate SN Detected!"; }
	if ($count != 0) {
		alert($msg);
		return false;
	} else {
		document.getElementById('div_add').innerHTML = document.getElementById('loading').innerHTML;
		return true;
	}
}

function validateDevice() {
	var $count = 0;
	var $msg = "Device Name should not be Empty";
	if (document.getElementById('device_name').value == '') $count++;
	if ($count != 0) {
		alert($msg);
		return false;
	}
}

function validatePerDevice() {
	var $count = 0;
	var $msg = "Device and User must be selected";
	if (document.getElementById('per_dev').value == '') $count++;
	if (document.getElementById('per_usr').value == '') $count++;
	if ($count != 0) {
		alert($msg);
		return false;
	}
}

function delPerDevice($id, $dev, $usr) {
	var check = confirm('Do you want to remove permission from ' + $dev + ' for ' + $usr);
	if (check == true)
		window.location = 'index.php?components=settings&action=delpermission_device&id=' + $id;
}

function registerDevice($task) {
	var device = document.getElementById('dev_id').value;
	if ($task == 'add') {
		if (device == '') {
			alert('A Device must be selected');
			return false;
		} else {
			window.location = 'index.php?components=manager&action=device_register&device=' + device;
		}
	}
	if ($task == 'remove') {
		window.location = 'index.php?components=manager&action=device_unregister&device=' + device;
	}
}


function validateSup() {
	var $count = 0;
	var $msg = "Name and Country is Mandatory";
	if (document.getElementById('sup_name').value == '') $count++;
	if (document.getElementById('country').value == '') $count++;
	if ($count != 0) {
		alert($msg);
		return false;
	}
}


function enableSup(id) {
	var check = confirm("Do you really want Activate this Supplier?");
	if (check == true)
		window.location = 'index.php?components=purchase_order&action=changest_sup&id=' + id + '&status=on';
}

function disableSup(id) {
	var check = confirm("Do you really want Deactivate this Supplier?");
	if (check == true)
		window.location = 'index.php?components=purchase_order&action=changest_sup&id=' + id + '&status=off';
}

function removeItemPO(id) {
	var po_no = document.getElementById('po_no').value;
	var check = confirm("Do you really want to Remove this Item from PO ?");
	if (check == true)
		window.location = 'index.php?components=purchase_order&action=remove_item_po&id=' + id + '&po_no=' + po_no;
}

function lockPO() {
	var po_no = document.getElementById('po_no').value;
	var check = confirm("Do you really want to Lock PO? Alter locking, you cannot modify the PO");
	if (check == true) {
		document.getElementById('div_lock_po').innerHTML = document.getElementById('loading').innerHTML;
		window.location = 'index.php?components=purchase_order&action=lock_po&po_no=' + po_no;
	}
}

function unlockPO() {
	var po_no = document.getElementById('po_no').value;
	var check = confirm("Do you really want to Unlock PO?");
	if (check == true) {
		document.getElementById('div_unlock_po').innerHTML = document.getElementById('loading').innerHTML;
		window.location = 'index.php?components=purchase_order&action=unlock_po&po_no=' + po_no;
	}
}

function setDateRange(date1, date2) {
	var daterange1 = '<strong>Date</strong>: &nbsp;<input type="date" id="date1" name="date1" style="width:130px" value="' + date1 + '" /> <input type="hidden" id="date2" name="date2" value="" />';
	var daterange2 = '<strong>From </strong>: &nbsp;<input type="date" id="date1" name="date1" style="width:130px" value="' + date1 + '" />&nbsp;&nbsp;&nbsp;<strong>To </strong>: &nbsp;<input type="date" id="date2" name="date2" style="width:130px" value="' + date2 + '" />';
	if (document.getElementById('date_range').checked == true) {
		document.getElementById('datediv').innerHTML = daterange2;
	} else {
		document.getElementById('datediv').innerHTML = daterange1;
	}
}

function validatePComm() {
	var $count = 0;
	var salesman = document.getElementById('tags1').value;
	var from_date = document.getElementById('from_date').value;
	var to_date = document.getElementById('to_date').value;
	var $msg = "Name and Country is Mandatory";
	if (salesman == '') $count++;
	if (from_date == '') $count++;
	if (to_date == '') $count++;
	if ($count != 0) {
		alert('Salesman, From Date, To Date must be Filled');
		return false;
	} else {
		if ((validateDate(from_date)) && (validateDate(to_date))) return true
		else {
			alert('Invalid Date Format');
			return false;
		}
	}
}

function KeyPress(e) {
	$id = document.getElementById('id').value;
	$fastprint = document.getElementById('fastprint').value;
	// look for window.event in case event isn't passed in
	e = e || window.event;
	if (e.keyCode == 13) {
		if ($fastprint == 'on') printdiv('print', 'printheader');
		if ($fastprint == 'off') print_bill($id);
	}
}

function KeyPress2(e) {
	$id = document.getElementById('id').value;
	// look for window.event in case event isn't passed in
	e = e || window.event;
	if (e.keyCode == 13) {
		print_bill($id);
	}
}

function validateDateRange() {
	var from_date = document.getElementById('from_date').value;
	var to_date = document.getElementById('to_date').value;
	if ((validateDate(from_date)) && (validateDate(to_date))) { return true } else {
		alert('Invalid Date Format');
		return false;
	}
}

function setUnic() {
	var selection = document.getElementById('unic').value;
	if (selection == 1) {
		document.getElementById('qty').disabled = true;
	} else {
		document.getElementById('qty').disabled = false;
	}
}

function validateUnic() {
	var $count = 0;
	loading = document.getElementById('loading').innerHTML;
	var $msg = "Item SN Could Not be Empty";
	if (document.getElementById('newsn').value == '') $count++;
	if ($count != 0) {
		alert($msg);
		return false;
	} else {
		document.getElementById('div_update').innerHTML = loading;
		return true;
	}
}

function deleteUnic($shipment_no, $ins_id, $sn) {
	var check = confirm("Do you really want to Delete Item ?");
	loading = document.getElementById('loading').innerHTML;
	if (check == true) {
		document.getElementById('div_delete').innerHTML = loading;
		window.location = 'index.php?components=inventory&action=delete_unic&ins_id=' + $ins_id + '&sn=' + $sn;
	}
}


function validateShipment() {
	var $count = 0;
	var $msg = "All fields must be filled out";
	if (document.getElementById('ship_date').value == '') $count++;
	if (document.getElementById('supplier').value == '') $count++;
	if (document.getElementById('ship_inv_no').value == '') $count++;
	if (document.getElementById('ship_inv_date').value == '') $count++;
	if (document.getElementById('ship_inv_dudate').value == '') $count++;
	if ($count != 0) {
		alert($msg);
		return false;
	}
}

function clearChque($id, $components) {
	$bnk = document.getElementById($id + '_bnk').value;
	$pydate = document.getElementById($id + '_pydate').value;
	if (($bnk != '') && ($pydate != ''))
		window.location = 'index.php?components=' + $components + '&action=clear_chque&id=' + $id + '&bnk=' + $bnk + '&pydate=' + $pydate;
}

function validateCAccount() {
	var $count = 0;
	var $msg = "All fields must be filled out";
	if (document.getElementById('category_l1').value == '') $count++;
	if (document.getElementById('ac_name').value == '') $count++;
	if ($count != 0) {
		alert($msg);
		return false;
	}
}

function deleteAC(id) {
	var check = confirm("Do you want Disable this Account?");
	if (check == true)
		window.location = 'index.php?components=fin&action=setst_chart_of_accounts&status=0&id=' + id;
}

function enableAC(id) {
	var check = confirm("Do you want Enable this Account?");
	if (check == true)
		window.location = 'index.php?components=fin&action=setst_chart_of_accounts&status=1&id=' + id;
}

function deleteExpense(id) {
	var check = confirm("Do you want Delete this Expense?");
	if (check == true)
		window.location = 'index.php?components=fin&action=delete_expense&id=' + id;
}

function deleteJournal(id) {
	var check = confirm("Do you want Delete this Journal?");
	if (check == true)
		window.location = 'index.php?components=fin&action=delete_journal&id=' + id;
}

function validatePayroll() {
	var $count = 0;
	var $msg = "Please Select the Month of Payroll";
	if (document.getElementById('month').value == '') $count++;
	if ($count != 0) {
		alert($msg);
		return false;
	}
}

function deletePayroll(id) {
	var check = confirm("Do you really want Delete this Payroll ?");
	if (check == true)
		window.location = 'index.php?components=fin&action=payroll_delete&id=' + id;
}

function validateLoan() {
	var $count = 0;
	var $msg = "Please Fully Fill the Form";
	if (document.getElementById('emp_name').value == '') $count++;
	if (document.getElementById('amount').value == '') $count++;
	if (document.getElementById('rate').value == '') $count++;
	if (document.getElementById('start_date').value == '') $count++;
	if (document.getElementById('duration').value == '') $count++;
	if ($count != 0) {
		alert($msg);
		return false;
	}
}

function validateGrantLoan() {
	var $count = 0;
	var $msg = "Please Select a Pay Account";
	if (document.getElementById('from_account').value == '') $count++;
	if ($count != 0) {
		alert($msg);
		return false;
	}
}

function setLoanStatus($id, $new_status) {
	if ($new_status == 2) $wording = 'Approve';
	if ($new_status == 3) $wording = 'Reject';
	var check = confirm('Do you want ' + $wording + ' this Loan ?');
	if (check == true)
		window.location = 'index.php?components=report&action=set_loan_status&new_status=' + $new_status + '&id=' + $id;
}

function setShipmentStatus($id, $new_status) {
	if ($new_status == 2) $wording = 'Approve';
	if ($new_status == 0) $wording = 'Reject';
	var check = confirm('Do you want ' + $wording + ' this Shipment ?');
	if (check == true)
		window.location = 'index.php?components=report&action=set_shipment_status&new_status=' + $new_status + '&id=' + $id;
}

function deleteLoan(id) {
	var check = confirm("Do you really want Delete this Loan ?");
	if (check == true)
		window.location = 'index.php?components=fin&action=delete_loan&id=' + id;
}


function validateSearchUnic() {
	var $count = 0;
	var $msg = "Please Select the Search Criteria";
	if (document.getElementById('search_unic').value == '') $count++;
	if ($count != 0) {
		alert($msg);
		return false;
	}
}

function authDeleteShipment(id) {
	var check = confirm("Are You Sure that you want to Request this Shipment for Deletion ?");
	loading = document.getElementById('loading').innerHTML;
	if (check == true)
		document.getElementById('div_delete').innerHTML = loading;
	window.location = 'index.php?components=inventory&action=auth_delete_shipment&shipment_no=' + id;
}

function deleteShipment(id) {
	var check = confirm("Do you really want Delete this Shipment ?");
	loading = document.getElementById('loading').innerHTML;
	if (check == true)
		document.getElementById('div_delete').innerHTML = loading;
	window.location = 'index.php?components=inventory&action=delete_shipment&shipment_no=' + id;
}

function validateMobileNo($country, $mobile) {
	var $count = 0;
	if ($country == 'AE') {
		$count = 0;
	}
	if ($country == 'SE') {
		if ($mobile.length != 10) $count++;
	}
	if ($country == 'LK') {
		if ($mobile.indexOf("07") == -1) $count++;
		if ($mobile.length != 10) $count++;
	}
	if ($count != 0) {
		return false;
	} else {
		return true;
	}
}