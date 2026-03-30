<?php
    include_once  'template/header.php';
?>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete2.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

<script type="text/javascript">
	$(function() {
		var availableTags0 = [<?php for ($x=0;$x<sizeof($town_id);$x++){ print '"'.$town_name[$x].'",'; } ?>	];
		$( "#town" ).autocomplete({
			source: availableTags0
		});
	});

 	function setOrder(){
 		var order=document.getElementById('order').value; 
 		if(order=='ASC') document.getElementById('order').value='DESC';
 		if(order=='DESC') document.getElementById('order').value='ASC';
 		setFilter()
  	}
 	
 	function setFilter(){
 		var from_date=document.getElementById('from_date').value; 
 		var to_date=document.getElementById('to_date').value; 
 		var town=document.getElementById('town').value; 
 		var order=document.getElementById('order').value; 
 		document.getElementById('div_submit').innerHTML=document.getElementById('loading').innerHTML;
 		window.location = 'index.php?components=<?php echo  $_REQUEST['components'] ?>&action=by_sale&from_date='+from_date+'&to_date='+to_date+'&town='+town+'&order='+order;
 	}
 	
 	function getCustMore($id){
 	  document.getElementById('div_custmore_'+$id).innerHTML=document.getElementById('loading').innerHTML;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    var returntext=this.responseText;
	    	$values=returntext.split('|');
			document.getElementById('c_name').innerHTML=$values[0];
			document.getElementById('c_mobile').innerHTML=$values[1];
			document.getElementById('c_tel').innerHTML=$values[2];
			document.getElementById('c_st').innerHTML=$values[3];
			document.getElementById('c_sm').innerHTML=$values[4];
			document.getElementById('c_gp').innerHTML=$values[5];
			document.getElementById('c_subsys').innerHTML=$values[6];
			document.getElementById('c_cname').innerHTML=$values[8];
			document.getElementById('c_address').innerHTML=$values[9];
			document.getElementById('c_email').innerHTML=$values[10];
			document.getElementById('c_crlimit').innerHTML=thousands_separators($values[11]);
			document.getElementById('c_crbalance').innerHTML=thousands_separators($values[13]);
			document.getElementById('c_outstanding').innerHTML=thousands_separators($values[14]);
			document.getElementById('c_1ysale').innerHTML=thousands_separators($values[15]);
			document.getElementById('c_linvoice').innerHTML=$values[17].padStart(7, '0')+'&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#3366CC">'+$values[16]+'</span>';
			document.getElementById('c_lpayment').innerHTML=$values[19].padStart(7, '0')+'&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#3366CC">'+$values[18]+'</span>';
			document.getElementById('c_retchq').innerHTML=$values[20];
			document.getElementById('c_poschq').innerHTML=$values[21];
			document.getElementById('c_depchq').innerHTML=$values[22];
			
			if($values[7]==0 ){
				document.getElementById('c_mcust').innerHTML='';
			}else{
				document.getElementById('c_mcust').innerHTML='<span style="color:#3366CC">YES</span>&nbsp;&nbsp;&nbsp;<input type="button" value="Customer Report" onclick="window.open(\'index.php?components=marketing&action=cust_sale&customer_id='+$values[7]+'&datefrom='+$values[23]+'&dateto='+$values[24]+'\',\'_blank\')" />';
			}

			if($values[12]=='no'){
				document.getElementById('c_gps').innerHTML='';
			}else{
				document.getElementById('c_gps').innerHTML='<a href="https://maps.google.com/?q='+$values[12]+'" target="_blank">Open on Map</a>';
			}
 	  		document.getElementById('c_custreport').innerHTML='<input type="button" value="Customer Report" onclick="window.open(\'index.php?components=<?php echo  $_REQUEST['components'] ?>&action=ust_sale&customer_id='+$id+'&datefrom='+$values[23]+'&dateto='+$values[24]+'\',\'_blank\')" />';
 	  		document.getElementById('c_srep2').innerHTML='<input type="button" value="Sales Report2" onclick="window.open(\'index.php?components=<?php echo  $_REQUEST['components'] ?>&action=sales_report2&customer_id='+$id+'&selection=customer&category=all&datefrom='+$values[23]+'&dateto='+$values[24]+'\',\'_blank\')" />';
 	  		document.getElementById('c_crsms').innerHTML='<input type="button" value="Outstanding SMS" style="background-color:maroon; color:white;" onclick="outstandingSMS(\''+$id+'\',\''+$values[14]+'\')" />';
 	  		document.getElementById('div_custmore_'+$id).innerHTML='<input type="button" value="Get" onclick="getCustMore('+$id+')" />';
	    }
	  };
	  xhttp.open("GET", 'index.php?components=<?php echo  $_REQUEST['components'] ?>&action=get_cust_more&id='+$id, true);
	  xhttp.send();
 	}
 	
 	function outstandingSMS($id,$outstanding){
		var check= confirm("Do you want to Dispatch Outstanding SMS ?");
		if(check==true){
	 	  document.getElementById('c_crsms').innerHTML=document.getElementById('loading').innerHTML;
		  var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
		    var returntext=this.responseText;
		    	if(returntext=='SMS Sent'){
		 	  		document.getElementById('c_crsms').innerHTML='<span style="color:green">SMS Sent</span>';
		 	  	}else{
		 	  		document.getElementById('c_crsms').innerHTML='<span style="color:red">Error</span>';
		 	  	}
		    }
		  };
		  xhttp.open("GET", 'index.php?components=<?php echo  $_REQUEST['components'] ?>&action=outstanding_sms&cust='+$id+'&outstanding='+$outstanding, true);
		  xhttp.send();
		}
 	}
 	
</script>
<!-- -------------------------------------------------------------------------------------------------------------------- -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px;" /></div>
<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	}
?></td></tr></table>

<input type="hidden" id="order" value="<?php print $order; ?>" />

<div id="div_hid1" style="display:none"></div>
<table align="center" style="font-family:Calibri; font-size:12pt"><tr><td>
	<div style="background-color:#EEEEEE; border-radius:10px">
		<table><tr height="50px">
		<td width="20px"></td><td>Town</td><td>
			<input type="text" style="width:200px" id="town" value="<?php print $set_town; ?>" /> 
		</td><td width="20px"></td>
		<td>From:</td><td>
			<input type="date" style="width:150px" id="from_date" value="<?php print $from_date; ?>" /> 
		</td><td width="20px"></td>
		<td>To:</td><td>
			<input type="date" style="width:150px" id="to_date" value="<?php print $to_date; ?>" /> 
		</td><td width="20px"></td>
		<td>
			<div id="div_submit"><input type="button" value="Submit" onclick="setFilter()" style="width:60px; height:35px;" /></div>
		</td><td width="20px"></td>
		</tr></table>
	</div>
</td></tr></table>

<table align="center" style="font-family:Calibri; font-size:12pt">
<tr><td valign="top">
	<table>
	<tr style="background-color:#467898; color:white; padding-left:20px; padding-right:20px;"><th colspan="2">Customer</th><th>Mobile</th><th>Shop Tel</th><th>Credit Limit</th><th>
		<table>
			<tr><td>Total Sale</td><td rowspan="2">
			<?php
				if($order=='ASC') print '<a onclick="setOrder()"><img src="images/arrow_up_red.png" style="height:30px;" /></a>';
				else print '<a onclick="setOrder()"><img src="images/arrow_down_red.png" style="height:30px;" /></a>';
			?>
			</td></tr><tr><td style="font-size:6pt">for the Duration</td></tr>
		</table>
	</th><th>More Details</th></tr>
	<?php
		for($i=0;$i<sizeof($cu_id);$i++){
			if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			print '<tr style="background-color:'.$color.'"><td><input type="checkbox" /></td><td class="shipmentTB4" style="color:blue">'.$cu_name[$i].'</td><td align="center" class="shipmentTB3"><a href="tel:'.$cu_mobile[$i].'" class="taptocall">'.$cu_mobile[$i].'</a></td><td align="center" class="shipmentTB3"><a href="tel:'.$cu_shop_tel[$i].'" class="taptocall">'.$cu_shop_tel[$i].'</a></td><td align="right" class="shipmentTB3">'.number_format($cu_credit_limit[$i]).'</td><td align="right" class="shipmentTB3">'.number_format($bm_total[$i]).'</td><td align="center" class="shipmentTB3"><div id="div_custmore_'.$cu_id[$i].'"><input type="button" value="Get" onclick="getCustMore('.$cu_id[$i].')" /></div></td></tr>';
		}
	?>
	</table>
</td><td valign="top" width="400px" bgcolor="#F2F9FF" class="shipmentTB3">

	<table border="0" cellspacing="0">
	<tr bgcolor="#E2E9EF"><td class="shipmentTB3" colspan="3" style="color:#3366CC; font-size:14pt"><strong><div id="c_name" ></div></strong></td></tr>
	<tr><td class="shipmentTB3"><strong>Mobile</strong></td><td>: </td><td class="shipmentTB3"><div id="c_mobile" ></div></td></tr>
	<tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Shop Tel</strong></td><td>: </td><td class="shipmentTB3"><div id="c_tel" ></div></td></tr>
	<tr><td class="shipmentTB3"><strong>Shop</strong></td><td>: </td><td class="shipmentTB3"><div id="c_st" ></div></td></tr>
	<tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Salesman</strong></td><td>: </td><td class="shipmentTB3"><div id="c_sm" ></div></td></tr>
	<tr><td class="shipmentTB3"><strong>Group</strong></td><td>: </td><td class="shipmentTB3"><div id="c_gp" ></div></td></tr>
	<tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Sub System</strong></td><td>: </td><td class="shipmentTB3"><div id="c_subsys" ></div></td></tr>
	<tr><td class="shipmentTB3"><strong>Master Cust</strong></td><td>: </td><td class="shipmentTB3"><div id="c_mcust" ></div></td></tr>
	<tr><td class="shipmentTB3" colspan="3"><hr /></td></tr>
	<tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Customer Name</strong></td><td>: </td><td class="shipmentTB3"><div id="c_cname" ></div></td></tr>
	<tr><td class="shipmentTB3"><strong>Shop Address</strong></td><td>: </td><td class="shipmentTB3"><div id="c_address" ></div></td></tr>
	<tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Email</strong></td><td>: </td><td class="shipmentTB3"><div id="c_email" ></div></td></tr>
	<tr><td class="shipmentTB3"><strong>GPS</strong></td><td>: </td><td class="shipmentTB3"><div id="c_gps" ></div></td></tr>
	<tr><td class="shipmentTB3" colspan="3"><hr /></td></tr>
	<tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Credit Limit</strong></td><td>: </td><td class="shipmentTB3" align="right"><div id="c_crlimit" ></div></td></tr>
	<tr><td class="shipmentTB3"><strong>CR Limit Balance</strong></td><td>: </td><td class="shipmentTB3" align="right"><div id="c_crbalance" ></div></td></tr>
	<tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Outstanding</strong></td><td>: </td><td class="shipmentTB3" align="right"><div id="c_outstanding" ></div></td></tr>
	<tr><td class="shipmentTB3"><strong>Last 1Year Sale</strong></td><td>: </td><td class="shipmentTB3" align="right"><div id="c_1ysale" ></div></td></tr>
	<tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Last Invoice</strong></td><td>: </td><td class="shipmentTB3"><div id="c_linvoice" ></div></td></tr>
	<tr><td class="shipmentTB3"><strong>Last Payment</strong></td><td>: </td><td class="shipmentTB3"><div id="c_lpayment" ></div></td></tr>
	<tr><td class="shipmentTB3" colspan="3"><hr /></td></tr>
	<tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Returned Chques</strong></td><td>: </td><td class="shipmentTB3" style="color:red; font-weight:bold;"><div id="c_retchq" ></div></td></tr>
	<tr><td class="shipmentTB3"><strong>Postponed Chques</strong></td><td>: </td><td class="shipmentTB3" style="color:red; font-weight:bold;"><div id="c_poschq" ></div></td></tr>
	<tr bgcolor="#E2E9EF"><td class="shipmentTB3"><strong>Deposited Chques</strong></td><td>: </td><td class="shipmentTB3" style="color:blue; font-weight:bold;"><div id="c_depchq" ></div></td></tr>
	<tr><td colspan="3">
		<table width="100%"><tr>
			<td align="left"><div id="c_custreport" ></div></td>
			<td align="center"><div id="c_srep2" ></div></td>
			<td align="right"><div id="c_crsms" ></div></td>
		</tr></table>
	</td></tr>
	</table>
	<br />
	<br />
	<div id="map" style="width:100%; height:500px"></div>
</tr>
</table>	

<?php
    include_once  'template/footer.php';
?>