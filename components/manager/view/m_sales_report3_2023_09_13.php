<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->

<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">

		<table align="center" height="100%" style="font-size:10pt">
		<?php if($selection=='store'){?>
		<tr bgcolor="#EEEEEE"><td><strong>Store </strong></td><td colspan="3"><?php print $store1; ?></td></tr>
		<?php }else{ ?>
		<tr bgcolor="#EEEEEE"><td><strong>Customer </strong></td><td colspan="3"><?php print $_GET['customer']; ?></td></tr>
		<?php } ?>
		<tr bgcolor="#EEEEEE"><td width="70px" ><strong>Category </strong></td><td colspan="3">
		<select name="category" id="category" disabled="disabled">
			<option value="all" >-ALL-</option>
			<?php for($i=0;$i<sizeof($cat_id);$i++){
				if($category==$cat_id[$i]) $select='selected="selected"'; else $select='';
				print '<option value="'.$cat_id[$i].'" '.$select.'>'.$cat_name[$i].'</option>';
			} ?>
		</select>
		</td></tr>
		<tr bgcolor="#EEEEEE"><td width="100px" ><strong>From Date </strong></td><td >
		<input type="date" id="datefrom" name="datefrom" style="width:130px" disabled="disabled" value="<?php print $fromdate; ?>" />
		</td><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;<strong>To Date </strong></td><td>
		<input type="date" id="dateto" name="dateto" style="width:130px" disabled="disabled" value="<?php print $todate; ?>" />
		</td></tr>
		</table>
	
	<br />
		<table align="center" height="100%" style="font-size:10pt">
		<tr><td colspan="11" style="border:0; background-color:black; color:white; font-weight:bold;"></td></tr>
		<tr bgcolor="#CCCCCC"><th width="200px">Item Description</th><th width="100px">Status of Selling<br />to the Customer</th></tr>
		<?php
		if(($customer_id!='')||($store1!='')){
			for($i=0;$i<sizeof($itm2_id);$i++){
				if($itm2_sold[$i]){ $img='<img src="images/action_check.gif" />'; $color1='green'; $color2='white'; }else{ $img=''; $color1='#EEEEEE'; $color2=''; }
				print '<tr bgcolor="'.$color1.'"><td style="padding-left:30px; color:'.$color2.';">'.$itm2_name[$i].'</td><td align="center">'.$img.'</td></tr>';
			}
		}
		?>
		</table>

  </div>
</div>
</div>
<hr>
<br />
<?php
                include_once  'template/m_footer.php';
?>
