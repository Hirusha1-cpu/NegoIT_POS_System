<?php
                include_once  'template/header.php';
?>
 
	<body id="login">
		
		<div id="login-wrapper" class="png_bg">
			<div id="login-top">
			
				<!-- Logo (221px width) -->
				&nbsp;</div> <!-- End #logn-top -->
			
			<div id="login-content">
<table align="center" width="600px" bgcolor="#CCCCFF"><tr><td align="center">
				<h1>Login</h1>

    	  <form method="post" action="index.php?components=authenticate&action=login">
    	  
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
						<label>Password</label>
						<input class="text-input" type="password" name="passwd" />
					</p>
					<div class="clear"></div>
					<p id="remember-password">
						<input type="checkbox" />Remember me
					</p>
					<div class="clear"></div>
					<p>
						<input class="button" style="width:150px" type="submit" value="Sign In" />
					</p>
					
				</form>
</td></tr></table>								
			</div> <!-- End #login-content -->
			
		</div> <!-- End #login-wrapper -->
		
<?php
                include_once  'template/footer.php';
?>