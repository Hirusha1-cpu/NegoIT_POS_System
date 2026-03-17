<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
<div class="w3-container" style="margin-top:75px">
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
  <table align="center" bgcolor="#EEEEEE" width="300px">
  	<tr><td height="80px"></td></tr>
  	<tr><td align="center"><h1>Your Subscription Has Expired</h1><br /><br /></td></tr>
  	<tr><td align="center"><p>Please contact NegoIT for reactivation</p><br /><br /></td></tr>
  	<tr><td align="center"><input type="button" value="Retry" style="width:50px; height:30px" onclick="window.location = 'index.php'" /></td></tr>
  	<tr><td height="60px"></td></tr>
  </table>
	
  </div>
</div>
</div>

<?php
                include_once  'template/m_footer.php';
?>