	<form action="#" onsubmit="return validateCust()" method="post" >
	<input type="hidden" name="cust_id" value="<?php print $cu_id1; ?>" />
	<table align="center" bgcolor="#E5E5E5" style="font-size:11pt; font-family:Calibri">
	<tr><td colspan="4" align="center"><table width="100%" >
		<td>
		<?php if($_GET['action2']=='finish_bill' || $_GET['action2']=='warranty_print'){ ?>
		<input type="button" value="Back" style="width:100px; height:30px" onclick="window.location = 'index.php?components=billing&action=<?php print $_GET['action2']; ?>&id=<?php print $_GET['id2']; ?>'" />
		<?php }else if($_GET['action2']=='unvisited'){ ?>
		<input type="button" value="Back" style="width:100px; height:30px" onclick="window.location = '<?php print $back1; ?>'" />
		<?php } ?>
		</td><td style="font-size:16pt; color:navy; font-weight:bold">Customer Detail</td>
	</table></td></tr>
	<tr><td colspan="4"><br /></td></tr>
	<tr><td width="50px"></td><td>Shop Name</td><td><input type="text" name="shop_name" id="shop_name" value="<?php print $cu_name1; ?>" /></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>NIC</td><td><input type="text" name="nic" id="nic" value="<?php print $cu_nic1; ?>"/></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Mobile</td><td><?php print '<a href="tel:'.$cu_mobile1.'">'.$cu_mobile1.'</a>'; ?></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Credit Limit</td><td><input type="number" name="cr_limit" id="cr_limit" value="<?php print $cu_crlimit1; ?>"/></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Associated Shop</td><td>
		<select id="store" name="cu_store">
		<?php for($i=0;$i<sizeof($st_id);$i++){
			if($cu_store==$st_id[$i]) $select='selected="selected"'; else $select='';
			print '<option value="'.$st_id[$i].'" '.$select.'>'.$st_name[$i].'</option>';
		}?>
		</select>
	</td></tr>
	<?php if($systemid==1){ ?>
	<tr><td width="50px"></td><td>Associated <a title="Associated Salesman" href="#">SM</a></td><td>
		<select id="salesref" name="salesref">
		<?php for($i=0;$i<sizeof($sm_id);$i++){
			if($cu_sa==$sm_id[$i]) $select='selected="selected"'; else $select='';
			print '<option value="'.$sm_id[$i].'" '.$select.'>'.ucfirst($sm_name[$i]).'</option>';
		}?>
		</select>
	</td></tr>
	<?php }else print '<input type="hidden" id="salesman" name="salesman" value="1" />'; ?>
	<tr><td width="50px"></td><td colspan="2"><hr /></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Customer Name</td><td><input type="text" name="customer" id="customer" value="<?php print $cu_custname1; ?>"/></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Shop Address</td><td><textarea name="shop_address" id="shop_address" style="width:97%"><?php print $cu_shop_add1; ?></textarea></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Shop Tel</td><td><?php print '<a href="tel:'.$cu_shop_tel1.'">'.$cu_shop_tel1.'</a>'; ?></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Home Address</td><td><textarea name="home_address" id="home_address" style="width:97%"><?php print $cu_home_add1; ?></textarea></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Home Tel</td><td><?php print '<a href="tel:'.$cu_home_tel1.'">'.$cu_home_tel1.'</a>'; ?></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>SMS Notifications</td><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="sms" id="sms" <?php if($cu_sms==1) print 'checked="checked"'; ?> /></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Location</td><td align="center"><?php if($cu_gps_x!=0) print '<a href="https://maps.google.com/?q=' . $cu_gps_x . ',' . $cu_gps_y . '" target="_blank"><img src="images/map.png" style="width:25px" /></a>'; else print '<i>No GPS Location</i>'; ?></td><td width="50px"></td></tr>
	<tr><td colspan="4" align="center"><br />  
	<br /><br /></td></tr>
	</table>
	</form>
