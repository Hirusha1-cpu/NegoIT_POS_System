<?php
                include_once  'template/header.php';
?>
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
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<form action="index.php" method="get" onsubmit="return validateForm()" >
	<input type="hidden" name="components" value="<?php print $components; ?>" />
	<input type="hidden" name="action" value="sales_summary_detail" />
	<input type="hidden" name="from_date" value="<?php print $_GET['from_date']; ?>" />
	<input type="hidden" name="to_date" value="<?php print $_GET['to_date']; ?>" />
	<table align="center"><tr><td>
		<div style="background-color:#DFDFDF; border-radius:10px; font-family:Calibri">
			<table align="center" height="100%" cellspacing="0" style="font-size:10pt">
			<tr><td width="30px"></td>
			<td align="right"><strong>Date : </strong></td><td>
			<input type="date" id="date" name="date" style="width:130px" value="<?php print $date; ?>" />
			<td width="50px"></td>
			<td align="right"><strong>Category : </strong></td><td>
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
			<td width="50px"></td>
			<td align="right"><strong>Store : </strong></td><td>
			<select name="store" id="store">
				<option value="all" >-ALL-</option>
				<?php 
				$sname='ALL';
				for($i=0;$i<sizeof($st_id);$i++){
					if($store==$st_id[$i]){ $select='selected="selected"'; $sname=$st_name[$i]; }else{ $select=''; }
					print '<option value="'.$st_id[$i].'" '.$select.'>'.$st_name[$i].'</option>';
				} ?>
			</select>
			</td><td><div id="div_submit"><input type="submit" value="GET" style="width:50px; height:40px" /></div></td>
			<td width="60px"></td><td>
			<div id="div_back"><input type="button" value="Back" style="width:70px;" onclick="backPage()" /></div>
			</td><td width="10px"></td></tr>
			</table>
		</div>
	</td></tr></table>
</form>

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Sales Summary Detailed Report</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px">Date</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print $date; ?></td></tr>
		<tr><td width="100px" style="background-color:#C0C0C0; padding-left:10px">Category</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print ucfirst($cname); ?></td></tr>
		<tr><td width="100px" style="background-color:#C0C0C0; padding-left:10px">Store</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print ucfirst($sname); ?></td></tr>
	</table><br />
</div>

<div id="print">
	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:12pt; font-family:Calibri">
	<tr><td colspan="11" style="border:0; background-color:black; color:white; font-weight:bold"></td></tr>
	<tr bgcolor="#CCCCCC"><th>Item</th><th>QTY</th><th width="100px">Total Sale</th></tr>
	<?php
		for($j=0;$j<sizeof($sumd_desc);$j++){
			print '<tr><td style="padding-left:20px; padding-right:20px; color:blue;" >'.$sumd_desc[$j].'</td><td align="right" style="padding-left:40px; padding-right:20px;">'.number_format($sumd_qty[$j]).'</td><td align="right" style="padding-left:40px; padding-right:20px;">'.number_format($sumd_total[$j]).'</td></tr>';
		}
			print '<tr bgcolor="#CCCCCC" style="font-weight:bold"><td style="padding-left:20px; padding-right:20px;" >TOTAL</td><td align="right" style="padding-left:40px; padding-right:20px;">'.number_format(array_sum($sumd_qty)).'</td><td align="right" style="padding-left:40px; padding-right:20px;">'.number_format(array_sum($sumd_total)).'</td></tr>';
	?>
	</table>
</div>	
<table align="center"><tr><td align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br />
	</span></a>
</div>
</td>
</tr></table>
<?php
                include_once  'template/footer.php';
?>