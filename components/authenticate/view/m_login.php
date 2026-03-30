<?php
                include_once  'template/m_header.php';
?>
	<script src="js/md5.min.js"></script>
<!-- ------------------------------------------------------------------------------------ -->
<form method="post" action="index.php?components=authenticate&action=login" onsubmit="return generateLogIn()">
	<input type="hidden" id="token" value="<?php print $token; ?>" />
	<input type="hidden" id="onetime_pass" name="onetime_pass" />
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
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
  	<tr><td align="center"><input type="text" name="uname" id="uname" value="Username"
  onblur="if(this.value==''){ this.value='Username'; this.style.color='#BBB'; this.style.fontSize='large';}"
  onfocus="if(this.value=='Username'){ this.value=''; this.style.color='#000';  this.style.fontSize='large';}"
  style="color:#BBB; font-size:large;" /><br /><br /></td></tr>
  	<tr><td align="center"><input type="password" id="passwd" value="Password"
  onblur="if(this.value==''){ this.value='Password'; this.style.color='#BBB'; this.style.fontSize='large';}"
  onfocus="if(this.value=='Password'){ this.value=''; this.style.color='#000'; this.style.fontSize='large';}"
  style="color:#BBB; font-size:large;" /><br /><br /></td></tr>
  	<tr><td align="center"><div id="div_login"><input class="button" style="width:150px; height:50px; font-weight:bold; color:gray" type="submit" value="Sign In" /></div></td></tr>
  	<tr><td height="80px"></td></tr>
  </table>
	
  </div>
</div>
</div>
</form>

<?php
                include_once  'template/m_footer.php';
?>