<?php
                include_once  'template/header.php';
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
<!-- ------------------------------------------------------------------------------------------------------ -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:25px" /></div>

<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
	}
?></td></tr></table>
<form action="index.php?components=portalsup&action=monthly_return" method="post">
	<table align="center" border="0" cellspacing="0" style="font-size:12pt; font-family:Calibri; border-radius: 15px; padding-left:20px; padding-right:20px; background-color:#F0F0F0">
	<tr ><td>From Date</td><td><input type="date" id="from_date" name="from_date" <?php print 'value="'.$from_date.'"'; ?> /></td><td></td>
	<td>To Date</td><td><input type="date" id="to_date" name="to_date" <?php print 'value="'.$to_date.'"'; ?> /></td><td rowspan="2"><input type="submit" value="Get" style="width:60px; height:50px; font-weight:bold; font-size:14pt" /></td>
	</tr>
	</table>
</form>

	<br />
	<table align="center" style="font-size:12pt; font-family:Calibri;">
	<tr style="background-color:#467898; color:white;"><th>Item</th>
	<?php for($i=0;$i<sizeof($month_list);$i++){
		print '<th width="100px">Sale<br />'.$month_list[$i].'</th>';
	} ?>
	<th>All Store<br>Inventory Qty</th></tr>
	<tr>
	<?php for($i=0;$i<sizeof($item_id);$i++){
		print '<tr><td bgcolor="#EEEEEE" class="shipmentTB3">'.$item_name[$i].'</td>';
			for($j=0;$j<sizeof($month_list);$j++){
				print '<td align="right" class="shipmentTB2" bgcolor="#DDDDEE">'.$quantity[$month_list[$j]][$item_id[$i]].'</td>';
			}
		print '<td bgcolor="#EEEEEE" align="right" class="shipmentTB2" title="';
		for($j=0;$j<sizeof($store_id2);$j++){
			print $store_name2[$j].' - '.$inv_store2_qty[$i][$store_id2[$j]].'&#xA;';
		}
		print '">'.$inv_all_qty[$i].'</td>';
		print '</tr>';
	} ?>
	</table>
	

<?php
                include_once  'template/footer.php';
?>