<?php
	include_once  'template/header.php';
	$paper_size=paper_size(1);
	if($paper_size=='A4'){
		$page_width=680;
		$page_height=1040;
	}
	if($paper_size=='A5'){
		$page_width=523;
		$page_height=765;
	}

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

window.onload = function() {
	document.getElementById("keytxt").focus();
};

</script>

<?php
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
	<script type="text/javascript">
	window.onload = function() {
	  document.getElementById("keytxt").focus();
	};
	</script>

<input type="hidden" name="id" id="id" value="<?php print $id; ?>" />
<input type="hidden" id="details_key" value="hide" />
<table align="center" style="font-family:Calibri"><tr><td valign="top"><div id="details_link"><a style="cursor:pointer; color:blue" onclick="showHideDetails()"><strong>+ Show Details</strong></a></div>
	<div id="details_div" style="display:none">
	<table align="center" style="font-size:11pt; font-family:Calibri" >
	<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Sub System</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_sub_system; ?></td></tr>
	<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Store</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_store; ?></td></tr>
	<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Referred Inventory</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print $main_refinv; ?></td></tr>
	<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Returned Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print substr($main_returned_date,0,16); ?></td></tr>
	<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Returned By</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print ucfirst($main_returned_by); ?></td></tr>
	<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Processing Status</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print ucfirst($main_process); ?></td></tr>
	<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Deleted Date</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print substr($main_deleted_date,0,16); ?></td></tr>
	<tr><td style="background-color:#467898; color:white;" class="shipmentTB4">Deleted By</td><td style="background-color:#DDDDDD; color:maroon;" class="shipmentTB4"><?php print ucfirst($main_deleted_by); ?></td></tr>
	</table>
	</div>
</td><td width="50px"></td><td>
	<table align="center">
	<tr><td bgcolor="#467898" style="font-family:Calibri; color:white; padding-left:10px">Return Invoice Status : <span <?php if($status_out=='Deleted') print 'class="blink"'; ?> style="color:<?php print $status_color; ?>;"><strong><?php print $status_out; ?></strong></span></td><td></td></tr>
	<tr><td>
	<!-- ------------------Item List----------------------- -->
	<?php
	 if($tm_template==1 || $tm_template==3) print '<iframe id="invoice_iframe" width="'.$page_width.'px" height="'.$page_height.'px" src="components/billing/view/tpl/return_print1.php?id='.$_GET['id'].'"></iframe>';
	 if($tm_template==2 || $tm_template==4) print '<iframe id="invoice_iframe" width="250px" height="740px" src="components/billing/view/tpl/return_print2.php?id='.$_GET['id'].'"></iframe>';
	 if($tm_template==5) print '<iframe id="invoice_iframe" width="440px" height="550px" src="components/billing/view/tpl/return_print5.php?id='.$_GET['id'].'"></iframe>';
	 if($tm_template==7) print '<iframe id="invoice_iframe" width="533px" height="775px" src="components/billing/view/tpl/return_print7.php?id='.$_GET['id'].'"></iframe>';
	 if($tm_template==17) print '<iframe id="invoice_iframe" width="250px" height="740px" src="components/billing/view/tpl/return_print17.php?id='.$_GET['id'].'"></iframe>';
	 if($tm_template==18) print '<iframe id="invoice_iframe"  width="'.$page_width.'px" height="740px" src="components/billing/view/tpl/return_print18.php?id='.$_GET['id'].'"></iframe>';
	 ?>


	</td><td valign="top" align="center">
	<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
					<a class="shortcut-button" onclick="print_bill3()" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
						<img src="images/print.png" alt="icon" /><br />
						Print Bill
					</span></a>
	</div>
	<br />
	<?php if(returnPermission($_GET['id'])){ ?>
	<div style="background-color:#FF9191; border:medium; border-color:black; width:80px;">
					<a class="shortcut-button" onclick="deleteReturn(<?php print $_GET['id']; ?>,'billing')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
						<img src="images/cancel.png" alt="icon" /><br />
						Cancel Bill
					</span></a>
	</div>
	<?php } ?>
	<input type="text" id="keytxt" style="width:10px; border:0px" onkeypress="KeyPress2(event);" />
	</td></tr>
	</table>
</td></tr></table>
<?php
                include_once  'template/footer.php';
?>