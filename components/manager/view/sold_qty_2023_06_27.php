<?php
	include_once  'template/header.php';
?>
<script type="text/javascript">
	function setFilter(){
		var date=document.getElementById('date0').value;
		var store=document.getElementById('store0').value;
		var category=document.getElementById('category0').value;
		var components=document.getElementById('components').value;
		window.location = 'index.php?components='+components+'&action=sold_qty&date='+date'+&store='+store+'&category='+category;
	}

</script>
<form action="index.php" method="get" id="sold_form">
	<input type="hidden" id="components" name="components" value="<?php print $components; ?>" />
	<input type="hidden" name="action" value="sold_qty" />
	<table align="center" cellspacing="0" style="font-family:Calibri" bgcolor="#F0F0F0">
		<tr><td colspan="8" ><br /></td></tr>
		<tr>
			<td width="50px"></td>
			<td>Date</td>
			<td><input type="date" id="date0" name="date" value="<?php print $date; ?>" /></td>
			<td width="100px"></td>
			<td>Category</td>
			<td>
				<select id="category0" name="category" onchange="setFilter()" <?php if($components == "billing" || $components == "bill2") echo 'disabled' ?>>
					<option value="all" >--ALL--</option>
					<?php
						$catname='ALL';
						for($i=0;$i<sizeof($cat_id);$i++){
							if($cat_id[$i]==$category){ 
								$select='selected="selected"'; $catname=$cat_name[$i]; 
							}else{ 
								$select=''; 
							}
							print '<option value="'.$cat_id[$i].'" '.$select.'>'.$cat_name[$i].'</option>';
						}
					?>
				</select>
			</td>
			<td width="100px"></td>
			<td>Store</td>
			<td>
				<select id="store0" name="store" onchange="setFilter()" <?php if($components == "billing" || $components == "bill2") echo 'disabled' ?>>
					<option value="all" >--ALL--</option>
					<?php
						$stname='ALL Stores';
						for($i=0;$i<sizeof($st_id);$i++){
							if($st_id[$i]==$store){ 
								$select='selected="selected"'; $stname=ucfirst($st_name[$i]); 
							}else{ 
								$select=''; 
							}
							print '<option value="'.$st_id[$i].'" '.$select.'>'.ucfirst($st_name[$i]).'</option>';
						}
					?>
				</select>
			</td>
			<td>
				<a onclick="document.getElementById('sold_form').submit();" style="cursor:pointer">
					<img src="images/search.png" style="width:30px; vertical-align:middle" />
				</a>
			</td>
			<td width="50px"></td>
		</tr>
		<tr><td colspan="8" ><br /></td></tr>
	</table>
</form>
	
<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Daily Sold Quantity</h2>
	<table border="1" cellspacing="0" width="400px">
		<tr>
			<td style="background-color:#C0C0C0; padding-left:10px">Date</td>
			<td style="background-color:#EEEEEE; padding-left:10px"><?php print $date; ?></td>
		</tr>
		<tr>
			<td width="100px" style="background-color:#C0C0C0; padding-left:10px">Category</td>
			<td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print $catname; ?></td>
		</tr>
		<tr>
			<td width="100px" style="background-color:#C0C0C0; padding-left:10px">Store</td>
			<td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print $stname; ?></td>
		</tr>
	</table>
	<hr />
</div>

<br />
<div id="print">
	<table align="center" style="font-family:Calibri" border="1" cellspacing="0">
		<tr style="background-color:#467898;color :white;">
			<th>Item Category</th>
			<th>Item Code</th>
			<th>Item Description</th>
			<th>Sold Qty</th>
			<th>Store</th>
		</tr>
		<?php for($i=0;$i<sizeof($itm_code);$i++){
			print '<tr>
					<td>&nbsp;&nbsp;'.$itm_category[$i].'&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;'.$itm_code[$i].'&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;'.$itm_description[$i].'&nbsp;&nbsp;</td>
					<td align="right">&nbsp;&nbsp;'.number_format($sold_qty[$i]).'&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;'.$itm_store[$i].'&nbsp;&nbsp;</td>
				</tr>';
		} ?>
	</table>
</div>

<br />
<table align="center">
	<tr>
		<td align="center">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#">
					<span style="text-decoration:none; font-family:Arial; color:navy;">
						<img src="images/print.png" alt="icon" /><br />Print
					</span>
				</a>
			</div>
		</td>
	</tr>
</table>

<?php
    include_once  'template/footer.php';
?>