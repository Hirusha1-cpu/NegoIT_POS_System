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
<div id="loading" style="display:none"><img src="../images/loading.gif" style="width:30px" /></div>

	<table align="center" style="font-size:11pt"><tr><td>
	<?php 
		if(isset($_REQUEST['message'])){
			if($_REQUEST['re']=='success') $color='green'; else $color='red';
		print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br /><br />'; 
		}
	?></td></tr></table>
<table align="center" border="0" >
<tr bgcolor="#E5E5E5"><th>&nbsp;&nbsp;&nbsp;Subscription End in&nbsp;&nbsp;</th><th style="color:red">&nbsp;&nbsp;&nbsp;<?php print $subscription_end; ?>&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;Days&nbsp;&nbsp;&nbsp;</th></tr>
</table>
<br />
<table align="center">
<tr><td align="center" colspan="3">Increment Subscription</td></tr>
<tr><td align="center"><input type="button" value="+" style="font-size:20pt; font-weight:bold; width:50px" onclick="window.location = 'index.php?components=backend&action=sub_up'" /></td><td width="30px"></td><td align="center"><input type="button" value="-" style="font-size:20pt; font-weight:bold; width:50px" onclick="window.location = 'index.php?components=backend&action=sub_down'" /></td></tr>
</table>
</div>	
  </div>
</div>
</div>
<hr>
<?php
                include_once  'template/m_footer.php';
?>
