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
<br>
<table align="center" border="1" cellspacing="0">
<tr><th>Debug ID</th><th>Item</th><th>Store</th><th>Action</th><th>Start Qty</th><th>Action Qty</th><th>End Qty</th><th>ITQ Qty</th><th>Operation</th><th></th></tr>
<?php
for($i=0;$i<sizeof($debug_id);$i++){
	if($debug_itq_qty[$i]=='') $button='<input type="button" value="Ack" onclick="window.location = '."'index.php?components=backend&action=debug_ack&id=$debug_id[$i]'".'"  />'; else $button=''; 
	print '<tr><td>'.$debug_id[$i].'</td><td>'.$debug_item[$i].'</td><td>'.$debug_store[$i].'</td><td>'.$debug_action[$i].'</td><td>'.$debug_start_qty[$i].'</td><td>'.$debug_action_qty[$i].'</td><td>'.$debug_end_qty[$i].'</td><td>'.$debug_itq_qty[$i].'</td><td>'.$debug_actionresult[$i].'</td><td>'.$button.'</td></tr>';
}
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
