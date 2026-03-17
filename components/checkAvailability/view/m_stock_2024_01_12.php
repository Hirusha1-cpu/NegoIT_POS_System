<?php
    include_once  'template/m_header.php';
	$cat='';
	if(isset($_GET['cat_id'])) $cat=$_GET['cat_id'];
	$systemid = inf_systemid(1);
?>
<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
	<hr>
	<div class="w3-row">
		<div class="w3-col s3">
		</div>
		<div class="w3-col">

			<table align="center" style="font-family:Calibri" bgcolor="#F0F0F0">
				<tr>
					<td width="20px"></td>
					<td><strong>Category</strong>&nbsp;&nbsp;&nbsp;</td>
					<td>
						<select id="cat_id"
							onchange="window.location = 'index.php?components=availability&action=stock&cat_id='+this.value">
							<option value="">-SELECT-</option>
							<?php if(($systemid == 13)){ ?>
								<option value="all" <?php if((isset($_GET['cat_id'])) && ($_GET['cat_id'] == 'all')) print 'selected="selected"'; ?>>-ALL-</option>
							<?php } ?>
							<?php for($i=0;$i<sizeof($category_id);$i++){
								// Add a condition to skip category_id = 17 and systemid = 13
								if ($category_id[$i] == 17 && $systemid == 13 && $sub_system == 1) {
									continue; // Skip this iteration
								}
								if($cat==$category_id[$i]) $select='selected="selected"'; else $select='';
								print '<option value="'.$category_id[$i].'" '.$select.'>'.$category_name[$i].'</option>';
							} ?>
						</select>
					</td>
					<td width="100px"></td>
				</tr>
				<tr>
					<td colspan="4" align="center" style="font-size:10pt; color:gray">&nbsp;&nbsp;&nbsp;Following shows
						all items for the selected category within the Group&nbsp;&nbsp;&nbsp;</td>
				</tr>
			</table>
			<table align="center" style="font-family:Calibri; font-size:10pt">
				<tr>
					<td>
						<table>
							<tr>
								<td width="18px" bgcolor="blue"></td>
								<td>Items in Pending/Transfering Transfers</td>
							</tr>
						</table>
					</td>
					<td style="padding: 0px 5px;">
						|
					</td>
					<td>
						<table>
							<tr>
								<td width="18px" bgcolor="yellow"></td>
								<td>Items in Unlocked Invoices</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

		</div>
		<div class="w3-col">

			<hr />
			<table align="center" style="font-family:Calibri">
				<tr style="background-color:#467898;color :white;">
					<th width="30px" align="center"></th>
					<th width="60px" align="center">#</th>
					<?php if(($systemid == 13)){ ?>
						<?php if(isset($_GET['cat_id']) && ($_GET['cat_id'] == 'all')) print '<th style="padding: 0px 10px;" align="center">Category</td>'; ?>
					<?php } ?>
					<th style="padding: 0px 10px;">Description</th>
					<?php for($i=0;$i<sizeof($store_name);$i++){
						if($systemid == 13){
							if ($store_id[$i] != $mystore) {
								continue; // Skip header cells for other stores
							}
						}
						print '<th style="padding: 0px 10px;">'.$store_name[$i].'</th>';
					} ?>
				</tr>
				<?php
					$row_number = 1;
					for($i=0;$i<sizeof($itm_id);$i++){
						if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
						if($row_number % 2 == 0) $color1 = '#FAFAFA'; else $color1 = '#EEEEEE';
						if($systemid == 13){
							// Flag to check if any quantity is "-"
							$hasDash = false;
							for ($j = 0; $j < sizeof($store_id); $j++) {
								if ($mystore != $store_id[$j]) {
									continue; // Skip items that are not associated with the selected store
								}
								$quantity = $itm_qty[$itm_id[$i]][$j];
								if ($quantity == '-') {
									$hasDash = true; // Set the flag if any quantity is "-"
									break; // No need to check further if "-" is found
								}
							}
							// Skip the entire row if any quantity is "-"
							if ($hasDash) {
								continue;
							}
							print '<tr style="background-color:'.$color1.'">
									<td align="center"><input type="checkbox" /></td>
									<td align="center">'.sprintf('%02d',($row_number)).'</td>';
									if(isset($_GET['cat_id']) && ($_GET['cat_id'] == 'all'))
										print '<td style="padding: 0px 10px;" align="left">'.$itq_category_name[$itm_id[$i]].'</td>';
									print '<td style="padding: 0px 10px;" align="left">'.$itm_des[$itm_id[$i]].'</td>';
									for($j=0;$j<sizeof($store_id);$j++){
										if ($mystore!=$store_id[$j]) {
											continue; // Skip items that are not associated with the selected store
										}
										if($mystore==$store_id[$j]) $color2='#FFAA55'; else $color2='';
										print '<td align="right" class="shipmentTB3" style="background-color:'.$color2.'">'.$itm_qty[$itm_id[$i]][$j].'</td>';
									}
							print '</tr>';
							$row_number++;
						}else{
							print '<tr style="background-color:'.$color.'">
							<td align="center"><input type="checkbox" /></td>
							<td align="center">'.sprintf('%02d',($i+1)).'</td>';
							if(isset($_GET['cat_id']) && ($_GET['cat_id'] == 'all'))
								print '<td style="padding: 0px 10px;" align="left">'.$itq_category_name[$itm_id[$i]].'</td>';
							print '<td style="padding: 0px 10px;" align="left">'.$itm_des[$itm_id[$i]].'</td>';
							for($j=0;$j<sizeof($store_id);$j++){
								if($mystore==$store_id[$j]) $color2='#FFAA55'; else $color2='';
								print '<td align="right" class="shipmentTB3" style="background-color:'.$color2.'">'.$itm_qty[$itm_id[$i]][$j].'</td>';
							}
							print '</tr>';
						}
					}
				?>
			</table>
		</div>
	</div>
</div>
</div>
<hr>
<br />
<?php
    include_once  'template/m_footer.php';
?>
