<?php
include_once 'template/m_header.php';
$components = $_GET['components'];
?>

</script>
</head>

<div class="w3-container" style="margin-top:75px">
	<?php
	if (isset($_REQUEST['message'])) {
		if ($_REQUEST['re'] == 'success')
			$color = 'green';
		else
			$color = 'red';
		print '<span style="color:' . $color . '; font-weight:bold;font-size:large;">' . $_REQUEST['message'] . '</span>';
	}
	?>

	<div class="w3-row">
		<div class="w3-col s3"></div>
		<div class="w3-col">
			<div style="background-color:#EEEEEF; border-radius: 5px; padding-left:10px; padding-right:10px">
				<form id="search_form" action="index.php?components=<?php print $_GET['components']; ?>&action=sales_byrep"
					method="post">
					<table height="100%" cellspacing="0" style="font-family:Calibri" bgcolor="#F0F0F0">
						<tr>
							<td width="10px"></td>
							<td>
								<strong>From </strong>&nbsp;
							</td>
							<td>
								: <input type="date" name="datefrom" style="width:130px" value="<?php print $fromdate; ?>" />
							</td>
							<td rowspan="3">
								<input type="submit" value="Get" style="width:50px; height:60px;" />
							</td>
						</tr>
						<tr>
							<td width="10px"></td>
							<td>
								<strong>To </strong>&nbsp;
							</td>
							<td>
								: <input type="date" name="dateto" style="width:130px" value="<?php print $todate; ?>" />
							</td>
						</tr>
						<tr>
							<td width="10px"></td>
							<td><strong>Category </strong>&nbsp;</td>
							<td> :
								<select name="category" id="category">
									<option value="">-SELECT-</option>
									<?php
									$cname = '';
									// Check if the category ID and name arrays are not empty
									if (!empty($cat_id) && !empty($cat_name)) {
										for ($i = 0; $i < sizeof($cat_id); $i++) {
											// Check if the current category ID matches the selected category
											if ($cat_id[$i] == $category) {
												$select = 'selected="selected"';
												$cname = $cat_name[$i];
											} else {
												$select = '';
											}
											// Print the option element
											print '<option value="' . htmlspecialchars($cat_id[$i]) . '" ' . $select . '>' . htmlspecialchars($cat_name[$i]) . '</option>';
										}
									} else {
										// Handle the case where there are no categories
										print '<option value="">No categories available</option>';
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td width="10px"></td>
							<td><strong>Store </strong>&nbsp;</td>
							<td> :
								<select name="store" id="store">
									<option value="">-ALL-</option>
									<?php
									$sname = '';
									// Check if the store ID array is not empty
									if (!empty($st_id) && !empty($st_name)) {
										for ($i = 0; $i < sizeof($st_id); $i++) {
											// Check if the current store ID matches the selected store
											if ($st_id[$i] == $store) {
												$select = 'selected="selected"';
												$sname = $st_name[$i];
											} else {
												$select = '';
											}
											// Print the option element
											print '<option value="' . htmlspecialchars($st_id[$i]) . '" ' . $select . '>' . htmlspecialchars($st_name[$i]) . '</option>';
										}
									} else {
										// Handle the case where there are no stores
										print '<option value="">No stores available</option>';
									}
									?>
								</select>
							</td>
						</tr>
						<?php if (isset($components) && ($components == 'manager' || $components == 'topmanager' || $components == 'supervisor')) { ?>
							<tr>
								<td width="10px"></td>
								<td><strong>Salesman </strong>&nbsp;</td>
								<td> :
									<select name="users[]" id="users" multiple style="width:200px; height:100px;">
										<?php
										if (!empty($sm_id) && !empty($sm_name)) {
											for ($i = 0; $i < sizeof($sm_id); $i++) {
												$selected = (isset($_REQUEST['users']) && in_array($sm_id[$i], $_REQUEST['users']))
													? 'selected="selected"'
													: '';
												print '<option value="' . htmlspecialchars($sm_id[$i]) . '" ' . $selected . '>'
													. htmlspecialchars($sm_name[$i]) . '</option>';
											}
										} else {
											print '<option value="">No users available</option>';
										}
										?>
									</select>
								</td>
							</tr>
						<?php } ?>
					</table>
				</form>
			</div>
			<div id="imageframe0"></div>
			<div id="print" style="overflow-x: auto;">
				<table align="center" height="100%" border="1" cellspacing="0" style="font-family:Calibri">
					<tr style="background-color:#467898; color:white;">
						<th width="60px">#</th>
						<th>Item</th>
						<?php
						for ($j = 0; $j < sizeof($rep_id); $j++) {
							print '<th class="tb2">' . ucfirst($rep_name[$j]) . '</th>';
						}
						?>
						<th class="tb2">Total</th>
						<th class="tb2">Available Stock</th>
					</tr>
					<?php
					$total_sub_qty = 0;
					for ($i = 0; $i < sizeof($itm_id); $i++) {
						$total_qty = 0;
						print '<tr>
									<td align="center" style="background-color:#EEEEEE">
										' . ($i + 1) . '
									</td>
									<td class="tb2" style="background-color:#DDDDFE">' . $itm_desc[$i] . '</td>';
						for ($j = 0; $j < sizeof($rep_id); $j++) {
							$total_qty += $itm_qty[$itm_id[$i]][$rep_id[$j]];
							$total_rep[$j] += $itm_qty[$itm_id[$i]][$rep_id[$j]];
							$total_sub_qty += $total_qty;
							print '<td align="right" class="tb2" style="background-color:#EEEEEE">' . number_format($itm_qty[$itm_id[$i]][$rep_id[$j]]) . '</td>';
						}
						print '<td align="right" class="tb2" style="background-color:#FDDDDD">' . number_format($total_qty) . '</td><td align="right" class="tb2" style="background-color:#FDDDDD">' . number_format($itm_stock[$i]) . '</td></tr>';
					}
					print '<tr>
								<td class="shipmentTB3" style="background-color:#DDDDFE" align="right" colspan="2"><strong>Total</strong>
								</td>';
					for ($j = 0; $j < sizeof($rep_id); $j++) {
						print '<td align="right" class="shipmentTB3" style="background-color:#DDDDDD"><strong>' . number_format($total_rep[$j]) . '</strong></td>';
					}
					print '<td align="right" class="shipmentTB3" style="background-color:#FDDDDD"><strong>' . number_format($total_sub_qty) . '</strong></td><td align="right" class="shipmentTB3" style="background-color:#FDDDDD"><strong>' . number_format(array_sum($itm_stock)) . '</strong></td>
							</tr>';
					?>
				</table>
			</div>
		</div>
	</div>
</div>

<hr>

<?php
include_once 'template/m_footer.php';
?>