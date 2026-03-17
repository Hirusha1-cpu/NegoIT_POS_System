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
	<form method="post" action="index.php?components=backend&action=lock">
	<table align="center">
	<tr><td><input type="text" name="lockinvid" /></td><td><input type="submit" value="Search" /></td></tr>
	</table>
	</form>
	<br>
	<?php
	if($lockstatus!=''){ ?>
		<table align="center" style="font-family:Calibri;" width="90%">
		<?php if($lockstatus==1) $checked='checked="checked"'; else $checked=''; ?>
		<tr bgcolor="#DDDDAA"><td align="center"><strong><?php print str_pad($_REQUEST['lockinvid'], 7, "0", STR_PAD_LEFT); ?></strong></td><td colspan="2" align="center">Lock Status : <input type="checkbox" name="lock" <?php print $checked; ?> onchange="window.location = 'index.php?components=backend&action=changelock&lockinvid=<?php print $_REQUEST['lockinvid']; ?>'" /></td></tr>
		<tr bgcolor="#DDDDDD"><td colspan="3" >&nbsp;&nbsp;Associated Payments</td></tr>
		<tr bgcolor="#DDDDDD"><th>Payment Invoice</th><th>Type</th><th>Amount</th></tr>
		<?php for($i=0;$i<sizeof($py_type);$i++){
		print '<tr bgcolor="#EEEEEE"><td align="center">'.str_pad($py_inv[$i], 7, "0", STR_PAD_LEFT).'</td><td align="center">'.$py_type[$i].'</td><td align="right" style="padding-right:10px">'.number_format($py_amount[$i]).'</td></tr>';
		} ?>
		</table>
		<hr>
<?php	} ?>
	<table align="center" style="font-family:Calibri;" width="90%">
	<tr bgcolor="#DDDDDD"><th>Invoice No</th><th>Time</th><th>User</th></tr>
	<?php
	for($i=0;$i<sizeof($bm_bill);$i++){
	print '<tr bgcolor="#EEEEEE"><td align="center"><a href="index.php?components=backend&action=home&lockinvid='.$bm_bill[$i].'" >'.str_pad($bm_bill[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center">'.$bm_time[$i].'</td><td>&nbsp;&nbsp;'.ucfirst($bm_user[$i]).'</td></tr>';
	}
	?>
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
