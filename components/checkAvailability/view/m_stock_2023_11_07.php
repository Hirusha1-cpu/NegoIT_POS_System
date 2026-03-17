<?php
                include_once  'template/m_header.php';
	$cat='';
	if(isset($_GET['cat_id'])) $cat=$_GET['cat_id'];
?>
<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">

	<table align="center" style="font-family:Calibri" bgcolor="#F0F0F0">
	<tr><td width="20px"></td><td><strong>Category</strong>&nbsp;&nbsp;&nbsp;</td><td>
		<select id="cat_id" onchange="window.location = 'index.php?components=availability&action=stock&cat_id='+this.value">
		<option value="" >-SELECT-</option>
		<?php for($i=0;$i<sizeof($category_id);$i++){
			if($cat==$category_id[$i]) $select='selected="selected"'; else $select='';
			print '<option value="'.$category_id[$i].'" '.$select.'>'.$category_name[$i].'</option>';
		} ?>
		</select>
	</td><td width="100px"></td></tr>
	<tr><td colspan="4" align="center" style="font-size:10pt; color:gray">&nbsp;&nbsp;&nbsp;Following shows all items for the selected category within the Group&nbsp;&nbsp;&nbsp;</td></tr>
	</table>
	<table align="center" style="font-family:Calibri; font-size:10pt"><tr><td>
		<table><tr><td width="18px" bgcolor="blue"></td><td>Items in Pending/Transfering Transfers</td></tr></table>
	</td><td>
		&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
	</td><td>
		<table><tr><td width="18px" bgcolor="yellow"></td><td>Items in Unlocked Invoices</td></tr></table>
	</td></tr></table>

</div>
  <div class="w3-col">

<hr />
	<table align="center" style="font-family:Calibri">
	<tr  style="background-color:#467898;color :white;" ><th>&nbsp;&nbsp;&nbsp;Description&nbsp;&nbsp;&nbsp;</th>
		<?php for($i=0;$i<sizeof($store_name);$i++){
			print '<th>&nbsp;&nbsp;&nbsp;'.$store_name[$i].'&nbsp;&nbsp;&nbsp;</th>';
		} ?>
	</tr>
	<?php for($i=0;$i<sizeof($itm_id);$i++){
			if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			print '<tr style="background-color:'.$color.'"><td>'.$itm_des[$itm_id[$i]].'</td>';
			for($j=0;$j<sizeof($store_id);$j++){
				if($mystore==$store_id[$j]) $color2='#FFAA55'; else $color2='';
					print '<td align="right" style="background-color:'.$color2.'">'.$itm_qty[$itm_id[$i]][$j].'&nbsp;&nbsp;</td>';
			}
			print '</tr>';
	} ?>
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
