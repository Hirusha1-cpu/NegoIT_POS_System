<?php
	include_once  'template/m_header.php';
		if($_GET['type']=='bill'){
                if($bi_type==1 || $bi_type==2){
                	$bill_title='INVOICE';
                	$sub_title='INVOICE NO';
                	$advance='';
                }else if($bi_type==3){
                	$bill_title='INVOICE';
                	$sub_title='REPAIR NO';
                	$advance='Advance';
                }else if($bi_type==4 || $bi_type==5){
                	if($bm_status<3){
                	$bill_title='CUST ORDER';
                	$sub_title='ORDER NO';
                	$advance='Advance';
                	}else{
                	$bill_title='INVOICE';
                	$sub_title='INVOICE NO';
                	$advance='';
                	}
                }
		}
?>
<!-- ------------------------------------------------------------------------------------ -->

<script type="text/javascript">
	function deleteBill(id){
		var check= confirm("Do you really want Delete this Bill?");
	 if (check== true)
		window.location = 'index.php?components=backend&action=delete_bill&id='+id;
	}
	function deleteBill2(id){
		var check= confirm("Do you really want Delete this Bill2?");
	 if (check== true)
		window.location = 'index.php?components=backend&action=delete_bill2&id='+id;
	}
	function deletePayment(id){
		var check= confirm("Do you really want Delete this Payment?");
	 if (check== true)
		window.location = 'index.php?components=backend&action=delete_pay&id='+id;
	}
	function deleteComReport(id){
		var check= confirm("Do you really want Delete this Commission Report?");
	 if (check== true)
		window.location = 'index.php?components=backend&action=delete_commission_report&id='+id;
	}
</script>
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
	<table align="center">
	<tr><td bgcolor="#DDDDDD" style="padding-left:10px; padding-right:10px;">Bill</td><td><form method="post" action="index.php?components=backend&action=delete_search&type=bill"><input type="text" style="width:100px" name="search1" id="search1" placeholder="Invoice Number" /><input type="Submit" value="Search" /></form></td></tr>
	<tr><td bgcolor="#DDDDDD" style="padding-left:10px; padding-right:10px;">Payment</td><td><form method="post" action="index.php?components=backend&action=delete_search&type=pay"><input type="text" style="width:100px" name="search2" id="search2" placeholder="Payment Number" /><input type="Submit" value="Search" /></form></td></tr>
	<tr><td bgcolor="#DDDDDD" style="padding-left:10px; padding-right:10px;">Commission Report</td><td><form method="post" action="index.php?components=backend&action=delete_search&type=commission"><input type="text" style="width:100px" name="search3" id="search3" placeholder="Commission Number" /><input type="Submit" value="Search" /></form></td></tr>
	</table>
	<br>
	<?php
	if($_GET['type']=='bill')   include_once  'components/backend/view/tpl/delete_bill.php';
	if($_GET['type']=='pay')    include_once  'components/backend/view/tpl/delete_pay.php';
	if($_GET['type']=='commission')    include_once  'components/backend/view/tpl/delete_commission.php';
  	?>
</div>
  </div>
</div>
</div>
<hr>
<br />
<?php
                include_once  'template/m_footer.php';
?>
