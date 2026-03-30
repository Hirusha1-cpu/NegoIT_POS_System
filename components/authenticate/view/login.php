<?php
                include_once  'template/header.php';
?>
	<script src="js/md5.min.js"></script>
	<body id="login">
	<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
		<div id="login-wrapper" class="png_bg">
			<div id="login-top">
			
				<!-- Logo (221px width) -->
				&nbsp;</div> <!-- End #logn-top -->
			
			<div id="login-content">
<table align="center" width="600px" bgcolor="#AAAAEE" style="font-family:Calibri">
<tr><td bgcolor="AAAAEE" height="15px"></td></tr>
<tr><td align="center">
<table align="center" width="560px" bgcolor="#CCCCFF" style="font-family:Calibri"><tr><td align="center">
				<h1>Login</h1>

    	  <form method="post" action="index.php?components=authenticate&action=login" onsubmit="return generateLogIn()">
    	  <input type="hidden" id="token" value="<?php print $token; ?>" />
    	  <input type="hidden" id="onetime_pass" name="onetime_pass" />
<?php  					if(!isset($_REQUEST['message'])){ ?>
					<div class="notification information png_bg">
						<div>
							Please enter username and password.
						</div>
					</div>
<?php } ?>					
					
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'; 
	}
	?>						
					
					<p>
						<label>Username</label>
						<input class="text-input" type="text" name="uname" />
					</p>
					<div class="clear"></div>
					<p>
						<label>Password &nbsp;</label>
						<input class="text-input" type="password" id="passwd" />
					</p>
					<div class="clear"></div>
					<p id="remember-password">
						<input type="checkbox" />Remember me
					</p>
					<div class="clear"></div>
					<p>
						<div id="div_login"><input class="button" style="width:150px" type="submit" value="Sign In" /></div>
					</p>
					
				</form>
</td></tr>
</table>						
</td></tr>
<tr><td bgcolor="AAAAEE" height="15px"></td></tr>
</table>								
		</div> <!-- End #login-content -->
			
		</div> <!-- End #login-wrapper -->
		
<?php
                include_once  'template/footer.php';
?>