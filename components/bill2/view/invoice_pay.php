<?php
    include_once  'template/header.php';
?>
<script type="text/javascript">
	function printdivBorderx($table_id,$x,$y){
		document.getElementById($table_id).border="1"
		document.getElementById($table_id).cellSpacing="0"
		printdiv($x,$y);
		document.getElementById($table_id).border="0"
		document.getElementById($table_id).cellSpacing="2"
	}
		
	function printdivBorder2(){
		document.getElementById('data_table').border="1";
		document.getElementById('data_table').cellSpacing="0";
		document.getElementById("data_table").style.fontSize = "9pt";
		
		var headstr = "<html><head><title></title></head><body>";
		var footstr = "</body></html>";
		var headerstr = document.all.item('printheader').innerHTML;
		var footerstr = document.all.item('printfooter').innerHTML;
		var newstr = document.all.item('print').innerHTML;
		var oldstr = document.body.innerHTML;
		document.body.innerHTML = headstr+headerstr+newstr+footerstr+footstr;
		window.print();
		document.body.innerHTML = oldstr;
		
		document.getElementById('data_table').border="0";
		document.getElementById('data_table').cellSpacing="2";
		document.getElementById("data_table").style.fontSize = "12pt";
		return true;
	}
</script>

<table align="center" cellspacing="0" style="font-size:11pt">
	<tr>
		<td>
			<?php
			if(isset($_REQUEST['message'])){
					if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
					print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
			}
			?>
		</td>
	</tr>
</table>

<form action="index.php" method="get">
	<input type="hidden" name="components" value="<?php print $components; ?>" />
	<input type="hidden" name="action" value="show_invoice_pay" />
	<table align="center" height="100%" cellspacing="0" border="0" style="font-family:Calibri; font-size:12pt; border-radius: 15px; padding-left:10px; padding-right:10px" bgcolor="#F0F0F0" border="0">
		<tr>
			<td width="50px"></td>
			<td width="80px">Invoice No</td>
			<td>
				<input type="text" id="invoice_no" name="invoice_no" value="<?php print $invoice_no; ?>" style="width:80px; text-align:center" />
			</td>
			<td width="50px"></td>
			<td>
				<input type="submit" value="Submit" style="height:35px; width:80px" />
			</td>
			<td width="50px"></td>
		</tr>
	</table>
</form>

<br/>
<br/>

<?php $outstanding=0; ?>

<div id="printheader" style="display:none" >
	<table width="95%" align="center" border="0">
		<tr>
			<td rowspan="2" style="font-family:Arial; font-size:11pt" width="230px">
				<span style="font-size:13pt"><strong><?php print $tm_company; ?></strong></span><br/>
				<?php print $tm_address; ?><br />
				Tel: <?php print $tm_tel; ?>
			</td>
			<td></td>
			<td align="right">
				<span style="font-family:'Arial Black'; font-size:18pt">Invoice Pay</span><br />
				<span style="font-size:12pt; font-family:Arial">Outstanding Report</span><br /></td>
		</tr>
		<tr>
			<td></td>
			<td align="right">
				<table style="font-family:Arial; font-size:9pt">
					<tr>
						<td style="font-size:11pt">INVOICE No</td>
						<td style="font-size:11pt">: [<?php print  str_pad($_GET['invoice_no'], 7, "0", STR_PAD_LEFT); ?> ]</td>
					</tr>
					<tr><td>DATE</td><td>: <?php print substr($bm_date_time,0,10); ?></td></tr>
					<tr><td>TIME</td><td>: <?php print substr($bm_date_time,11,5); ?></td></tr>
					<tr><td>PRINT DATE</td><td>: <?php print substr($print_time,0,10); ?></td></tr>
					<tr><td></td><td></td></tr>
				</table>
			</td>
		</tr>
	</table>
	<br/>
</div>

<div id="printfooter" style="display:none" >
	<br />
	<table width="95%" align="center" border="1" cellspacing="0">
		<tr>
			<td>
				<table align="center" width="100%">
					<tr><td width="65px" style="font-family:Arial; font-size:9pt">Salesman : </td><td style="font-family:Arial; font-size:9pt">  <?php print ucfirst($_COOKIE['user']); ?></td><td></td><td width="80px" style="font-family:Arial; font-size:9pt">Name</td><td width="130px">  ..............................</td></tr>
					<tr><td style="font-family:Arial; font-size:9pt" colspan="2"><?php print '<a href="../../../../index.php?components=bill2&action=cust_details&id='.$bm_cust_id.'&action2=finish_bill&id2='.$_GET['invoice_no'].'" target="_parent" style="text-decoration:none" >'.ucfirst($bm_cust).'</a>'; ?></td><td></td><td style="font-family:Arial; font-size:9pt">Signature</td><td>  ..............................</td></tr>
				</table>
			</td>
		</tr>
	</table>
</div>

<div id="print">
	<table id="data_table" align="center" style="font-family:Calibri; font-size:12pt" height="450px" >
		<tr style="background-color:#CCCCEE;">
			<td class="shipmentTB3" colspan="4" height="22px">
				<strong>Invoice Total</strong>
			</td>
			<td class="shipmentTB3" align="center"></td>
			<!-- <td class="shipmentTB3" align="right"><strong><?php print number_format($bill_total); $outstanding=$bill_total; ?></strong> -->
			 <td class="shipmentTB3" align="right">
<strong><?php print number_format((float)($bill_total ?? 0), 2); $outstanding = (float)($bill_total ?? 0); ?></strong>
</td>

			</td>
		</tr>
		<tr style="background-color:#CCCCEE;">
			<td class="shipmentTB3" colspan="4" height="22px">Bill Payment (Total)</td>
			<!-- <td class="shipmentTB3" align="right"><?php print number_format($bill_payment); $outstanding-=$bill_payment; ?></td> -->
			 <td class="shipmentTB3" align="right">
<?php print number_format((float)($bill_payment ?? 0), 2); $outstanding -= (float)($bill_payment ?? 0); ?>
</td>
			<td class="shipmentTB3" align="right"><?php print number_format($outstanding); ?></td>
		</tr>
		<tr style="background-color:#CCCCCC; color:black; font-weight:bold">
			<td class="shipmentTB3" align="center" height="22px">Payment No</td>
			<td class="shipmentTB3" align="center">Payment Date</td>
			<td class="shipmentTB3" align="center">Instalment Date</td>
			<td class="shipmentTB3" align="center">Pay Type</td>
			<td class="shipmentTB3" align="center">Pay Amount</td>
			<td class="shipmentTB3" align="center">Outstanding</td>
		</tr>
		<?php
		for($i=0;$i<sizeof($py_id);$i++){
			if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			$outstanding-=$py_amount[$i];
			if($components=='hire_purchase') $comp='hire_purchase'; else $comp='bill2';
			print '<tr style="background-color:'.$color.';">
					<td class="shipmentTB3" align="center" height="22px">
						<a href="index.php?components='.$comp.'&action=finish_payment&id='.$py_id[$i].'" style="text-decoration:none">'.str_pad($py_id[$i], 7, "0", STR_PAD_LEFT).'</a>
					</td>
					<td class="shipmentTB3" align="center">'.$py_date[$i].'</td>
					<td class="shipmentTB3" align="center">'.$py_hp_inst_date[$i].'</td>
					<td class="shipmentTB3" align="center">'.$py_type[$i].'</td>
					<td class="shipmentTB3" align="right">'.number_format($py_amount[$i]).'</td>
					<td class="shipmentTB3" align="right">'.number_format($outstanding).'</td>
				</tr>';
		}
			print '	<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
			print '<tr style="background-color:#CCCCEE;">
					<td class="shipmentTB3" colspan="4" height="22px"><strong>Outstanding Balance</strong></td>
					<td class="shipmentTB3" align="center"></td>
					<td class="shipmentTB3" align="right"><strong>'.number_format($outstanding).'</strong></td>
				</tr>';
		
		?>
	</table>
</div>

<br />

<table align="center">
	<tr>
		<td align="center">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="printdivBorder2()" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/print.png" alt="icon" /><br />
					Print
					</span>
				</a>
			</div>
		</td>
	</tr>
</table>

<?php
    include_once  'template/footer.php';
?>