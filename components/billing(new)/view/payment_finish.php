<?php
                include_once  'template/header.php';
?>
<script type="text/javascript">
	function showHideDetails(){
		var details_key=document.getElementById('details_key').value; 
		if(details_key=='hide'){
		document.getElementById('details_key').value='show';
		document.getElementById('details_link').innerHTML='<a style="cursor:pointer; color:blue" onclick="showHideDetails()"><strong>- Hide Details</strong></a>'; 
		document.getElementById('details_div').style.display='block'; 
		}else if(details_key=='show'){
		document.getElementById('details_key').value='hide';
		document.getElementById('details_link').innerHTML='<a style="cursor:pointer; color:blue" onclick="showHideDetails()"><strong>+ Show Details</strong></a>'; 
		document.getElementById('details_div').style.display='none'; 
		}
	}
	
	function smsResend($sms_id){
	  document.getElementById('div_smsresend').innerHTML=document.getElementById('loading').innerHTML;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
		    var returntext=this.responseText;
		    if(returntext=='done') document.getElementById('div_smsresend').innerHTML='<span style="color:green">Sent</span>'; 
		    else document.getElementById('div_smsresend').innerHTML='<span style="color:red">Error</span>';
	    }
	  };
	  xhttp.open("GET", 'index.php?components=billing&action=sms_resend&smsid='+$sms_id, true);
	  xhttp.send();
	}
</script>
<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<form action="#" method="post" >
<input type="hidden" name="id" value="<?php print $id; ?>" />
<?php if($main_sub_system_id==$sub_system){ ?>
<table align="center">
<tr><td style="font-family:Calibri; font-size:10pt"><input type="hidden" id="details_key" value="hide" /><div id="details_link"><a style="cursor:pointer; color:blue" onclick="showHideDetails()"><strong>+ Show Details</strong></a></div></td><td width="50px"></td><td bgcolor="#467898" style="font-family:Calibri; color:white; padding-left:10px">Payment Invoice Status : <span <?php if($status_out=='Deleted') print 'class="blink"'; ?> style="color:<?php print $status_color; ?>;"><strong><?php print $status_out; ?></strong></span></td><td></td></tr>
<tr><td valign="top">
		<div id="details_div" style="display:none">
		<table align="center" style="font-size:11pt; font-family:Calibri" >
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Sub System</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_sub_system; ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Store</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_store; ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">SMS Status</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><div id="div_smsresend"><table border="0" cellspacing="0"><tr><td><?php print $main_sms; ?> </td><td align="right"><?php if($sms_resend!=0) print '<input type="button" value="Resend" style="height:20px; font-size:10pt" onclick="smsResend('."'$sms_resend'".')" />'; ?></td></tr></table></div></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Paid Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print substr($main_paid_date,0,16); ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Salesman</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print ucfirst($main_paid_by); ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">System User</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print ucfirst($main_sys_user); ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Deleted Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print substr($main_deleted_date,0,16); ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Deleted By</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print ucfirst($main_deleted_by); ?></td></tr>
		</table>
		</div>
</td><td width="50px"></td><td>
<!-- ------------------Item List----------------------- -->
<?php
 if($tm_template==1) print '<iframe id="invoice_iframe" width="480px" height="710px" src="components/billing/view/tpl/payment_print1.php?id='.$_GET['id'].'"></iframe>';
 if($tm_template==2) print '<iframe id="invoice_iframe" width="250px" height="710px" src="components/billing/view/tpl/payment_print2.php?id='.$_GET['id'].'"></iframe>';
 if($tm_template==3) print '<iframe id="invoice_iframe" width="480px" height="710px" src="components/billing/view/tpl/payment_print1.php?id='.$_GET['id'].'"></iframe>';
 if($tm_template==5) print '<iframe id="invoice_iframe" width="480px" height="710px" src="components/billing/view/tpl/payment_print5.php?id='.$_GET['id'].'"></iframe>';
 if($tm_template==7) print '<iframe id="invoice_iframe" width="533px" height="785px" src="components/billing/view/tpl/payment_print7.php?id='.$_GET['id'].'"></iframe>';
?>

</td><td valign="top" align="center">
				<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="print_bill()" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/print.png" alt="icon" /><br />
					Print
				</span></a>
				</div>
<br />
<?php if($py_status==0){
		if($paymentpermission){ ?>
				<div style="background-color:#FF9191; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="deletePayment(<?php print $_GET['id']; ?>)" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/cancel.png" alt="icon" /><br />
					Cancel
				</span></a>
				</div>
<?php } } ?>
</td></tr>
</table>
<?php } ?>
</form>

<?php
                include_once  'template/footer.php';
?>