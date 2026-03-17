<?php
include_once 'template/header.php';
?>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<script type="text/javascript">
	$(function () {
		var availableTags1 = [<?php for ($x = 0; $x < sizeof($code); $x++) {
			print '"' . $code[$x] . '",';
		} ?>];
		$("#tags1").autocomplete({
			source: availableTags1
		});
		var availableTags2 = [<?php for ($x = 0; $x < sizeof($description); $x++) {
			print '"' . $description[$x] . '",';
		} ?>];
		$("#tags2").autocomplete({
			source: availableTags2
		});
		var availableTags3 = [<?php for ($x = 0; $x < sizeof($cust_name); $x++) {
			print '"' . $cust_name[$x] . '",';
		} ?>];
		$("#tags3").autocomplete({
			source: availableTags3
		});
		var availableTags4 = [<?php for ($x = 0; $x < sizeof($unic_item_list2); $x++) {
			print '"' . $unic_item_list2[$x] . '",';
		} ?>];
		$("#tags4").autocomplete({
			source: availableTags4
		});
		$("#tags6").autocomplete({ source: availableTags1 });
		$("#tags7").autocomplete({ source: availableTags2 });
	});

	function itemSearch($input, $output, $key) {
		var itemid = [<?php for ($x = 0; $x < sizeof($id); $x++) {
			print '"' . $id[$x] . '",';
		} ?>];
		var code = [<?php for ($x = 0; $x < sizeof($code); $x++) {
			print '"' . $code[$x] . '",';
		} ?>];
		var desc = [<?php for ($x = 0; $x < sizeof($description); $x++) {
			print '"' . $description[$x] . '",';
		} ?>];
		if ($input == 'code') {
			var a = code.indexOf($key);
		} else if ($input == 'desc') {
			var a = desc.indexOf($key);
		}
		if ($output == 'itemid') {
			$out = itemid[a];
		} else if ($output == 'code') {
			$out = code[a];
		} else if ($output == 'desc') {
			$out = desc[a];
		}
		return $out;
	}

	function showCodeDesc() {
		var $replace_code = document.getElementById('tags6').value;
		var $replace_desc = document.getElementById('tags7').value;
		if ($replace_code != '') {
			var $replace_itemid = itemSearch('code', 'itemid', $replace_code);
			document.getElementById('tags7').value = itemSearch('code', 'desc', $replace_code);
		} else if ($replace_desc != '') {
			var $replace_itemid = itemSearch('desc', 'itemid', $replace_desc);
			document.getElementById('tags6').value = itemSearch('desc', 'code', $replace_desc);
		}
		document.getElementById('replace_itemid').value = $replace_itemid;
	}

	function getReplacement() {
		var $replace_itemid = '';
		var availableTags8 = new Array();
		var replacesn = document.getElementById('tags8').value;
		var $replace_code = document.getElementById('tags6').value;
		var $replace_desc = document.getElementById('tags7').value;
		if ($replace_code != '') {
			var $replace_itemid = itemSearch('code', 'itemid', $replace_code);
			document.getElementById('tags7').value = itemSearch('code', 'desc', $replace_code);
		} else if ($replace_desc != '') {
			var $replace_itemid = itemSearch('desc', 'itemid', $replace_desc);
			document.getElementById('tags6').value = itemSearch('desc', 'code', $replace_desc);
		}
		document.getElementById('replace_itemid').value = $replace_itemid;
		if (replacesn == '') {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function () {
				if (this.readyState == 4 && this.status == 200) {
					var $listarr = this.responseText.split(",");
					for ($i = 0; $i < ($listarr.length); $i++) {
						availableTags8.push($listarr[$i]);
					}
					$("#tags8").autocomplete({ source: availableTags8 });
				}
			}
			xhttp.open("GET", 'index.php?components=bill2&action=get_replacementsn&replace_itemid=' + $replace_itemid, true);
			xhttp.send();
		}
	}

	function getPriceDiff() {
		var $return_itemid = $replace_itemid = '';
		var $qty = parseInt(document.getElementById('qty1').value);
		var $return_code = document.getElementById('tags1').value;
		var $return_desc = document.getElementById('tags2').value;
		var $replace_code = document.getElementById('tags6').value;
		var $replace_desc = document.getElementById('tags7').value;
		var $replace_desc = document.getElementById('tags7').value;

		if ($return_code != '') {
			var $return_itemid = itemSearch('code', 'itemid', $return_code);
		} else if ($return_desc != '') {
			var $return_itemid = itemSearch('desc', 'itemid', $return_desc);
		}
		if ($replace_code != '') {
			var $replace_itemid = itemSearch('code', 'itemid', $replace_code);
		} else if ($replace_desc != '') {
			var $replace_itemid = itemSearch('desc', 'itemid', $replace_desc);
		}

		if (isNaN($qty)) {
			window.alert('Please Enter a Valid Quantity');
		} else {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function () {
				if (this.readyState == 4 && this.status == 200) {
					var returntext = this.responseText;
					document.getElementById("price_diff").innerHTML = returntext * $qty;
				}
			};
			xhttp.open("GET", 'index.php?components=bill2&action=get_pricediff&return_itemid=' + $return_itemid + '&replace_itemid=' + $replace_itemid, true);
			xhttp.send();
		}
	}

	function selectCust() {
		var custid_arr = [<?php for ($x = 0; $x < sizeof($cust_id); $x++) {
			print '"' . $cust_id[$x] . '",';
		} ?>];
		var custname_arr = [<?php for ($x = 0; $x < sizeof($cust_name); $x++) {
			print '"' . $cust_name[$x] . '",';
		} ?>];
		var custname = document.getElementById('tags3').value;
		var gps_x = document.getElementById('gps_x').value;
		var gps_y = document.getElementById('gps_y').value;
		if (custname != '') {
			var a = custname_arr.indexOf(custname);
			window.location = 'index.php?components=bill2&action=new_return&cust=' + custid_arr[a] + '&gps_x=' + gps_x + '&gps_y=' + gps_y;
		}
	}

	function getReturn() {
		var itemid = [<?php for ($x = 0; $x < sizeof($id); $x++) {
			print '"' . $id[$x] . '",';
		} ?>];
		var code = [<?php for ($x = 0; $x < sizeof($code); $x++) {
			print '"' . $code[$x] . '",';
		} ?>];
		var description = [<?php for ($x = 0; $x < sizeof($description); $x++) {
			print '"' . $description[$x] . '",';
		} ?>];
		var qty = [<?php for ($x = 0; $x < sizeof($qty); $x++) {
			print '"' . $qty[$x] . '",';
		} ?>];
		var ttitm = [<?php for ($x = 0; $x < sizeof($tt_item); $x++) {
			print '"' . $tt_item[$x] . '",';
		} ?>];
		var ttqty = [<?php for ($x = 0; $x < sizeof($tt_qty); $x++) {
			print '"' . $tt_qty[$x] . '",';
		} ?>];
		var unic = [<?php for ($x = 0; $x < sizeof($unic); $x++) {
			print '"' . $unic[$x] . '",';
		} ?>];
		var itemcode = document.getElementById('tags1').value;
		var itemdesc = document.getElementById('tags2').value;
		var itm_tmp = document.getElementById('itm_tmp').value;
		var invoice_no = document.getElementById('id').value;
		var invoice_cust = document.getElementById('cust').value;
		var unic_list_size = document.getElementById('unic_list_size').value;
		if (itemcode != '') {
			var a = code.indexOf(itemcode);
			var b = ttitm.indexOf(itemcode);
			document.getElementById('return_itemid').value = itemid[a];
			document.getElementById('tags2').value = description[a];
			if (document.getElementById('tags6').value == '') document.getElementById('tags6').value = itemcode;
			document.getElementById('av_qty').innerHTML = qty[a];
			document.getElementById('tt_qty').innerHTML = '';
			if (b > -1) {
				document.getElementById('av_qty').innerHTML = 'Old Stock - ' + qty[a];
				document.getElementById('tt_qty').innerHTML = 'New Stock - ' + ttqty[b];
			}
			if ((unic[a] == 1) && (itemcode != itm_tmp)) {
				document.getElementById('addtoreturn').innerHTML = '';
				window.location = 'index.php?components=bill2&action=item_return&id=' + invoice_no + '&cust=' + invoice_cust + '&itemid=' + itemid[a] + '&unic=yes';
			}
			if ((unic[a] == 0) && (unic_list_size > 0)) {
				document.getElementById('addtoreturn').innerHTML = '';
				window.location = 'index.php?components=bill2&action=item_return&id=' + invoice_no + '&cust=' + invoice_cust + '&itemid=' + itemid[a] + '&unic=no';
			}
		} else if (itemdesc != '') {
			var a = description.indexOf(itemdesc);
			document.getElementById('return_itemid').value = itemid[a];
			document.getElementById('tags1').value = code[a];
			if (document.getElementById('tags6').value == '') document.getElementById('tags6').value = code[a];
			document.getElementById('av_qty').innerHTML = qty[a];
			document.getElementById('tt_qty').innerHTML = '';
			if (b > -1) {
				document.getElementById('av_qty').innerHTML = 'Old Stock - ' + qty[a];
				document.getElementById('tt_qty').innerHTML = 'New Stock - ' + ttqty[b];
			}
			if ((unic[a] == 1) && (itemcode != itm_tmp)) {
				document.getElementById('addtoreturn').innerHTML = '';
				window.location = 'index.php?components=bill2&action=item_return&id=' + invoice_no + '&cust=' + invoice_cust + '&itemid=' + itemid[a] + '&unic=yes';
			}
			if ((unic[a] == 0) && (unic_list_size > 0)) {
				document.getElementById('addtoreturn').innerHTML = '';
				window.location = 'index.php?components=bill2&action=item_return&id=' + invoice_no + '&cust=' + invoice_cust + '&itemid=' + itemid[a] + '&unic=no';
			}
		}
	}


	function finalize() {
		document.getElementById('finalize').innerHTML = '';
		window.location = 'index.php?components=bill2&action=finalize_return&id=<?php if (isset($_GET['id']))
			print $_GET['id']; ?>'
	}

	window.onload = function () {
		document.getElementById("tags1").focus();
	};
</script>
<style type="text/css">
	select.selected {
		color: gray;
	}
</style>
<?php
$unic_item_select = false;
if (isset($_REQUEST['id']))
	$id = $_REQUEST['id'];
else
	$id = 0;
if (isset($_GET['unic'])) {
	if ($_GET['unic'] == 'yes')
		$unic_item_select = true;
}
;
?>

<table align="center">
	<tr>
		<td valign="top">
			<form action="index.php?components=bill2&action=apend_return" onsubmit="return validateReturn()"
				method="post">
				<input type="hidden" name="id" id="id" value="<?php print $id; ?>" />
				<input type="hidden" id="itm_tmp" value="<?php if ($unic_item_code != '')
					print $unic_item_code; ?>" />
				<input type="hidden" id="unic_list_size" value="<?php print sizeof($unic_item_list); ?>" />
				<input type="hidden" id="qty" name="qty" value="" />
				<input type="hidden" id="gps_x" name="gps_x" value="0" />
				<input type="hidden" id="gps_y" name="gps_y" value="0" />
				<?php if ($is_unic_item == 0) { ?>
					<input type="hidden" name="unic_item1" id="tags4" value="0" />
					<input type="hidden" name="unic_item2" id="tags8" value="0" />
				<?php } ?>
				<div
					style="margin:0 auto; background-color:#EEEEEF; border-radius: 15px; padding-left:10px; padding-right:10px; height:50px; ">
					<table height="100%"
						style="color:#0158C2; font-family:Calibri; font-size:16pt; vertical-align:middle;">
						<tr>
							<td><strong>Item Return</strong></td>
						</tr>
					</table>
				</div>
				<table align="center" bgcolor="#E5E5E5" style=" border-radius: 15px;">
					<tr>
						<td colspan="5"><?php
						if (isset($_REQUEST['message'])) {
							if ($_REQUEST['re'] == 'success')
								$color = 'green';
							else
								$color = '#DD3333';
							print '<script type="text/javascript">document.getElementById("notifications").innerHTML=' . "'" . '<span style="color:' . $color . '; font-weight:bold;font-size:12pt;">' . $_REQUEST['message'] . '</span>' . "'" . ';</script>';
						}
						?><br /></td>
					</tr>
					<tr>
						<td width="50px"></td>
						<td style="font-size:12pt">Customer</td>
						<td colspan="2">
							<?php
							if (isset($_GET['cust'])) {
								$cid = array_search($_GET['cust'], $cust_id);
								print '<span style="font-size:12pt">' . $cust_name[$cid] . '</span>';
								print '<input type="hidden" name="cust" id="cust" value="' . $_GET['cust'] . '" />';
							} else {
								print '<input type="text" name="cust" id="tags3" />';
							} ?>
						</td>
						<td width="50px"></td>
					</tr>
					<tr>
						<td width="50px"></td>
						<td style="font-size:12pt"></td>
						<td colspan="2" style="font-size:12pt; color:maroon">
							<?php
							if (isset($_GET['cust'])) {
								print 'NIC &nbsp;: ' . $cust_nic[$cid] . '<br />';
								print 'Mob : ' . $cust_mobile[$cid];
							} ?>
						</td>
						<td width="50px"></td>
					</tr>
					<?php if (!isset($_REQUEST['cust'])) { ?>
						<tr>
							<td width="50px"></td>
							<td style="font-size:12pt"></td>
							<td colspan="2">
								<input type="button" value="Submit" style="width:100px; height:50px;"
									onclick="selectCust()" />
								<br /><br />
							</td>
							<td width="50px"></td>
						</tr>
					<?php } else { ?>
						<tr>
							<td width="50px"></td>
							<td style="font-size:12pt">Return Item Code</td>
							<td colspan="2"><input type="text" name="code" id="tags1"
									value="<?php if ($unic_item_code != '')
										print $unic_item_code; ?>"
									onclick="this.value=''" /></td>
							<td width="50px"></td>
						</tr>
						<tr>
							<td width="50px"></td>
							<td style="font-size:12pt">Return Item Description</td>
							<td colspan="2"><input type="text" name="description" id="tags2" /></td>
							<td width="50px"></td>
						</tr>
						<tr>
							<td></td>
							<td style="font-size:12pt">Quantity</td>
							<td><input type="number" name="qty1" id="qty1" onfocus="getReturn()" style="width:50px" <?php if ($unic_item_select) { ?> value="1" disabled="disabled" <?php } ?> /></td>
							<td>
								<div style="font-size:10pt;" id="av_qty" align="right"></div>
								<div style="font-size:10pt; color:#CC0000" id="tt_qty" align="right"></div>
							</td>
							<td></td>
						</tr>
						<?php if ($unic_item_select) {
							print '	<tr><td></td><td style="font-size:12pt">Returned SN</td><td>';
							print '<input type="text" name="unic_item1" id="tags4" onfocus="getReturn()" />';
							print '</td><td></td></tr>';
						} ?>
						<tr>
							<td colspan="5">
								<hr />
							</td>
						</tr>
						<tr>
							<td width="50px"></td>
							<td style="font-size:12pt">Replacement Item Code</td>
							<td colspan="2"><input type="text" name="rplace_code" id="tags6"
									value="<?php if ($unic_item_code != '')
										print $unic_item_code; ?>"
									onclick="this.value=''" /></td>
							<td width="50px"></td>
						</tr>
						<tr>
							<td width="50px"></td>
							<td style="font-size:12pt">Replacement Item Description</td>
							<td colspan="2"><input type="text" name="rplace_description" id="tags7" /></td>
							<td width="50px"></td>
						</tr>
						<?php if ($unic_item_select) {
							print '	<tr><td></td><td style="font-size:12pt">Replacement SN</td><td>';
							print '<input type="text" name="unic_item2" id="tags8" onkeyup="getReplacement()" />';
							print '</td><td></td></tr>';

						} ?>
						<tr>
							<td colspan="5">
								<hr />
							</td>
						</tr>
						<?php if (!isQuickBooksActive(1)) { ?>
							<tr>
								<td width="50px"></td>
								<td style="font-size:12pt">Extra Payment</td>
								<td colspan="2"><input type="text" name="extra_pay" style="width:50px"
										onfocus="showCodeDesc()" /><input type="button" value="Get Cost Difference"
										onclick="getPriceDiff()" /></td>
								<td width="50px">
									<div id="price_diff" style="font-size:12pt; color:red"></div>
								</td>
							</tr>
						<?php }else{ ?>
							<input type="text" name="extra_pay" style="width:50px; display:none;" onfocus="showCodeDesc()" />
						<?php } ?>
						<tr>
							<td colspan="2" align="right"><br />
								<div id="addtoreturn">
									<?php if (sizeof($rt_id) < 16)
										print '<input type="submit" value="Add to Return" style="width:100px; height:30px" onclick="showCodeDesc()" />'; ?><br /><br /><br /><br />
								</div>
								<?php if (sizeof($rt_id) > 0) { ?>
								</td>
								<td colspan="3" align="left">
									<div id="finalize"><input type="Button" value="Finalize" style="width:100px; height:70px"
											onclick="finalize()" /></div>
								<?php } ?>
								<br />
								<br />
							</td>
						</tr>
						<tr>
							<td colspan="5" align="center" style="font-size:9pt; color:gray"><i>The Extra Payment Need to Be
									Collected As the Same Time of Issuing this Invoice</i></td>
						</tr>
					<?php } ?>
				</table>
				<input type="hidden" name="return_itemid" id="return_itemid" />
				<input type="hidden" name="replace_itemid" id="replace_itemid" />
			</form>
			<br />
		</td>
		<td width="50px"></td>
		<td valign="top">
			<!-- ------------------Item List----------------------- -->
			<div style="background-color:#D1D8F1; border-radius: 5px; padding-left:20px; padding-right:20px; height:500px; width:400px"
				align="left">
				<br />
				<?php if (isset($_REQUEST['id'])) { ?>
					<span
						style="font-weight:bold; font-size:12pt; border-radius:5px;  background-color:#E5E5EA; padding-left:15px; padding-right:15px">Return
						Invoice No: &nbsp;&nbsp;&nbsp;<?php print str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?></span>
					<br /><br /><br />
					<table align="center" bgcolor="#E5E5E5" style="border-radius:5px;">
						<?php
						for ($i = 0; $i < sizeof($rt_id); $i++) {
							print '<tr style="font-size:12pt"><td width="30px" style="color:blue"><strong>' . ($i + 1) . '</strong></td><td>' . $rt_itmdesc[$i] . '</td><td width="50px"></td><td align="right"><input style="width:50px; type="text" id="billitemid' . $rt_id[$i] . '" value="' . $rt_qty[$i] . '" />
			<input type="Button" value="Remove"  onclick="removeReturn(' . $rt_id[$i] . ',\'bill2\')" style="background-color:maroon; color:white"/>
			</td></tr>';
						} ?>
					</table>
				<?php } else { ?>
					<form id="searchinv" action="index.php?components=bill2&action=search_return" method="post">
						<table height="100%">
							<tr>
								<td style="font-size:12pt;"><input type="text" style="width:200px" name="search1"
										id="search1" placeholder="Search Return" /><input type="Submit" value="Search" />
								</td>
							</tr>
						</table>
					</form>
				<?php } ?>
			</div>
		</td>
	</tr>
</table>
<?php
if (isset($unic_item_code))
	if ($unic_item_code != '') {
		?>
		<script type="text/javascript">
			getReturn();
		</script>
		<?php
	}
include_once 'template/footer.php';
?>