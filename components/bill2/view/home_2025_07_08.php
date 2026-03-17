<?php
                include_once  'template/header.php';
                $paper_size=paper_size(1);
                if($paper_size=='A4') $apend_limit=30;
                if($paper_size=='A5') $apend_limit=16;
                if($tm_template==2) $apend_limit=100;
                
                $bill_user=$bill_salesman=$_COOKIE['user_id'];
                if(isset($_GET['s'])){ if($_GET['s']!='')  $bill_salesman=$_GET['s']; }
                $cust_odr=$_GET['cust_odr'];
                if($cust_odr=='yes') $main_tale_color='#C6DEFE'; else $main_tale_color='#E5E5E5';
                $decimal=0;
                if($systemid==13) $decimal=2; 
                if($systemid==14) $decimal=2; 
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete2.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
	<script type="text/javascript">
<?php  if(isset($_COOKIE['district'])){ ?>
	$(function() {
		<?php  if($systemid==1 || $systemid==4 || $systemid==10){ ?>
		var availableTags44 = [<?php for ($x=0;$x<sizeof($sm_name);$x++){ print '"'.ucfirst($sm_name[$x]).'",'; } ?>	];
		$( "#sm" ).autocomplete({
			source: availableTags44
		});
		<?php } ?>
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
		<?php  if($systemid==1 || $systemid==4 || $systemid==10){ ?>
		var sm_id_arr = [<?php for ($x=0;$x<sizeof($sm_id);$x++){ print '"'.$sm_id[$x].'",'; } ?>	];
		var sm_name_arr = [<?php for ($x=0;$x<sizeof($sm_name);$x++){ print '"'.$sm_name[$x].'",'; } ?>	];
		var sm_name=document.getElementById('sm').value.toLowerCase();
		if(sm_name!=''){
			var b=sm_name_arr.indexOf(sm_name);
			document.getElementById('salesman').value=sm_id_arr[b];
		}
		<?php } ?>
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
		var cachback_tmp=document.getElementById('cachback_tmp').value;
		var invoice_no=document.getElementById('id').value;
		var invoice_cust=document.getElementById('cust').value;
		var unic_list_size=document.getElementById('unic_list_size').value;
		var salesman=document.getElementById('salesman').value;
		var systemid=document.getElementById('systemid').value;
		var storecrossitm=document.getElementById('storecrossitm').value;

		if(document.getElementById('cashback').checked) $cashback=1; else $cashback=0;
		if(itemcode!=''){
			var a=code.indexOf(itemcode);
			var b=ttitm.indexOf(itemid[a]);
			document.getElementById('itemid').value=itemid[a];
			document.getElementById('tags2').value=description[a];
			if(itemid[a]!=storecrossitm){
				document.getElementById('storecrossitm').value=0;
				<?php if($_COOKIE['direct_mkt']==1){ ?>	
					document.getElementById('priceshow').innerHTML='<select name="price"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option></select>';
				<?php }else{ ?>
					<?php if($_COOKIE['retail']==0){ ?>		
					document.getElementById('priceshow').innerHTML='<select name="price"><option value="'+wprice[a]+'"><?php print $pricename1; ?> - '+wprice[a]+'</option><option value="'+rprice[a]+'"><?php print $pricename2; ?>- '+rprice[a]+'</option></select>';
					<?php } ?>
					<?php if($_COOKIE['retail']==1){ ?>		
					document.getElementById('priceshow').innerHTML='<input type="hidden" name="price" value="'+rprice[a]+'" /><select disabled="disabled"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option></select>';
					document.getElementById('price_div').innerHTML='<select name="price"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option></select>';
					<?php }
				} ?>
				if(pr_sr[a]==3 && systemid==4)	document.getElementById("div_rep").style.display="block"; else	document.getElementById("div_rep").style.display="none";
				if(pr_sr[a]==2 || pr_sr[a]==3){
					document.getElementById('priceshow').innerHTML='<input type="text" name="price" value="'+wprice[a]+'" />';
					document.getElementById('com').innerHTML='Comment';
					if(pr_sr[a]==3){
						document.getElementById('it_drawer').innerHTML='<textarea name="comment" id="comment" ></textarea><br /><input type="text" name="repair_model" placeholder="Repair Model" /><br /><input type="text" name="repair_sn" placeholder="Repair SN" />';
						document.getElementById('tech_n').style.display= "block";
						document.getElementById('tech_d').style.display= "block";
					}else{
						document.getElementById('it_drawer').innerHTML='<textarea name="comment" id="comment" ></textarea>';
						document.getElementById('tech_n').style.display= "none";
						document.getElementById('tech_d').style.display= "none";
					}
				}else{
					document.getElementById('com').innerHTML='Drawer No<input type="hidden" name="comment" id="comment" value="" />';
					document.getElementById('it_drawer').innerHTML=drawer[a];
					document.getElementById('av_qty_val').value=qty[a];
				}
				if(systemid!=1)
					document.getElementById('av_qty').innerHTML=qty[a];
				else 
					document.getElementById('av_qty').innerHTML=qty[a]+'<input type="button" value="CrossCHK" onclick="storeCrossCheck('+itemid[a]+')" />';
				document.getElementById('tt_qty').innerHTML='';
				if(b>-1){
					document.getElementById('av_qty').innerHTML='Old Stock - '+qty[a];
					document.getElementById('tt_qty').innerHTML='New Stock - '+ttqty[b];
				}
				if((unic[a]==1)&&((itemcode!=itm_tmp)||(cachback_tmp!=$cashback))){
					document.getElementById('addtobill').innerHTML='';
					window.location = 'index.php?components=billing&action=home&id='+invoice_no+'&s='+salesman+'&cust='+invoice_cust+'&itemid='+itemid[a]+'&unic=yes&cashback='+$cashback+'&cust_odr=<?php print $_GET["cust_odr"]; ?>';
				}
				if((unic[a]==0)&&((unic_list_size>0)||(cachback_tmp!=$cashback))){
					document.getElementById('addtobill').innerHTML='';
					window.location = 'index.php?components=billing&action=home&id='+invoice_no+'&s='+salesman+'&cust='+invoice_cust+'&itemid='+itemid[a]+'&unic=no&cashback='+$cashback+'&cust_odr=<?php print $_GET["cust_odr"]; ?>';
				}
			}
		}else if(itemdesc!=''){
			var a=description.indexOf(itemdesc);
			document.getElementById('itemid').value=itemid[a];			
			document.getElementById('tags1').value=code[a];
			if(itemid[a]!=storecrossitm){
				document.getElementById('storecrossitm').value=0;
				<?php if($_COOKIE['direct_mkt']==1){ ?>	
					document.getElementById('priceshow').innerHTML='<select name="price"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option></select>';
				<?php }else{ ?>
					<?php if($_COOKIE['retail']==0){ ?>		
					document.getElementById('priceshow').innerHTML='<select name="price"><option value="'+wprice[a]+'"><?php print $pricename1; ?> - '+wprice[a]+'</option><option value="'+rprice[a]+'"><?php print $pricename2; ?> - '+rprice[a]+'</option></select>';
					<?php } ?>
					<?php if($_COOKIE['retail']==1){ ?>		
					document.getElementById('priceshow').innerHTML='<input type="hidden" name="price" value="'+rprice[a]+'" /><select disabled="disabled"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option></select>';
					document.getElementById('price_div').innerHTML='<select name="price"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option></select>';
					<?php } 
				} ?>
				if(pr_sr[a]==3 && systemid==4)	document.getElementById("div_rep").style.display="block"; else	document.getElementById("div_rep").style.display="none";
				if(pr_sr[a]==2 || pr_sr[a]==3){
					document.getElementById('priceshow').innerHTML='<input type="text" name="price" value="'+wprice[a]+'" />';
					document.getElementById('com').innerHTML='Comment';
					if(pr_sr[a]==3){
						document.getElementById('it_drawer').innerHTML='<textarea name="comment" id="comment" ></textarea><br /><input type="text" name="repair_model" placeholder="Repair Model" /><br /><input type="text" name="repair_sn" placeholder="Repair SN" />';
						document.getElementById('tech_n').style.display= "block";
						document.getElementById('tech_d').style.display= "block";
					}else{
						document.getElementById('it_drawer').innerHTML='<textarea name="comment" id="comment" ></textarea>';
						document.getElementById('tech_n').style.display= "none";
						document.getElementById('tech_d').style.display= "none";
					}
				}else{
					document.getElementById('com').innerHTML='Drawer No<input type="hidden" name="comment" id="comment" value="" />';
					document.getElementById('it_drawer').innerHTML=drawer[a];
					document.getElementById('av_qty_val').value=qty[a];		
				}
					if(systemid!=1)
						document.getElementById('av_qty').innerHTML=qty[a];
					else 
						document.getElementById('av_qty').innerHTML=qty[a]+'<input type="button" value="CrossCHK" onclick="storeCrossCheck('+itemid[a]+')" />';
				document.getElementById('tt_qty').innerHTML='';
				var itemcode=document.getElementById('tags1').value;
				var b=ttitm.indexOf(itemid[a]);
				if(b>-1){
					document.getElementById('av_qty').innerHTML='Old Stock - '+qty[a];
					document.getElementById('tt_qty').innerHTML='New Stock - '+ttqty[b];
				}
				if((unic[a]==1)&&((itemcode!=itm_tmp)||(cachback_tmp!=$cashback))){
					document.getElementById('addtobill').innerHTML='';
					window.location = 'index.php?components=billing&action=home&id='+invoice_no+'&s='+salesman+'&cust='+invoice_cust+'&itemid='+itemid[a]+'&unic=yes&cashback='+$cashback+'&cust_odr=<?php print $_GET["cust_odr"]; ?>';
				}
				if((unic[a]==0)&&((unic_list_size>0)||(cachback_tmp!=$cashback))){
					document.getElementById('addtobill').innerHTML='';
					window.location = 'index.php?components=billing&action=home&id='+invoice_no+'&s='+salesman+'&cust='+invoice_cust+'&itemid='+itemid[a]+'&unic=no&cashback='+$cashback+'&cust_odr=<?php print $_GET["cust_odr"]; ?>';
				}
			}
		}
		cashBack();
	}
	
<?php } ?>
	
	function payment(){
		var salesman=document.getElementById('salesman').value;
		document.getElementById('payment').innerHTML='';
		if(document.getElementById('fastprint').value=='on') 
		printdiv('print2','printheader');
		window.location = 'index.php?components=billing&action=pay_bill&cust_odr=<?php print $_GET["cust_odr"]; ?>&id=<?php if(isset($_GET['id'])) print $_GET['id']; ?>&s='+salesman;
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

	function repairePreCheck(){
		var rep_com="";
		var chkcount=0;
		for($i=1;$i<=15;$i++){
			if(document.getElementById('pre_'+$i+'_1').checked) {
			  chkcount++;
			}
			if(document.getElementById('pre_'+$i+'_2').checked) {
			  rep_com+= document.getElementById('pre_'+$i+'_2').value+'; ';
			  chkcount++;
			}
		}
		document.getElementById('comment').value=rep_com;
	}

	function cashBack(){
		var $qty=document.getElementById('qty1').value;
		if(document.getElementById('cashback').checked){
			if($qty>0){
				document.getElementById('qty1').value=(-1 * $qty);
			}
		}else{
			if($qty<0){
				document.getElementById('qty1').value=(-1 * $qty);
			}
		}
	}
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
	
	function changeDiscount(){
		var old_discount_type=document.getElementById('discount_type').value;
		if(old_discount_type=='price'){
			document.getElementById('discount_type').value='percentage';
			document.getElementById('discount_div').innerHTML='%';
		}else{
			document.getElementById('discount_type').value='price';
			document.getElementById('discount_div').innerHTML='Rs';
		}
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
	
	function storeCrossCheck(itmid){
	  document.getElementById('av_qty').innerHTML=document.getElementById('loading').innerHTML;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    var returntext=this.responseText;
	    	$values=returntext.split(',');
	    	if(isNaN($values[2])){
		    	document.getElementById('av_qty').innerHTML='<span style="color:red">Error</span>';
	    	}else{
				document.getElementById('storecrossitm').value=itmid;
				document.getElementById('storecrossst').value=$values[3];
		    	document.getElementById('av_qty').innerHTML='<span style="color:blue">'+$values[2]+'</span>';
		    	document.getElementById('av_qty_val').value=$values[2];
				document.getElementById('price_div').innerHTML='<select name="price"><option value="'+$values[0]+'">Retail - '+$values[0]+'</option><option value="'+$values[1]+'">Wholesale - '+$values[1]+'</option></select>';
	    	}
	    }
	  };
	  xhttp.open("GET", 'index.php?components=billing&action=get_store_stock&itmid='+itmid, true);
	  xhttp.send();
	}
	
	</script>
	
<style type="text/css">
select.selected{
    color: gray;
}
.yesno{
	font-size:7pt;
}
</style>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<input type="hidden" id="systemid" value="<?php print $systemid; ?>" />
<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
<div id="printheader" style="display:none" ></div>
<div id="print1" style="display:none" >
		<table width="210px"><tr><td>
		  <table width="190px" align="center" style="font-family:Arial, Helvetica, sans-serif">
		  <tr><td><span style="font-family:'Arial'; font-size:20pt">INVOICE</span></td></tr>
		  <tr><td style="font-size:8pt"><strong><?php print $tm_shopname; ?></strong>.<br />
			<?php print $tm_address1; ?><br />
			Tel: <?php print $tm_tel1; ?>
			</td></tr>
			<tr><td height="10px"></td></tr>
			<tr><td style="font-size:8pt">
			INVOICE # [<?php print  str_pad($id, 7, "0", STR_PAD_LEFT); ?> ]<br />
			<span  style="font-family:Arial; font-size:8pt">
			DATE: <?php print date("Y-m-d",time()); ?><br /><br />
			</span>
			</td></tr>
			<tr><td height="8px"></td></tr>
		  </table>
		
			<table align="center" width="210px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >
			<tr><td colspan="4"><hr></td></tr>
			<tr style="font-family:Arial; font-size:8pt; text-align:center"><td>DESCRIPTION</td><td>UNIT<br />PRICE</td><td>QTY</td><td>TOTAL</td></tr>
			<tr><td colspan="4"><hr></td></tr>
			</table>
			</td></tr></table>
</div>
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

	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	//print '<table align="center"><tr><td><span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span></td></tr></table>'; 
	}
?>

<table align="center" style="font-size:10pt">
<tr><td valign="top" align="center">
	<div id="div_rep" style="display:none">
	<br /><br />
	<table width="100%" bgcolor="#EEEEEE"><tr><td style="font-size:16pt; color:navy" align="center">Pre Check for Repairs</td></tr></table>
	<br />
	<table border="1" cellspacing="0">
	<tr>
		<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_1" id="pre_1_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_1" id="pre_1_2" value="Display Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Display</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
		<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_2" id="pre_2_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_2" id="pre_2_2" value="Mic Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Mic</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
		<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_3" id="pre_3_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_3" id="pre_3_2" value="Vol/Down Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Vol/Down<br />Switch</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
	</tr>
	<tr>
		<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_4" id="pre_4_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_4" id="pre_4_2" value="Speaker Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Speaker</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
		<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_5" id="pre_5_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_5" id="pre_5_2" value="Ringer Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Ringer</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
		<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_6" id="pre_6_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_6" id="pre_6_2" value="Camera Switch Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Camera<br />Switch</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
	</tr>
	<tr>
		<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_7" id="pre_7_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_7" id="pre_7_2" value="Signal Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Signal</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
		<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_8" id="pre_8_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_8" id="pre_8_2" value="Touch Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Touch</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
		<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_9" id="pre_9_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_9" id="pre_9_2" value="Keypad Lights Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Light<br />(Keypad)</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
	</tr>
	<tr>
		<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_10" id="pre_10_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_10" id="pre_10_2" value="Camera Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Camera</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
		<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_11" id="pre_11_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_11" id="pre_11_2" value="Charging Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Charging</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
		<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_12" id="pre_12_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_12" id="pre_12_2" value="Display Light Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Light<br />(Display)</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
	</tr>
	<tr>
		<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_13" id="pre_13_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_13" id="pre_13_2" value="Memory Card Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Memory<br />Card</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
		<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_14" id="pre_14_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_14" id="pre_14_2" value="Call Transmitting Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Call<br />Transmitting</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
		<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_15" id="pre_15_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_15" id="pre_15_2" value="Keypad Buttons Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Keypad<br />Button</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
	</tr>
	</table>
	</div>
</td><td valign="top">
<div style="background-color:#EEEEEF; border-radius: 15px; padding-left:10px; padding-right:10px">
<table width="100%"><tr><td><h1 style="color:#2277DD"><?php if($cust_odr=='no') print 'Sales Billing'; else print 'Cust Order'; ?></h1></td>
<?php 
if(isset($_GET['cust'])){
	if($tm_template==2){
	if($_COOKIE['fastprint']=='on') $checked='checked="checked"'; else $checked='';
	print '<td style="vertical-align:middle; font-size:10pt">Fast Print <input type="checkbox" onchange="window.location = '."'".'index.php?components=billing&action=setfastprint&id='.$_GET["id"].'&s='.$_GET["s"].'&cust='.$_GET["cust"]."'".'"  '.$checked.' /></td>';
	}
}
?>
<td align="right">
<select name="district" id="district" onchange="setDistrict('billing')" <?php if($static_district!=0) print 'disabled="disabled"'; ?> >
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
</div>

<input type="hidden" id="cust_odr" value="<?php print $cust_odr; ?>" />

<?php if($current_district!=''){ 
 if(isset($_REQUEST['id'])) print '<form id="billingForm" action="index.php?components=billing&action=apend_bill&cust_odr='.$cust_odr.'" onsubmit="return validateBill()" method="post" >';
	else print '<form id="billingForm" action="index.php?components=billing&action=new_bill&cust_odr='.$cust_odr.'" method="post" >';	

if(!isset($_REQUEST['quotation'])) print '<input type="hidden" name="quotation" value="0" />';
?>
<input type="hidden" name="cust_id" id="cust_id" value="" />
<input type="hidden" name="id" id="id" value="<?php print $id; ?>" />
<input type="hidden" id="gps_x" name="gps_x" value="0" />
<input type="hidden" id="gps_y" name="gps_y" value="0" />
<input type="hidden" id="storecrossitm" name="storecrossitm" value="0" />
<input type="hidden" id="storecrossst" name="storecrossst" value="0" />
<?php if(isset($_GET['cust'])) print '<input type="hidden" id="cust" value="'.$_GET['cust'].'" />'; ?>
<input type="hidden" id="fastprint" value="<?php print $_COOKIE['fastprint']; ?>" />
<input type="hidden" id="billitemcount" value="<?php print sizeof($bill_id); ?>" />
<input type="hidden" id="itm_tmp" value="<?php if($unic_item_code!='') print $unic_item_code; ?>" />
<input type="hidden" id="cachback_tmp" value="<?php if(isset($_GET['cashback'])) print $_GET['cashback']; else print '0'; ?>" />
<input type="hidden" id="qty" name="qty" value="" />
<input type="hidden" id="unic_list_size" value="<?php print sizeof($unic_item_list); ?>" />
<div id="price_div" style="display:none" ></div>
<?php if(sizeof($unic_item_list)==0){ ?>
<input type="hidden" name="unic_item" id="tags4" value="0" />
<?php }else{ ?>
<input type="hidden" name="unic_item" value="1" />
<?php } ?>
	<table align="center" bgcolor="<?php print $main_tale_color; ?>" style="border-radius: 15px;">
	<tr><td colspan="5"><br /></td></tr>
	
	<?php
		if(isset($_GET['cust'])){
			$cid=array_search($_GET['cust'],$cust_id);
			print '<tr><td width="50px"></td><td style="font-size:12pt">';
			if($cust_mtype=='primary') print '<span style="color:red">Primary<br />Customer</span>';
			elseif($cust_mtype=='secondary') print '<span style="color:red">Secondary<br />Customer</span>';
			else print 'Customer';
			print '</td><td colspan="2">';
			print '<span style="font-size:12pt">'.$cust_name[$cid].'</span>';
			print '<input type="hidden" name="cust" id="cust" value="'.$_GET['cust'].'" />';
			print '</td><td width="50px"></td></tr>';
		}else{
			print '<tr><td width="50px"></td><td style="font-size:12pt">Customer</td><td colspan="2"><input type="text" name="cust" id="tags3" /></td><td width="50px"></td></tr>'; 
			if($_COOKIE['retail']==1)
				print '<tr><td width="50px"></td><td style="font-size:12pt">Mobile</td><td colspan="2"><input type="text" name="mob" id="mob" /></td><td width="50px"></td></tr>'; 
			else
				print '<tr><td colspan="4"><input type="hidden" name="mob" id="mob" value="0" /></td><td width="50px"></td></tr>'; 
			if($systemid==1 || $systemid==4 || $systemid==10)
				print '<tr><td width="50px"></td><td style="font-size:12pt">Salesman</td><td colspan="2"><input type="text" id="sm" /></td><td width="50px"></td></tr>'; 
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
		<input type="submit" value="Submit" style="width:100px; height:50px;" onclick="selectCust()" />
		<?php if(!isset($_GET['cust'])){ 
			if($_COOKIE['retail']==0){ ?><input type="button" value="Create Cust" onclick="window.location = 'index.php?components=billing&action=wholesale_cust&s=<?php print $_GET['s']; ?>&cust_odr=<?php print $cust_odr; ?>'"  style="width:100px; height:50px;" /> <?php }
			if($_COOKIE['retail']==1){ ?><input type="button" value="Create Cust" onclick="window.location = 'index.php?components=billing&action=onetime_cust&s=<?php print $_GET['s']; ?>&cust_odr=<?php print $cust_odr; ?>'"  style="width:100px; height:50px;" /> <?php }
		} ?>
	<br /><br /></td><td width="50px"></td></tr>
	<?php }else{ ?>
	<?php if($systemid==4){ ?>
	<tr><td width="50px"></td><td style="font-size:12pt">Salesman</td><td colspan="2">
		<?php if(sizeof($bill_id)==0){ ?>
			<select name="salesman" id="salesman" >
			<?php for($i=0;$i<sizeof($sm_id);$i++){
				if($sm_id[$i]==$bill_salesman) $select='selected="selected"'; else $select='';
				print '<option value="'.$sm_id[$i].'" '.$select.' >'.ucfirst($sm_name[$i]).'</option>';
			} ?>
			</select>
		<?php }else{
			$sid=array_search($bill_salesman,$sm_id);
			print '<input type="text" value="'.ucfirst($sm_name[$sid]).'" disabled="disabled" /><input type="hidden" name="salesman" id="salesman" value="'.$bill_salesman.'" />';
		} ?>
	</td><td width="50px"></td></tr>
	<?php }else{ 
		print '<tr><td colspan="5"><input type="hidden" name="salesman" id="salesman" value="'.$bill_salesman.'" /></td></tr>';
	} ?>
	<tr><td width="50px"></td><td style="font-size:12pt">Cash Back Invoice</td><td colspan="2"><input id="cashback" type="checkbox" <?php if(isset($_GET['cashback'])){ if($_GET['cashback']==1) print 'checked="checked"'; } ?> /></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td style="font-size:12pt">Item Code</td><td colspan="2"><input type="text" name="code" id="tags1" value="<?php if($unic_item_code!='') print $unic_item_code; ?>" onclick="this.value=''" /></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td style="font-size:12pt">Item Description</td><td colspan="2"><input type="text" name="description" id="tags2" onchange="document.getElementById('tags1').value=''" /></td><td width="50px"></td></tr>
	<tr><td></td><td style="font-size:12pt">Quantity</td><td><?php if($is_unic_item==0){ ?><input type="number" name="qty1" id="qty1" onfocus="getPrice()"  /><?php }else{ print '<input type="button" value="Refresh" onclick="getPrice()" /><input type="hidden" name="qty1" id="qty1" value="1"  />'; } ?></td><td><div style="font-size:10pt;" id="av_qty" align="right"></div><div style="font-size:10pt; color:#CC0000" id="tt_qty" align="right"></div><input type="hidden" id="av_qty_val" /></td><td></td></tr>
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
	<input type="hidden" id="precheck" value="1" />
	<input type="hidden" name="itemid" id="itemid" />
	<input type="hidden" name="discount_type" id="discount_type" value="<?php print $discount; ?>" />
	<input type="hidden" name="type" id="type" value="1" />
	</td><td></td></tr>
	<tr><td width="50px"></td><td style="font-size:12pt"><table border="0"><tr><td>Unit Discount</td><td><div id="discount_div" onclick="changeDiscount()" style="color:blue; cursor:pointer;"><?php if($discount=='price') print 'Rs'; else print '%'; ?></div></td></tr></table></td><td colspan="2"><table width="100%"><tr><td><input type="number" name="discount" id="discount" value="0" style="width:50px" onclick="this.value=''" /><input type="button" value="Check" onclick="loadDiscount('<?php print $_GET['cust']; ?>')" /></td><td><div style="font-size:10pt; color:red" id="div_discount" align="right"></div></td></tr></table></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td style="font-size:12pt"><div id="com" >Drawer No<input type="hidden" name="comment" id="comment" value="" /></div></td><td colspan="2"><div style="font-size:12pt" id="it_drawer"></div></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td style="font-size:12pt"><div id="tech_n" style="display:none" >Technicient</div></td><td colspan="2"><div style="font-size:12pt; display:none" id="tech_d" >		
		<select name="technicient">
		<option value="">-SELECT Technicient-</option>
		<?php for($i=0;$i<sizeof($tech_id);$i++){
		print '<option value="'.$tech_id[$i].'">'.ucfirst($tech_name[$i]).'</option>';
		} ?>
		</select>
</div></td><td width="50px"></td></tr>
	<tr><td colspan="2" align="right"><br /><div id="addtobill"><?php if(((sizeof($bill_id)<$apend_limit)||($tm_template==2))&&($bill_user==$bm_sys_user0)) print '<input type="submit" value="Add to Bill" style="width:100px; height:30px" onclick="cashBack()" />'; ?></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	<?php if((sizeof($bill_id)>0)&&($bill_user==$bm_sys_user0)) { ?>
		</td><td colspan="3" align="left"><div id="payment"><input type="Button" value="Payment" style="width:100px; height:70px" onclick="payment()" /></div>
	<?php } ?>
<!--	<input type="Button" value="Finalyze" style="width:100px; height:70px" onclick="window.location = 'index.php?components=billing&action=finish_bill&id=<?php if(isset($_GET['id'])) print $_GET['id']; ?>'" /> -->
	<br /><br /></td></tr>
	<?php } ?>
	</table>
	<!-- </form> -->
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
<div id="printorder_c" style="display:none" >
	<table border="1" cellspacing="0" style="font-family:Calibri; font-size:9pt;">
	<tr><th width="30px">DR</th><th width="118px">Item</th><th width="25px">Qty</th></tr>
	<?php for($i=0;$i<sizeof($bill_id);$i++){
		print '<tr><td>&nbsp;'.$bi_drawer[$i].'&nbsp;</td><td>&nbsp;'.$bi_desc2[$i].'&nbsp;</td><td align="right">&nbsp;'.$bi_qty[$i].'&nbsp;</td></tr>';
	} ?>
	</table>
</div>
<div style="background-color:#D1D8F1; border-radius: 5px; padding-left:20px; padding-right:20px; height:600px;">
<br />
<table><tr><td><div id="printorder_h"><span style="font-weight:bold; font-size:12pt;" >Invoice No: &nbsp;&nbsp;&nbsp;<?php print str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?></span></div></td><td width="20px"></td><td><input type="button" value="Print Order" onclick="printdiv('printorder_c','printorder_h')" /></td></tr></table>
<br /><br /><br />
	<table align="center" bgcolor="#E5E5EA" style="border-radius:5px;">
<?php
	for($i=0;$i<sizeof($bill_id);$i++){
		$new_description[]=$bi_desc2[$i];
		$counts=array_count_values($new_description);
		$count=$counts[$bi_desc2[$i]];
		$tc=array_search($bi_desc2[$i],$dups);
		if($tc>-1){ 
			if($dups_count[$tc]==$count) $allow_remove=true; else $allow_remove=false; 
		}else $allow_remove=true;
		if($bill_cross_tr[$i]>0) $color1="#AAAAAA"; else 	$color1="auto";
		if($bi_no_update[$i]==0) $update_button='<input type="Button" value="Update"  onclick="updateBill('.$bill_id[$i].')" />'; else $update_button='<input type="Button" value="Update" onclick="alert('."'Update is Restricted for this item'".')" />';
		if($allow_remove) $remove_button='<input type="Button" value="Remove"  onclick="removeBill('.$bill_id[$i].')" style="background-color:maroon; color:white"/>'; else $remove_button='';
		print '<tr style="font-size:12pt; background-color:'.$color1.'"><td width="30px" style="color:blue"><strong>'.($i+1).'</strong></td><td>'.$bi_desc[$i].'</td><td width="50px"></td><td align="right"><div id="itmdiv'.$bill_id[$i].'"><input style="width:50px; type="text" id="billitemid'.$bill_id[$i].'" value="'.$bi_qty[$i].'" /> '.$update_button.' '.$remove_button.' </div></td><td width="80px" align="right">'.number_format(($bi_price[$i]*$bi_qty[$i]),$decimal).'</td></tr>';
	}
		print '<tr style="font-size:12pt; font-weight:900;"><td colspan="2">Total Amount</td><td width="50px"></td><td align="right" colspan="2">'.number_format($total,$decimal).'</td></tr>';	
?>	
	</table>
<?php 
	if($total==0){
		print '<br /><hr /><br />';
		print '<table width="100%" style="font-size:10pt">	<tr bgcolor="#E5E5FF"><th>Chque No</th><th width="100px">Amount</th></tr>';
		for($k=0;$k<sizeof($chq0_code);$k++){
			print '<tr bgcolor="#EFEFEF" style="color:#CC0000; font-weight:bold"><td style="padding-left:10px; padding-right:10px"><a href="index.php?components=billing&action=chque_return" style="color:#CC0000; text-decoration:none;">'.$chq0_code[$k].'</a></td><td align="right" style="padding-left:10px; padding-right:10px">'.number_format($chq0_amount[$k]).'</td></tr>';
		}
		print '</table>';
		?><hr />
		<table width="100%" style="font-size:10pt">
		<tr bgcolor="#F5F5FF"><td>Returned Chques</td><td align="right" style="padding-left:10px; padding-right:10px; color:red"><?php print $chq2_retuned; ?></td></tr>
		<tr bgcolor="#F5F5FF"><td>Postponed Chques</td><td align="right" style="padding-left:10px; padding-right:10px; color:red"><?php print $chq2_postpone; ?></td></tr>
		<tr bgcolor="#F5F5FF"><td>Deposited Chques</td><td align="right" style="padding-left:10px; padding-right:10px; color:blue"><?php print $chq2_banked; ?></td></tr>
		</table>
		<?php
	}
?><br /></div><?php
}else{ ?><br />
	<input type="hidden" name="salesman" id="salesman" value="<?php print $bill_salesman; ?>" />
	</form>
	<div style="background-color:#EEEEEF; border-radius: 5px; padding-left:10px; padding-right:10px; width:250px">
	<br />
	<table align="center" height="100%">
	<tr><td style="font-size:12pt;"><form id="searchinv" action="index.php?components=billing&action=search_bill&s=<?php print $_GET['s']; ?>&cust_odr=<?php print $_GET['cust_odr']; ?>" method="post"><input type="text" style="width:100px" name="search1" id="search1" placeholder="Invoice Number" /><input type="Submit" value="Search" /></form></td></tr>
	<tr><td style="font-size:12pt;"><form action="index.php"><input type="hidden" name="components" value="billing" /><input type="hidden" name="action" value="home" /><input type="hidden" name="s" value="<?php print $_GET['s']; ?>" /><input type="hidden" name="cust_odr" value="<?php print $_GET['cust_odr']; ?>" /><input type="text" style="width:100px" name="searchcust" placeholder="Customer Name" /><input type="Submit" value="Search" /></form></td></tr>
	<tr><td style="font-size:12pt;"><form action="index.php"><input type="hidden" name="components" value="billing" /><input type="hidden" name="action" value="home" /><input type="hidden" name="s" value="<?php print $_GET['s']; ?>" /><input type="hidden" name="cust_odr" value="<?php print $_GET['cust_odr']; ?>" /><input type="text" style="width:100px" name="searchunic" placeholder="Unique ID" /><input type="Submit" value="Search" /></form></td></tr>
	</table>
	<br />
	</div>
	<?php if(isset($_GET['searchcust'])){
		print '<iframe id="search_frm" width="200px" height="350px" src="components/billing/view/tpl/search_bill.php?searchcust='.$_GET['searchcust'].'"></iframe>';
	}else if(isset($_GET['searchunic'])){
		print '<iframe id="search_frm" width="200px" height="350px" src="components/billing/view/tpl/search_bill.php?searchunic='.$_GET['searchunic'].'"></iframe>';
	} ?>
<?php } ?>
</td></tr>
</table>
<?php
if(isset($unic_item_code))
if($unic_item_code!=''){
	print '<script type="text/javascript"> getPrice(); </script>';
}
if($current_district==''){
	if($static_district!=0) print '<script type="text/javascript"> document.getElementById("district").value='.$static_district.'; setDistrict("billing"); </script>';
}
                include_once  'template/footer.php';
?>