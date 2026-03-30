<?php
                include_once  'template/header.php';
?>
<script type="text/javascript">
const zeroPad = (num, places) => String(num).padStart(places, '0');

function getHPInvoiceList($cust_id){
    document.getElementById("div_inv").innerHTML=document.getElementById("loading").innerHTML;
    $out_txt='<table width="100%" style="font-family:Calibri; font-size:12pt">';
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var returntext=xmlhttp.responseText;
			$invoice_no=returntext.split('|');
			for($i=0;$i<$invoice_no.length;$i++){
				if(($i%2)==0) $color='#EEEEEE'; else $color='#FAFAFA';
				$out_txt+='<tr style="background-color:'+$color+';"><td class="shipmentTB3" align="center"><a href="index.php?components=hire_purchase&action=home&invoice_no='+$invoice_no[$i]+'#page_top" style="text-decoration:none">'+zeroPad($invoice_no[$i],7)+'</a></td></tr>';
			}
			$out_txt+='</table>';
			document.getElementById("div_inv").innerHTML=$out_txt;
		}
	};

	xmlhttp.open("POST", "index.php?components=hire_purchase&action=hp_get_invoice_list", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('cust_id='+$cust_id);
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
	<table style="font-family:Calibri; font-size:12pt">
	<tr style="background-color:#467898; color:white;"><th colspan="2">Customer Name</th></tr>
	<?php 
	// for($i=0;$i<sizeof($his_cu_id);$i++){
	// 	if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
	// 	print '<tr style="background-color:'.$color.'"><td class="shipmentTB3"><input type="checkbox" /></td><td class="shipmentTB3"><a href="#div_inv" onclick="getHPInvoiceList('.$his_cu_id[$i].')" style="text-decoration:none; color:blue; cursor:pointer" >'.$his_cu_name[$i].'</a></td></tr>';
	// }
	if(isset($his_cu_id) && is_array($his_cu_id) && count($his_cu_id) > 0) {
    for($i=0; $i < count($his_cu_id); $i++) {
        if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
        $customer_name = isset($his_cu_name[$i]) ? $his_cu_name[$i] : 'Unknown';
        print '<tr style="background-color:'.$color.'">
            <td class="shipmentTB3"><input type="checkbox" />\\
            <td class="shipmentTB3">
                <a href="#div_inv" onclick="getHPInvoiceList('.$his_cu_id[$i].')" style="text-decoration:none; color:blue; cursor:pointer">
                    '.$customer_name.'
                </a>
            \\
          </tr>';
    }
} else {
    print '<tr><td colspan="2" align="center">No customers found</td></tr>';
}
	?>
	</table>
</td><td width="100px"></td><td width="300px" valign="top">
	<div id="div_inv"></div>
</td></tr>
</table>	




<?php
                include_once  'template/footer.php';
?>