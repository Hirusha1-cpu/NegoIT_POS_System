			<div class="clear"></div>

			<br /><br /><br />
			<div id="footer" align="center" style="position:fixed; left: 30px; bottom: 0; font-family:Calibri; font-size:10pt; color:gray ">
				<small>
					<!-- Remove this notice or replace it with whatever you want -->
					&copy; Copyright <script>
						document.write(new Date().getFullYear());
					</script> NegoIT | Powered by <a href="http://www.negoit.info">Nego IT</a> | V4.7 | <?php print gethostname(); ?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php if (isset($_COOKIE['store_shop_name'])) print $_COOKIE['store_shop_name']; ?>
				</small>
			</div><!-- End #footer -->

			<?php //if($_SERVER['SERVER_PORT']==443){ 
			?>
			<div style="position:fixed; right: 10px; bottom: 0; ">
				<script type="text/javascript">
					//<![CDATA[
					var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.trust-provider.com/" : "http://www.trustlogo.com/");
					document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
					//]]>
				</script>
				<script language="JavaScript" type="text/javascript">
					TrustLogo("images/sectigo_trust_seal_sm_82x32.png", "SECDV", "none");
				</script>
				<!-- <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script> -->
			</div>
  			<script src="js/toastr.min.js"></script>
			</body>

			</html>