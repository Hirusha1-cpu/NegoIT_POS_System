<?php
                include_once  'template/header.php';
?>

<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<script type="text/javascript">
	function validateForm1(){
		var case_n=document.getElementById('case_n').value;
		var tracking_id1=document.getElementById('tracking_id1').value;
				
		if(case_n=="2"){
			var tracking_id0=document.getElementById('tracking_id0').value;
			if(tracking_id0==''){
				alert("Please Enter the Tracking Code");
				return false;
			}else{
				document.getElementById('tracking_id1').value=tracking_id0;
				document.getElementById('case_n').value="3";
				document.getElementById('frm1_div1').innerHTML='<input type="number" step="0.10" min="0" id="weight0" placeholder="Weight : KG" style="width:200px; height:50px; font-size:16pt; text-align:center" />';
				document.getElementById("weight0").focus();
				return false;
			}
		}
		
		if(case_n=="3"){
				document.getElementById("form1").action = "index.php?components=order_process&action=apend_courier";
				document.getElementById('weight').value=document.getElementById('weight0').value;
				document.getElementById('frm1_div1').innerHTML=document.getElementById('loading').innerHTML;
				return true;
		}
	}
</script>
<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
	
	<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<table align="center" bgcolor="#E5E5E5"><tr><td colspan="5"><span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span></td></tr></table><br />'; 
	}
	?>
	
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<form action="" id="form1" method="post" style="font-size:12pt" onsubmit="return validateForm1()" >
<input type="hidden" name="order_no" value="<?php print $id; ?>" />
<input type="hidden" id="tracking_id1" name="tracking_id" value="" />
<input type="hidden" id="weight" name="weight" value="" />
<input type="hidden" id="case_n" value="2" />
<table align="center">
	<tr><td><div id="frm1_div1"><input type="text" id="tracking_id0" name="tracking_id0" placeholder="Courier Service BARCODE" style="width:300px; height:50px; font-size:16pt; text-align:center" /></div></td></tr>
</table>
</form>


	<table align="center" style="font-size:12pt" width="900px">
	<tr><td style="background-color:#467898;color :white;"><strong><?php if($bm_status<3) print 'Order No'; else print 'Invoice No'; ?></strong></td><td bgcolor="#EEEEEE"><?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?></td><td></td><td style="background-color:#467898;color :white;"><strong>Salesman</strong></td><td bgcolor="#EEEEEE"><?php print  ucfirst($bi_salesman); ?></td><td rowspan="2">
	<?php if($button=='Packed' && $bi_seen_by==$_COOKIE['user']){ ?><div id="orderprocess"><input type="button" value="<?php print $button; ?>" style="height:50px; width:70px; background-color:#CC5100; font-weight:bold; color:white" onclick="orderProcess()" /></div><?php } ?>
	<input type="button" value="Print" style="height:50px; width:70px; background-color:#007799; font-weight:bold; color:white" onclick="window.location = 'index.php?components=billing&action=finish_bill&id=<?php print $_REQUEST['id']; ?>'" />
	<?php if((isset($_COOKIE['report']) || isset($_COOKIE['manager']))&& $button=='Packed'){
				print '<div id="orderprocess3"><input type="button" value="Unassign" style="height:50px; width:70px; background-color:orange; font-weight:bold; color:white" onclick="orderUnassign('.$bm_type.')" /></div>'; 
		  }
	?>
	</td></tr>
	<tr><td style="background-color:#467898;color :white;"><strong>Order Date</strong></td><td bgcolor="#EEEEEE"><?php print  substr($odr_date,0,16); ?></td><td></td><td style="background-color:#467898;color :white;"><strong>Customer</strong></td><td bgcolor="#EEEEEE"><?php print '<a href="index.php?components=billing&action=cust_details&id='.$cu_id.'&action2=list_one_custodr&id2='.$_REQUEST['id'].'" >'.ucfirst($bi_cust).'</a>'; ?></td></tr>
	<tr><td colspan="5" height="50px"></td><td></td></tr>
	<tr><td colspan="6" align="center">
		<table align="center">
		<tr style="background-color:#C0C0C0"><th></th><th>Item Description</th><th style="padding-left:20px; padding-right:20px">Item Qty</th></tr>
		<?php
		for($i=0;$i<sizeof($odr_bill_id);$i++){
			if($odr_bi_order[$i]==0){
				print '<tr style="background-color:#F0F0F0"><td><input type="checkbox" /></td><td style="padding-left:20px; padding-right:20px">'.$odr_bi_desc[$i].'</td><td style="padding-right:20px" align="right"><input type="text" id="qty_'.$odr_bill_id[$i].'" value="'.$odr_bi_qty[$i].'" style="width:50px; text-align:right;" /></td></tr>';
			}
		}
		?>
		</table>
		
		<?php if($systemid==1 || $systemid==10){ ?>
		<!-- --------------------------Return Item Handling------------------------------- -->
		<hr />

		<table align="center">
		<tr><th style="background-color:#787898;color :white;" colspan="7">Return Items | Packed</th></tr>
		<tr style="background-color:#C0C0C0"><th></th><th style="padding-left:20px; padding-right:20px">Return Date</th><th style="padding-left:20px; padding-right:20px">Item</th><th style="padding-left:20px; padding-right:20px">Return Qty</th><th style="padding-left:20px; padding-right:20px">Salesman</th></tr>
		<?php
		for($i=0;$i<sizeof($rtn2_id);$i++){
			print '<tr style="background-color:#F0F0F0"><td><div id="return1_done_'.$rtn2_id[$i].'"><input type="checkbox" /></div></td><td align="center"><div id="return3_done_'.$rtn2_id[$i].'">'.$rtn2_date[$i].'</div></td><td style="padding-left:20px; padding-right:20px"><div id="return4_done_'.$rtn2_id[$i].'">'.$rtn2_itm_desc[$i].'</div></td><td style="padding-right:20px" align="right"><div id="return5_done_'.$rtn2_id[$i].'">'.$rtn2_qty[$i].'</div></td><td style="padding-right:20px; padding-left:20px;" align="right"><div id="return6_done_'.$rtn2_id[$i].'">'.ucfirst($rtn2_by[$i]).'</div></td></tr>';
		} 
		?>
		</table>
		<?php } ?>
	</td></tr>
	<tr><td colspan="5" height="50px"></td><td></td></tr>
	<tr><td colspan="5">
		<table width="100%">
		<tr style="background-color:#C0C0C0"><td style="padding-left:20px;">Picked By</td><td style="padding-left:20px;">Packed By</td><td style="padding-left:20px;">Shipped By</td><td style="padding-left:20px;">Deliverd By</td></tr>
		<tr style="background-color:#F0F0F0"><td style="padding-left:20px;"><?php print  ucfirst($bi_seen_by); ?></td><td style="padding-left:20px;"><?php print  ucfirst($bi_packed_by); ?></td><td style="padding-left:20px;"><?php print  ucfirst($bi_shipped_by); ?></td><td style="padding-left:20px;"><?php print  ucfirst($bi_deliverd_by); ?></td></tr>
		<tr style="background-color:#F0F0F0"><td style="padding-left:20px;"><?php print  $bi_seen_date.'<br/>'.$bi_seen_time; ?></td><td style="padding-left:20px;"><?php print  $bi_packed_date.'<br/>'.$bi_packed_time; ?></td><td style="padding-left:20px;"><?php print  $bi_shipped_date.'<br/>'.$bi_shipped_time; ?></td><td style="padding-left:20px;"><?php print  $bi_deliverd_date.'<br/>'.$bi_deliverd_time; ?></td></tr>
		</table>
	</td><td></td></tr>
	</table>
<script type="text/javascript">
	document.getElementById("tracking_id0").focus();
</script>

<?php
                include_once  'template/footer.php';
?>