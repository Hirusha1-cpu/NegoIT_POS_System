<script type="text/javascript">
	function setFilter($action){
		$type=document.getElementById('type').value;
		$store=document.getElementById('store').value;
		$category=document.getElementById('category').value;
		$tags=document.getElementById('tags').value;
		$tag_selection=document.getElementById('tag_selection').value;
		$qtymore0='n';
		if(($type==1)||($type==5)){
			if(document.getElementById('qtymore0').checked==true) $qtymore0='y';
		}
		window.location = 'index.php?components=inventory&action='+$action+'&category='+$category+'&store='+$store+'&type='+$type+'&tag_selection='+$tag_selection+'&tags='+$tags+'&qtymore0='+$qtymore0;
	}
	function nextTagAction(){
		$action=document.getElementById('action').value;
		setFilter($action);
	}
</script>
<?php
	$decimal=getDecimalPlaces(1);
	$selected_category='';
	if($type==4) $report_title='Rapair Item Inventory'; else $report_title='Inventory Report';
	if(isset($_COOKIE['report'])) $report_access=true; else  $report_access=false;
	$action=$_GET['action'];
?>
<input type="hidden" id="type" value="<?php print $type; ?>" />
<input type="hidden" id="action" value="<?php print $_GET['action']; ?>" />
<table align="center" border="0" style="font-family:Calibri; font-size:11pt" width="80%" bgcolor="#F5F5F5">
	<tr>
		<td align="center" style="font-weight:bold; color:navy;">
			<?php
				if($type==1) print 'PRODUCT INVENTORY';
				if($type==2) print 'SERVICE INVENTORY';
				if($type==3) print 'REPAIR INVENTORY';
				if($type==4) print 'REPAIR ITEM INVENTORY';
				if($type==5) print 'UNALLOCATED PRODUCT INVENTORY';
			?>
		</td>
		<?php
			if($type==4){
				print '<td width="70px" rowspan="2"><input style="width:100px; height:30px" type="button" value="Parts List"
					onclick="window.location = '."'index.php?components=inventory&action=repair_parts_list&category=".$_GET['
					category']."&store=".$_GET['store']." &type=4'".'" /></td>';
			}
		?>
	</tr>
	<tr>
		<td>
			<table width="100%">
				<tr>
					<?php if($type==5) print '<td width="18px" bgcolor="blue"></td><td>Items in Unlocked Invoices or Pending/Transfering Transfers</td>'; ?>
					<td></td>
					<?php if($type==1 || $type==5) print '<td width="18px" bgcolor="orange"></td><td>Quantity mismatch in unique items</td>'; ?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="font-weight:bold; color:navy;">
			<?php if($type!=4) include_once  'template/tag.php'; ?>
		</td>
	</tr>
</table>
<?php if($mismatch>0)
	print '<table align="center" style="font-family:Calibri; font-size:10pt">
			<tr>
				<td>Inventory Item Quantity Mismatch Count <span style="color:red; font-weight:bold;">'.$mismatch.'</span></td>
		</table>';
?>

<br />
<?php if($type==4){ ?>
	<form
		action="index.php?components=inventory&action=add_repair_inv&type=<?php print $_GET['type']; ?>&category=<?php print $_GET['category']; ?>&store=<?php print $_GET['store']; ?>"
		method="post">
		<table align="center" border="0" bgcolor="#EEEEEE" style="font-family:Calibri; font-size:11pt">
			<tr>
				<td width="50px"></td>
				<td>Add New Quantity</td>
				<td><input type="text" id="part" name="part" value="" placeholder="Repair Part" /><strong><input
							type="number" id="qty" name="qty" value="" style="width:50px"
							placeholder="Qty" /></strong><input type="submit" value="Add" </td> <td width="50px"></td>
			</tr>
		</table>
	</form>

	<br />

	<table align="center" border="0" style="font-family:Calibri; font-size:11pt">
		<tr bgcolor="#CCCCCC">
			<th>Description</th>
			<th>Drawer No</th>
			<th width="100px">Cost</th>
			<th width="100px">QTY</th>
			<th width="100px">Reorder<br>Level</th>
			<th width="100px">Reorder<br>Qty</th>
			<th>Total Cost</th>
			<th>Store</th>
			<td></td>
		</tr>
		<tr bgcolor="#CCCCCC">
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<td>
				<select id="store"
					onchange="window.location = 'index.php?components=inventory&action=show_all_item&type=4&category=1&store='+document.getElementById('store').value">
					<?php
						for($i=0;$i<sizeof($stores_id);$i++){
							if($stores_id[$i]==$_GET['store']){
								$select='selected="selected"';
								$selected_store=$stores_name[$i];
							}else $select='';
							print '<option value="'.$stores_id[$i].'" '.$select.'>'.$stores_name[$i].'</option>';
						}
					?>
				</select>
				</th>
			</td>
			<th>
		</tr>
		<?php
			$total_c_price=0;
			$qitem_list=$qqty_list='';
			for($i=0;$i<sizeof($id);$i++){
				$total_c_price+=$cost[$i]*$qty[$i];
				if($report_access || ($reorder_level[$i]==0)) $update_button='<input type="button" value="Update" onclick="updateRepairInv('.$id[$i].')" />'; else $update_button='';
				print '<tr bgcolor="#EEEEEE">
					<td>&nbsp;'.$description[$i].'&nbsp;</td>
					<td>&nbsp;'.$drawer[$i].'&nbsp;</td>
					<td align="center"><input type="number" id="cost'.$id[$i].'" value="'.$cost[$i].'"
							style="width:70px; text-align:right;" /></td>
					<td align="center"><input type="number" id="qty'.$id[$i].'" value="'.$qty[$i].'"
							style="width:50px; text-align:right;" /></td>
					<td align="center"><input type="number" id="relvl'.$id[$i].'" value="'.$reorder_level[$i].'"
							style="width:50px; text-align:right;" /></td>
					<td align="center"><input type="number" id="reqty'.$id[$i].'" value="'.$reorder_qty[$i].'"
							style="width:50px; text-align:right;" /></td>
					<td align="right">'.number_format($cost[$i]*$qty[$i]).'&nbsp;&nbsp;</td>
					<td align="center">'.$st_name[$i].'</td>
					<td>
						<div id="partaction|'.$id[$i].'">'.$update_button.'</div>
					</td>
				</tr>';
			}
			print '<tr bgcolor="#DDDDDD">
				<td align="center" colspan="6">
					<div id="inv_rtotal1"><strong>&nbsp;&nbsp;TOTAL COST OF ITEMS</strong></div>
				</td>
				<td align="right">
					<div id="inv_rtotal2"><strong>&nbsp;&nbsp;'.number_format($total_c_price).'&nbsp;&nbsp;</div></strong>
				</td>
				<td align="center"></td>
				<td></td>
			</tr>';
		?>
	</table>

	<div id="print" style="display:none">
		<table align="center" border="1" cellspacing="0" style="font-family:Calibri">
			<tr bgcolor="#CCCCCC">
				<th>Description</th>
				<th width="100px">Cost</th>
				<th width="100px">QTY</th>
				<th>Total Cost</th>
			</tr>
			<?php
				$total_c_price=0;
				for($i=0;$i<sizeof($id);$i++){
					$total_c_price+=$cost[$i]*$qty[$i];
					print '<tr bgcolor="#EEEEEE">
						<td>&nbsp;'.$description[$i].'&nbsp;</td>
						<td align="right">'.number_format($cost[$i]).'&nbsp;&nbsp;</td>
						<td align="right">'.number_format($qty[$i]).'&nbsp;&nbsp;</td>
						<td align="right">'.number_format($cost[$i]*$qty[$i]).'&nbsp;&nbsp;</td>
					</tr>';
				}
				print '<tr bgcolor="#DDDDDD">
					<td align="center" colspan="3"><strong>&nbsp;&nbsp;TOTAL COST OF ITEMS</strong></td>
					<td align="right"><strong>&nbsp;&nbsp;'.number_format($total_c_price).'&nbsp;&nbsp;</strong></td>
				</tr>';
			?>
		</table>
	</div>

<?php }else{ ?>
	<table align="center" border="0" style="font-family:Calibri">
		<tr bgcolor="#CCCCCC">
			<th>Item Category</th>
			<th>Item Code</th>
			<th>Description</th>
			<?php
				$qtymore0_check='';
				$qtymore0='n';
				if(isset($_GET['qtymore0'])){
					$qtymore0=$_GET['qtymore0'];
					if($qtymore0=='y') $qtymore0_check='checked="checked"';
				}
				if($type==2 || $type==3)
				print '<th>Default Price</th>
						<th width="70px">Cost</th>';
				else
				print '<th>Wholesale Price</th>
					<th>Retail Price</th>
					<th width="70px">Cost</th>
					<th>Drawer No</th>
					<th>Quantity<br>[<input type="checkbox" id="qtymore0" onchange="setFilter('."'$action'".')" '.$qtymore0_check.' />>0]
					</th>
					<th>Total Cost</th>';
				?>
			<th>Store</th>
		</tr>

		<tr bgcolor="#CCCCCC">
			<th>
				<select id="category" onchange="setFilter('<?php print $_GET['action']; ?>')">
					<option value="all">-ALL-</option>
					<?php
						for($i=0;$i<sizeof($category_id);$i++){
							if($category_id[$i]==$_GET['category']){
								$select='selected="selected"';
								$selected_category=$category_name[$i];
							}else{ $select=''; $selected_category='ALL';}
							print '<option value="'.$category_id[$i].'" '.$select.'>'.$category_name[$i].'</option>';
						}
					?>
				</select>
				<?php if(!($type==2 || $type==3)) print '</th><th></th><th></th><th></th><th>'; ?>
			</th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th>
				<select id="store" onchange="setFilter('<?php print $_GET['action']; ?>')">
					<option value="all-sub">ALL-Sub System</option>
					<?php
						for($i=0;$i<sizeof($stores_id);$i++){
							if($stores_id[$i]==$_GET['store']){
								$select='selected="selected"';
								$selected_store=$stores_name[$i];
							}else $select='';
							print '<option value="'.$stores_id[$i].'" '.$select.'>'.$stores_name[$i].'</option>';
						}
					?>
				</select>
			</th>
		</tr>

		<?php
			$total_c_price=0;
			for($i=0;$i<sizeof($id);$i++){
				if(($i%2)==0) $color7='#FAFAFA'; else $color7='#EEEEEE';
				if($type==3){ $link1='<a href="index.php?components=inventory&action=show_repair_map&item='.$id[$i].'" >'; $link2='</a>'; }else $link1=$link2='';
				if(($qtymore0=='n')||(($qtymore0=='y')&&($qty[$i]>0)&&(($type==1)||($type==5)))){
					print '<tr bgcolor="'.$color7.'" style="color:'.$color[$i].'"><td align="center">'.$cat_name[$i].'</td><td>&nbsp;'.$code[$i].'</td><td>&nbsp;'.$link1.$description[$i].$link2.'&nbsp;</td><td align="right">'.number_format($w_price[$i],$decimal).'&nbsp;&nbsp;</td>';
					if($type==2 || $type==3){
						print '<td align="right">'.number_format($cost[$i],$decimal).'&nbsp;&nbsp;</td><td align="center">'.$st_name[$i].'</td></tr>';
					}else{
						print '<td align="right">'.number_format($r_price[$i],$decimal).'&nbsp;&nbsp;</td><td align="right">'.number_format($cost[$i],$decimal).'&nbsp;&nbsp;</td><td align="center">'.$drawer[$i].'</td><td align="right">'.number_format($qty[$i]).'&nbsp;&nbsp;</td><td align="right">'.number_format(($cost[$i]*$qty[$i]),$decimal).'&nbsp;&nbsp;</td><td align="center">'.$st_name[$i].'</td></tr>';
						$total_c_price+=$cost[$i]*$qty[$i];
					}
				}
			}
			if(!($type==2 || $type==3))
			print '<tr bgcolor="#DDDDDD">
				<td align="center" colspan="8"><strong>&nbsp;&nbsp;TOTAL COST OF ITEMS</strong></td>
				<td align="right"><strong>&nbsp;&nbsp;'.number_format($total_c_price,$decimal).'&nbsp;&nbsp;</strong></td>
				<td align="center"></td>
			</tr>';
		?>
	</table>

	<div id="print" style="display:none">
		<table align="center" border="1" cellspacing="0" style="font-family:Calibri">
			<tr bgcolor="#CCCCCC">
				<th>&nbsp;&nbsp;Item Category&nbsp;&nbsp;</th>
				<th>&nbsp;&nbsp;Item Code&nbsp;&nbsp;</th>
				<th>&nbsp;&nbsp;Description&nbsp;&nbsp;</th>
				<?php if($type==2)
					print '<th>&nbsp;&nbsp;Default<br />Price&nbsp;&nbsp;</th>
							<th width="70px">&nbsp;&nbsp;Cost&nbsp;&nbsp;</th>';
					else
					print '<th>&nbsp;&nbsp;Wholesale<br />Price&nbsp;&nbsp;</th>
							<th>&nbsp;&nbsp;Retail<br />Price&nbsp;&nbsp;</th>
							<th width="70px">&nbsp;&nbsp;Cost&nbsp;&nbsp;</th>
							<th>&nbsp;&nbsp;Drawer<br />No&nbsp;&nbsp;</th>
							<th>&nbsp;&nbsp;Quantity&nbsp;&nbsp;</th>
							<th>&nbsp;&nbsp;Total Cost&nbsp;&nbsp;</th>';
				?>
				<th>Store</th>
			</tr>
			<?php
				for($i=0;$i<sizeof($id);$i++){
					print '<tr bgcolor="#EEEEEE" style="color:' .$color[$i].'">
					<td>&nbsp;&nbsp;'.$cat_name[$i].'&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;'.$code[$i].'&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;'.$description[$i].'&nbsp;&nbsp;</td>
					<td align="right">&nbsp;&nbsp;'.number_format($w_price[$i],$decimal).'&nbsp;&nbsp;</td>';
					if($type==2 || $type==3){
					print '<td align="right">&nbsp;&nbsp;'.number_format($cost[$i],$decimal).'&nbsp;&nbsp;</td>
					<td align="center">&nbsp;&nbsp;'.$st_name[$i].'&nbsp;&nbsp;</td>
					</tr>';
					}else{
						print '<td align="right">&nbsp;&nbsp;'.number_format($r_price[$i],$decimal).'&nbsp;&nbsp;</td>
								<td align="right">&nbsp;&nbsp;'.number_format($cost[$i],$decimal).'&nbsp;&nbsp;</td>
								<td align="center">'.$drawer[$i].'</td>
								<td align="right">&nbsp;&nbsp;'.number_format($qty[$i]).'&nbsp;&nbsp;</td>
								<td align="right">&nbsp;&nbsp;'.number_format($cost[$i]*$qty[$i]).'&nbsp;&nbsp;</td>
								<td align="center">&nbsp;&nbsp;'.$st_name[$i].'&nbsp;&nbsp;</td>
						</tr>';
					}
				}
				if(!($type==2 || $type==3))
				print '<tr bgcolor="#DDDDDD">
					<td align="center" colspan="7"><strong>&nbsp;&nbsp;TOTAL COST OF ITEMS</strong></td>
					<td align="right"><strong>&nbsp;&nbsp;'.number_format($total_c_price,$decimal).'&nbsp;&nbsp;</strong></td>
				</tr>';
			?>
		</table>
	</div>
<?php } ?>
<div id="printheader" style="display:none">
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline"><?php print $report_title; ?></h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr>
			<td width="100px" style="background-color:#C0C0C0; padding-left:10px">Store</td>
			<td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php if(isset($selected_store)) print $selected_store; ?></td>
		</tr>
		<tr>
			<td style="background-color:#C0C0C0; padding-left:10px">Category</td>
			<td style="background-color:#EEEEEE; padding-left:10px"><?php print $selected_category; ?></td>
		</tr>
	</table><br />
</div>
