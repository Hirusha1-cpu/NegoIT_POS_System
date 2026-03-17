<?php
                include_once  'template/header.php';
?>
<script type="text/javascript">
const zeroPad = (num, places) => String(num).padStart(places, '0');

function getInvoiceData($invoice_no){
    document.getElementById("div_inv").innerHTML=document.getElementById("loading").innerHTML;
    document.getElementById("invoice_no").value=$invoice_no;
    $components=document.getElementById("components").value;
    if($components!='hire_purchase') $components='bill2';
    $out_txt='<table width="100%">';
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var myObj = JSON.parse(xmlhttp.responseText);
				document.getElementById("div_inv_no").innerHTML='<a style="text-decoration:none" href="index.php?components='+$components+'&action=finish_bill&id='+myObj.invoice_no+'">'+zeroPad(myObj.invoice_no,7)+'</a>';
				document.getElementById("div_hp_cust").innerHTML=myObj.hp_cust;
				document.getElementById("div_hp_nick").innerHTML=myObj.hp_nick;
				document.getElementById("div_cal_start").innerHTML=myObj.hp_cal_start;
				document.getElementById("div_hp_schdule").innerHTML=myObj.hp_schdule;
				document.getElementById("div_bm_total").innerHTML=thousands_separators(myObj.bm_total);
				document.getElementById("div_pay_total").innerHTML=thousands_separators(myObj.pay_total);
				document.getElementById("div_hp_due").innerHTML=thousands_separators(myObj.bm_total - myObj.pay_total);
				document.getElementById("div_hp_amount").innerHTML=thousands_separators(myObj.hp_amount);
				document.getElementById("div_hp_count").innerHTML=myObj.hp_count;
				
				$inst_str=myObj.inst_str;
				$inst_str1=$inst_str.split('|');
				$out_txt+='<tr style="background-color:#DDDDDD;"><td class="shipmentTB3" align="center"><strong>Instalment Date</strong></td><td class="shipmentTB3">';
				$out_txt+='<table><tr><td class="shipmentTB3" align="center" style="background-color:#FFDDDD;" width="100px"><strong>Pay No</strong></td><td class="shipmentTB3" align="right" style="background-color:#EEDDDD;" width="90px"><strong>Pay Amount</strong></td></tr></table>';
				$out_txt+='</td><td class="shipmentTB3"><strong>Total Amount</strong></td></tr>';
				for($i=0;$i<$inst_str1.length;$i++){
					if(($i%2)==0) $color='#EEEEEE'; else $color='#FAFAFA';
					$inst_str2=$inst_str1[$i].split(',');
					$out_txt+='<tr style="background-color:'+$color+';"><td class="shipmentTB3" >'+$inst_str2[0]+'</td><td class="shipmentTB3">';
					$out_txt+='<table>';
					$inst_py1=$inst_str2[1].split('/');
						for($j=0;$j<$inst_py1.length;$j++){
							$inst_py2=$inst_py1[$j].split('-');
							$pay_no=parseInt($inst_py2[0]);
							if(isNaN($pay_no)) $pay_no0='<br />'; else $pay_no0=zeroPad($pay_no,7);
							if(isNaN($inst_py2[1])) $pay_amo0='<br />'; else $pay_amo0=thousands_separators($inst_py2[1],7);
							$out_txt+='<tr><td class="shipmentTB3" align="center" style="background-color:#FFDDDD;" width="100px">'+$pay_no0+'</td><td class="shipmentTB3" align="right" style="background-color:#EEDDDD;" width="90px">'+$pay_amo0+'</td></tr>';
						}
					$out_txt+='</table>';
					$out_txt+='</td><td class="shipmentTB3" align="right">'+$inst_str2[2]+'</td></tr>';
				}
			 	$out_txt+='</table>';
				document.getElementById("div_out").innerHTML=$out_txt;
				document.getElementById("div_inv_pay_btn").style.display="block";
				
				document.getElementById("div_inv").innerHTML='';
		}
	};

	xmlhttp.open("POST", "index.php?components=hire_purchase&action=hp_get_invoice_data", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('invoice_no='+$invoice_no);
}

function invoicePayStatement(){
    $invoice_no=document.getElementById("invoice_no").value;
    $components=document.getElementById("components").value;
    if($components=='hire_purchase') $comp='hire_purchase'; else $comp='bill2';
	window.open('index.php?components='+$comp+'&action=show_invoice_pay&invoice_no='+$invoice_no);
}
</script>
<!-- -------------------------------------------------------------------------------------------------------------------- -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	}
?></td></tr></table>

<table align="center">
<tr><td valign="top">
	<table align="center" style="font-family:Calibri; font-size:12pt">
	<tr style="background-color:#467898; color:white;"><th></th><th>Invoice No</th><th>Customer</th><th class="shipmentTB3">Invoice Date</th><th class="shipmentTB3">Schedule Type</th><th class="shipmentTB3">Instalment</th><th class="shipmentTB3">Paid/Count</th></tr>
	<?php 
	//	if($components=='hire_purchase') $comp='hire_purchase'; else $comp='bill2';
		for($i=0;$i<sizeof($hp_invoice_no);$i++){
			if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			print '<tr style="background-color:'.$color.'"><td><input type="checkbox" /></td><td align="center"><a href="#page_top" onclick="getInvoiceData('.$hp_invoice_no[$i].')" style="text-decoration:none; color:blue; cursor:pointer" >'.str_pad($hp_invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td class="shipmentTB3">'.$hp_cust[$i].'</td><td align="center">'.$hp_bill_date[$i].'</td><td class="shipmentTB3">'.$hp_type[$i].'</td><td align="right" class="shipmentTB3">'.number_format($hp_amount[$i]).'</td><td align="center">'.$hp_paidcount[$i].'/'.$hp_paycount[$i].'</td></tr>';
		}
	
	?>
	</table>
</td><td width="100px"></td><td valign="top">
	<div id="page_top"></div>
	<input type="hidden" id="invoice_no" value="0" />
	<input type="hidden" id="components" value="<?php print $components; ?>" />
	<table style="font-family:Calibri; font-size:12pt">
	<tr><td class="shipmentTB3" style="background-color:#467898; color:white;" height="30px" width="150px" ><strong>Invoice Data</strong></td><td class="shipmentTB3" style="background-color:#467898; color:white;" width="200px" ><div id="div_inv"></div></td></tr>
	<tr><td class="shipmentTB6" >Invoice No</td><td class="shipmentTB3" style="background-color:#EEEEFF"><div id="div_inv_no"></div></td></tr>
	<tr><td class="shipmentTB6">Customer</td><td class="shipmentTB3" style="background-color:#EEEEFF"><div id="div_hp_cust"></div></td></tr>
	<tr><td class="shipmentTB6">Nickname</td><td class="shipmentTB3" style="background-color:#EEEEFF"><div id="div_hp_nick"></div></td></tr>
	<tr><td class="shipmentTB6">Instalment Start</td><td class="shipmentTB3" style="background-color:#EEEEFF"><div id="div_cal_start"></div></td></tr>
	<tr><td class="shipmentTB6">Invoice Total</td><td class="shipmentTB3" style="background-color:#EEEEFF"><div id="div_bm_total"></div></td></tr>
	<tr><td class="shipmentTB6">Invoice Paid (Total)</td><td class="shipmentTB3" style="background-color:#EEEEFF"><div id="div_pay_total"></div></td></tr>
	<tr><td class="shipmentTB6">Invoice Due (Total)</td><td class="shipmentTB3" style="background-color:#EEEEFF"><div id="div_hp_due"></div></td></tr>
	<tr><td class="shipmentTB6">Schedule</td><td class="shipmentTB3" style="background-color:#EEEEFF"><div id="div_hp_schdule"></div></td></tr>
	<tr><td class="shipmentTB6">Instalment Amount</td><td class="shipmentTB3" style="background-color:#EEEEFF"><div id="div_hp_amount"></div></td></tr>
	<tr><td class="shipmentTB6">Instalment Count</td><td class="shipmentTB3" style="background-color:#EEEEFF"><div id="div_hp_count"></div></td></tr>
	<tr><td class="shipmentTB3" colspan="2" style="background-color:#EEEEFF"><div id="div_out"></div></td></tr>
	</table>
	
<br />	
<hr />
	<div id="div_inv_pay_btn" style="display:none">
	<input type="button" style="width:150px; height:40px" value="Invoice Pay Statement" onclick="invoicePayStatement()" />
	</div>
<br />	
<hr />
<?php if($components=='manager') print '<a href="index.php?components=manager&action=hp_active_list&mismatch" style="text-decoration:none; color:blue; cursor:pointer; font-family:Calibri;">+Show Mismatch List </a>';
	  if(isset($_GET['mismatch'])){
?>
	<table style="font-family:Calibri; font-size:12pt">
	<tr style="background-color:#467898; color:white;" height="30px" width="100%"><td></td><td class="shipmentTB3" colspan="3" ><strong>Payment Date Mismatch Invoice List</strong></td></tr>
	<tr style="background-color:#467898; color:white;" width="100%"><td></td><td class="shipmentTB3" ><strong>Invoice No</strong></td><td class="shipmentTB3" ><strong>Missing Instalment Date</strong></td><td class="shipmentTB3" ><strong>Amount</strong></td></tr>
	<?php 
		for($i=0;$i<sizeof($issue_list1);$i++){
			if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			print '<tr style="background-color:'.$color.'"><td><input type="checkbox" /></td><td class="shipmentTB3" align="center"><a href="#page_top" onclick="getInvoiceData('.$issue_list1[$i].')" style="text-decoration:none; color:blue; cursor:pointer" >'.str_pad($issue_list1[$i], 7, "0", STR_PAD_LEFT).'</a></td><td class="shipmentTB3" align="center">'.$issue_list2[$i].'</td><td class="shipmentTB3" align="right">'.number_format($issue_list3[$i]).'</td></tr>';
		}
	?>
	</table>
<?php } ?>
</td></tr>
</table>	




<?php
	if(isset($_GET['invoice_no'])){
		print '<script>getInvoiceData('.$_GET['invoice_no'].');</script>';
	}

                include_once  'template/footer.php';
?>