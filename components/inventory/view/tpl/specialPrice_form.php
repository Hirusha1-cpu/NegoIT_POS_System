<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<script>
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
	});
</script>

<table align="center" cellspacing="0" style="font-size:12pt">
	<tr>
		<td>
			<?php
				if(isset($_REQUEST['message'])){
					if($_REQUEST['re']=='success') $color0='green'; else $color0='red';
				print '<span style="color:'.$color0.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br /><br />';
				}
			?>
		</td>
	</tr>
</table>

<?php
	if(isset($_GET['id'])){
		$j=array_search($_GET['id'],$sr_id);
		$item_t=$sr_item[$j];
		$district_t=$sr_district[$j];
		$increment_t=$sr_increment[$j];
		$action='update_specialprice';
	}else{
		$item_t=$district_t=$increment_t='';
		$action='add_specialprice';
	}
?>
<form action="index.php?components=inventory&action=<?php print $action; ?>" onsubmit="return validateSpecial()"
	method="post">
	<?php if(isset($_GET['id'])) print '<input type="hidden" name="id" value="'.$_GET['id'].'" />'; ?>
	<table align="center" bgcolor="#E5E5E5" style="font-size:11pt;">
		<tr>
			<td colspan="4" height="20px"></td>
		</tr>
		<tr>
			<td width="30px" height="30px"></td>
			<td><strong>Item</strong></td>
			<td><input type="text" name="item" id="tags1" style="width:200px" value="<?php print $item_t; ?>" /></td>
			<td width="30px"></td>
		</tr>
		<tr>
			<td width="30px" height="30px"></td>
			<td><strong>District</strong></td>
			<td>
				<select name="district" id="district">
					<option value="">-SELECT-</option>
					<?php for($i=0;$i<sizeof($district_id);$i++){
						if($district_name[$i]==$district_t) $select='selected="selected"'; else $select='';
						print '<option value="'.$district_id[$i].'" '.$select.'>'.$district_name[$i].'</option>';
						}
					?>
				</select>
			</td>
			<td width="30px"></td>
		</tr>
		<tr>
			<td width="30px" height="30px"></td>
			<td><strong>Increment</strong></td>
			<td><input type="text" id="increment" name="increment" style="width:70px"
					value="<?php print $increment_t; ?>" /></td>
			<td width="30px"></td>
		</tr>
		<tr>
			<td colspan="4" height="20px" align="center">
				<?php
					if($item_t=='') print '<input type="submit" value="Add" style="width:80px; height:40px" />'; else{
						print '<input type="submit" value="Update" style="width:80px; height:40px" /> &nbsp;&nbsp;&nbsp;';
						print '<input type="Button" value="Delete" style="width:55px; height:40px; color:white; background-color:maroon;" onclick="deleteSpecial('.$_GET['id'].')" />';

					}
				?>
			</td>
		</tr>
		<tr>
			<td colspan="4" height="20px"></td>
		</tr>
	</table>
</form>