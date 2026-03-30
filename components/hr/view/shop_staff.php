<?php
                include_once  'template/header.php';
?>
<script type="text/javascript">
function setGivenDate(){
	$given_date=document.getElementById('given_date').value;
	window.location = 'index.php?components=hr&action=shop_staff&given_date='+$given_date;
}
</script>

<!-- -------------------------------------------------------------------------------------------------------------------- -->

<table align="center" style="font-family:Calibri" cellspacing="0" >
<tr><td colspan="3" height="10px" class="shipmentTB4" bgcolor="silver" align="center" >
	<strong>As of Date</strong> <input type="date" id="given_date" onchange="setGivenDate()" value="<?php print $given_date; ?>" />
</td></tr>
<tr>
	<?php  for($k=0;$k<sizeof($store_id);$k++){ 
			$st_id=$store_id[$k];
			if(($k%3)==0) print '</tr><tr><td height="10px"></td></tr><tr>';;
			print '<td valign="top" style="border-radius: 10px; border-style:solid; border-color:gray"">';
	?>
	<table align="center" style="border-radius: 10px; font-family:Calibri" ><tr><td>
		<table height="100%" border="0" cellspacing="0" bgcolor="white" style="border-radius: 10px;" align="center" border="0"  style="font-size:12pt; font-family:Calibri" width="200px">
			<tr><td height="10px"></td></tr>
			<tr bgcolor="#467898"><td class="shipmentTB4" align="center" style="color:white"><strong><?php print $store_name[$k]; ?></strong></td></tr>
			<tr><td height="2px"></td></tr>
			<?php 
			if (array_key_exists($st_id,$total_staff_id)){
				for($i=0;$i<sizeof($total_staff_id[$st_id]);$i++){
					$key=array_search($total_staff_id[$st_id][$i],$onleave_staff[$st_id]);
					if($key>-1) $color1='red'; else $color1='green';
					if(($i%2)==0) $color2='#DADADA'; else $color2='#F3F3F3';
					print '<tr bgcolor="'.$color2.'"><td height="19px" class="shipmentTB4" style="color:'.$color1.'"><strong>'.$total_staff_name[$st_id][$i].'</strong></td></tr>';
					print '<tr><td colspan="6" height="10px"></td></tr>';
				} 
			}
			?>
			
		</table>
	</td></tr></table>
	<?php 
	print '</td>';
	} ?>
</tr>
</table>	

<?php
                include_once  'template/footer.php';
?>