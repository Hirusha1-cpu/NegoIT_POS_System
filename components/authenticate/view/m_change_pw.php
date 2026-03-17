<?php
                include_once  'template/m_header.php';
                $form='pwreset';
?>
	<script src="js/md5.min.js"></script>
<script type="text/javascript">
	function validateChangePW(){
		generateLogIn();
		if(validateUser()){
    		document.getElementById('div_chpw').innerHTML=document.getElementById('loading').innerHTML;
			return true;
		}else{
			return false;
		}
	}
</script>
<!-- ------------------------------------------------------------------------------------ -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<div id="div_login" style="display:none"></div>

<form action="index.php?components=authenticate&action=set_pw" onsubmit="return validateChangePW()" method="post" >
    <input type="hidden" id="token" value="<?php print $token; ?>" />
    <input type="hidden" id="onetime_pass" name="onetime_pass" />
	<input type="hidden" id="action" value="create" />
	<input type="hidden" id="passhash" name="passhash" />
	<input type="hidden" id="emp_name" value="aaa" />
	<input type="hidden" id="action" value="create" />
<div class="w3-container" style="margin-top:75px">
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success'){ $color='green'; $form='redirect'; }else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>'; 
	}
?>	
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
  <table align="center" bgcolor="#EEEEEE" width="300px">
  	<?php if($form=='pwreset'){ ?>
	  	<tr><td height="80px" align="center" style="color:silver">Store: <span style="color:gray"><?php if(isset($_COOKIE['store_name'])) print $_COOKIE['store_name']; ?></span></td></tr>
	  	<tr><td align="center"><input type="text" id="user_name" value="<?php print $_COOKIE['user']; ?>" readonly="readonly" style="background-color:#F5F5F5" /><br /><br /></td></tr>
	  	<tr><td align="center"><input type="password" id="passwd" placeholder="OLD Password"/><br /><br /></td></tr>
	  	<tr><td align="center"><input type="password" id="user_pass1" placeholder="New Password"/><br /><br /></td></tr>
	  	<tr><td align="center"><input type="password" id="user_pass2" placeholder="Confirm Password"/><br /><br /></td></tr>
	  	<tr><td align="center"><div id="div_chpw"><input class="button" style="width:150px; height:50px; font-weight:bold; color:gray" type="submit" value="Change Password" /></div></td></tr>
	  	<tr><td height="80px"></td></tr>
  	<?php }else{ ?>
	  	<tr><td height="150px" align="center"></td></tr>
	  	<tr><td align="center"><div id="div_chpw"><input class="button" style="height:50px; font-weight:bold; color:gray" type="button" value="Redirect to the Application" onclick="window.location = 'index.php?components=authenticate&action=reload'" /></div></td></tr>
	  	<tr><td height="150px"></td></tr>
  	<?php } ?>
  </table>
	
  </div>
</div>
</div>
</form>

<?php
                include_once  'template/m_footer.php';
?>