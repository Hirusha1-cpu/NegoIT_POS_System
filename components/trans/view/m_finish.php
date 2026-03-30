<?php
                include_once  'template/m_header.php';
				generateGTN();
				
			
	$fqdn=$_SERVER['SERVER_NAME'];
	if($fqdn==$inf_url_primary){
		$url=$inf_url_primary;
	}else{
		$url=$inf_url_backup;
	}
				
?>


	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
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
  	<table width="90%"><tr><td style="vertical-align:top;">
		  <table border="1" cellspacing="0" align="center"><tr><td>
		  <table width="90%" align="center">
		  <tr><td><span style="font-family:'Arial Black'; font-size:20pt">GTN</span></td></tr>
		  <tr><td><?php print $inf_company; ?><br /><br />
		  FROM &nbsp;&nbsp;: <?php print $gtn_item_from; ?><br />
		  To &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php print $gtn_item_to; ?><br />
		  STATUS: <?php print $gtn_status; ?><br />
			</td></tr>
			<tr><td height="10px"></td></tr>
			<tr><td>
			GTN # [<?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?> ]<br />
			<span  style="font-family:Arial; font-size:11pt">
			DATE: <?php print $gtn_date; ?><br /><br />
			</span>
			</td></tr>
			<tr><td height="10px"></td></tr>
		  </table>
		
			<table align="center" width="300px" border="0" cellspacing="0" >
			<tr><td colspan="3">------------------------------------------------------------------------</td></tr>
			<tr style="font-family:Arial; font-size:10pt"><th>Item</th><th style="padding-right:25px">QTY</th><th width="10px"></th></tr>
			<tr><td colspan="3">------------------------------------------------------------------------</td></tr>
		<?php
			for($i=0;$i<sizeof($gtn_item_id);$i++){
				print '<tr style="font-size:10pt" height="20px"><td style="border-bottom:0; border-top:0; padding-left:10px; padding-right:5px">'.$gtn_item_des[$i].'</td><td width="25px" style="border-bottom:0; border-top:0; padding-right:20px" align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($gtn_item_qty[$i]).'</td><td><input type="checkbox" /></td></tr>';
				//print '<tr><td height="5px"></td><td></td></tr>';
			}
				print '	<tr><td colspan="3">------------------------------------------------------------------------</td></tr>';
				print '	<tr><td colspan="3" style="padding-left:10px">Issued By : '.ucfirst($gtn_from_user).'</td></tr>';
				print '	<tr><td colspan="3" style="padding-left:10px">Name   : _ _ _ _ _  _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ </td></tr>';
				print '	<tr><td colspan="3">&nbsp;</td></tr>';
				print '	<tr><td colspan="3" style="padding-left:10px">Signature : _ _ _ _ _  _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ </td></tr>';
				print '	<tr><td colspan="3" style="padding-left:10px">Location : '.$gtn_item_from.'</td></tr>';
				print '	<tr><td colspan="3">------------------------------------------------------------------------</td></tr>';
				print '	<tr><td colspan="3" align="center">Note: By Signing this, Customer confirms that he/she received replacement items for above listed</td></tr>';
				print '	<tr><td colspan="3" align="center">THANK YOU</td></tr>';
		?>	
			</table>
			<br />
		</td></tr></table>
</td><td style="vertical-align:top" align="right">
		<table align="right"><tr><td>
		<?php if($_GET['approve_permission']==1) { ?>
			<div id="approvegtn" style="background-color:#6699FF; border:medium; border-color:black; width:80px; text-align:center">
				<a class="shortcut-button" onclick="approveGTN(<?php print $_GET['id']; ?>)" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/approve.png" alt="icon" /><br />
					Approve
				</span></a>
			</div>
		<?php } ?>
			</td></tr><tr><td><br />
		<?php if($_GET['approve_permission']==1) { ?>
			<div id="rejectgtn" style="background-color:#FF9191; border:medium; border-color:black; width:80px; text-align:center">
				<a class="shortcut-button" onclick="rejectGTN(<?php print $_GET['id']; ?>)" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/reject.png" alt="icon" /><br />
					Reject
				</span></a>
			</div>
		<?php } if($gtnowner_crossinv){ ?>
		<div id="crossgtn" style="background-color:#6699FF; border:medium; border-color:black; width:80px; border-radius: 15px; padding-top:4px; padding-bottom:4px; padding-left:5px; padding-right:5px;">
						<a class="shortcut-button" onclick="crossSubmitGTN(<?php print $_GET['id']; ?>)" href="#" style="text-decoration:none; font-family:Arial; color:white;">
							SUBMIT
						</a>
		</div>
		<?php } ?>
		</td></tr></table>
  	</td></tr></table>
  </div>
</div>
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col " align="center">

  </div>
</div>
<hr>
</div>

<?php
                include_once  'template/m_footer.php';
?>