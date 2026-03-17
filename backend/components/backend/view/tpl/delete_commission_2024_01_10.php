  	<table width="90%">
	<tr><td bgcolor="#467898" style="font-family:Calibri; color:white; padding-left:10px">Commission Report Status : <span <?php if($status_out=='Deleted') print 'class="blink"'; ?> style="color:<?php print $status_color; ?>;"><strong><?php print $status_out; ?></strong></span></td><td></td></tr>
  	<tr><td style="vertical-align:top;"><br /><br /></td><td></td></tr>
  	<tr><td style="vertical-align:top; font-family:Calibri; font-size:16pt" align="center"><span>Commission Report No:</span><span> <?php print str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?></span></td><td></td></tr>
  	<tr><td style="vertical-align:top;"><br /><br /></td><td></td></tr>
  	<tr><td style="vertical-align:top;" align="center">			
  			<div style="background-color:#FF9191; border:medium; border-color:black; width:80px; text-align:center">
				<a class="shortcut-button" onclick="deleteComReport(<?php print $_GET['id']; ?>)" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="../images/cancel.png" alt="icon" /><br />
					Delete
				</span></a>
			</div>
</td><td></td></tr>
  	</table>
	