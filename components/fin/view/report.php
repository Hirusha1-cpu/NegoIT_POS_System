<?php
                include_once  'template/header.php';
?>

<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
	}
?></td></tr></table>
<form action="#" method="post">
	<table align="center" border="0"  style="font-size:12pt;font-family :Calibri; font-weight:bold; color:navy" width="800px" bgcolor="#EEEEEE">
	<tr><td width="80px"></td><td><span >Please Select The Report </span>&nbsp;&nbsp;&nbsp;
		<select style="height:40px; width:200px" name="report_name" id="report_name" onchange="window.location = 'index.php?components=fin&action='+this.value+'&to_date='+document.getElementById('to_date').value" >
			<option value="">-SELECT-</option>
			<option value="rep_balance_sheet" <?php if($_GET['action']=='rep_balance_sheet') print 'selected="selected"'; ?> >Balance Sheet</option>
			<option value="rep_profit_and_loss" <?php if($_GET['action']=='rep_profit_and_loss') print 'selected="selected"'; ?> >Profit & Loss Account</option>
			<option value="rep_trial_balance" <?php if($_GET['action']=='rep_trial_balance') print 'selected="selected"'; ?> >Trial Balance</option>
		</select>
	</td><td>As of:<input type="date" id="to_date" value="<?php print $_GET['to_date']; ?>" onchange="window.location = 'index.php?components=fin&action='+document.getElementById('report_name').value+'&to_date='+this.value" /></td><td width="80px"></td></tr>
	</table>
</form>
	
<?php
	if($_GET['action']=='rep_balance_sheet')include_once  'components/fin/view/tpl/rep_balance_sheet.php';
	if($_GET['action']=='rep_profit_and_loss')include_once  'components/fin/view/tpl/rep_profit_and_loss.php';
	if($_GET['action']=='rep_trial_balance')include_once  'components/fin/view/tpl/rep_trial_balance.php';
?>
<br />
<br />
	<table align="center"><tr><td align="center">
	<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br />
	Print
	</span></a>
	</div>
	</td></tr></table>

<?php
                include_once  'template/footer.php';
?>