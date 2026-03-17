<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
<script type="text/javascript">
function setGivenDate(){
	$given_date=document.getElementById('given_date').value;
	window.location = 'index.php?components=hr&action=shop_staff&given_date='+$given_date;
}
</script>
<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
<!-- ------------------Item List----------------------- -->
	<table align="center" width="95%">
	<tr><td height="10px" class="shipmentTB4" style="font-family:Calibri" bgcolor="silver" align="center">
		<strong>As of Date</strong> <input type="date" id="given_date" onchange="setGivenDate()" value="<?php print $given_date; ?>" />
	</td></tr>
		<?php  for($k=0;$k<sizeof($store_id);$k++){ 
				$st_id=$store_id[$k];
		?>
	<tr><td valign="top">
		<table align="center" bgcolor="gray" style="border-radius: 10px; font-family:Calibri"><tr><td>
			<table border="0" cellspacing="0" bgcolor="white" style="border-radius: 10px;" align="center" border="0"  style="font-size:12pt; font-family:Calibri" width="200px">
				<tr><td height="10px"></td></tr>
				<tr bgcolor="#467898"><td class="shipmentTB4" align="center" style="color:white"><strong><?php print $store_name[$k]; ?></strong></td></tr>
				<tr><td height="2px"></td></tr>
				<?php 
				if (array_key_exists($st_id,$total_staff_id)){
					for($i=0;$i<sizeof($total_staff_id[$st_id]);$i++){
						$key=array_search($total_staff_id[$st_id][$i],$onleave_staff[$st_id]);
						if($key>-1) $color1='red'; else $color1='green';
						if(($i%2)==0) $color2='#DADADA'; else $color2='#F3F3F3';
						print '<tr bgcolor="'.$color2.'"><td class="shipmentTB4" style="color:'.$color1.'"><strong>'.$total_staff_name[$st_id][$i].'</strong></td></tr>';
						print '<tr><td height="10px"></td></tr>';
					} 
				}
				?>
			</table>
		</td></tr>
		</table>
	</td></tr>
	<tr><td height="10px"></td></tr>
		<?php } ?>
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
