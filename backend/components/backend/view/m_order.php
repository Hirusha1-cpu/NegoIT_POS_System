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
<br><br>
<table align="center">
<tr><td>
<input type="button" value="Order Inventory" onclick="window.location = 'index.php?components=backend&action=inv_setorder'" style="width:150px; height:50px" />
</td></tr>
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
