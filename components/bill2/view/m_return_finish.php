<?php
                include_once  'template/m_header.php';
				generateRtnInvoice();
				
			
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
	
<script>
function sendToQuickPrinterChrome(){
    var commandsToPrint =document.getElementById('print').innerHTML;
    var textEncoded = encodeURI(commandsToPrint);
    window.location.href="intent://"+textEncoded+"#Intent;scheme=quickprinter;package=pe.diegoveloper.printerserverapp;end;";
}

</script>
<!-- ------------------------------------------------------------------------------------ -->
<?php
 include_once  'components/bill2/view/tpl/pos_rtn.php';
?>

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
  <table width="90%"><tr><td bgcolor="#467898" style="font-family:Calibri; color:white; padding-left:10px">Return Invoice Status : <span <?php if($status_out=='Deleted') print 'class="blink"'; ?> style="color:<?php print $status_color; ?>;"><strong><?php print $status_out; ?></strong></span></td><td></td></tr></table>
  	<table width="90%"><tr><td style="vertical-align:top;">
		  <table border="1" cellspacing="0" align="center"><tr><td>
		  <table width="90%" align="center">
		  <tr><td><span style="font-family:'Arial Black'; font-size:20pt">RETURN INVOICE</span></td></tr>
		  <tr><td><?php print $tm_company; ?><br />
			<?php print $tm_address; ?><br />
			Tel: <?php print $tm_tel; ?>
			</td></tr>
			<tr><td height="10px"></td></tr>
			<tr><td>
			RETURN INVOICE # [<?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?> ]<br />
			<span  style="font-family:Arial; font-size:11pt">
			DATE: <?php print strtoupper (date("F d, Y",time())); ?><br /><br />
			</span>
			</td></tr>
			<tr><td height="10px"></td></tr>
		  </table>
		
			<table align="center" width="300px" border="0" cellspacing="0" >
			<tr><td colspan="2">------------------------------------------------------------------------</td></tr>
			<tr style="font-family:Arial; font-size:10pt"><th>Item</th><th style="padding-right:25px">QTY</th></tr>
			<tr><td colspan="2">------------------------------------------------------------------------</td></tr>
		<?php
			for($i=0;$i<sizeof($bill_id);$i++){
				print '<tr style="font-size:10pt" height="20px"><td style="border-bottom:0; border-top:0; padding-left:10px; padding-right:5px">'.$bill_item[$i].'</td><td width="25px" style="border-bottom:0; border-top:0; padding-right:20px" align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($bill_qty[$i]).'</td></tr>';
				print '<tr><td height="10px"></td><td></td></tr>';
			}
				print '	<tr><td colspan="2">------------------------------------------------------------------------</td></tr>';
				print '	<tr><td colspan="2" style="padding-left:10px">Salesman : '.ucfirst($bill_salesman).'</td></tr>';
				print '	<tr><td colspan="2" style="padding-left:10px">Customer : '.ucfirst($bill_cust).'</td></tr>';
				print '	<tr><td colspan="2">&nbsp;</td></tr>';
				print '	<tr><td colspan="2" style="padding-left:10px">Signature : _ _ _ _ _  _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ </td></tr>';
				print '	<tr><td colspan="2">------------------------------------------------------------------------</td></tr>';
				print '	<tr><td colspan="2" align="center">Note: By Signing this, Customer confirms that he/she received replacement items for above listed</td></tr>';
				print '	<tr><td colspan="2" align="center">THANK YOU</td></tr>';
		?>	
			</table>
			<br />
		</td></tr></table>
</td><td style="vertical-align:top" align="right">
		<table align="right">
		<tr><td>
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px; text-align:center">
				<a class="shortcut-button" onclick="parent.location='printscheme://<?php print $url; ?>/pos_rtn.php?id=<?php print $_GET['id']; ?>'" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/print.png" alt="icon" /><br />
					Print Bill
				</span></a>
			</div>
			</td></tr>
		<tr><td>
			<div style="background-color:#9966FF; border:medium; border-color:black; width:80px; text-align:center">
				<a class="shortcut-button" onclick="sendToQuickPrinterChrome()" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/print.png" alt="icon" /><br />
					New Print
				</span></a>
			</div>
			</td></tr>
			<tr><td><br />
			<?php if(returnPermission($_GET['id'])){ ?>
			<div style="background-color:#FF9191; border:medium; border-color:black; width:80px; text-align:center">
				<a class="shortcut-button" onclick="deleteReturn(<?php print $_GET['id']; ?>,'bill2')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/cancel.png" alt="icon" /><br />
					Cancel Bill
				</span></a>
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