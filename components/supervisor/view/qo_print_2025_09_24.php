<?php
include_once 'template/header.php';
$page_width = 680;
$page_height = 1040;
$action = $_GET['action'];
$bill_module = bill_module(1);
$systemid = inf_systemid(1);
?>
<style>
	.checkbox-container {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		/* Vertically center items */
		height: 65px;
		padding-top: 10px;
	}

	.checkbox-container input[type="checkbox"] {
		margin-right: 5px;
		/* Adjust as needed */
		transform: scale(1.5);
		/* Increase the size by scaling */
	}

	.prtbutton3 {
		background-color: #20B2AA;
		width: 80px;
		border-radius: 15px;
		font-size: 10pt;
		border: 2px solid #20B2AA;
		color: white;
		text-decoration: none;
	}
</style>
<script type="text/javascript">
	<?php if ($components == 'to' && $qm_status <= 2) { ?>
		function setQuotStatus($id, $status) {
			var check = confirm('Do you want to ' + $status + ' this Quotation ?');
			if ($status == 'Delete') $newstatus = 0;
			if (check == true) {
				document.getElementById('button_div1').innerHTML = '';
				window.location = 'index.php?components=<?php print $components; ?>&action=set_quot_status&id=' + $id + '&new_status=' + $newstatus;
			}
		}
		function deleteQOImage(id) {
			var check = confirm("Do you want Delete this Image from Quotation ?");
			if (check == true) {
				document.getElementById('div_submit').innerHTML = document.getElementById('loading').innerHTML;
				window.location = 'index.php?components=<?php print $components; ?>&action=qo_delete_image&id=' + id;
			}
		}
		function reviseQuot(id) {
			var check = confirm("Do you want Revise this Quotation ?");
			if (check == true) {
				document.getElementById('revise_div').innerHTML = document.getElementById('loading').innerHTML;
				window.location = 'index.php?components=<?php print $components; ?>&action=qo_revise&id=' + id;
			}
		}
	<?php } ?>


	<?php if (($components != 'to')) { ?>
		function setCustAccept($id) {
			var check = confirm('Do you want to mark this as "Accepted by the Customer" ?');
			var date = document.getElementById('accdate').value;
			var custpo = document.getElementById('custpo').value;
			if (check == true)
				document.getElementById('acc_div1').innerHTML = document.getElementById('loading2').innerHTML;
			window.location = 'index.php?components=<?php print $components; ?>&action=set_quot_status&id=' + $id + '&new_status=6&accdate=' + date + '&custpo=' + custpo;
		}
		function setCustReject($id) {
			var check = confirm('Do you want to mark this as "Rejected by the Customer" ?');
			var custreject = document.getElementById('custreject').value;
			if (check == true)
				document.getElementById('acc_div2').innerHTML = document.getElementById('loading2').innerHTML;
			window.location = 'index.php?components=<?php print $components; ?>&action=set_quot_status&id=' + $id + '&new_status=7&custreject=' + custreject;
		}
		function setQuotStatus($id, $status) {
			var check = confirm('Do you want to ' + $status + ' this Quotation ?');
			if ($status == 'Delete') $newstatus = 0;
			if ($status == 'Approve') $newstatus = 3;
			if ($status == 'Reject') $newstatus = 4;
			if ($status == 'complete') $newstatus = 8;
			if (check == true) {
				document.getElementById('button_div1').innerHTML = '';
				window.location = 'index.php?components=<?php print $components; ?>&action=set_quot_status&id=' + $id + '&new_status=' + $newstatus;
			}
		}
		function deleteQOImage(id) {
			var check = confirm("Do you want Delete this Image from Quotation ?");
			if (check == true) {
				document.getElementById('div_submit').innerHTML = document.getElementById('loading').innerHTML;
				window.location = 'index.php?components=<?php print $components; ?>&action=qo_delete_image&id=' + id;
			}
		}
		function reviseQuot(id) {
			var check = confirm("Do you want Revise this Quotation ?");
			if (check == true) {
				document.getElementById('revise_div').innerHTML = document.getElementById('loading').innerHTML;
				window.location = 'index.php?components=<?php print $components; ?>&action=qo_revise&id=' + id;
			}
		}
		function editQuot(id, s, cust) {
			var check = confirm("Do you want edit this quotation?");
			if (check == true) {
				document.getElementById('button_div2').innerHTML = document.getElementById('loading').innerHTML;
				window.location = 'index.php?components=<?php print $components; ?>&action=quotation&id=' + id + '&s=' + s + '&cust=' + cust;
			}
		}
	<?php } ?>

	function print_quot($id) {
		iframe = document.getElementById("quot_iframe");
		<?php if (($components != 'to')) { ?>
			xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET", "index.php?components=<?php print $components; ?>&action=set_submit&id=" + $id, true);
			xmlhttp.send();
		<?php } ?>
		<?php if ($systemid == 13) {
			if ($is_quotation_sent_with_tax) { ?>
				iframe = document.getElementById("quot_iframe_3");
			<?php } ?>
		<?php } ?>
		iframe.focus();
		iframe.contentWindow.print();
	}

	function print_quot_inv() {
		document.getElementById('quot_iframe').focus();
		document.getElementById('quot_iframe').contentWindow.print();
	}

	function showHideDetails() {
		var details_key = document.getElementById('details_key').value;
		if (details_key == 'hide') {
			document.getElementById('details_key').value = 'show';
			document.getElementById('details_link').innerHTML = '<a style="cursor:pointer; color:blue" onclick="showHideDetails()"><strong>- Hide Details</strong></a>';
			document.getElementById('details_div').style.display = 'block';
		} else if (details_key == 'show') {
			document.getElementById('details_key').value = 'hide';
			document.getElementById('details_link').innerHTML = '<a style="cursor:pointer; color:blue" onclick="showHideDetails()"><strong>+ Show Details</strong></a>';
			document.getElementById('details_div').style.display = 'none';
		}
	}

	function validateQOImage() {
		if (document.getElementById('fileToUpload1').value != '') {
			document.getElementById('div_submit').innerHTML = document.getElementById('loading').innerHTML;
			return true;
		} else {
			alert("Please select a file to upload.");
			return false;
		}
	}
	function validateImgHeight() {
		if (document.getElementById('height').value != '') {
			document.getElementById('div_submit').innerHTML = document.getElementById('loading').innerHTML;
			return true;
		} else {
			window.alert('Please Enter a Valid Height');
			return false;
		}
	}

	function oneDescriptionMode() {
		var $n = 0;
		var iframe = document.getElementById("quot_iframe");
		if (document.getElementById("onemode").checked == true) {
			var item_count = iframe.contentWindow.document.getElementById('item_count').value;
			for ($i = 0; $i < item_count; $i++) {
				if ((iframe.contentWindow.document.getElementById('comm_' + $i).innerHTML != '') && ($n == 0)) {
					iframe.contentWindow.document.getElementById('id_' + $i).innerHTML = '1';
					iframe.contentWindow.document.getElementById('des_' + $i).innerHTML = '';
					iframe.contentWindow.document.getElementById('qty_' + $i).innerHTML = '&nbsp;&nbsp;1&nbsp;&nbsp;';
					iframe.contentWindow.document.getElementById('uprice_' + $i).innerHTML = '&nbsp;&nbsp;' + iframe.contentWindow.document.getElementById('total').innerHTML;
					iframe.contentWindow.document.getElementById('tprice_' + $i).innerHTML = iframe.contentWindow.document.getElementById('total').innerHTML;
					var $n = 1;
				} else {
					iframe.contentWindow.document.getElementById('id_' + $i).innerHTML = '';
					iframe.contentWindow.document.getElementById('des_' + $i).innerHTML = '';
					iframe.contentWindow.document.getElementById('qty_' + $i).innerHTML = '';
					iframe.contentWindow.document.getElementById('uprice_' + $i).innerHTML = '';
					iframe.contentWindow.document.getElementById('tprice_' + $i).innerHTML = '';
					iframe.contentWindow.document.getElementById('comm_' + $i).innerHTML = '';
				}
			}
		} else {
			location.reload();
		}
	}

	function validateNote() {
		if (document.getElementById('textnote').value !== '') {
			document.getElementById('notesend').innerHTML = document.getElementById('loading2').innerHTML;
			return true;
		} else
			return false;
	}

</script>

<?php
if (isset($_REQUEST['message'])) {
	if ($_REQUEST['re'] == 'success')
		$color00 = 'green';
	else
		$color00 = '#DD3333';
	print '<script type="text/javascript">document.getElementById("notifications").innerHTML=' . "'" . '<span style="color:' . $color00 . '; font-weight:bold;font-size:12pt;">' . $_REQUEST['message'] . '</span>' . "'" . ';</script>';
}
?>

<div id="loading" style="display:none">
	<img src="images/loading.gif" style="width:70px" /><br><span style="color:maroon; vertical-align:middle">Please
		Wait</span>
</div>

<div id="loading2" style="display:none">
	<img src="images/loading.gif" style="width:40px" />
</div>

<input type="hidden" id="details_key" value="hide" />
<table align="center" style="font-family:Calibri">
	<tr>
		<td valign="top">
			<div id="details_link">
				<a style="cursor:pointer; color:blue" onclick="showHideDetails()"><strong>+ Show Details</strong></a>
			</div>
			<div id="details_div" style="display:none">
				<table align="center" style="font-size:11pt; font-family:Calibri">
					<tr>
						<td style="background-color:#467898; color:white;" class="shipmentTB4">Sub System</td>
						<td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_sub_system; ?>
						</td>
					</tr>
					<tr>
						<td style="background-color:#467898; color:white;" class="shipmentTB4">Pricing District</td>
						<td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_district; ?></td>
					</tr>
					<tr>
						<td style="background-color:#467898; color:white;" class="shipmentTB4">Quotation Store</td>
						<td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_store; ?></td>
					</tr>
					<tr>
						<td style="background-color:#467898; color:white;" class="shipmentTB4">Referred Inventory</td>
						<td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_refinv; ?></td>
					</tr>
					<tr>
						<td style="background-color:#467898; color:white;" class="shipmentTB4">Quotation Type</td>
						<td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_type; ?></td>
					</tr>
					<tr>
						<td style="background-color:#467898; color:white;" class="shipmentTB4">Generated Date</td>
						<td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">
							<?php print substr($main_created_date, 0, 16); ?>
						</td>
					</tr>
					<tr>
						<td style="background-color:#467898; color:white;" class="shipmentTB4">Generated By</td>
						<td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">
							<?php print ucfirst($main_created_by); ?>
						</td>
					</tr>
					<tr>
						<td style="background-color:#467898; color:white;" class="shipmentTB4">Processed Date</td>
						<td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">
							<?php print substr($main_approved_date, 0, 16); ?>
						</td>
					</tr>
					<tr>
						<td style="background-color:#467898; color:white;" class="shipmentTB4">Processed By</td>
						<td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">
							<?php print ucfirst($main_approved_by); ?>
						</td>
					</tr>
					<tr>
						<td style="background-color:#467898; color:white;" class="shipmentTB4">Submited Date</td>
						<td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">
							<?php print substr($main_submited_date, 0, 16); ?>
						</td>
					</tr>
					<tr>
						<td style="background-color:#467898; color:white;" class="shipmentTB4">Submited By</td>
						<td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">
							<?php print ucfirst($main_submited_by); ?>
						</td>
					</tr>
					<tr>
						<td style="background-color:#467898; color:white;" class="shipmentTB4">Deleted Date</td>
						<td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">
							<?php print substr($main_deleted_date, 0, 16); ?>
						</td>
					</tr>
					<tr>
						<td style="background-color:#467898; color:white;" class="shipmentTB4">Deleted By</td>
						<td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">
							<?php print ucfirst($main_deleted_by); ?>
						</td>
					</tr>
					<tr>
						<td style="background-color:#467898; color:white;" class="shipmentTB4"><a
								style="text-decoration:none; color:white; cursor:pointer"
								title="Once selected, only the fist comment in the item list will be shown.&#013;The QTY will be '1' and Unit price will shown as a total.">Once
								Description Mode</a></td>
						<td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><input type="checkbox" id="onemode"
								onchange="oneDescriptionMode()" /></td>
					</tr>
					<?php
					if (($qm_status == 5) && ($components != 'to')) {
						print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Customer Accept Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">' . '<input type="date" name="accdate" id="accdate" style="width:125px" value="' . dateNow() . '" /></td></tr>';
						print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Customer&apos;s PO No</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">' . '<input type="text" name="custpo" id="custpo" style="width:125px" value="" /> <div id="acc_div1"><input type="button" value="submit" onclick="setCustAccept(' . $_GET['id'] . ')" /></div></td></tr>';
						print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Customer&apos;s Reject</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">' . '<input type="text" name="custreject" id="custreject" style="width:125px" value="" placeholder="Rejected Reason" /> <div id="acc_div2"><input type="button" value="Reject" onclick="setCustReject(' . $_GET['id'] . ')" /></div></td></tr>';
					}
					if ($qm_status == 6 || $qm_status == 8) {
						print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Customer Accept Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">' . $main_custacc_date . '</td></tr>';
						print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Customer&apos;s PO No</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">' . $main_cust_po . '</td></tr>';
					}
					if ($qm_status == 7) {
						print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Rejected Comment</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><textarea style="width:130px">' . $main_reject_comm . '</textarea></td></tr>';
						print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Rejected Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">' . $main_custacc_date . '</td></tr>';
						print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Reject Marked By</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">' . $main_custacc_by . '</td></tr>';
					}
					if ($qm_status == 8) {
						print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Completed Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">' . substr($main_completed_date, 0, 16) . '</td></tr>';
						print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Completed By</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">' . ucfirst($main_completed_by) . '</td></tr>';
					}
					if (($qm_status == 6) && (sizeof($bm_no) > 0) && ($components != 'to')) {
						print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4" colspan="2" align="center" ><input type="button" value="Set Order As COMPLETED" onclick="setQuotStatus(' . "'" . $_GET['id'] . "','complete'" . ')" /></td></tr>';
					}

					if ($qm_status == 2 || $qm_status == 3) {
						if ($qm_status == 3 && ($components != 'to')) {
							if ($main_image == 0) {
								print '<tr>
										<td colspan="2" style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4" align="center">
											<form enctype="multipart/form-data" action="index.php?components=' . $components . '&action=qo_add_image&id=' . $_GET['id'] . '" method="post" onsubmit="return validateQOImage()">
												<input type="file" name="fileToUpload1" id="fileToUpload1" />
												<div id="div_submit">
													<input type="submit" value="Add Image" name="submit"/>
												</div>
											</form>
										</td>
									</tr>';
							} else {
								print '<tr>
										<td colspan="2" style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4" align="center">
											<form action="index.php?components=' . $components . '&action=qo_img_height&id=' . $_GET['id'] . '"
												method="post" onsubmit="return validateImgHeight()"><strong>Image</strong>&nbsp;&nbsp;<input
													type="number" name="height" id="height" value="' . $main_image_hei . '" style="width:50px" /><input
													type="submit" value="set" />&nbsp;&nbsp;&nbsp;<input type="button" value="Remove"
													onclick="deleteQOImage(' . $_GET['id'] . ')" />
												<div id="div_submit"></div>
											</form>
										</td>
									</tr>';
							}
						}
					}
					?>
				</table>
			</div>
			<hr />
			<div>
				<table cellspacing="0">
					<tr>
						<td style="font-weight:bold; color:#467898"><em>Followup Notes</em></td>
					</tr>
					<tr>
						<td>
							<table border="0" bordercolor="#C0C0C0" bgcolor="#EEEEEE" cellspacing="0" width="300px">
								<tr>
									<td>
										<?php for ($i = 0; $i < sizeof($qn_noid); $i++) {
											if ($edit_note[$i])
												$edit_button = '<a href="index.php?components=supervisor&action=qo_finish&id=' . $_GET['id'] . '&comid=' . $qn_noid[$i] . '"><img src="/images/edit.gif" /></a>';
											else
												$edit_button = '';
											print '<div style="background-color:#99CCEE; font-size:10pt; border-radius: 5px; padding-left:4px; padding-right:3px"><strong>' . $qn_user[$i] . '</strong><br />' . html_entity_decode($qn_note[$i]) . ' ' . $edit_button . '<br />
											<table cellspacing="0" width="100%" style="color:#999999; font-size:8pt; text-align:right"><tr><td align="right"><em>' . $qn_timestamp[$i] . '</em></td></tr></table></div>';
											print '<div style="height:5px"><br /></div>';
										} ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<?php if ($edit_note0 == '')
								$action0 = 'qo_add_note';
							else
								$action0 = 'qo_update_note'; ?>
							<form
								action="index.php?components=<?php print $components; ?>&action=<?php print $action0; ?>&id=<?php print $_GET['id']; ?>"
								onsubmit="return validateNote()" method="post">
								<textarea id="textnote" name="textnote" style="width:98%"><?php print $edit_note0; ?></textarea>
								<?php if ($edit_note0 == '') {
									print '<div id="notesend"><input type="submit" value="Submit" /></div>';
								} else {
									print '<input type="hidden" name="comid" value="' . $_GET['comid'] . '" />';
									print '<div id="notesend"><input type="submit" value="Update" /> <input type="button" value="Cancel" onclick="window.location = \'index.php?components=supervisor&action=qo_finish&id=' . $_GET['id'] . '\'" /></div>';
								} ?>
							</form>
						</td>
					</tr>
				</table>
			</div>
			<hr>
		</td>
		<td width="50px"></td>
		<td>
			<table align="center" style="font-size:11pt">
				<tr>
					<td bgcolor="#467898" style="font-family:Calibri; color:white; padding-left:10px">
						Quotation Status : <span <?php if ($status == 'Deleted')
							print 'class="blink"'; ?>
							style="color:<?php print $color; ?>;"><strong><?php print $status; ?></strong></span>
					</td>
					<td></td>
				</tr>
				<tr>
					<td>
						<!-- ------------------Item List----------------------- -->
						<?php
						if ($tm_template == 1 || $tm_template == 2) {
							print '<iframe id="quot_iframe" width="' . $page_width . 'px" height="' . $page_height . 'px" src="components/supervisor/view/tpl/quotation_print2.php?id=' . $_GET['id'] . '&action=' . $action . '"></iframe>';
						} else if ($tm_template == 3 || $tm_template == 4 || $tm_template == 5 || $tm_template == 6) {
							if ($systemid == 13) { // techneeds tax invoice
								if ($is_quotation_sent_with_tax) {
									print '<iframe id="quot_iframe_3" width="' . $page_width . 'px" height="' . $page_height . 'px" src="components/supervisor/view/tpl/quotation_print3_2.php?id=' . $_GET['id'] . '&action=' . $action . '&sub_components=' . $components . '"></iframe>';
								} else {
									print '<iframe id="quot_iframe" width="' . $page_width . 'px" height="' . $page_height . 'px" src="components/supervisor/view/tpl/quotation_print3.php?id=' . $_GET['id'] . '&action=' . $action . '&sub_components=' . $components . '"></iframe>';
								}
							} else {
								print '<iframe id="quot_iframe" width="' . $page_width . 'px" height="' . $page_height . 'px" src="components/supervisor/view/tpl/quotation_print3.php?id=' . $_GET['id'] . '&action=' . $action . '&sub_components=' . $components . '"></iframe>';
							}
						} else {
							print '<iframe id="quot_iframe" width="' . $page_width . 'px" height="' . $page_height . 'px" src="components/supervisor/view/tpl/quotation_print1.php?id=' . $_GET['id'] . '&action=' . $action . '"></iframe>';
						}
						?>
					</td>
					<td valign="top" align="center">
						<?php if ($qm_status <= 4 && $systemid == 13) { ?>
							<!-- techneeds tax invoice -->
							<div class="checkbox-container prtbutton3">
								<input type="checkbox" id="is_quotation_sent_with_tax" <?php echo ($is_quotation_sent_with_tax) ? 'checked' : ''; ?>>
								<label for="is_quotation_sent_with_tax">Is Quotation Sent With Tax?</label>
							</div>
							<br>
						<?php } ?>
						<?php
						if (isset($is_quotation_sent_with_tax) && $is_quotation_sent_with_tax) { ?>
							<div class="checkbox-container prtbutton3">
								<input type="checkbox" id="show_excluded" />
								<label for="show_excluded">Show Prices Excluding VAT</label>
							</div>
							<br>
						<?php }
						?>
						<?php if ((($qm_status == 2 || $qm_status == 1) && $approver) && ($components != 'to')) { ?>
							<div id="button_div1" class="prtbutton1">
								<a class="shortcut-button" style="text-decoration:none;"
									onclick="setQuotStatus('<?php print $_GET['id']; ?>','Approve')" href="#">
									<span style="text-decoration:none; font-family:Arial; color:navy;">
										<img src="images/approve.png" alt="icon" /><br />
										Approve
									</span>
								</a>
							</div>
							<br />
							<div id="button_div2" class="prtbutton1">
								<a class="shortcut-button" style="text-decoration:none;"
									onclick="editQuot(<?php print $_GET['id']; ?>,<?php print $qm_created_by; ?>,<?php print $qm_cust; ?>)"
									href="#">
									<span style="text-decoration:none; font-family:Arial; color:navy;">
										<img src="images/edit.gif" alt="icon" style="width:48px;" /><br />
										Edit
									</span>
								</a>
							</div>
							<br />
							<div id="button_div1" class="prtbutton2">
								<a class="shortcut-button" style="text-decoration:none;"
									onclick="setQuotStatus('<?php print $_GET['id']; ?>','Reject')" href="#">
									<span style="text-decoration:none; font-family:Arial; color:navy;">
										<img src="images/reject.png" alt="icon" /><br />
										Reject
									</span>
								</a>
							</div>
						<?php } else if ($qm_status == 3 || $qm_status == 5 || $qm_status == 6) { ?>
								<div class="prtbutton1">
									<a class="shortcut-button" style="text-decoration:none; font-family:Arial; color:white;"
										onclick="print_quot(<?php print $_GET['id']; ?>)" href="#">
										<img src="images/print.png" alt="icon" /><br /> Print
									</a>
								</div>
						<?php } ?>
						<?php if (($qm_status == 2 || $qm_status == 3) || ($qm_status == 5 && $components != 'to')) { ?>
							<?php if (($qm_status == 5) && ($components != 'to')) { ?>
								<br />
								<div id="revise_div" class="prtbutton1">
									<a class="shortcut-button" style="text-decoration:none; font-family:Arial; color:white;"
										onclick="reviseQuot(<?php print $_GET['id']; ?>)" href="#">
										<img src="images/revise.png" alt="icon" /><br />Revise
									</a>
								</div>
							<?php }
						} ?>
						<br />
						<?php if (($authorize) && ($qm_status == 1 || $qm_status == 2 || (($qm_status == 3) && ($components != 'to')))) { ?>
							<div id="button_div1" class="prtbutton2">
								<a class="shortcut-button" style="text-decoration:none; font-family:Arial; color:navy;"
									onclick="setQuotStatus('<?php print $_GET['id']; ?>','Delete')" href="#">
									<img src="images/cancel.png" alt="icon" /><br />
									Cancel
								</a>
							</div>
						<?php } else if (($qm_status == 6 || $qm_status == 7) && ($components != 'to')) {
							if (($qm_status == 6)) { ?>
									<div id="button_div1" class="prtbutton1">
										<?php
										if ($bill_module == 'bill2') {
											$link = 'index.php?components=bill2&action=new_tmp_bill3&quotation=' . $_GET['id'] . '&cust_odr=yes&cust_id=' . $qm_cust . '&sm_id=' . $qm_created_by . '"';
										} else {
											$link = 'index.php?components=billing&action=new_bill&quotation=' . $_GET['id'] . '&cust_odr=yes&cust_id=' . $qm_cust . '&salesman=' . $qm_created_by . '"';
										}
										if ($systemid == 15) {
											$link = 'index.php?components=bill2&action=new_tmp_bill3&quotation=' . $_GET['id'] . '&cust_odr=no&cust_id=' . $qm_cust . '&sm_id=' . $qm_created_by . '"';
										}
										?>
										<a class="shortcut-button" target="_blank" href="<?php print $link; ?>" style="text-decoration:none;">
											<span style="text-decoration:none; font-family:Arial; color:white;">
												<img src="images/order.png" alt="icon" /><br />
												Place Order
											</span></a>
									</div>
									<br />
									<div id="button_div1" class="prtbutton1">
										<a class="shortcut-button" target="_blank"
											href="index.php?components=purchase_order&action=home&item_list=<?php print $qitem_list; ?>&qty_list=<?php print $qqty_list; ?>"
											style="text-decoration:none;">
											<span style="text-decoration:none; font-family:Arial; color:white;">
												<img src="images/po.png" alt="icon" /><br />
												Generate PO
											</span></a>
									</div>
							<?php }
							for ($i = 0; $i < sizeof($bm_no); $i++) { ?>
									<br />
									<div id="button_div1"
										style="background-color:#6699FF; border:medium; border-color:black; width:80px; border-radius: 15px; font-size:10pt">
										<a class="shortcut-button"
											href="index.php?components=<?php print $bill_module; ?>&action=finish_bill&id=<?php print $bm_no[$i]; ?>"
											style="text-decoration:none;" target="_blank">
											<span style="text-decoration:none; font-family:Arial; color:navy;">
												Order-In-Place<br /><br /><span style="color:white">
												<?php print str_pad($bm_no[$i], 7, "0", STR_PAD_LEFT); ?>
												</span></span></a>
									</div>
							<?php }
						} ?>
						<?php if (($action == 'qo_finish' && $qm_status == 8) && ($components != 'to')) { ?>
							<div
								style="background-color:#6699FF; border:medium; border-color:black; width:80px; border-radius: 15px; font-size:10pt">
								<a class="shortcut-button" style="text-decoration:none;" target="_blank"
									href="index.php?components=<?php print $components; ?>&action=qo_com_inv&id=<?php print $_GET['id']; ?>">
									<span style="font-family:Arial; color:white;">
										<img src="images/print.png" alt="icon" /><br />
										Generate Combine Invoice
									</span>
								</a>
							</div>

							<?php for ($i = 0; $i < sizeof($bm_no); $i++) { ?>
								<br />
								<div id="button_div1"
									style="background-color:#6699FF; border:medium; border-color:black; width:80px; border-radius: 15px; font-size:10pt">
									<a class="shortcut-button"
										href="index.php?components=<?php print $bill_module; ?>&action=finish_bill&id=<?php print $bm_no[$i]; ?>"
										style="text-decoration:none;">
										<span style="text-decoration:none; font-family:Arial; color:navy;">
											Order-In-Place<br /><br /><span style="color:white">
												<?php print str_pad($bm_no[$i], 7, "0", STR_PAD_LEFT); ?>
											</span></span>
									</a>
								</div>
							<?php } ?>
						<?php } else if ($action == 'qo_com_inv' && $qm_status == 8) { ?>
								<div
									style="background-color:#6699FF; border:medium; border-color:black; width:80px; border-radius: 15px; font-size:10pt">
									<a class="shortcut-button" style="text-decoration:none;" onclick="print_quot_inv()" href="#">
										<span style="font-family:Arial; color:white;">
											<img src="images/print.png" alt="icon" /><br />
											Print Combine Invoice
										</span>
									</a>
								</div>
						<?php } ?>
						<input type="text" id="keytxt" style="width:10px; border:0px" onkeypress="KeyPress(event);" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?php
include_once 'template/footer.php';
?>
<script>
	<?php if ($systemid == 13) { ?>
		<?php
		$components = htmlspecialchars($components, ENT_QUOTES, 'UTF-8');
		$qo_id = htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');
		?>
		var taxCheckbox = document.getElementById("is_quotation_sent_with_tax");
		if (taxCheckbox) {
			document.getElementById("is_quotation_sent_with_tax").addEventListener("change", function () {
				const id = '<?php echo $qo_id; ?>';
				var checkbox = document.getElementById("is_quotation_sent_with_tax");
				if (checkbox.checked) {
					window.location = 'index.php?components=<?php echo $components; ?>&action=quotation_sent_with_tax&flag=1&id=' + id;
				} else {
					window.location = 'index.php?components=<?php echo $components; ?>&action=quotation_sent_with_tax&flag=0&id=' + id;
				}
			});
		}

		document.getElementById("show_excluded").addEventListener("change", function () {
			var iframe = document.getElementById("quot_iframe_3");
			var baseUrl =
				"components/supervisor/view/tpl/quotation_print3_2.php?id=<?php echo $_GET['id']; ?>&action=<?php echo $action; ?>&sub_components=<?php echo $components; ?>";

			if (this.checked) {
				iframe.src = baseUrl + "&show_excluded=1";
			} else {
				iframe.src = baseUrl + "&show_excluded=0";
			}
		});
	<?php } ?>
</script>