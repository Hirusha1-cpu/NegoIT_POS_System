<?php
                include_once  'template/m_header.php';

                $bill_user=$bill_salesman=$_COOKIE['user_id'];
                if(isset($_GET['s'])){ if($_GET['s']!='')  $bill_salesman=$_GET['s']; }
                $cust_odr=$_GET['cust_odr'];
                if($cust_odr=='yes') $main_tale_color='#FFDDCC'; else $main_tale_color='#EEEEEE';
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
	<script type="text/javascript">
<?php  if(isset($_COOKIE['district'])){ ?>
	$(function() {
		<?php  if($systemid==1 || $systemid==4 || $systemid==10 || $systemid==17){ ?>
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
		<?php  if($systemid==1 || $systemid==4 || $systemid==10 || $systemid==17){ ?>
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
		var invoice_no=document.getElementById('id').value;
		var invoice_cust=document.getElementById('cust').value;
		var unic_list_size=document.getElementById('unic_list_size').value;
		var salesman=document.getElementById('salesman').value;
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
					<?php if(($_COOKIE['retail']==0)||(($cust_odr=='yes')&&($systemid==16))){ ?>
					document.getElementById('priceshow').innerHTML='<select name="price"><option value="'+wprice[a]+'"><?php print $pricename1; ?> - '+wprice[a]+'</option><option value="'+rprice[a]+'"><?php print $pricename2; ?> - '+rprice[a]+'</option></select>';
					<?php }else{ ?>
					document.getElementById('priceshow').innerHTML='<input type="hidden" name="price" value="'+rprice[a]+'" /><select disabled="disabled"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option></select>';
					document.getElementById('price_div').innerHTML='<select name="price"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option></select>';
					<?php }
				} ?>
				if(pr_sr[a]==2 || pr_sr[a]==3){
					document.getElementById('priceshow').innerHTML='<input type="text" name="price" value="'+wprice[a]+'" />';
					document.getElementById('com').innerHTML='Comment';
					document.getElementById('it_drawer').innerHTML='<textarea name="comment" ></textarea>';
				}else{
					document.getElementById('com').innerHTML='Drawer No<input type="hidden" name="comment" id="comment" value="" />';
					document.getElementById('it_drawer').innerHTML=drawer[a];
					document.getElementById('av_qty_val').value=qty[a];
				}
				document.getElementById('av_qty').innerHTML=qty[a];
				document.getElementById('tt_qty').innerHTML='';
				if(b>-1){
					document.getElementById('av_qty').innerHTML='Old Stock - '+qty[a];
					document.getElementById('tt_qty').innerHTML='New Stock - '+ttqty[b];
				}
				if((unic[a]==1)&&(itemcode!=itm_tmp)){
					document.getElementById('addtobill').innerHTML='';
					window.location = 'index.php?components=billing&action=home&id='+invoice_no+'&s='+salesman+'&cust='+invoice_cust+'&itemid='+itemid[a]+'&unic=yes&cashback='+$cashback+'&cust_odr=<?php print $_GET["cust_odr"]; ?>';
				}
				if((unic[a]==0)&&(unic_list_size>0)){
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
					<?php if(($_COOKIE['retail']==0)||(($cust_odr=='yes')&&($systemid==16))){ ?>
					document.getElementById('priceshow').innerHTML='<select name="price"><option value="'+wprice[a]+'"><?php print $pricename1; ?> - '+wprice[a]+'</option><option value="'+rprice[a]+'"><?php print $pricename2; ?> - '+rprice[a]+'</option></select>';
					<?php }else{ ?>
					document.getElementById('priceshow').innerHTML='<input type="hidden" name="price" value="'+rprice[a]+'" /><select disabled="disabled"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option></select>';
					document.getElementById('price_div').innerHTML='<select name="price"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option></select>';
					<?php }
				} ?>
				if(pr_sr[a]==2 || pr_sr[a]==3){
					document.getElementById('priceshow').innerHTML='<input type="text" name="price" value="'+wprice[a]+'" />';
					document.getElementById('com').innerHTML='Comment';
					document.getElementById('it_drawer').innerHTML='<textarea name="comment" ></textarea>';
				}else{
					document.getElementById('com').innerHTML='Drawer No<input type="hidden" name="comment" id="comment" value="" />';
					document.getElementById('it_drawer').innerHTML=drawer[a];
					document.getElementById('av_qty_val').value=qty[a];
				}
				document.getElementById('av_qty').innerHTML=qty[a];
				document.getElementById('tt_qty').innerHTML='';
				var itemcode=document.getElementById('tags1').value;
				var b=ttitm.indexOf(itemid[a]);
				if(b>-1){
					document.getElementById('av_qty').innerHTML='Old Stock - '+qty[a];
					document.getElementById('tt_qty').innerHTML='New Stock - '+ttqty[b];
				}
				if((unic[a]==1)&&(itemcode!=itm_tmp)){
					document.getElementById('addtobill').innerHTML='';
					window.location = 'index.php?components=billing&action=home&id='+invoice_no+'&s='+salesman+'&cust='+invoice_cust+'&itemid='+itemid[a]+'&unic=yes&cashback='+$cashback+'&cust_odr=<?php print $_GET["cust_odr"]; ?>';
				}
				if((unic[a]==0)&&(unic_list_size>0)){
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
		window.location = 'index.php?components=billing&action=pay_bill&cust_odr=<?php print $_GET["cust_odr"]; ?>&id=<?php if(isset($_GET['id'])) print $_GET['id']; ?>&s='+salesman;
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
	//-----------------------------Cross Transfer-----------------------------//
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
	<?php if(isset($_GET['cust'])){ ?>
	//-----------------get GPS coordinates-----------------------------------------//
	function tagLocation() {
	    document.getElementById('div_gps').innerHTML='GPS/HTTPS are not Enabled';
	    if (navigator.geolocation) {
	        navigator.geolocation.getCurrentPosition(showPosition);
	    }
	}
	function showPosition(position) {
		document.getElementById('div_gps').innerHTML=document.getElementById('loading').innerHTML;
	    $gps_x=position.coords.latitude;
	    $gps_y=position.coords.longitude;

	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    var returntext=this.responseText;
	    if(returntext=='done'){
	    	document.getElementById('div_gps').innerHTML='<span style="color:green;"><strong>Done !</strong></span>';
	    }else{
	    	document.getElementById('div_gps').innerHTML='<span style="color:red;"><strong>Error !</strong></span>';
	    }
	    }
	  };
	  xhttp.open("GET", 'index.php?components=billing&action=tag_gps&id=<?php print $_GET["cust"]; ?>&gps_x='+$gps_x+'&gps_y='+$gps_y, true);
	  xhttp.send();
	}
	<?php } ?>

	//get GPS coordinates-------------------------------------------//
	function billLocation() {
	    if (navigator.geolocation) {
	        navigator.geolocation.getCurrentPosition(showPosition2);
	    }
	}
	function showPosition2(position) {
	    document.getElementById('gps_x').value=position.coords.latitude;
	    document.getElementById('gps_y').value=position.coords.longitude;
	}
	function removeLeadingZeros() {
		var inputValue = document.getElementById('search1').value; // Get the input value
		var trimmedValue = inputValue.replace(/^0+/, ''); // Remove leading zeros
		document.getElementById('search1').value = trimmedValue; // Update the input field with the trimmed value
	}
	</script>

<style type="text/css">
select.selected{
    color: gray;
}
</style>
<!-- ------------------------------------------------------------------------------------ -->
<?php
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<div class="w3-container" style="margin-top:75px">
<?php
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>';
	}
?>
	<input type="hidden" id="cust_odr" value="<?php print $cust_odr; ?>" />

<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
<?php if($systemid==13) $val='yes'; else $val='no';
print '<input type="hidden" id="system_tmp" value="'.$val.'" />';
?>

<table><tr><td style="vertical-align:top;">
<?php if($current_district!=''){ ?>
	<?php
		if(isset($_GET['cust'])){
			$cid=array_search($_GET['cust'],$cust_id);
			print '<table width="100%"><tr><td width="200px" style="font-size:large; background-color:#EEEEFF">'.$cust_name[$cid].'</td><td></td><td width="210px" style="font-weight:bold; font-size:large; background-color:#EEEEEE" align="right">';
			if(isset($_REQUEST['id'])) print 'Invoice No: &nbsp;&nbsp;&nbsp;'.str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT);
			print '<tr><td style="font-size:large; color:maroon; background-color:#EEEEFF">NIC &nbsp;: '.$cust_nic[$cid].'</td><td></td><td></td></tr>';
			print '<tr><td style="font-size:large; color:maroon; background-color:#EEEEFF">Mob : '.$cust_mobile[$cid].'</td><td></td><td>';
			if($gps_x[$cid]==0) print '<div id="div_gps"><input type="button" style="width:100%" value="Tag GPS Location" onclick="tagLocation()" /></div>';
			else print '<input type="button" style="width:100%" value="Show in MAP" onclick="window.open('."'https://maps.google.com/?q=$gps_x[$cid],$gps_y[$cid]'".')"  />';
			print '</td></tr>';
			if($systemid==1) print '<tr><td style="font-size:large; color:maroon; background-color:#EEEEFF">Ref : '.ucfirst($cust_asso_sman[$cid]).'</td><td></td><td></td></tr>';
			print '</td></tr></table>';
		}
	?>

	<?php if(!isset($_REQUEST['id'])) { ?>
		<form id="billingForm" action="index.php?components=billing&action=new_bill&cust_odr=<?php print $_GET['cust_odr']; ?>" method="post" >
		<?php if(!isset($_REQUEST['quotation'])) print '<input type="hidden" name="quotation" value="0" />'; ?>
		<input type="hidden" name="cust_id" id="cust_id" value="" />
		<input type="hidden" name="salesman" id="salesman" value="<?php print $bill_salesman; ?>" />
		<input type="hidden" id="gps_x" name="gps_x" value="0" />
		<input type="hidden" id="gps_y" name="gps_y" value="0" />
		<input type="hidden" id="storecrossitm" name="storecrossitm" value="0" />
		<input type="hidden" id="storecrossst" name="storecrossst" value="0" />
		<?php if(($_COOKIE['retail']==0)||($_GET['cust_odr']=='yes')){ ?>
			<input type="hidden" name="mob" id="mob" value="0" />
			<table>
			<tr><td><input type="text" name="cust" id="tags3"  placeholder="Customer" style="font-size:large" /></td>
			<td rowspan="2"><input type="submit" value="Submit" style="width:90px; height:50px; font-size:large;" onclick="selectCust()"/>
			<input type="button" value="Create Cust" onclick="window.location = 'index.php?components=billing&action=wholesale_cust&s=<?php print $_GET['s']; ?>&cust_odr=<?php print $cust_odr; ?>&mob='"  style="width:90px; height:50px;" />
			</td>
			</tr>
			<?php
			if($systemid==1 || $systemid==4 || $systemid==10 || $systemid==17)
				print '<tr><td><input type="text" id="sm" placeholder="Sallesman" style="font-size:large" /></td></tr>';
			?>
			</table>
		<?php }else{ ?>
			<table>
			<tr><td><strong>Customer </strong> </td><td><input type="text" name="cust" id="tags3" style="font-size:large" /></td></tr>
			<tr><td><strong>Mobile </strong> </td><td><input type="text" name="mob" id="mob" style="font-size:large" /></td></tr>
			<?php
			if($systemid==1 || $systemid==4 || $systemid==10 || $systemid==17)
				print '<tr><td><strong>Salesman </strong> </td></td><td><input type="text" id="sm" /></td></tr>';
			?>
			<tr><td align="center" colspan="2"><input type="submit" value="Submit" style="width:90px; height:50px; font-size:large;" onclick="selectCust()"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" value="Create Cust" onclick="window.location = 'index.php?components=billing&action=onetime_cust&s=<?php print $_GET['s']; ?>&cust_odr=<?php print $cust_odr; ?>&mob='+document.getElementById('mob').value"  style="width:90px; height:50px;" /></td></tr>
			</table>
		<?php } ?>
		</form>
	<?php }else{ ?>
	<form action="index.php?components=billing&action=apend_bill&cust_odr=<?php print $_GET['cust_odr']; ?>" onsubmit="return validateBill()" method="post" >
	<input type="hidden" name="cust_id" id="cust_id" value="" />
	<input type="hidden" name="id" id="id" value="<?php print $id; ?>" />
	<input type="hidden" name="cust" id="cust" value="<?php print $_GET['cust']; ?>" />
	<input type="hidden" id="fastprint" value="<?php print $_COOKIE['fastprint']; ?>" />
	<input type="hidden" id="billitemcount" value="<?php print sizeof($bill_id); ?>" />
	<input type="hidden" id="itm_tmp" value="<?php if($unic_item_code!='') print $unic_item_code; ?>" />
	<input type="hidden" id="storecrossitm" name="storecrossitm" value="0" />
	<input type="hidden" id="storecrossst" name="storecrossst" value="0" />
	<input type="hidden" id="qty" name="qty" value="" />
	<input type="hidden" id="precheck" value="1" />
	<input type="hidden" id="unic_list_size" value="<?php print sizeof($unic_item_list); ?>" />
	<div id="price_div" style="display:none" ></div>
	<?php if(sizeof($unic_item_list)==0){ ?>
		<input type="hidden" name="unic_item" id="tags4" value="0" />
	<?php }else{ ?>
		<input type="hidden" name="unic_item" value="1" />
	<?php } ?>
		<table width="100%">
		<tr style="font-size:large; background-color:<?php print $main_tale_color; ?>"><td>Cash Back Invoice</td><td colspan="2"><input id="cashback" type="checkbox" onchange="cashBack()" <?php if(isset($_GET['cashback'])){ if($_GET['cashback']==1) print 'checked="checked"'; } ?> /></td></tr>
		<tr style="font-size:large; background-color:<?php print $main_tale_color; ?>"><td>Item Code</td><td colspan="2"><input type="text" name="code" id="tags1" style="width:200px" value="<?php if($unic_item_code!='') print $unic_item_code; ?>" onclick="this.value=''" />&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
		<tr style="font-size:large; background-color:<?php print $main_tale_color; ?>"><td>Item Description</td><td colspan="2"><input type="text" name="description" style="width:200px" id="tags2" onchange="document.getElementById('tags1').value=''" /></td></tr>
		<tr style="font-size:large; background-color:<?php print $main_tale_color; ?>"><td>Quantity</td><td><?php if($is_unic_item==0){ ?><input type="number" name="qty1" id="qty1" style="width:100px" onfocus="getPrice()"  /><?php }else{ print '<input type="button" value="Refresh" onclick="getPrice()" /><input type="hidden" name="qty1" id="qty1" value="1"  />'; } ?></td><td><div style="font-size:medium; padding-right:10px" id="av_qty" align="right"></div><div style="font-size:medium; padding-right:10px; color:#CC0000" id="tt_qty" align="right"></div><input type="hidden" id="av_qty_val" /></td></tr>
	<?php
		$k=0;
		if(sizeof($unic_item_list)>0){
			if($_GET['cashback']==1){
				$k++;
				print '<tr style="font-size:large; background-color:'.$main_tale_color.'"><td>Unic Item</td><td colspan="2"><input type="text" name="unic_item1" id="tags4" onfocus="getPrice() style="width:200px" /></td></tr>';
				print '<tr><td colspan="3">';
				for($i=2;$i<=10;$i++){
					print '<input type="hidden" name="unic_item'.$i.'" id="tags'.($i+3).'" value="" />';
				}
				print '</td></tr>';
			}else{
				for($i=1;$i<=10;$i++){
					if($i<=$unic_qty){
						$k++;
						if($i==1) $trigger='onfocus="getPrice()"'; else $trigger='';
						print '<tr style="font-size:large; background-color:'.$main_tale_color.'"><td>Unic Item'.$i.'</td><td colspan="2"><input type="text" name="unic_item'.$i.'" id="tags'.($i+3).'" '.$trigger.' style="width:200px" /></td></tr>';
			 		}else{
			 			print '<input type="hidden" name="unic_item'.$i.'" id="tags'.($i+3).'" />';
			 		}
			 	}
		 	}
		 	print '<input type="hidden" id="uitem_limit" value="'.$k.'" />';
	} ?>
		<tr style="font-size:large; background-color:<?php print $main_tale_color; ?>"><td>Unit Price</td><td colspan="2">
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
		<input type="hidden" name="discount_type" id="discount_type" value="<?php print $discount; ?>" />
		<input type="hidden" name="salesman" id="salesman" value="<?php print $bill_salesman; ?>" />
		</td></tr>
		<tr style="font-size:large; background-color:<?php print $main_tale_color; ?>"><td><table border="0"><tr><td>Unit Discount</td><td><div id="discount_div" onclick="changeDiscount()" style="color:blue; cursor:pointer;"><?php if($discount=='price') print 'Rs'; else print '%'; ?></div></td></tr></table></td><td colspan="2"><table width="100%"><tr><td><input type="text" name="discount" id="discount" value="0" style="width:50px" onclick="this.value=''" /><input type="button" value="Check" onclick="loadDiscount('<?php print $_GET['cust']; ?>')" /></td><td><div style="font-size:10pt; color:red" id="div_discount" align="right"></div></td></tr></table></td></tr>
		<tr style="font-size:large; background-color:<?php print $main_tale_color; ?>"><td><div id="com" >Drawer No<input type="hidden" name="comment" id="comment" value="" /></div></td><td colspan="2"><div style="font-size:12pt" id="it_drawer"></div></td></tr>
		<tr style="font-size:large; background-color:<?php print $main_tale_color; ?>"><td colspan="2" align="right"><br /><div id="addtobill"><?php if(($bill_user==$bm_sys_user0)) print '<input type="submit" value="Add to Bill" style="width:100px; height:30px" onclick="cashBack()" />'; ?>&nbsp;&nbsp;&nbsp;<br /><br /></div> </td><td align="left">
		<?php if((sizeof($bill_id)>0)&&($bill_user==$bm_sys_user0)) { ?>
			<div id="payment"><input type="Button" value="Payment" style="width:100px; height:70px" onclick="payment()" /></div>
		<?php } ?>
	<!--	<input type="Button" value="Finalyze" style="width:100px; height:70px" onclick="window.location = 'index.php?components=billing&action=finish_bill&id=<?php if(isset($_GET['id'])) print $_GET['id']; ?>'" /> -->
		</td></tr>
		</table>
	</form>
		<?php } ?>
<?php }else{ print '<input type="hidden" name="salesman" id="salesman" value="'.$bill_salesman.'" />'; }  ?>
<hr />
		<table align="left" style="font-size:12pt" width="100%">
		<tr style="height:35px"><th bgcolor="#C5C5C5" >Up to 30+</th><th bgcolor="#C5C5C5" >Up to 14+</th><th bgcolor="#C5C5C5" >Up to 7+</th><th bgcolor="#C5C5C5" >Up to Now</th></tr>
		<tr><td bgcolor="#E5E5E5" align="right" ><?php print number_format($balance30); ?></td><td bgcolor="#E5E5E5" align="right" ><?php print number_format($balance14); ?></td><td bgcolor="#E5E5E5" align="right" ><?php print number_format($balance7); ?></td><td bgcolor="#E5E5E5" align="right" ><?php print number_format($balance0); ?></td></tr>
		<tr><td colspan="4" height="5px"></td></tr>
		<tr><td colspan="3" bgcolor="#E5E5E5" align="right" style="padding-right:10px" >Chque to be Credited</td><td bgcolor="#E5E5E5" align="right" ><?php print number_format($pending_chque); ?></td></tr>
		<tr><td colspan="3" bgcolor="#E5E5E5" align="right" style="padding-right:10px" >Remaining Credit Limit</td><td bgcolor="#E5E5E5" align="right" ><?php print number_format($remaining_cr_limit); ?></td></tr>
		</table>

	</td><td width="10px"></td><td style="vertical-align:top;">
	<div id="landscape" style="vertical-align:top" ></div>
	</td></tr></table>
  </div>
</div>
<hr>
	<div class="w3-row">
	  <div class="w3-col s3">
	  </div>
	  <div class="w3-col" style="vertical-align:top">
		<div id="portrait">
			<?php if(isset($_REQUEST['id'])) { ?>
			<table align="left" bgcolor="#E5E5E5" height="100%">
			<?php
				for($i=0;$i<sizeof($bill_id);$i++){
					$new_description[]=$bi_desc2[$i];
					$counts=array_count_values($new_description);
					$count=$counts[$bi_desc2[$i]];
					$tc=array_search($bi_desc2[$i],$dups);
					if($tc>-1){

						if($dups_count[$tc]==$count) $allow_remove=true; else $allow_remove=false;
					}else $allow_remove=true;
					if($bi_no_update[$i]==0) $update_button='<input type="Button" value="Update" style="width:70px" onclick="updateBill('.$bill_id[$i].')" />'; else $update_button='<input type="Button" value="Update" style="width:70px" onclick="alert('."'Update is Restricted for this item'".')" />';
					if($allow_remove) $remove_button='<input type="Button" value="Remove"  onclick="removeBill('.$bill_id[$i].')" style="background-color:maroon; color:white; width:80px"/>';  else $remove_button='';
					print '<tr style="font-size:12pt"><td width="30px" style="color:blue"><strong>'.($i+1).'</strong></td><td>'.$bi_desc[$i].'</td><td width="10px"></td><td align="right" width="209px"><div id="itmdiv'.$bill_id[$i].'"><input style="width:45px; type="text" id="billitemid'.$bill_id[$i].'" value="'.$bi_qty[$i].'" /> '.$update_button.' '.$remove_button.'</div></td><td width="80px" align="right">'.number_format(($bi_price[$i]*$bi_qty[$i]),$decimal).'</td></tr>';
				}
					print '<tr style="font-size:12pt; font-weight:900;"><td colspan="2">Total Amount</td><td></td><td align="right" colspan="2">'.number_format($total,$decimal).'</td></tr>';
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
					<table width="100%" style="font-size:10pt">
					<tr bgcolor="#F5F5F5"><td>Returned Chques</td><td align="right" style="padding-left:10px; padding-right:10px; color:red"><?php print $chq2_retuned; ?></td></tr>
					<tr bgcolor="#F5F5F5"><td>Postponed Chques</td><td align="right" style="padding-left:10px; padding-right:10px; color:red"><?php print $chq2_postpone; ?></td></tr>
					<tr bgcolor="#F5F5F5"><td>Deposited Chques</td><td align="right" style="padding-left:10px; padding-right:10px; color:blue"><?php print $chq2_banked; ?></td></tr>
					</table>
					<?php
				}
			}else{ ?><br />
				<form id="searchinv" action="index.php?components=billing&action=search_bill&s=<?php print $_GET['s']; ?>&cust_odr=<?php print $_GET['cust_odr']; ?>" method="post" onsubmit="removeLeadingZeros()">
				<table align="center" height="100%">
				<tr><td style="font-size:12pt;"><input type="text" name="search1" id="search1" placeholder="Search Invoice" style="font-size:large; width:200px" /><input type="Submit" value="Search" /></td></tr>
				</table>
				</form>
			<?php } ?>
	  </div>
	</div>
</div>
<hr />
<div class="w3-row">
  <div class="w3-col s3"></div>
  <div class="w3-col " align="center">
  </div>
</div>
<hr>
</div>

<script type="text/javascript">
	billLocation();
	(function () {
	    var field1 = document.getElementById("tags1");
	    var field2 = document.getElementById("tags2");
	    var field3 = document.getElementById("qty1");

	    field1.onkeypress = function () {
	        return enter(field2);
	    };

	    field2.onkeypress = function () {
	        return enter(field3);
	    };


	    function enter(nextfield) {
	        if (window.event && window.event.keyCode === 13) {
	            nextfield.focus();
	            return false;
	        } else {
	            return true;
	        }
	    }
	})();
</script>
<?php
if(isset($unic_item_code))
if($unic_item_code!=''){
	print '<script type="text/javascript"> getPrice(); </script>';
}

if($current_district==''){
	if($systemid==2) print '<script type="text/javascript"> document.getElementById("district").value=10; setDistrict("billing"); </script>';
}

                include_once  'template/m_footer.php';
?>