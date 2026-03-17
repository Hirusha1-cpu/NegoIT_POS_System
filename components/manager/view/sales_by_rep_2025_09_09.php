<?php
	include_once  'template/header.php';
	$components=$_GET['components'];
?>
<!-- ------------------Item List----------------------- -->
<div style="background-color:#EEEEEF; border-radius: 5px; padding-left:10px; padding-right:10px">
	<form id="search_form" action="index.php?components=<?php print $_GET['components']; ?>&action=sales_byrep" method="post" >
		<table align="center" height="100%" cellspacing="0" style="font-family:Calibri" bgcolor="#F0F0F0" >
			<tr>
				<td width="50px"></td>
				<td colspan="8">
					<table>
						<tr><td><strong>From </strong>: &nbsp;<input type="date" name="datefrom" style="width:130px" value="<?php print $fromdate; ?>" />
						&nbsp;&nbsp;&nbsp;<strong>To </strong>: &nbsp;<input type="date" name="dateto" style="width:130px" value="<?php print $todate; ?>" />
						</td></tr>
					</table>
				</td>
				<td width="50px"></td>
				<td><strong>Category</strong></td>
				<td>
					<select name="category" id="category" >
						<option value="" >-SELECT-</option>
						<?php
						$cname='';
						for($i=0;$i<sizeof($cat_id);$i++){
							if($cat_id[$i]==$category){ $select='selected="selected"'; $cname=$cat_name[$i]; }else{ $select=''; }
							print '<option value="'.$cat_id[$i].'" '.$select.'>'.$cat_name[$i].'</option>';
						} ?>
					</select>
				</td>
				<td width="50px"></td>
				<td><strong>Store</strong></td>
				<td>
					<select name="store" id="store" >
						<option value="" >-ALL-</option>
						<?php
						$sname='';
						for($i=0;$i<sizeof($st_id);$i++){
							if($st_id[$i]==$store){ $select='selected="selected"'; $sname=$st_name[$i]; }else{ $select=''; }
							print '<option value="'.$st_id[$i].'" '.$select.'>'.$st_name[$i].'</option>';
						} ?>
					</select>
				</td>
				<td width="50"></td>
				<td><a onclick="document.getElementById('search_form').submit();" style="cursor:pointer"><img src="images/search.png" style="width:30px; vertical-align:middle" /></a></td>
				<td width="50"></td>
			</tr>
		</table>
	</form>
</div>

<hr />
<p style="text-align:center; color:teal; font-family:Calibri; font-size:10pt">All the Packed, Shipped and Delivered Invoices are counted for this report</p>

<div id="printheader" style="display:none" >
	<h2 align="center" style="color:navy"><?php print $inf_company; ?></h2>
	<h3 align="center" style="color:#333399; text-decoration:underline">Sales Quantity By Sales Rep</h3>
	<table>
		<tr>
			<td>
				<table style="font-size:12pt" border="1" cellspacing="0">
					<tr><td style="background-color:#C0C0C0; padding-left:10px">Date</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print 'From :'.$fromdate.' To :'.$todate; ?></td></tr>
					<tr><td style="background-color:#C0C0C0; padding-left:10px">Category</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print $cname; ?></td></tr>
					<tr><td style="background-color:#C0C0C0; padding-left:10px">Store</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print $sname; ?></td></tr>
				</table>
			</td>
		</tr>
	</table>
	<hr />
</div>

<div id="print">
	<table align="center" height="100%" border="1" cellspacing="0" style="font-family:Calibri">
		<tr style="background-color:#467898; color:white;">
			<th width="60px">#</th>
			<th>Item</th>
			<?php
			for($j=0;$j<sizeof($rep_id);$j++){
				print '<th class="shipmentTB3">'.ucfirst($rep_name[$j]).'</th>';
			}
			?>
			<th class="shipmentTB4">Total Sale QTY</th>
			<th class="shipmentTB4">Available Stock</th>
		</tr>
		<?php
			$total_sub_qty=0;
			for($i=0;$i<sizeof($itm_id);$i++){
				$total_qty=0;
				$itm_id_tmp='';
				print '<tr>
						<td align="center" style="background-color:#EEEEEE">
							'.($i+1).'
						</td>
						<td class="shipmentTB3" style="background-color:#DDDDFE">'.$itm_desc[$i].'</td>';
						for($j=0;$j<sizeof($rep_id);$j++){
							$total_qty+=$itm_qty[$itm_id[$i]][$rep_id[$j]];
							$total_rep[$j]+=$itm_qty[$itm_id[$i]][$rep_id[$j]];
							$total_sub_qty+=$total_qty;
							print '<td align="right" class="shipmentTB3" style="background-color:#EEEEEE">
									'.number_format($itm_qty[$itm_id[$i]][$rep_id[$j]]).'
								</td>';
						}
						print '<td align="right" class="shipmentTB3" style="background-color:#FDDDDD">'.number_format($total_qty).'</td>
							<td align="right" class="shipmentTB3" style="background-color:#FDDDDD">'.number_format($itm_stock[$i]).'</td>
				</tr>';
			}
			print '<tr>
					<td class="shipmentTB3" align="right" colspan="2" style="background-color:#DDDDFE"><strong>Total</strong></td>';
					for($j=0;$j<sizeof($rep_id);$j++){
						print '<td align="right" class="shipmentTB3" style="background-color:#DDDDDD">
							<strong>'.number_format($total_rep[$j]).'</strong>
						</td>';
					}
			print '<td align="right" class="shipmentTB3" style="background-color:#FDDDDD">
					<strong>'.number_format($total_sub_qty).'</strong>
				</td>
				<td align="right" class="shipmentTB3" style="background-color:#FDDDDD">
					<strong>'.number_format(array_sum($itm_stock)).'</strong>
				</td>
			</tr>';
		?>
	</table>
</div>
<br />
<table align="center">
	<tr>
		<td align="center">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
				<img src="images/print.png" alt="icon" /><br />
				Print
				</span></a>
			</div>
		</td>
	</tr>
</table>

<?php
    include_once  'template/footer.php';
?>