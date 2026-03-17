<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
<script type="text/javascript">
  	function validateForm(){
  		if(validateDate(document.getElementById('date').value)){
  			document.getElementById('div_submit').innerHTML=document.getElementById('loading').innerHTML;
  			return true;
  		}else{
			return false;
  		}
  	}
  	
  	function backPage(){
  		document.getElementById('div_back').innerHTML=document.getElementById('loading').innerHTML;
  		window.location = "<?php print 'index.php?components='.$components.'&action=sales_summary&from_date='.$_GET['from_date'].'&to_date='.$_GET['to_date'].'&category='.$category.'&store='.$store.'#'.$date; ?>";
  	}
</script>

<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
  <div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<form action="index.php" method="get" onsubmit="return validateForm()" >
	<input type="hidden" name="components" value="<?php print $components; ?>" />
	<input type="hidden" name="action" value="sales_summary_detail" />
	<input type="hidden" name="from_date" value="<?php print $_GET['from_date']; ?>" />
	<input type="hidden" name="to_date" value="<?php print $_GET['to_date']; ?>" />
	<table ><tr><td>
		<div style="background-color:#F0F0F0; border-radius:10px; font-family:Calibri">
			<br />
			<table height="100%" cellspacing="0" style="font-size:10pt">
			<tr>
				<td></td>
				<td><strong>Date </strong></td><td>: 
				<input type="date" id="date" name="date" style="width:130px" value="<?php print $date; ?>" />
				<td></td>
			</tr>
			<tr>	
				<td></td>
				<td><strong>Category </strong></td><td>: 
				<select name="category" id="category">
					<option value="all" >-ALL-</option>
					<?php 
					$cname='ALL';
					for($i=0;$i<sizeof($cat_id);$i++){
						if($category==$cat_id[$i]){ $select='selected="selected"'; $cname=$cat_name[$i]; }else{ $select=''; }
						print '<option value="'.$cat_id[$i].'" '.$select.'>'.$cat_name[$i].'</option>';
					} ?>
				</select>
				</td><td>
			</tr>
			<tr>	
				<td></td>
				<td><strong>Store </strong></td><td>: 
				<select name="store" id="store">
					<option value="all" >-ALL-</option>
					<?php 
					$sname='ALL';
					for($i=0;$i<sizeof($st_id);$i++){
						if($store==$st_id[$i]){ $select='selected="selected"'; $sname=$st_name[$i]; }else{ $select=''; }
						print '<option value="'.$st_id[$i].'" '.$select.'>'.$st_name[$i].'</option>';
					} ?>
				</select>
				</td><td>
			<tr>	
				<td></td>
				<td align="center" colspan="2"><div id="div_submit"><input type="submit" value="GET" style="width:100px; height:40px" /></div></td>
				</td><td></td>
			</tr>
			</table>
			<br />
		</div>
	</td></tr></table>
</form>
</div>
  <div class="w3-col">
  <br />
  
	<table align="center" height="100%"  style="font-size:10pt; font-family:Calibri">
	<tr bgcolor="#BBBBBB"><th>ITEM</th><th>QTY</th><th>Total Sale</th></tr>
	<?php
		for($j=0;$j<sizeof($sumd_desc);$j++){
			if(($j%2)==0) $color='#EEEEEE'; else $color='#DDDDDD';
			print '<tr bgcolor="'.$color.'"><td style="padding-left:10px; padding-right:10px; color:blue;">'.$sumd_desc[$j].'</td><td align="right" style="padding-left:20px; padding-right:10px;">'.number_format($sumd_qty[$j]).'</td><td align="right" style="padding-left:20px; padding-right:10px;">'.number_format($sumd_total[$j]).'</td></tr>';
		}
			print '<tr bgcolor="#CCCCCC" style="font-weight:bold"><td style="padding-left:10px; padding-right:10px;" >TOTAL</td><td align="right" style="padding-left:20px; padding-right:10px;">'.number_format(array_sum($sumd_qty)).'</td><td align="right" style="padding-left:20px; padding-right:10px;">'.number_format(array_sum($sumd_total)).'</td></tr>';
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
