<?php
include_once 'template/header.php';
$store_report = $group_report = $salesman_report = 'ALL';
$customer_address = getQOCustomerAddress();
?>
<script type="text/javascript">
	function setTerms() {
		var terms_val = document.getElementById('terms_list').value;
		document.getElementById('terms1').value = document.getElementById('term_' + terms_val + '_1').value;
		document.getElementById('terms2').value = document.getElementById('term_' + terms_val + '_2').value;
		document.getElementById('from_add').value = terms_val;
		showAddress();
	}

	function validateQoTerms() {
		var $count = 0;
		if (document.getElementById('warranty').value == '') $count++;
		if ((document.getElementById('terms1').value == '') && (document.getElementById('terms2').value == '')) $count++;
		if (document.getElementById('validity').value == '') $count++;
		if (document.getElementById('from_add').value == '') $count++;
		if ($count != 0) {
			alert('Please fill the Form Details');
			return false;
		} else {
			document.getElementById('finalyze').innerHTML = document.getElementById('loading').innerHTML;
			return true;
		}
	}

	function showAddress() {
		var from_add = document.getElementById('from_add').value;
		if (from_add == '') document.getElementById('address').innerHTML = '<br />';
		if (from_add == '1') document.getElementById('address').innerHTML = '<?php print $address1; ?>'.replace(",<br />", ", ");
		if (from_add == '2') document.getElementById('address').innerHTML = '<?php print $address2; ?>'.replace(",<br />", ", ");
	}

	function setNewAddress() {
		var customer_new_address = document.getElementById('customer_address').value;
		var confirmUpdate = confirm("Are you sure you want to update customer address to: " + customer_new_address + "?");
		if (confirmUpdate) {
			if (customer_new_address != null && customer_new_address != "") {
				// Get quotation ID from URL parameters
				var urlParams = new URLSearchParams(window.location.search);
				var quotationId = urlParams.get('id');

				if (!quotationId) {
					alert("Quotation ID is missing. Cannot update address.");
					return;
				}

				// Prepare data to send
				var data = {
					address: customer_new_address,
					quotation_id: quotationId
				};

				// Send AJAX request
				const xhr = new XMLHttpRequest();
				xhr.open("POST", "index.php?components=<?php echo $components; ?>&action=qo_insert_customer_address_in_quotation_main", true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

				xhr.onreadystatechange = function () {
					if (xhr.readyState === 4) {
						if (xhr.status === 200) {
							try {
								const response = JSON.parse(xhr.responseText);
								if (response.status === "success") {
									alert("Customer address updated successfully");
									document.getElementById('customer_address').value = customer_new_address;
								} else {
									alert("Customer address could not be updated: " + response.message);
								}
							} catch (e) {
								alert("Server response could not be parsed: " + e.message);
							}
						} else {
							alert("Request failed. Please try again.");
						}
					}
				};
				xhr.send("data=" + encodeURIComponent(JSON.stringify(data)));
			} else {
				alert("Customer address cannot be empty");
			}
		}
	}
</script>


<div id="loading" style="display:none"><img src="images/loading.gif" style="width:50px" /></div>

<table align="center" style="font-family:Calibri; font-size:12pt; color:navy; font-weight:bold;" bgcolor="#EEEEEE">
	<tr>
		<td width="730px" align="center">Terms and Condition for the Quotation</td>
	</tr>
</table>

<table align="center" cellspacing="0" style="font-family:Calibri">
	<tr>
		<td>
			<?php
			if (isset($_REQUEST['message'])) {
				if ($_REQUEST['re'] == 'success')
					$color = 'green';
				else
					$color = '#DD3333';
				print '<script type="text/javascript">document.getElementById("notifications").innerHTML=' . "'" . '<span style="color:' . $color . '; font-weight:bold;font-size:12pt;">' . $_REQUEST['message'] . '</span>' . "'" . ';</script>';
			}
			?>
		</td>
	</tr>
</table>

<br />
<input type="hidden" id="term_0_1" value='' />
<input type="hidden" id="term_0_2" value='' />
<input type="hidden" id="term_1_1"
	value='All payments should be settled by cheque in favour of "<strong><?php print $st_comp_name; ?></strong>"' />
<input type="hidden" id="term_1_2" value='* 30% Should be paid when placing the order' />
<input type="hidden" id="term_2_1"
	value='All payments should be settled by cheque in favour of "<strong><?php print $sys_comp_name; ?></strong>"' />
<input type="hidden" id="term_2_2" value='* 30% Should be paid when placing the order' />
<input type="hidden" id="term_3_1" value='Payment should be made within 7 days after the delivery' />
<input type="hidden" id="term_3_2" value='* 30% Should be paid when placing the order' />
<input type="hidden" id="term_4_1" value='Payment can be Cash, Bank or Cheque' />
<input type="hidden" id="term_4_2" value='* 100% payment in advance.' />
<input type="hidden" id="term_5_1"
	value='All payments should be settled by cheque in favor of "<strong><?php print $sys_comp_name; ?></strong>"' />
<input type="hidden" id="term_5_2"
	value='* 60% Should be paid when confirmation the job and rest 40% should be settled when Finished the job.' />
<input type="hidden" id="term_6_1"
	value='All payments should be settled by cheque in favor of "<strong><?php print $sys_comp_name; ?></strong>"' />
<input type="hidden" id="term_6_2"
	value='* Please confirm the job officially and the total amount should be settled when completed the job.' />
<!-- Term 07 -->
<?php if ($systemid == 13) { ?>
	<input type="hidden" id="term_7_1" value='All payments should be settled by cheque in favor of "<strong></strong>"' />
	<input type="hidden" id="term_7_2"
		value='* Please confirm the job officially and the total amount should be settled when completed the job.' />
<?php } ?>

<form id="search_form"
	action="index.php?components=<?php print $components; ?>&action=set_qo_terms&id=<?php print $_GET['id']; ?>"
	method="post" onsubmit="return validateQoTerms()">
	<table align="center" style="font-family:Calibri; font-size:11pt">
		<tr bgcolor="#EEEEEE">
			<td style="padding-left:30px"><strong>Attention</strong></td>
			<td style="padding-right:30px">
				<input type="text" name="att" value="<?php if ($tm_att != '')
					print $tm_att;
				else
					print 'M/S'; ?>" onclick="this.value=''" />
			</td>
			<td width="100px"></td>
			<td style="padding-left:30px"><strong>Heading</strong></td>
			<td style="padding-right:30px">
				<input type="text" name="heading" value="<?php if ($tm_heading != '')
					print $tm_heading; ?>" />
			</td>
		</tr>
		<tr bgcolor="#EEEEEE">
			<td style="padding-left:30px"><strong>Warranty</strong></td>
			<td style="padding-right:30px">
				<input type="number" name="warranty" id="warranty" value="<?php if ($tm_warranty != '')
					print $tm_warranty;
				else
					print $qo_warranty; ?>" placeholder="Number of Months" />
			</td>
			<td></td>
			<td style="padding-left:30px"><strong>Validity</strong></td>
			<td style="padding-right:30px">
				<input type="number" name="validity" id="validity" value="<?php if ($tm_validity != '')
					print $tm_validity;
				else
					print $qo_validity; ?>" placeholder="Number of Days" />
			</td>
		</tr>
		<tr bgcolor="#EEEEEE">
			<td style="padding-left:30px">
				<strong><?php if ($systemid == 13)
					print 'Project Duration';
				else
					print 'Lead Time'; ?></strong>
			</td>
			<td style="padding-right:30px">
				<input type="text" name="leadtime" id="leadtime" value="<?php if ($tm_leadtime != '')
					print $tm_leadtime;
				else
					print $qo_leadtime; ?>" />
			</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr bgcolor="#EEEEEE">
			<td style="padding-left:30px; vertical-align:top;"><strong>Payment Terms</strong></td>
			<td style="padding-right:30px">
				<select id="terms_list" onchange="setTerms()">
					<option value='0'>-SELECT-</option>
					<option value='1'>Terms 1</option>
					<option value='2'>Terms 2</option>
					<option value='3'>Terms 3</option>
					<option value='4'>Terms 4</option>
					<option value='5'>Terms 5</option>
					<option value='6'>Terms 6</option>
				</select>
				<br />
				<textarea id="terms1" name="terms1" style="width:170px" rows="5"><?php if ($tm_terms1 != '')
					print $tm_terms1; ?></textarea><br />
				<span style="font-size:10pt"><em>Terms for Quotation and Invoice</em></span>
			</td>
			<td></td>
			<td></td>
			<td>
				<br />
				<textarea id="terms2" name="terms2" style="width:170px" rows="5"><?php if ($tm_terms2 != '')
					print $tm_terms2; ?></textarea>
				<br />
				<span style="font-size:10pt"><em>Terms for Quotation Only</em></span>
			</td>
		</tr>
		<tr bgcolor="#EEEEEE">
			<td style="padding-left:30px; vertical-align:top;"><strong>Customer Address</strong></td>
			<td style="padding-right:30px" colspan="4">
				<input id="customer_address" name="customer_address" value="<?php if ($customer_address != '')
					print $customer_address; ?>" style="width:500px;" />
				<button type="button" onclick="setNewAddress()">Set New Address</button>
			</td>
		</tr>
		<tr bgcolor="#EEEEEE">
			<td style="padding-left:30px; vertical-align:top;"><strong>Note</strong></td>
			<td style="padding-right:30px" colspan="4">
				<textarea id="note" name="note" style="width:570px" rows="3"><?php if ($tm_note != '')
					print $tm_note; ?></textarea>
			</td>
		</tr>
		<tr bgcolor="#EEEEEE">
			<td style="padding-left:30px"><strong>From Address</strong></td>
			<td style="padding-right:30px">
				<select id="from_add" name="from_add" onchange="showAddress()">
					<option value="">-SELECT-</option>
					<option value="1" <?php if ($tm_address == 1)
						print 'selected="selected"'; ?>>Store Address</option>
					<option value="2" <?php if ($tm_address == 2)
						print 'selected="selected"'; ?>>System Address</option>
				</select>
			</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr bgcolor="#EEEEEE">
			<td colspan="5" align="center">
				<div id="address"><br /></div>
			</td>
		</tr>
		<tr bgcolor="#EEEEEE">
			<td colspan="5" align="center">
				<div id="finalyze"><input type="submit" value="Finalyze"
						style="width:100px; height:40px; background-color:maroon; color:white;" /></div>
			</td>
		</tr>
	</table>
</form>
<?php
include_once 'template/footer.php';
?>