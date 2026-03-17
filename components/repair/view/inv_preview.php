<?php
                include_once  'template/header.php';
                $paper_size=paper_size(1);
                if($paper_size=='A4'){
                	$page_width=680;
                	$page_height=1040;
                }
                if($paper_size=='A5'){
                	$page_width=480;
                	$page_height=740;
                }
				if($_GET['action']=='finish_dn') $dn='yes'; else $dn='no';
?>

<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

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
</script>
<?php if(isset($_REQUEST['message'])){
			if($_REQUEST['re']=='success') $color='green'; else $color='red';
		print '<table align="center"><tr><td><span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span></td></tr></table>'; 
		}
?>
<input type="hidden" name="id" id="id" value="<?php print $id; ?>" />
<input type="hidden" id="fastprint" value="<?php print $_COOKIE['fastprint']; ?>" />
<table align="center" style="font-family:Calibri; font-size:10pt"><tr><td valign="top">
		<input type="hidden" id="details_key" value="hide" />
		<div id="details_link"><a style="cursor:pointer; color:blue" onclick="showHideDetails()"><strong>+ Show Details</strong></a></div>	
		<div id="details_div" style="display:none">
		<table align="center" style="font-size:11pt; font-family:Calibri" >
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Sub System</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_sub_system; ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Store</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_store; ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Referred Inventory</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_refinv; ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Billed District</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_district; ?></td></tr>
		<?php if($main_quotation!='' && $main_quotation!=0)
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Quotation No</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.str_pad($main_quotation, 7, "0", STR_PAD_LEFT).'</td></tr>';
		?>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Type</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_type; ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">SMS Status</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_sms; ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Invoiced Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print substr($main_billed_date,0,16); ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Invoiced By</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print ucfirst($main_billed_by); ?></td></tr>
		<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">System User</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print ucfirst($main_sys_user); ?></td></tr>
		<?php if($bm_type==3 && $bm_status==5){
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Repaired Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.substr($main_shipped_date,0,16).'</td></tr>';
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Technicient</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.ucfirst($main_shipped_by).'</td></tr>';
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Delivered Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.substr($main_deliverd_date,0,16).'</td></tr>';
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Delivered By</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.ucfirst($main_deliverd_by).'</td></tr>';
		} ?>
		<?php if($bm_type==3 && $bm_status==7){
		if($main_shipped_by=='-') $rejected_by=$main_deliverd_by; else $rejected_by=$main_shipped_by;
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Rejected By</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.ucfirst($rejected_by).'</td></tr>';
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Rejected Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.substr($main_shipped_date,0,16).'</td></tr>';
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Technicient</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.ucfirst($main_shipped_by).'</td></tr>';
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Delivered Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.substr($main_deliverd_date,0,16).'</td></tr>';
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Delivered By</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.ucfirst($main_deliverd_by).'</td></tr>';
		} ?>
		<?php if($bm_status==0){
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Deleted Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.substr($main_deleted_date,0,16).'</td></tr>';
		print '<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Deleted By</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4">'.ucfirst($main_deleted_by).'</td></tr>';
		} ?>
		</table>
		</div>
</td><td width="50px"></td><td>
	<table align="center" style="font-size:11pt" >
	<tr><td bgcolor="#467898" style="font-family:Calibri; color:white; padding-left:10px">Invoice Status : <span <?php if($status_out=='Deleted') print 'class="blink"'; ?> style="color:<?php print $status_color; ?>;"><strong><?php print $status_out; ?></strong></span></td><td></td></tr>
	<tr><td>
	<!-- ------------------Item List----------------------- -->
	<?php
	 if($tm_template==1) print '<iframe id="invoice_iframe" width="'.$page_width.'px" height="'.$page_height.'px" src="components/billing/view/tpl/invoice_print1.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	 if($tm_template==2) print '<iframe id="invoice_iframe" width="250px" height="740px" src="components/billing/view/tpl/invoice_print2.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	 if($tm_template==3) print '<iframe id="invoice_iframe" width="'.$page_width.'px" height="'.$page_height.'px" src="components/billing/view/tpl/invoice_print3.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	 if($tm_template==4) print '<iframe id="invoice_iframe" width="250px" height="740px" src="components/billing/view/tpl/invoice_print4.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	 if($tm_template==5) print '<iframe id="invoice_iframe" width="440px" height="550px" src="components/billing/view/tpl/invoice_print5.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	 if($tm_template==6) print '<iframe id="invoice_iframe" width="300px" height="740px" src="components/billing/view/tpl/invoice_print6.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	 if($tm_template==7) print '<iframe id="invoice_iframe" width="533px" height="775px" src="components/billing/view/tpl/invoice_print7.php?id='.$_GET['id'].'&dn='.$dn.'"></iframe>';
	?>
	
	
	</td><td valign="top" align="center">
					<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
					<a class="shortcut-button" onclick="print_bill('.$_REQUEST['id'].')" href="#">
					<span style="text-decoration:none; font-family:Arial; color:navy;">
						<img src="images/print.png" alt="icon" /><br />
						Print Bill
					</span>
					</a>
					</div>
	<br />
	</td></tr>
	</table>
</td></tr></table>

<?php


                include_once  'template/footer.php';
?>