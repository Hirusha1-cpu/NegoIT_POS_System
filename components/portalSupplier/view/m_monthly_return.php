<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
<form action="index.php?components=portalsup&action=monthly_return" method="post">
	<table align="center" border="0" width="90%" cellspacing="0" style="font-size:10pt; font-family:Calibri; border-radius: 15px; padding-left:20px; padding-right:20px; background-color:#F0F0F0">
	<tr ><td>From Date</td><td><input type="date" id="from_date" name="from_date" <?php print 'value="'.$from_date.'"'; ?> /></td><td rowspan="2"><input type="submit" value="Get" style="width:45px; height:50px; font-weight:bold; font-size:12pt" /></td></tr>
	<tr><td>To Date</td><td><input type="date" id="to_date" name="to_date" <?php print 'value="'.$to_date.'"'; ?> /></td></tr>
	</table>
</form>
<hr />
	<table align="center" style="font-size:9pt; font-family:Calibri;">
	<tr style="background-color:#467898; color:white;"><th>Item</th>
	<?php for($i=0;$i<sizeof($month_list);$i++){
		print '<th width="100px">Sale<br />'.$month_list[$i].'</th>';
	} ?>
	<th>All Store<br>Inventory Qty</th></tr>
	<tr>
	<?php for($i=0;$i<sizeof($item_id);$i++){
		print '<tr><td bgcolor="#EEEEEE" class="shipmentTB3">'.$item_name[$i].'</td>';
			for($j=0;$j<sizeof($month_list);$j++){
				print '<td align="right" class="shipmentTB3" bgcolor="#DDDDEE">'.$quantity[$month_list[$j]][$item_id[$i]].'</td>';
			}
		print '<td bgcolor="#EEEEEE" align="right" class="shipmentTB3" title="';
		for($j=0;$j<sizeof($store_id2);$j++){
			print $store_name2[$j].' - '.$inv_store2_qty[$i][$store_id2[$j]].'&#xA;';
		}
		print '">'.$inv_all_qty[$i].'</td>';
		print '</tr>';
	} ?>
	</table>

  </div>
</div>
</div>
<hr>
<br />
<?php
                include_once  'template/m_footer.php';
?>
