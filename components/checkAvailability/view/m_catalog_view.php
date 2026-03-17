<?php
                include_once  'template/m_header.php';
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>

<!-- ------------------------------------------------------------------------------------ -->

<div class="w3-container" style="margin-top:75px">
<?php if($direct_mkt==0){ ?>
	<input type="hidden" id="cust_id" name="cust_id" value="<?php print $cust_id; ?>" />
	<input type="hidden" id="item_id" name="item_id" value="" />
	<table align="center" style="font-family:Calibri; font-size:11pt; border-radius:10px" bgcolor="#EEEEEE" border="0">
	<tr><td width="60px" align="center" style="font-size:8pt" title="Show All Items Even though No Stock Available">Show All<br /><input type="checkbox" name="show_all" id="show_all" <?php print $show_all; ?> /></td><td><strong>District</strong></td><td>
		<select id="district" name="district" >
		<option value="">-SELECT-</option>
		<?php for($i=0;$i<sizeof($district_id);$i++){
			if($district_id[$i]==$district) $select2='selected="selected"'; else $select2='';
			print '<option value="'.$district_id[$i].'" '.$select2.'>'.$district_name[$i].'</option>';
		} ?>
		</select>
	</td><td rowspan="4"><input type="button" value="Submit" onclick="filterData()" style="width:100px; height:100px" /></td></tr>
	<tr><td width="50px"></td><td><strong>From Date</strong><br />
	<strong>To Date</strong></td><td><input type="date" id="from_date" name="from_date" value="<?php print $from_date; ?>" /><br />
	<input type="date" id="to_date" name="to_date" value="<?php print $to_date; ?>" />
	</td>
	</tr>
	<tr><td width="50px"></td><td><strong>Customer</strong><br />
	<strong>Item</strong></td><td><input type="text" id="tags1" name="cust" value="<?php if(isset($_REQUEST['cust'])) print $_REQUEST['cust']; ?>" onclick="this.value=''" /><br />
	<input type="text" id="tags2" name="item" value="<?php print $item_desc; ?>" onclick="this.value=''" />
	</td>
	</tr>
	</table>
<?php }else{ ?>
	<input type="hidden" id="cust_id" value="0" />
	<input type="hidden" id="tags1" value="0" />
	<input type="hidden" id="district" value="9" />
<?php } ?>

<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>'; 
	}
?>	

<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
	<?php
		include_once  'components/checkAvailability/view/tpl/catalog.php';
	?>
  </div>
</div>
</div>
<hr>

<?php
                include_once  'template/m_footer.php';
?>