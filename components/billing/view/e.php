<?php
                include_once  'template/header.php';
                $paper_size=paper_size(1);
                if($paper_size=='A4') $apend_limit=30;
                if($paper_size=='A5') $apend_limit=16;
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
	<script type="text/javascript">
<?php  if(isset($_COOKIE['district'])){ ?>
	$(function() {
		var availableTags0 = [<?php for ($x=0;$x<sizeof($cust_mobile);$x++){ print '"'.$cust_mobile[$x].'",'; } ?>	];
		$( "#mob" ).autocomplete({
			source: availableTags0
		});
		var availableTags1 = [<?php for ($x=0;$x<sizeof($code);$x++){ print '"'.$code[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
		var availableTags2 = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		$( "#tags2" ).autocomplete({
			source: availableTags2
		});
		var availableTags3 = [<?php for ($x=0;$x<sizeof($cust_name);$x++){ print '"'.$cust_name[$x].'",'; } ?>	];
		$( "#tags3" ).autocomplete({
			source: availableTags3
		});
		var availableTags4 = [<?php for ($x=0;$x<sizeof($unic_item_list);$x++){ print '"'.$unic_item_list[$x].'",'; } ?>	];
		$( "#tags4" ).autocomplete({
			source: availableTags4
		});
		$( "#tags5" ).autocomplete({ source: availableTags4	});
		$( "#tags6" ).autocomplete({ source: availableTags4	});
		$( "#tags7" ).autocomplete({ source: availableTags4	});
		$( "#tags8" ).autocomplete({ source: availableTags4	});
		$( "#tags9" ).autocomplete({ source: availableTags4	});
		$( "#tags10" ).autocomplete({ source: availableTags4 });
		$( "#tags11" ).autocomplete({ source: availableTags4 });
		$( "#tags12" ).autocomplete({ source: availableTags4 });
		$( "#tags13" ).autocomplete({ source: availableTags4 });
	});
	
	function selectCust(){
		var custid_arr = [<?php for ($x=0;$x<sizeof($cust_id);$x++){ print '"'.$cust_id[$x].'",'; } ?>	];
		var custname_arr = [<?php for ($x=0;$x<sizeof($cust_name);$x++){ print '"'.$cust_name[$x].'",'; } ?>	];
		var mobile_arr = [<?php for ($x=0;$x<sizeof($cust_mobile);$x++){ print '"'.$cust_mobile[$x].'",'; } ?>	];
		var custname=document.getElementById('tags3').value;
		var mobile=document.getElementById('mob').value;
		if((custname=='')&&(mobile!=0)){
			var a=mobile_arr.indexOf(mobile);
			document.getElementById('tags3').value=custname_arr[a];
			document.getElementById('cust_id').value=custid_arr[a];
		}else{
			var a=custname_arr.indexOf(custname);
			document.getElementById('cust_id').value=custid_arr[a];
		}
	}
	
	function getPrice(){
		var itemid = [<?php for ($x=0;$x<sizeof($id);$x++){ print '"'.$id[$x].'",'; } ?>	];
		var code = [<?php for ($x=0;$x<sizeof($code);$x++){ print '"'.$code[$x].'",'; } ?>	];		
		var description = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		var wprice = [<?php for ($x=0;$x<sizeof($w_price);$x++){ print '"'.$w_price[$x].'",'; } ?>	];
		var rprice = [<?php for ($x=0;$x<sizeof($r_price);$x++){ print '"'.$r_price[$x].'",'; } ?>	];
		var qty = [<?php for ($x=0;$x<sizeof($qty);$x++){ print '"'.$qty[$x].'",'; } ?>	];		
		var drawer = [<?php for ($x=0;$x<sizeof($drawer);$x++){ print '"'.$drawer[$x].'",'; } ?>	];	
		var ttitm = [<?php for ($x=0;$x<sizeof($tt_item);$x++){ print '"'.$tt_item[$x].'",'; } ?>	];	
		var ttqty = [<?php for ($x=0;$x<sizeof($tt_qty);$x++){ print '"'.$tt_qty[$x].'",'; } ?>	];	
		var unic = [<?php for ($x=0;$x<sizeof($unic);$x++){ print '"'.$unic[$x].'",'; } ?>	];	
		var pr_sr = [<?php for ($x=0;$x<sizeof($pr_sr);$x++){ print '"'.$pr_sr[$x].'",'; } ?>	];	
		var itemcode=document.getElementById('tags1').value;
		var itemdesc=document.getElementById('tags2').value;
		var itm_tmp=document.getElementById('itm_tmp').value;
		var invoice_no=document.getElementById('id').value;
		var invoice_cust=document.getElementById('cust').value;
		var unic_list_size=document.getElementById('unic_list_size').value;

		if(itemcode!=''){
			var a=code.indexOf(itemcode);
			var b=ttitm.indexOf(itemcode);
			document.getElementById('itemid').value=itemid[a];
			document.getElementById('tags2').value=description[a];
			<?php if($_COOKIE['direct_mkt']==1){ ?>	
				document.getElementById('priceshow').innerHTML='<select name="price"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option></select>';
			<?php }else{ ?>
				<?php if($_COOKIE['retail']==0){ ?>		
				document.getElementById('priceshow').innerHTML='<select name="price"><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option></select>';
				<?php } ?>
				<?php if($_COOKIE['retail']==1){ ?>		
				document.getElementById('priceshow').innerHTML='<input type="hidden" name="price" value="'+rprice[a]+'" /><select disabled="disabled"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option></select>';
				document.getElementById('price_div').innerHTML='<select name="price"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option></select>';
				<?php }
			} ?>
			if(pr_sr[a]==2){
				document.getElementById('priceshow').innerHTML='<input type="text" name="price" value="'+wprice[a]+'" />';
				document.getElementById('com').innerHTML='Comment';
				document.getElementById('it_drawer').innerHTML='<textarea name="comment" ></textarea>';
			}else{
				document.getElementById('com').innerHTML='Drawer No<input type="hidden" name="comment" id="comment" value="" />';
				document.getElementById('it_drawer').innerHTML=drawer[a];
			}
			document.getElementById('av_qty').innerHTML=qty[a];
			document.getElementById('tt_qty').innerHTML='';
			if(b>-1){
				document.getElementById('av_qty').innerHTML='Old Stock - '+qty[a];
				document.getElementById('tt_qty').innerHTML='New Stock - '+ttqty[b];
			}
			if((unic[a]==1)&&(itemcode!=itm_tmp)){
				document.getElementById('addtobill').innerHTML='';
				window.location = 'index.php?components=billing&action=home&id='+invoice_no+'&s=1&cust='+invoice_cust+'&itemid='+itemid[a]+'&unic=yes&cashback='+$cashback;
			}
			if((unic[a]==0)&&(unic_list_size>0)){
				document.getElementById('addtobill').innerHTML='';
				window.location = 'index.php?components=billing&action=home&id='+invoice_no+'&s=1&cust='+invoice_cust+'&itemid='+itemid[a]+'&unic=no';
			}
		}else if(itemdesc!=''){
			var a=description.indexOf(itemdesc);
			document.getElementById('itemid').value=itemid[a];			
			document.getElementById('tags1').value=code[a];
			<?php if($_COOKIE['direct_mkt']==1){ ?>	
				document.getElementById('priceshow').innerHTML='<select name="price"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option></select>';
			<?php }else{ ?>
				<?php if($_COOKIE['retail']==0){ ?>		
				document.getElementById('priceshow').innerHTML='<select name="price"><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option></select>';
				<?php } ?>
				<?php if($_COOKIE['retail']==1){ ?>		
				document.getElementById('priceshow').innerHTML='<input type="hidden" name="price" value="'+rprice[a]+'" /><select disabled="disabled"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option></select>';
				document.getElementById('price_div').innerHTML='<select name="price"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option></select>';
				<?php } 
			} ?>
			if(pr_sr[a]==2){
				document.getElementById('priceshow').innerHTML='<input type="text" name="price" value="'+wprice[a]+'" />';
				document.getElementById('com').innerHTML='Comment';
				document.getElementById('it_drawer').innerHTML='<textarea name="comment" ></textarea>';
			}else{
				document.getElementById('com').innerHTML='Drawer No<input type="hidden" name="comment" id="comment" value="" />';
				document.getElementById('it_drawer').innerHTML=drawer[a];
			}
			document.getElementById('av_qty').innerHTML=qty[a];		
			document.getElementById('tt_qty').innerHTML='';
			var itemcode=document.getElementById('tags1').value;
			var b=ttitm.indexOf(itemcode);
			if(b>-1){
				document.getElementById('av_qty').innerHTML='Old Stock - '+qty[a];
				document.getElementById('tt_qty').innerHTML='New Stock - '+ttqty[b];
			}
			if((unic[a]==1)&&(itemcode!=itm_tmp)){
				document.getElementById('addtobill').innerHTML='';
				window.location = 'index.php?components=billing&action=home&id='+invoice_no+'&s=1&cust='+invoice_cust+'&itemid='+itemid[a]+'&unic=yes&cashback='+$cashback;
			}
			if((unic[a]==0)&&(unic_list_size>0)){
				document.getElementById('addtobill').innerHTML='';
				window.location = 'index.php?components=billing&action=home&id='+invoice_no+'&s=1&cust='+invoice_cust+'&itemid='+itemid[a]+'&unic=no';
			}
		}
	}
	
<?php } ?>
	
	function payment(){
		document.getElementById('payment').innerHTML='';
		if(document.getElementById('fastprint').value=='on') 
		printdiv('print2','printheader');
		window.location = 'index.php?components=billing&action=pay_bill&id=<?php if(isset($_GET['id'])) print $_GET['id']; ?>'
	}
	
<?php if(isset($_GET['cust'])){ ?>
	window.onload = function() {
	  document.getElementById("tags1").focus();
	};	
<?php }else{ ?>
	window.onload = function() {
	  document.getElementById("tags3").focus();
	};	
<?php } ?>

	//-----------------------------Discount Check-----------------------------//
	function loadDiscount($cust) {
	  var $itemid=document.getElementById('itemid').value;
	  var $system_tmp=document.getElementById('system_tmp').value;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    var returntext=this.responseText;
	    var n=returntext.indexOf("|");
	    var m = returntext.returntext;
	    var dis=returntext.substring(n+1, m);
	    if($system_tmp=='yes') $last_discount='<br />Last Dis: ' +dis+'%'; else $last_discount='';
	     document.getElementById("div_discount").innerHTML = returntext.substring(0, n)+$last_discount;
	     document.getElementById("discount").value = dis;
	    }
	  };
	  xhttp.open("GET", 'index.php?components=billing&action=get_discount&itemid='+$itemid+'&cust='+$cust, true);
	  xhttp.send();
	}	
	
	//-----------------------------Authorize Wholesale-----------------------------//
	function authorize(){
	  var $invoice_no=document.getElementById('id').value;
	  var $code=document.getElementById('code').value;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    var returntext=this.responseText;
	    if(returntext==$code){
	    	document.getElementById('auth_result').innerHTML='';
	   		document.getElementById('priceshow').innerHTML=document.getElementById('price_div').innerHTML;
	    }else{
	    	document.getElementById('auth_result').innerHTML='Invalid Code';
	    }
	    }
	  };
	  xhttp.open("GET", 'index.php?components=billing&action=get_authorize&invoice_no='+$invoice_no, true);
	  xhttp.send();
	}
	</script>
	
<style type="text/css">
select.selected{
    color: gray;
}
</style>
<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
<div id="print2" style="display:none" >
			<table width="210px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >
		<?php
			 for($i=0;$i<sizeof($bill_id);$i++){
			 print '<tr><td colspan="4">'.$bi_desc[$i].'</td></tr>';
			 print '<tr><td>'.$bi_code[$i].'</td><td align="right">'.$bi_price[$i].'</td><td align="right">'.$bi_qty[$i].'</td><td align="right">'.number_format($bi_qty[$i]*$bi_price[$i]).'</td></tr>';
			 print '<tr><td colspan="4">&nbsp;</td></tr>';;
			}
			print '	<tr><td colspan="4"><hr></td></tr>';
			print '<tr style="font-size:8pt;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; ">Total Amount</td><td align="right">'.number_format($total).'&nbsp;&nbsp;</td></tr>';	
?>			</table>
</div>
<?php if($systemid==13) $val='yes'; else $val='no';
print '<input type="hidden" id="system_tmp" value="'.$val.'" />';
?>
<table align="center">
<tr><td valign="top">
<table width="100%"><tr><td><h1 style="color:orange">Customer Order</h1></td>
<td align="right">
<select name="district" id="district" onchange="setDistrict2()">
	<option>-SELECT-</option>
<?php for($i=0;$i<sizeof($district_id);$i++){
		if($current_district==$district_id[$i]){
			$select='selected="selected"'; 
			$style='style="color:red; font-weight:bold;"';
		}else{
			$select='';
			$style='';
		}
		print '<option '.$select.' '.$style.'  value="'.$district_id[$i].'">'.$district_name[$i].'</option>';
} ?>
	
</select>
</td></tr></table>
<?php if($current_district!=''){ 
 if(isset($_REQUEST['id'])) print '<form id="billingForm" action="index.php?components=billing&action=apend_corder" onsubmit="return validateBill()" method="post" >';
	else print '<form id="billingForm" action="index.php?components=billing&action=new_bill&next_action=cust_order" method="post" >';	
?>
<input type="hidden" name="cust_id" id="cust_id" value="" />
<input type="hidden" name="id" id="id" value="<?php print $id; ?>" />
<?php if(isset($_GET['cust'])) print '<input type="hidden" id="cust" value="'.$_GET['cust'].'" />'; ?>
<input type="hidden" id="fastprint" value="<?php print $_COOKIE['fastprint']; ?>" />
<input type="hidden" id="billitemcount" value="<?php print sizeof($bill_id); ?>" />
<input type="hidden" id="itm_tmp" value="<?php if($unic_item_code!='') print $unic_item_code; ?>" />
<input type="hidden" id="qty" name="qty" value="" />
<input type="hidden" id="unic_list_size" value="<?php print sizeof($unic_item_list); ?>" />
<div id="price_div" style="display:none" ></div>
<?php if(sizeof($unic_item_list)==0){ ?>
<input type="hidden" name="unic_item" id="tags4" value="0" />
<?php }else{ ?>
<input type="hidden" name="unic_item" value="1" />
<?php } ?>
	<table align="center" bgcolor="#E5E5E5">
	<tr><td colspan="5"><?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'; 
	}
	?><br /></td></tr>
	
	<?php
		if(isset($_GET['cust'])){
			$cid=array_search($_GET['cust'],$cust_id);
			print '<tr><td width="50px"></td><td style="font-size:12pt">Customer</td><td colspan="2">';
			print '<span style="font-size:12pt">'.$cust_name[$cid].'</span>';
			print '<input type="hidden" name="cust" id="cust" value="'.$_GET['cust'].'" />';
			print '</td><td width="50px"></td></tr>';
		}else{
			print '<tr><td width="50px"></td><td style="font-size:12pt">Customer</td><td colspan="2"><input type="text" name="cust" id="tags3" /></td><td width="50px"></td></tr>'; 
			if($_COOKIE['retail']==1)
				print '<tr><td width="50px"></td><td style="font-size:12pt">Mobile</td><td colspan="2"><input type="text" name="mob" id="mob" /></td><td width="50px"></td></tr>'; 
			else
				print '<tr><td colspan="4"><input type="hidden" name="mob" id="mob" value="0" /></td><td width="50px"></td></tr>'; 
		?>
			
	<?php } ?>
				
	<tr><td width="50px"></td><td style="font-size:12pt"></td><td colspan="2" style="font-size:12pt; color:maroon">
	<?php
		if(isset($_GET['cust'])){
			print 'NIC &nbsp;: '.$cust_nic[$cid].'<br />';
			print 'Mob : '.$cust_mobile[$cid].'<br />';
			if($systemid==1) print 'Ref &nbsp;: '.ucfirst($cust_asso_sman[$cid]);
 } ?>
	</td><td width="50px"></td></tr>
	<?php if(!isset($_REQUEST['id'])) { ?>
	<tr><td width="50px"></td><td style="font-size:12pt"></td><td colspan="2">  
		<input type="hidden" name="salesman" id="salesman" value="<?php print $_COOKIE['user_id']; ?>" />
		<input type="submit" value="Submit" style="width:100px; height:50px;" onclick="selectCust()" />
	<br /><br /></td><td width="50px"></td></tr>
	<?php }else{ ?>
	<tr><td width="50px"></td><td style="font-size:12pt">Item Code</td><td colspan="2"><input type="text" name="code" id="tags1" value="<?php if($unic_item_code!='') print $unic_item_code; ?>" /></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td style="font-size:12pt">Item Description</td><td colspan="2"><input type="text" name="description" id="tags2" onchange="document.getElementById('tags1').value=''" /></td><td width="50px"></td></tr>
	<tr><td></td><td style="font-size:12pt">Quantity</td><td><?php if($is_unic_item==0){ ?><input type="number" name="qty1" id="qty1" onfocus="getPrice()"  /><?php }else{ print '<input type="button" value="Refresh" onclick="getPrice()" /><input type="hidden" name="qty1" id="qty1" value="1"  />'; } ?></td><td><div style="font-size:10pt;" id="av_qty" align="right"></div><div style="font-size:10pt; color:#CC0000" id="tt_qty" align="right"></div></td><td></td></tr>
	<?php 
		$k=0;
		if(sizeof($unic_item_list)>0){
			if($_GET['cashback']==1){
				$k++;
				print '<tr><td></td><td style="font-size:12pt">Unic Item</td><td><input type="text" name="unic_item1" id="tags4" onfocus="getPrice()" /></td><td></td><td></td></tr>';
				print '<tr><td colspan="5">';
				for($i=2;$i<=10;$i++){
					print '<input type="hidden" name="unic_item'.$i.'" id="tags'.($i+3).'" value="" />';
				}
				print '</td></tr>';
			}else{
				for($i=1;$i<=10;$i++){
					if($i<=$unic_qty){
						$k++;
						if($i==1) $trigger='onfocus="getPrice()"'; else $trigger='';
						print '<tr><td></td><td style="font-size:12pt">Unic Item'.$i.'</td><td><input type="text" name="unic_item'.$i.'" id="tags'.($i+3).'" '.$trigger.' /></td><td></td><td></td></tr>';
			 		}else{
			 			print '<input type="hidden" name="unic_item'.$i.'" id="tags'.($i+3).'" />';
			 		}
			 	}
		 	}
		 	print '<input type="hidden" id="uitem_limit" value="'.$k.'" />';	
	} ?>
	<tr><td></td><td style="font-size:12pt">Unit Price</td><td colspan="2">
	<div id="priceshow"></div>
	<?php if(($_COOKIE['direct_mkt']==0)&&($_COOKIE['retail']==1)){ ?>
		<table><tr><td>
		<input type="text" id="code" placeholder="Code" style="width:50px" />
		<input type="button" value="authorize" onclick="authorize()" />
		</td><td>
		<div id="auth_result" style="color:red; font-size:11pt"></div>
		</td></tr></table>
	<?php } ?>
	<input type="hidden" name="itemid" id="itemid" />
	<input type="hidden" name="type" id="type" value="1" />
	<input type="hidden" name="salesman" id="salesman" value="<?php print $_COOKIE['user_id']; ?>" />
	</td><td></td></tr>
	<tr><td width="50px"></td><td style="font-size:12pt">Unit Discount</td><td colspan="2"><table width="100%"><tr><td><input type="number" name="discount" id="discount" value="0" style="width:50px" onclick="this.value=''" /><input type="button" value="Check" onclick="loadDiscount('<?php print $_GET['cust']; ?>')" /></td><td><div style="font-size:10pt; color:red" id="div_discount" align="right"></div></td></tr></table></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td style="font-size:12pt"><div id="com" >Drawer No<input type="hidden" name="comment" id="comment" value="" /></div></td><td colspan="2"><div style="font-size:12pt" id="it_drawer"></div></td><td width="50px"></td></tr>
	<tr><td colspan="2" align="right"><br /><div id="addtobill"><?php print '<input type="submit" value="Add to Bill" style="width:100px; height:30px" onclick="cashBack()" />'; ?></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	<?php if(sizeof($bill_id)>0) { ?>
		</td><td colspan="3" align="left"><div id="payment"><input type="Button" value="Payment" style="width:100px; height:70px" onclick="payment()" /></div>
	<?php } ?>
<!--	<input type="Button" value="Finalyze" style="width:100px; height:70px" onclick="window.location = 'index.php?components=billing&action=finish_bill&id=<?php if(isset($_GET['id'])) print $_GET['id']; ?>'" /> -->
	<br /><br /></td></tr>
	<?php } ?>
	</table>
	</form>
<?php  } ?>
<br />
<table align="center" style="font-size:12pt" width="100%">
<tr style="height:35px"><th bgcolor="#C5C5C5" >Up to 30+</th><th bgcolor="#C5C5C5" >Up to 14+</th><th bgcolor="#C5C5C5" >Up to 7+</th><th bgcolor="#C5C5C5" title="Calculation of 'Up to Now'&#13;Invoice Total - Cash Payments - All Chque Payments" >Up to Now</th></tr>
<tr><td bgcolor="#E5E5E5" align="right" ><?php print number_format($balance30); ?></td><td bgcolor="#E5E5E5" align="right" ><?php print number_format($balance14); ?></td><td bgcolor="#E5E5E5" align="right" ><?php print number_format($balance7); ?></td><td bgcolor="#E5E5E5" align="right" ><?php print number_format($balance0); ?></td></tr>
<tr><td colspan="4" height="5px"></td></tr>
<tr><td colspan="3" bgcolor="#E5E5E5" align="right" style="padding-right:10px" >Chque to be Credited</td><td bgcolor="#E5E5E5" align="right" ><?php print number_format($pending_chque); ?></td></tr>
<tr><td colspan="3" bgcolor="#E5E5E5" align="right" style="padding-right:10px" title="Calculation of Remaining Credit Limit&#13;Customer Credit Limit - Invoice Total + Cash Payments + Deposited Cheque Payments">Remaining Credit Limit</td><td bgcolor="#E5E5E5" align="right" ><?php print number_format($remaining_cr_limit); ?></td></tr>
</table>
</td><td width="50px"></td><td valign="top">
<!-- ------------------Item List----------------------- -->
<?php if(isset($_REQUEST['id'])) { ?>
<span style="font-weight:bold; font-size:12pt;" >Invoice No: &nbsp;&nbsp;&nbsp;<?php print str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?></span>
<br /><br /><br />
	<table align="center" bgcolor="#E5E5E5" height="100%">
<?php
	for($i=0;$i<sizeof($bill_id);$i++){
		$new_description[]=$bi_desc2[$i];
		$counts=array_count_values($new_description);
		$count=$counts[$bi_desc2[$i]];
		$tc=array_search($bi_desc2[$i],$dups);
		if($tc>-1){ 
			
			if($dups_count[$tc]==$count) $allow_remove=true; else $allow_remove=false; 
		}else $allow_remove=true;
		if($bi_no_update[$i]==0) $update_button='<input type="Button" value="Update"  onclick="updateBill('.$bill_id[$i].')" />'; else $update_button='<input type="Button" value="Update" onclick="alert('."'Update is Restricted for this item'".')" />';
		if($allow_remove) $remove_button='<input type="Button" value="Remove"  onclick="removeBill('.$bill_id[$i].')" style="background-color:maroon; color:white"/>'; else $remove_button='';
		print '<tr style="font-size:12pt"><td width="30px" style="color:blue"><strong>'.($i+1).'</strong></td><td>'.$bi_desc[$i].'</td><td width="50px"></td><td align="right"><input style="width:50px; type="text" id="billitemid'.$bill_id[$i].'" value="'.$bi_qty[$i].'" /> '.$update_button.' '.$remove_button.' </td><td width="80px" align="right">'.number_format($bi_price[$i]*$bi_qty[$i]).'</td></tr>';
	}
		print '<tr style="font-size:12pt; font-weight:900;"><td colspan="2">Total Amount</td><td width="50px"></td><td align="right" colspan="2">'.number_format($total).'</td></tr>';	
?>	
	</table>
<?php 
	if($total==0){
		print '<br /><hr /><br />';
		print '<table width="100%" style="font-size:10pt">	<tr bgcolor="#E5E5E5"><th>Chque No</th><th width="100px">Amount</th></tr>';
		for($k=0;$k<sizeof($chq0_code);$k++){
			print '<tr bgcolor="#EFEFEF" style="color:#CC0000; font-weight:bold"><td style="padding-left:10px; padding-right:10px"><a href="index.php?components=billing&action=chque_return" style="color:#CC0000; text-decoration:none;">'.$chq0_code[$k].'</a></td><td align="right" style="padding-left:10px; padding-right:10px">'.number_format($chq0_amount[$k]).'</td></tr>';
		}
		print '</table>';
		?><hr />
		<table width="100%" style="font-size:10pt"><tr bgcolor="#F5F5F5"><td>Return Chques</td><td align="right" style="padding-left:10px; padding-right:10px; color:red"><?php print $chq2_retuned; ?></td></tr><tr bgcolor="#F5F5F5"><td>Deposited Chques</td><td align="right" style="padding-left:10px; padding-right:10px; color:blue"><?php print $chq2_banked; ?></td></tr></table>
		<?php
	}
}else{ ?><br />
	<form id="searchinv" action="index.php?components=billing&action=search_bill" method="post">
	<table align="center" height="100%">
	<tr><td style="font-size:12pt;"><input type="text" style="width:100px" name="search1" id="search1" placeholder="Search Invoice" /><input type="Submit" value="Search" /></td></tr>
	</table>
	</form>
<?php } ?>
</td></tr>
</table>

<?php
if(isset($unic_item_code))
if($unic_item_code!=''){
	print '<script type="text/javascript"> getPrice(); </script>';
}

if($current_district==''){
	if($systemid==2) print '<script type="text/javascript"> document.getElementById("district").value=10; setDistrict("billing","set_district"); </script>';
}

                include_once  'template/footer.php';
?>