<?php
    include_once  'template/m_header.php';
?>
<script type="text/javascript">
	function ackDelete1($id) {
	  document.getElementById('ack_div1_'+$id).innerHTML=document.getElementById('loading').innerHTML;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    var returntext=this.responseText;
		if(returntext=='done'){
			document.getElementById('ack_div1_'+$id).innerHTML='<span style="color:green">Done</span>';
		}else{
			document.getElementById('ack_div1_'+$id).innerHTML='<span style="color:red">Error</span>';
		}
		}
	  };
	  xhttp.open("GET", 'index.php?components=report&action=ackdeleted1&id='+$id, true);
	  xhttp.send();
	}	
	
	function ackDelete2($id) {
	  document.getElementById('ack_div2_'+$id).innerHTML=document.getElementById('loading').innerHTML;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    var returntext=this.responseText;
		if(returntext=='done'){
			document.getElementById('ack_div2_'+$id).innerHTML='<span style="color:green">Done</span>';
		}else{
			document.getElementById('ack_div2_'+$id).innerHTML='<span style="color:red">Error</span>';
		}
		}
	  };
	  xhttp.open("GET", 'index.php?components=report&action=ackdeleted2&id='+$id, true);
	  xhttp.send();
	}	

	function ackDelete3($invoice_no) {
	  document.getElementById('ack_div3_'+$invoice_no).innerHTML=document.getElementById('loading').innerHTML;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    var returntext=this.responseText;
		if(returntext=='done'){
			document.getElementById('ack_div3_'+$invoice_no).innerHTML='<span style="color:green">Done</span>';
		}else{
			document.getElementById('ack_div3_'+$invoice_no).innerHTML='<span style="color:red">Error</span>';
		}
		}
	  };
	  xhttp.open("GET", 'index.php?components=<?php print $components; ?>&action=ackdeleted3&invoice_no='+$invoice_no, true);
	  xhttp.send();
	}
	
</script>

<!-- ------------------------------------------------------------------------------------ -->
<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
</head>

<div class="w3-container" style="margin-top:75px">
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>'; 
	}
?>	
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
<table>
<tr><td align="center">This report shows last 100 records of deleted "Bill Invoices" and "Payment Invoices"</td></tr>
<tr><td height="1px" bgcolor="silver"></td></tr>
</table>
<br />

	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0">
	<tr><td colspan="7" style="background-color:silver; color:navy; font-family:Calibri; font-size:15pt">&nbsp;&nbsp;&nbsp;<strong>Billing Invoices</strong></td></tr>
	<tr><th width="100px">Invoice No</th><th width="100px">Associated Payment<br>Invoice No</th><th width="110px">Date</th><th>Invoice Price</th><th>Invoice Profit</th><th>Deleted By</th><th>Ack</th></tr>
	<?php
	$inv=0;
		for($i=0;$i<sizeof($bi_inv_no);$i++){
			if($bm_delete_ack[$i]==1) $ackdeleteBtn1='<input type="button" value="Ack" onclick="ackDelete1('.$bi_inv_no[$i].')" />'; else $ackdeleteBtn1='';
			if($bi_asso_pay[$i]=='') $bi_asso_pay1=''; else $bi_asso_pay1=str_pad($bi_asso_pay[$i], 7, "0", STR_PAD_LEFT);
				print '<tr><td align="center"><a href="index.php?components=billing&action=finish_bill&id='.$bi_inv_no[$i].'">'.str_pad($bi_inv_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center"><a href="index.php?components=billing&action=finish_payment&id='.$bi_asso_pay[$i].'">'.$bi_asso_pay1.'</a></td><td align="center" width="50px">'.$bi_date[$i].'</td><td align="right">'.number_format($bi_inv_total[$i]).'&nbsp;&nbsp;</td><td align="right">'.number_format($bi_inv_profit[$i]).'&nbsp;&nbsp;</td><td align="center">'.ucfirst($bi_deleted_by[$i]).'</td><td align="center"><div id="ack_div1_'.$bi_inv_no[$i].'">'.$ackdeleteBtn1.'</div></td></tr>';
		}
	?>	
	</table>
	<br>
	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0">
	<tr><td colspan="5" style="background-color:silver; color:navy; font-family:Calibri; font-size:15pt">&nbsp;&nbsp;&nbsp;<strong>Payment Invoices</strong></td></tr>
	<tr><th width="100px">Invoice No</th><th width="110px">Date</th><th>Invoice Profit</th><th width="150px">Deleted By</th><th>Ack</th></tr>
	<?php
	$inv=0;
		for($i=0;$i<sizeof($py_inv_no);$i++){
			if($py_delete_ack[$i]==1) $ackdeleteBtn2='<input type="button" value="Ack" onclick="ackDelete2('.$py_inv_no[$i].')" />'; else $ackdeleteBtn2='';
				print '<tr><td align="center"><a href="index.php?components=billing&action=finish_payment&id='.$py_inv_no[$i].'">'.str_pad($py_inv_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center" width="50px">'.$py_date[$i].'</td><td align="right">'.number_format($py_amount[$i]).'&nbsp;&nbsp;</td><td align="center">'.ucfirst($py_deleted_by[$i]).'</td><td align="center"><div id="ack_div2_'.$py_inv_no[$i].'">'.$ackdeleteBtn2.'</div></td></tr>';
		}
	?>	
	</table>

	<!---------------- Deleted Return Bills ---------------->
	<br />
	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-family:Calibri">
		<tr>
			<td colspan="4" style="background-color:silver; color:navy; font-family:Calibri; font-size:15pt">&nbsp;&nbsp;&nbsp;			<strong>Return Invoices</strong>
			</td>
		</tr>
		<tr>
			<th width="100px">Invoice No</th>
			<th width="100px">Date</th>
			<th width="150px">Deleted By</th>
			<th>Ack</th>
		</tr>
		<?php
			for($i=0;$i<sizeof($rt_inv_no);$i++){
				if($rt_delete_ack[$i]==1) $ackdeleteBtn3='<input type="button" value="Ack" onclick="ackDelete3('.$rt_inv_no[$i].')" />'; else $ackdeleteBtn3='';
				print '<tr>
							<td align="center">
								<a href="index.php?components=billing&action=finish_return&id='.$rt_inv_no[$i].'">'.str_pad($rt_inv_no[$i], 7, "0", STR_PAD_LEFT).'</a>
							</td>
							<td align="center" width="50px">'.$rt_date[$i].'</td>
							<td align="center">'.ucfirst($rt_deleted_by[$i]).'</td>
							<td align="center"><div id="ack_div3_'.$rt_inv_no[$i].'">'.$ackdeleteBtn3.'</div></td>
						</tr>';
			}
		?>	
	</table>
	<br>

  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>