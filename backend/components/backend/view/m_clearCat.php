<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->

	<script src="js/zigo.js"></script>
<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
<!-- ------------------Item List----------------------- -->
	<table align="center" style="font-size:11pt"><tr><td>
	<?php 
		if(isset($_REQUEST['message'])){
			if($_REQUEST['re']=='success') $color='green'; else $color='red';
		print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br /><br />'; 
		}
	?></td></tr></table>
	<form method="post" action="index.php?components=backend&action=set_clear">
	<table align="center" width="90%">
	<tr bgcolor="#EEEEEE"><td colspan="2">&nbsp;</td></tr>
	<tr bgcolor="#EEEEEE"><td align="center">
	<select name="category" >
		<option value="">-SELECT-</option>
		<?php for($i=0;$i<sizeof($cat_id);$i++){
		print '<option value="'.$cat_id[$i].'">'.$cat_name[$i].'</option>';
		} ?>
	</select>
	</td><td align="center">
	<select name="store" >
		<option value="">-SELECT-</option>
		<?php for($i=0;$i<sizeof($st_id);$i++){
		print '<option value="'.$st_id[$i].'">'.$st_name[$i].'</option>';
		} ?>
	</select>
	</td></tr>
	<tr bgcolor="#EEEEEE"><td align="center">Password</td><td align="center"><input type="password" name="password" style="width:80%;" /></td></tr>
	<tr bgcolor="#EEEEEE"><td colspan="2" align="center"><input type="submit" value="Clear Category Qty" style="width:180px; height:40px" /></td></tr>
	<tr bgcolor="#EEEEEE"><td colspan="2">&nbsp;</td></tr>
	</table>
	</form>
	<br>
	<hr>
	
	<form method="post" action="index.php?components=backend&action=restore_clear_cat">
	<input type="hidden" name="last_job_id" value="<?php print $last_job_id; ?>" />
	<table align="center" width="90%">
	<tr bgcolor="#EEEEEE"><td colspan="2">&nbsp;</td></tr>
	<tr bgcolor="#EEEEEE"><td align="center">Clear Category<br>Last Job ID</td><td align="center"><strong><?php print $last_job_id; ?></strong></td></tr>
	<tr bgcolor="#EEEEEE"><td align="center">Password</td><td align="center"><input type="password" name="password" style="width:80%;" /></td></tr>
	<tr bgcolor="#EEEEEE"><td colspan="2" align="center"><input type="submit" value="Restore Category Qty" style="width:180px; height:40px" /></td></tr>
	<tr bgcolor="#EEEEEE"><td colspan="2">&nbsp;</td></tr>
	</table>
	</form>
</div>	
  </div>
</div>
</div>
<hr>
<br />
<?php
                include_once  'template/m_footer.php';
?>
