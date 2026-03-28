<?php
include_once 'template/header.php';
$decimal = getDecimalPlaces(1);
$currency = getCurrency(1);
$systemid = inf_systemid(1);
$sub_system = $_COOKIE['sub_system'];
$bill_module = bill_module(1);
?>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<script type="text/javascript">
	function orderProcess() {
		var check = confirm("Are you sure you want to perform this action?");
		if (check == true) {
			document.getElementById('orderprocess').innerHTML = document.getElementById('loading').innerHTML;
			window.location = 'index.php?components=order_process&action=<?php print $button_action; ?>&id=<?php print $_REQUEST['id']; ?>'
		}
	}
	function orderUnassign($type) {
		var check = confirm("Do want to unassign this order from current user?");
		if (check == true) {
			document.getElementById('orderprocess3').innerHTML = document.getElementById('loading').innerHTML;
			if ($type == 4 || $type == 5) $action2 = 'list_custodr'; else $action2 = 'list_pending';
			window.location = 'index.php?components=order_process&action=set_unassign&next_action=' + $action2 + '&id=<?php print $_GET['id']; ?>';
		}
	}

	function removeItemODR($id) {
		var check = confirm("Are you sure you want to perform this action?");
		if (check == true) {
			document.getElementById('button_' + $id).innerHTML = document.getElementById('loading').innerHTML;
			window.location = 'index.php?components=order_process&action=bill_item_remove&s=&cust=&id=' + $id;
		}
	}
	function updateItemODR($id) {
		var check = confirm("Are you sure you want to perform this action?");
		if (check == true) {
			document.getElementById('button_' + $id).innerHTML = document.getElementById('loading').innerHTML;
			var qty = document.getElementById('qty_' + $id).value;
			window.location = 'index.php?components=order_process&action=bill_item_gpdate&cust_odr=&id=' + $id + '&qty=' + qty + '&s=&cust=';
		}
	}

	function returnPacked($id) {
		var check = confirm("Are you sure you want to perform this action?");
		if (check == true) {
			document.getElementById('return7_' + $id).innerHTML = document.getElementById('loading').innerHTML;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function () {
				if (this.readyState == 4 && this.status == 200) {
					var returntext = this.responseText;
					if (returntext == 'done') {
						document.getElementById('return1_done_' + $id).innerHTML = document.getElementById('return1_' + $id).innerHTML;
						document.getElementById('return2_done_' + $id).innerHTML = document.getElementById('return2_' + $id).innerHTML;
						document.getElementById('return3_done_' + $id).innerHTML = document.getElementById('return3_' + $id).innerHTML;
						document.getElementById('return4_done_' + $id).innerHTML = document.getElementById('return4_' + $id).innerHTML;
						document.getElementById('return5_done_' + $id).innerHTML = document.getElementById('return5_' + $id).innerHTML;
						document.getElementById('return6_done_' + $id).innerHTML = document.getElementById('return6_' + $id).innerHTML;
						document.getElementById('return7_done_' + $id).innerHTML = '<input type="button" value="Remove" onmouseup="removeReturnPacked(' + $id + ')" style="background-color:maroon; color:white" />';

						document.getElementById('return1_' + $id).innerHTML = '';
						document.getElementById('return2_' + $id).innerHTML = '';
						document.getElementById('return3_' + $id).innerHTML = '';
						document.getElementById('return4_' + $id).innerHTML = '';
						document.getElementById('return5_' + $id).innerHTML = '';
						document.getElementById('return6_' + $id).innerHTML = '';
						document.getElementById('return7_' + $id).innerHTML = '';
					} else {
						document.getElementById('return7_' + $id).innerHTML = '<span style="color:red"><strong>' + returntext + '</strong></span>';
					}
				}
			};
			xhttp.open("GET", 'index.php?components=order_process&action=return_packed&id=' + $id + '&odr_no=<?php print $_GET['id']; ?>', true);
			xhttp.send();
		}
	}

	function removeReturnPacked($id) {
		var check = confirm("Are you sure you want to perform this action?");
		if (check == true) {
			document.getElementById('return7_done_' + $id).innerHTML = document.getElementById('loading').innerHTML;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function () {
				if (this.readyState == 4 && this.status == 200) {
					var returntext = this.responseText;
					console.log(returntext);
					if (returntext == 'done') {
						document.getElementById('return1_' + $id).innerHTML = document.getElementById('return1_done_' + $id).innerHTML;
						document.getElementById('return2_' + $id).innerHTML = document.getElementById('return2_done_' + $id).innerHTML;
						document.getElementById('return3_' + $id).innerHTML = document.getElementById('return3_done_' + $id).innerHTML;
						document.getElementById('return4_' + $id).innerHTML = document.getElementById('return4_done_' + $id).innerHTML;
						document.getElementById('return5_' + $id).innerHTML = document.getElementById('return5_done_' + $id).innerHTML;
						document.getElementById('return6_' + $id).innerHTML = document.getElementById('return6_done_' + $id).innerHTML;
						document.getElementById('return7_' + $id).innerHTML = '<input type="button" value="Pack" onmouseup="returnPacked(' + $id + ')" style="background-color:maroon; color:white" />';

						document.getElementById('return1_done_' + $id).innerHTML = '';
						document.getElementById('return2_done_' + $id).innerHTML = '';
						document.getElementById('return3_done_' + $id).innerHTML = '';
						document.getElementById('return4_done_' + $id).innerHTML = '';
						document.getElementById('return5_done_' + $id).innerHTML = '';
						document.getElementById('return6_done_' + $id).innerHTML = '';
						document.getElementById('return7_done_' + $id).innerHTML = '';
					} else {
						document.getElementById('return7_done_' + $id).innerHTML = '<span style="color:red"><strong>Error</strong></span>';
					}
				}
			};
			xhttp.open("GET", 'index.php?components=order_process&action=remove_return_packed&id=' + $id + '&odr_no=<?php print $_GET['id']; ?>', true);
			xhttp.send();
		}
	}
</script>
<?php
if (isset($_REQUEST['id']))
	$id = $_REQUEST['id'];
else
	$id = 0;
?>

<?php
if (isset($_REQUEST['message'])) {
	if ($_REQUEST['re'] == 'success')
		$color = 'green';
	else
		$color = 'red';
	print '<table align="center" bgcolor="#E5E5E5"><tr><td colspan="5"><span style="color:' . $color . '; font-weight:bold;font-size:12pt;">' . $_REQUEST['message'] . '</span></td></tr></table><br />';
}
?>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<form action="#" method="post">
	<table align="center" style="font-size:12pt" width="900px">
		<tr>
			<td style="background-color:#467898;color :white;">
				<strong><?php if ($bm_status < 3)
					print 'Order No';
				else
					print 'Invoice No'; ?></strong>
			</td>
			<td bgcolor="#EEEEEE"><?php print str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?></td>
			<td></td>
			<td style="background-color:#467898;color :white;"><strong>Salesman</strong></td>
			<td bgcolor="#EEEEEE"><?php print ucfirst($bi_salesman); ?></td>
			<td rowspan="2">
				<?php
				if ($button == 'Packed' && $bi_seen_by == $_COOKIE['user']) {
					if ($button == 'Packed' && $systemid == 13 && $sub_system == 1)
						$button1 = 'Started';
					else
						$button1 = 'Packed';
					?>
					<div id="orderprocess">
						<input type="button" value="<?php print $button1; ?>"
							style="height:50px; width:70px; background-color:#CC5100; font-weight:bold; color:white"
							onclick="orderProcess()" />
					</div>
				<?php } ?>
				<input type="button" value="Print"
					style="height:50px; width:70px; background-color:#007799; font-weight:bold; color:white"
					onclick="window.location = 'index.php?components=<?php print $bill_module; ?>&action=finish_bill&id=<?php print $_REQUEST['id']; ?>'" />
				<?php
				if ((isset($_COOKIE['report']) || isset($_COOKIE['manager'])) && $button == 'Packed') {
					print '<div id="orderprocess3"><input type="button" value="Unassign"
							style="height:50px; width:70px; background-color:orange; font-weight:bold; color:white"
							onclick="orderUnassign(' . $bm_type . ')" /></div>';
				}
				?>
			</td>
		</tr>
		<tr>
			<td style="background-color:#467898;color :white;"><strong>Order Date</strong></td>
			<td bgcolor="#EEEEEE"><?php print substr($odr_date, 0, 16); ?></td>
			<td></td>
			<td style="background-color:#467898;color :white;"><strong>Customer</strong></td>
			<td bgcolor="#EEEEEE">
				<?php print '<a href="index.php?components=' . $bill_module . '&action=cust_details&id=' . $cu_id . '&action2=list_one_custodr&id2=' . $_REQUEST['id'] . '" >' . ucfirst($bi_cust) . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td colspan="5" height="50px"></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="6" align="center">
				<table align="center">
					<tr style="background-color:#C0C0C0">
						<th></th>
						<th>Item Description</th>
						<th style="padding-left:20px; padding-right:20px">Item Qty</th>
						<th style="padding-left:20px; padding-right:20px">Drawer No</th>
						<th></th>
					</tr>
					<?php
					for ($i = 0; $i < sizeof($odr_bill_id); $i++) {
						if ($odr_bi_order[$i] == 0) {
							if ($odr_bi_no_update[$i] == 0)
								$update_button = '<input type="Button" value="Update" onclick="updateItemODR(' . $odr_bill_id[$i] . ')" />';
							else
								$update_button = '<input type="Button" value="Update" onclick="alert(' . "'Update is Restricted for this item'" . ')" />';
							print '<tr style="background-color:#F0F0F0">
									<td><input type="checkbox" /></td>
									<td style="padding-left:20px; padding-right:20px">' . $odr_bi_desc[$i] . '</td>
									<td style="padding-right:20px" align="right"><input type="text" id="qty_' . $odr_bill_id[$i] . '"
											value="' . $odr_bi_qty[$i] . '" style="width:50px; text-align:right;" /></td>
									<td style="padding-right:20px" align="right">' . $odr_bi_drawer[$i] . '</td>
									<td>
										<div id="button_' . $odr_bill_id[$i] . '">' . $update_button . ' <input type="button" value="Remove"
												onmouseup="removeItemODR(' . $odr_bill_id[$i] . ')" style="background-color:maroon; color:white" />
										</div>
									</td>
								</tr>';
						}
					}
					print '<tr style="background-color:#F0F0F0">
							<td colspan="2"></td>
							<td></td>
							<td></td>
							<td>
								<input type="button" value="Add New" onmouseup="window.location = ' . "'index.php?components=order_process&action=setdistrict_custodr&bill_no=" . $_GET['id'] . "&id=$bm_district&return=0'" . '"/>
							</td>
						</tr>';
					?>
				</table>

				<?php if ($systemid == 1 || $systemid == 10 || $systemid == 16 || $systemid == 17) { ?>
					<!-- --------------------------Return Item Handling------------------------------- -->
					<hr />
					<table align="center" width="100%">
						<tr style="background-color:#467898; color:white;">
							<th height="50px">RETURN ITEM PACKING</th>
						</tr>
					</table>
					<table align="center">
						<tr style="background-color:#467898; color:white;">
							<td colspan="4" style="padding-left:10px">Credit Balance for Cancel Returns : </td>
							<td align="right" style="padding-right:10px">
								<?php print $currency; ?> 	<?php print number_format($return_cr_bal, $decimal); ?>
							</td>
						</tr>
						<tr style="background-color:#C0C0C0">
							<th></th>
							<th>Item Description</th>
							<th style="padding-left:20px; padding-right:20px">Item Qty</th>
							<th style="padding-left:20px; padding-right:20px">Drawer No</th>
							<th></th>
						</tr>
						<?php
						for ($i = 0; $i < sizeof($odr_bill_id); $i++) {
							if ($odr_bi_order[$i] == 1) {
								if ($odr_bi_no_update[$i] == 0)
									$update_button = '<input type="Button" value="Update" onclick="updateItemODR(' . $odr_bill_id[$i] . ')" />';
								else
									$update_button = '<input type="Button" value="Update" onclick="alert(' . "'Update is Restricted for this item'" . ')" />';
								print '<tr style="background-color:#F0F0F0">
										<td><input type="checkbox" /></td>
										<td style="padding-left:20px; padding-right:20px">' . $odr_bi_desc[$i] . '</td>
										<td style="padding-right:20px" align="right">
											<input type="text" id="qty_' . $odr_bill_id[$i] . '" value="' . $odr_bi_qty[$i] . '" style="width:50px; text-align:right;" />
										</td>
										<td style="padding-right:20px" align="right">' . $odr_bi_drawer[$i] . '</td>
										<td>
											<div id="button_' . $odr_bill_id[$i] . '">' . $update_button . ' <input type="button" value="Remove" onmouseup="removeItemODR(' . $odr_bill_id[$i] . ')" style="background-color:maroon; color:white" />
											</div>
										</td>
								</tr>';
							}
						}
						print '<tr style="background-color:#F0F0F0">
							<td colspan="2"></td>
							<td></td>
							<td></td>
							<td>
								<input type="button" value="Add New" onmouseup="window.location = ' . "'index.php?components=order_process&action=setdistrict_custodr&bill_no=" . $_GET['id'] . "&id=$bm_district&return=1'" . '" />
							</td>
						</tr>';
						?>
					</table>

					<table align="center">
						<tr>
							<th style="background-color:#467898;color :white;" colspan="7">Return Items | Pending</th>
						</tr>
						<tr style="background-color:#C0C0C0">
							<th></th>
							<th style="padding-left:20px; padding-right:20px">RT Invoice</th>
							<th style="padding-left:20px; padding-right:20px">Return Date</th>
							<th style="padding-left:20px; padding-right:20px">Item</th>
							<th style="padding-left:20px; padding-right:20px">Return Qty</th>
							<th style="padding-left:20px; padding-right:20px">Salesman</th>
							<th style="padding-left:20px; padding-right:20px">Pack</th>
						</tr>
						<?php
						for ($i = 0; $i < sizeof($rtn_id); $i++) {
							print '<tr style="background-color:#F0F0F0">
									<td>
										<div id="return1_' . $rtn_id[$i] . '"><input type="checkbox" /></div>
									</td>
									<td align="center">
										<div id="return2_' . $rtn_id[$i] . '">
											<a href="index.php?components=' . $bill_module . '&action=finish_return&id=' . $rtn_inv[$i] . '">' . str_pad($rtn_inv[$i], 7, "0", STR_PAD_LEFT) . '</a>
										</div>
									</td>
									<td align="center">
										<div id="return3_' . $rtn_id[$i] . '">' . $rtn_date[$i] . '</div>
									</td>
									<td style="padding-left:20px; padding-right:20px">
										<div id="return4_' . $rtn_id[$i] . '">
										<a style="text-decoration:none" href="index.php?components=order_process&action=show_one_return_item&odr_id=' . $_GET['id'] . '&rtn_id=' . $rtn_id[$i] . '">' . $rtn_itm_desc[$i] . '</a>
										</div>
									</td>
									<td style="padding-right:20px" align="right">
										<div id="return5_' . $rtn_id[$i] . '">' . $rtn_qty[$i] . '</div>
									</td>
									<td style="padding-right:20px; padding-left:20px;" align="right">
										<div id="return6_' . $rtn_id[$i] . '">' . ucfirst($rtn_by[$i]) . '</div>
									</td>
									<td align="center">
										<div id="return7_' . $rtn_id[$i] . '">
										<input type="button" value="Pack" onmouseup="returnPacked(' . $rtn_id[$i] . ')" style="background-color:maroon; color:white" /></div>
									</td>
								</tr>';
						}
						for ($i = 0; $i < sizeof($rtn2_id); $i++) {
							print '<tr style="background-color:#F0F0F0">
									<td>
										<div id="return1_' . $rtn2_id[$i] . '"></div>
									</td>
									<td align="center">
										<div id="return2_' . $rtn2_id[$i] . '"></div>
									</td>
									<td align="center">
										<div id="return3_' . $rtn2_id[$i] . '"></div>
									</td>
									<td style="padding-left:20px; padding-right:20px">
										<div id="return4_' . $rtn2_id[$i] . '"></div>
									</td>
									<td style="padding-right:20px" align="right">
										<div id="return5_' . $rtn2_id[$i] . '"></div>
									</td>
									<td style="padding-right:20px; padding-left:20px;" align="right">
										<div id="return6_' . $rtn2_id[$i] . '"></div>
									</td>
									<td align="center">
										<div id="return7_' . $rtn2_id[$i] . '"></div>
									</td>
								</tr>';
						}
						?>
						<tr>
							<th colspan="7"><br /></th>
						</tr>
						<tr>
							<th style="background-color:#787898;color :white;" colspan="7">Return Items |
								<?php if ($systemid == 13 && $sub_system == 1)
									echo 'Started';
								else
									print 'Packed'; ?>
							</th>
						</tr>
						<tr style="background-color:#C0C0C0">
							<th></th>
							<th style="padding-left:20px; padding-right:20px">RT Invoice</th>
							<th style="padding-left:20px; padding-right:20px">Return Date</th>
							<th style="padding-left:20px; padding-right:20px">Item</th>
							<th style="padding-left:20px; padding-right:20px">Return Qty</th>
							<th style="padding-left:20px; padding-right:20px">Salesman</th>
							<th style="padding-left:20px; padding-right:20px">Pack</th>
						</tr>
						<?php
						for ($i = 0; $i < sizeof($rtn_id); $i++) {
							print '<tr style="background-color:#F0F0F0">
									<td>
										<div id="return1_done_' . $rtn_id[$i] . '"></div>
									</td>
									<td align="center">
										<div id="return2_done_' . $rtn_id[$i] . '"></div>
									</td>
									<td align="center">
										<div id="return3_done_' . $rtn_id[$i] . '"></div>
									</td>
									<td style="padding-left:20px; padding-right:20px">
										<div id="return4_done_' . $rtn_id[$i] . '"></div>
									</td>
									<td style="padding-right:20px" align="right">
										<div id="return5_done_' . $rtn_id[$i] . '"></div>
									</td>
									<td style="padding-right:20px; padding-left:20px;" align="right">
										<div id="return6_done_' . $rtn_id[$i] . '"></div>
									</td>
									<td align="center">
										<div id="return7_done_' . $rtn_id[$i] . '"></div>
									</td>
								</tr>';
						}
						for ($i = 0; $i < sizeof($rtn2_id); $i++) {
							print '<tr style="background-color:#F0F0F0">
									<td>
										<div id="return1_done_' . $rtn2_id[$i] . '"><input type="checkbox" /></div>
									</td>
									<td align="center">
										<div id="return2_done_' . $rtn2_id[$i] . '">
											<a href="index.php?components=' . $bill_module . '&action=finish_return&id=' . $rtn2_inv[$i] . '">' . str_pad($rtn2_inv[$i], 7, "0", STR_PAD_LEFT) . '</a>
										</div>
									</td>
									<td align="center">
										<div id="return3_done_' . $rtn2_id[$i] . '">' . $rtn2_date[$i] . '</div>
									</td>
									<td style="padding-left:20px; padding-right:20px">
										<div id="return4_done_' . $rtn2_id[$i] . '">' . $rtn2_itm_desc[$i] . '</div>
									</td>
									<td style="padding-right:20px" align="right">
										<div id="return5_done_' . $rtn2_id[$i] . '">' . $rtn2_qty[$i] . '</div>
									</td>
									<td style="padding-right:20px; padding-left:20px;" align="right">
										<div id="return6_done_' . $rtn2_id[$i] . '">' . ucfirst($rtn2_by[$i]) . '</div>
									</td>
									<td align="center">
										<div id="return7_done_' . $rtn2_id[$i] . '">
											<input type="button" value="Remove" onmouseup="removeReturnPacked(' . $rtn2_id[$i] . ')" style="background-color:maroon; color:white" />
										</div>
									</td>
								</tr>';
						}
						?>
					</table>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td colspan="5" height="50px"></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="5">
				<table width="100%">
					<tr style="background-color:#C0C0C0">
						<td style="padding-left:20px;">Picked By</td>
						<td style="padding-left:20px;">
							<?php if ($systemid == 13 && $sub_system == 1)
								echo 'Started';
							else
								print 'Packed'; ?> By
						</td>
						<?php if ($systemid != 13 || $sub_system != 1) { ?>
							<td style="padding-left:20px;">Shipped By</td><?php } ?>
						<td style="padding-left:20px;">
							<?php if ($systemid == 13 && $sub_system == 1)
								echo 'Finished';
							else
								print 'Delivered'; ?> By
						</td>
					</tr>
					<tr style="background-color:#F0F0F0">
						<td style="padding-left:20px;"><?php print ucfirst($bi_seen_by ?? ''); ?></td>
						<td style="padding-left:20px;"><?php print ucfirst($bi_packed_by ?? ''); ?></td>
						<?php if ($systemid != 13 || $sub_system != 1) { ?>
							<td style="padding-left:20px;"><?php print ucfirst($bi_shipped_by ?? ''); ?></td><?php } ?>
						<td style="padding-left:20px;"><?php print ucfirst($bi_deliverd_by ?? ''); ?></td>
					</tr>
					<tr style="background-color:#F0F0F0">
						<td style="padding-left:20px;"><?php print $bi_seen_date . '<br/>' . $bi_seen_time; ?></td>
						<td style="padding-left:20px;"><?php print $bi_packed_date . '<br/>' . $bi_packed_time; ?></td>
						<?php if ($systemid != 13 || $sub_system != 1) { ?>
							<td style="padding-left:20px;"><?php print $bi_shipped_date . '<br/>' . $bi_shipped_time; ?>
							</td>
						<?php } ?>
						<td style="padding-left:20px;"><?php print $bi_deliverd_date . '<br/>' . $bi_deliverd_time; ?>
						</td>
					</tr>
				</table>
			</td>
			<td></td>
		</tr>
	</table>
</form>

<?php
include_once 'template/footer.php';
?>