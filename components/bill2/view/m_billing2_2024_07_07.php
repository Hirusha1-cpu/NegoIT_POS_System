<?php
	include_once  'template/m_header.php';
	if($cust_odr=='yes') $main_tale_color='#C6DEFE'; else $main_tale_color='#E5E5E5';
	$systemid = inf_systemid(1);
	$currency=getCurrency(1);
	$decimal = getDecimalPlaces(1);
?>
<style>
	#code-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
	#code-list li{padding: 20px;; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
	#code-list li:hover{background:#ece3d2;cursor: pointer;}
	#search-code{padding: 20px;;border: #a8d4b1 1px solid;border-radius:4px;}
	#desc-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
	#desc-list li{padding: 20px;; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
	#desc-list li:hover{background:#ece3d2;cursor: pointer;}
	#search-desc{padding: 20px;;border: #a8d4b1 1px solid;border-radius:4px;}
	#sn-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
	#sn-list li{padding: 20px;; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
	#sn-list li:hover{background:#ece3d2;cursor: pointer;}
	#search-sn{padding: 20px;;border: #a8d4b1 1px solid;border-radius:4px;}

	@media only screen and (min-width: 600px) {
		#landscape{
			margin-left: 15px;
		}
	}
	#portrait{
		margin-top: 15px;
	}
	table{
		font-size:12pt;
		font-family:Calibri;
	}
	.distric-table{
		background-color:#EEEEEF;
		border-radius: 15px;
		padding-left:10px;
		padding-right:10px
	}
	.bill-table{
		border-radius: 15px;
		box-shadow: 0 0 10px rgb(0 0 0 / 10%);
	}
	.div-temp-bill{
		box-shadow: 0 0 10px rgb(0 0 0 / 10%);
		background-color:#D1D8F1;
		border-radius: 20px;
		padding: 0 10px;
	}
	.table-cheques, .table-balance{
		padding: 20px 0px;
	}
</style>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
	$(document).ready(function(){
		$("#search-code").keyup(function(){
			if(document.getElementById('search-code').value.length>2){
				$item_filter=document.getElementById('itm_pr_sr').value;
				$.ajax({
				type: "POST",
				url: "index.php?components=bill2&action=code-list&item_type=all&item_filter="+$item_filter,
				data:'keyword='+encodeURIComponent($(this).val()),
				beforeSend: function(){
					$("#search-code").css("background","#FFF url(images/LoaderIcon.gif) no-repeat 165px");
				},
				success: function(data){
					$("#suggesstion-code").show();
					$("#suggesstion-code").html(data);
					$("#search-code").css("background","#FFF");
				}
				});
			}
		});
		$("#search-desc").keyup(function(){
			if(document.getElementById('search-desc').value.length>2){
				$item_filter=document.getElementById('itm_pr_sr').value;
				$.ajax({
				type: "POST",
				url: "index.php?components=bill2&action=desc-list&item_type=all&item_filter="+$item_filter,
				data:'keyword='+encodeURIComponent($(this).val()),
				beforeSend: function(){
					$("#search-desc").css("background","#FFF url(images/LoaderIcon.gif) no-repeat 165px");
				},
				success: function(data){
					$("#suggesstion-desc").show();
					$("#suggesstion-desc").html(data);
					$("#search-desc").css("background","#FFF");
				}
				});
			}
		});
		$("#search-sn").keyup(function(){
			if(document.getElementById('search-sn').value.length>2){
				$itm_id=document.getElementById('itm_id').value;
				$bm_no=document.getElementById('bm_no').value;
				$cust_id=document.getElementById('cust_id').value;
				if(document.getElementById('cashback').checked) $cashback=1; else $cashback=0;
				$.ajax({
				type: "POST",
				url: "index.php?components=bill2&action=sn-list&cashback="+$cashback+"&item_id="+$itm_id+"&bm_no="+$bm_no+"&cust_id="+$cust_id,
				data:'keyword='+encodeURIComponent($(this).val()),
				beforeSend: function(){
					$("#search-sn").css("background","#FFF url(images/LoaderIcon.gif) no-repeat 165px");
				},
				success: function(data){
					$("#suggesstion-sn").show();
					$("#suggesstion-sn").html(data);
					$("#search-sn").css("background","#FFF");
				}
				});
			}
		});
	});

	function selectCode(val) {
		$("#search-code").val(val);
		$("#suggesstion-code").hide();
		getItemData('code',val);
	}

	function selectDesc(val) {
		$("#search-desc").val(val);
		$("#suggesstion-desc").hide();
		getItemData('desc',val);
	}

	function selectSN(val) {
		$("#search-sn").val(val);
		$("#suggesstion-sn").hide();
		$itu_id=document.getElementById('sn_'+val).value;
		document.getElementById("itu_chbox_"+$itu_id).checked=true;
		setSN($itu_id);
		document.getElementById("search-sn").value="";
	}

	function selectSN2(val) {
		$("#search-sn").val(val);
		$("#suggesstion-sn").hide();
		getUnicCashbackData(val);
	}

	function getUnicCashbackData(sn){
		if(document.getElementById('cashback').checked){
			document.getElementById('div_iuprice').innerHTML=document.getElementById('loading').innerHTML;
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var myObj = JSON.parse(xmlhttp.responseText);
					document.getElementById("itu_list_tmp").value=myObj.itu_id+'|';
					document.getElementById('uprice0').innerHTML=thousands_separators(myObj.unit_price);
					document.getElementById('div_leftpannel').innerHTML='<input type="hidden" id="itu_price_'+myObj.itu_id+'" value="cashback,'+myObj.unit_price +'" />';
					document.getElementById('div_iuprice').innerHTML='';
				}
			};
			xmlhttp.open("POST", "index.php?components=bill2&action=get_unic_cashback_data", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send('sn='+sn);
		}
	}

	function getItemData($case,$val){
		$val=encodeURIComponent($val);
		if(document.getElementById('cashback').checked) $cashback=1; else $cashback=0;
		$itm_qty=0;
		document.getElementById('qty').value='';
		document.getElementById('div_idesc').innerHTML=document.getElementById('loading').innerHTML;
		document.getElementById('div_icode').innerHTML=document.getElementById('loading').innerHTML;
		document.getElementById('av_qty').innerHTML=document.getElementById('loading').innerHTML;
		document.getElementById('div_iuprice').innerHTML=document.getElementById('loading').innerHTML;
		var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
					var myObj = JSON.parse(this.responseText);
					document.getElementById("itm_id").value=myObj.itm_id;
					document.getElementById("itm_unic").value=myObj.itm_unic;
					document.getElementById("itm_pr_sr").value=myObj.itm_pr_sr;
					document.getElementById("search-code").value=myObj.itm_code;
					document.getElementById("search-desc").value=myObj.itm_desc;
					document.getElementById("last_price").value=myObj.itm_lprice;
					if(myObj.itm_qty!=null) $itm_qty=myObj.itm_qty;
					document.getElementById('av_qty').innerHTML='Current Stock - '+$itm_qty;
					if(myObj.itn_qty!=null){
						document.getElementById('tt_qty').innerHTML='New Stock - '+myObj.itn_qty;
					}else{
						document.getElementById('tt_qty').innerHTML='';
					}
					document.getElementById("uprice0").innerHTML=myObj.itm_uprice;
					if((myObj.itm_pr_sr=='1')||(myObj.itm_pr_sr=='2')){
						document.getElementById('div_repair').style.display="none";
						document.getElementById('div_leftpannel').innerHTML='';
					}
					if(myObj.itm_pr_sr=='3'){
						showRepInputs();
					}
					if(myObj.itm_unic=='1'){
						document.getElementById('av_qty').innerHTML='';
						document.getElementById('tt_qty').innerHTML='';
						document.getElementById('h_div').innerHTML='S/N';
						document.getElementById('sn_div').style.display="block";
						document.getElementById('qty_div').style.display="none";
						if($cashback==0) getSNList();
					}else{
						document.getElementById('h_div').innerHTML='Quantity';
						document.getElementById('sn_div').style.display="none";
						document.getElementById('qty_div').style.display="block";
					}

					document.getElementById('div_idesc').innerHTML='';
					document.getElementById('div_icode').innerHTML='';
					document.getElementById('div_iuprice').innerHTML='';
				}
			};
		//	window.location = 'index.php?components=bill2&action=more_item&case='+$case+'&val='+$val+'&bm_no=<?php print $_GET['bill_no']; ?>';
		xhttp.open("GET", 'index.php?components=bill2&action=more_item&case='+$case+'&val='+$val+'&bm_no=<?php print $_GET['bill_no']; ?>', true);
		xhttp.send();
	}

	function getSNList(){
		$bm_no=document.getElementById("bm_no").value;
		$itm_id=document.getElementById("itm_id").value;
		$discount=document.getElementById("discount").value;
		document.getElementById("itu_list_tmp").value='';
		if(isNaN($discount)) $discount=0;

		$datalist='<table style="width:100%; margin-bottom:10px; box-shadow: 0 0 10px rgb(0 0 0 / 10%);" align="center"><tr style="background-color:#467898; color:white;"><td></td><td></td><td align="center">Dis. Price</td><td></td></tr>';

		document.getElementById('div_leftpannel0').innerHTML=document.getElementById('loading').innerHTML+' <span style="font-size:16pt;">Loading</span>';
		var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if(this.readyState == 4 && this.status == 200) {
					var returntext=this.responseText;
					if(returntext!=''){
						$items=returntext.split('|');
						for($i=0;$i<$items.length;$i++){
							$item=$items[$i].split(',');
							$itu_id=$item[0];
							$uprice=thousands_separators(parseFloat($item[2])-parseFloat($discount));
							if(($i%2)==0) $color='#EEEEEE'; else $color='#FAFAFA';
							$datalist+=
							'<tr style="background-color:'+$color+';">'
								+'<td class="shipmentTB3" style="color:blue">'+$item[1]+'</td>'
								+'<td class="shipmentTB3" align="center">'
									+'<select id="itu_price_'+$itu_id+'" onchange="calDprice('+$itu_id+')">'
										+'<option value="r,'+$item[2]+'">Retail - '+thousands_separators($item[2])+'</option>'
										+'<option value="w,'+$item[3]+'">Wholesale - '+thousands_separators($item[3])+'</option>'
									+'</select>'
									+'<input type="hidden" id="r_'+$itu_id+'" value="'+$item[2]+'" />'
									+'<input type="hidden" id="w_'+$itu_id+'" value="'+$item[3]+'" />'
									+'<input type="hidden" id="sn_'+$item[1]+'" value="'+$itu_id+'" />'
								+'</td>'
								+'<td class="shipmentTB3" align="center">'
									+'<input type="text" id="dis_price_'+$itu_id+'" style="width:60px; text-align:right;" readonly="readonly" value="'+$uprice+'" /></td>'
									+'<td class="shipmentTB3" align="center">'
										+'<input type="checkbox" id="itu_chbox_'+$itu_id+'" onclick="setSN('+$itu_id+')" /></td>'
							+'</tr>';
						}
					}
					$datalist+='</table>';
					document.getElementById('div_leftpannel').innerHTML=$datalist;
					document.getElementById('div_leftpannel0').innerHTML='<br /><br />';
				}
			};
		xhttp.open("GET", 'index.php?components=bill2&action=get_sn_list&itm_id='+$itm_id+'&bm_no='+$bm_no, true);
		xhttp.send();
	}

	function calDprice($itu_id){
		$discount=document.getElementById("discount").value;
		if(isNaN($discount)) $discount=0;
		$price_code=document.getElementById("itu_price_"+$itu_id).value;
		$uprice=document.getElementById($price_code+'_'+$itu_id).value;
		document.getElementById("dis_price_"+$itu_id).value=thousands_separators(parseFloat($uprice)-parseFloat($discount));
	}

	function setSN($itu_id){
		$itu_list_tmp=document.getElementById("itu_list_tmp").value;
		if(document.getElementById("itu_chbox_"+$itu_id).checked){
			$itu_list_tmp+=$itu_id+'|';
		}else{
			$itu_list_tmp=$itu_list_tmp.replace($itu_id+'|', '');
		}
		document.getElementById("itu_list_tmp").value=$itu_list_tmp;
	}

	function addToBill(){
		$bm_no=document.getElementById("bm_no").value;
		$itm_id=document.getElementById("itm_id").value;
		$qty=document.getElementById("qty").value;
		$discount=document.getElementById("discount").value;
		$itm_unic=document.getElementById("itm_unic").value;
		$last_price=document.getElementById("last_price").value;
		$discount_type=document.getElementById("discount_type").value;
		$cust_id=document.getElementById("cust_id").value;
		// Repair
		$comment=document.getElementById("comment").value;
		$repair_model=document.getElementById("repair_model").value;
		$repair_sn=document.getElementById("repair_sn").value;
		$technicient=document.getElementById("technicient").value;
		$itm_pr_sr = document.getElementById("itm_pr_sr").value;

		if(document.getElementById('cashback').checked) $cashback=1; else $cashback=0;
		$itu_list='';
		$uprice=0;
		$msg='';
		$out=true;

		if((document.getElementById("search-code").value=='')||(document.getElementById("search-desc").value=='')){
			$msg='<span style="color:orange; font-weight:bold; font-size:12pt;">Please Select an Item</span>';
			$out=false;
		}

		if($itm_unic=='1'){
			$itu_list_tmp=document.getElementById("itu_list_tmp").value;
			$itu_list_tmp=$itu_list_tmp.slice(0, -1);
			$items=$itu_list_tmp.split('|');
			for($i=0;$i<$items.length;$i++){
				$itu_id=$items[$i];
				$price=document.getElementById("itu_price_"+$itu_id).value;
				$itu_list+=$itu_id+','+$price+'|';

				if($discount_type=='percentage') $discount=((($price.slice(2))/100)*($discount)).toFixed(2);
				$fprice=$price.slice(2)-$discount;
				<?php if($systemid != 20){ ?>
					if(($last_price!='NA')&&($last_price>$fprice)){
						$out=false;
						$msg='<span style="color:red; font-weight:bold; font-size:12pt;">Price Under Value</span>';
					}
				<?php } ?>
			}
		}else{
			$uprice=document.getElementById("uprice").value;
			if($discount_type=='percentage') $discount=((($uprice.slice(2))/100)*($discount)).toFixed(2);
			$fprice=$uprice.slice(2)-$discount;
			<?php if($systemid != 20){ ?>
				if(($last_price!='NA')&&($last_price>$fprice)){
					$out=false;
					$msg='<span style="color:red; font-weight:bold; font-size:12pt;">Price Under Value</span>';
				}
			<?php } ?>
			if($cashback==1) $qty=-$qty;
			if(($qty==0)||($qty=='')){
				$msg='<span style="color:orange; font-weight:bold; font-size:12pt;">Quantity cannot be 0</span>';
				$out=false;
			}
		}
		if($itm_pr_sr == 2){
			if(document.getElementById("uprice").value <= 0) {
				$msg='<span style="color:orange; font-weight:bold; font-size:12pt;">Service amount cannot be less than or equal to 0</span>';
				$out=false;
			}
		}

		$itu_list=$itu_list.slice(0, -1);

		if(!$out){
			document.getElementById('notifications').innerHTML=$msg;
		}

		if($out){
			document.getElementById('div_add_to_bill').innerHTML=document.getElementById('loading').innerHTML;
			var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
						var returntext=this.responseText;
						if(returntext=='Done'){
							document.getElementById('notifications').innerHTML='<span style="color:green; font-weight:bold; font-size:12pt;">Item was Added to the Bill</span>';
							document.getElementById('div_leftpannel').innerHTML='';
							document.getElementById('search-code').value='';
							document.getElementById('search-desc').value='';
							document.getElementById('discount').value=0;
							document.getElementById('qty').value=0;
							document.getElementById('uprice').value=0;
							document.getElementById('div_discount').innerHTML='';
							hideAndResetRepair();
						}else{
							document.getElementById('notifications').innerHTML='<span style="color:red; font-weight:bold; font-size:12pt;">'+returntext+'</span>';
						}
						document.getElementById('div_add_to_bill').innerHTML='<input type="button" value="Add to Bill" onclick="addToBill()" style="width:100px; height:50px;" />';
						getBillItems();
					}
				};
		//	window.location = 'index.php?components=bill2&action=add_to_bill&bm_no='+$bm_no+'&itm_id='+$itm_id+'&unic='+$itm_unic+'&qty='+$qty+'&discount='+$discount+'&uprice='+$uprice+'&itu_list='+$itu_list+'&comment='+$comment+'&rp_model='+$repair_model+'&rp_sn='+$repair_sn+'&technicient='+$technicient+'&discount_type='+$discount_type+'&cust_id='+$cust_id;
			xhttp.open("GET", 'index.php?components=bill2&action=add_to_bill&bm_no='+$bm_no+'&itm_id='+$itm_id+'&unic='+$itm_unic+'&qty='+$qty+'&discount='+$discount+'&uprice='+$uprice+'&itu_list='+$itu_list+'&comment='+$comment+'&rp_model='+$repair_model+'&rp_sn='+$repair_sn+'&technicient='+$technicient+'&discount_type='+$discount_type+'&cust_id='+$cust_id, true);
			xhttp.send();
		}
	}

	function getBillItems(){
		$bm_no=document.getElementById("bm_no").value;
		document.getElementById('div_bill_item0').innerHTML=document.getElementById('loading').innerHTML;
		$datalist='<table align="center" bgcolor="#E5E5EA" style="border-radius:5px; font-size:12pt" border="0">';
		$sub_total=0;
		$i=0;

		var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
					var returntext=this.responseText;
					if(returntext!=''){
						$items=returntext.split('|');
						for($i=0;$i<$items.length;$i++){
							$item=$items[$i].split(',');
							$bill_id=$item[0];
							if($item[4]=='') $item_desc=$item[1]; else $item_desc=$item[1]+'<br />'+$item[4];
							$item_qty=$item[2];
							$item_uprice=$item[3];
							$total=$item_qty*$item_uprice;
							$sub_total+=$total;
							$update_button='<input type="button" value="Update" style="width:64px;" onclick="updateBillItem('+$bill_id+')" />';
							$remove_button='<input type="button" value="Remove"  onclick="removeBillItem('+$bill_id+')" style="background-color:maroon; color:white; width:64px;" />';
							$datalist+='<tr><td class="tb2"width="10px" style="color:blue" valign="top"><strong>'+ ($i+1) +'</strong></td><td class="tb2">'+$item_desc+'</td><td width="20px"></td><td class="tb2" align="right"><div id="itmdiv'+$bill_id+'"><input type="number" id="bi_qty_'+$bill_id+'" value="'+$item_qty+'" style="width:64px; text-align:right;" /> '+$update_button+' '+$remove_button+'</div></td><td class="tb2" width="10px" align="right">'+thousands_separators($total)+'</td><td><div id="actiondiv_'+$bill_id+'"></div></td></tr>';
							$datalist+='<tr><td colspan="6" style="background-color:#F5F5F5" height="3px"></td></tr>';
						}
						$datalist+='<tr><td colspan="4" class="tb2"><strong>Total Amount</strong></td><td class="tb2" align="right"><strong>'+thousands_separators($sub_total)+'</strong></td><td></td></tr>';
					}
				$datalist+='</table>';

				document.getElementById('div_bill_item0').innerHTML='';
				document.getElementById('div_bill_item').innerHTML=$datalist;
				if($i>0){
					document.getElementById('div_submit').style = "display:inline-block; margin:20px;";
					document.getElementById('div_submit').innerHTML='<input type="button" value="To Payment" onclick="proceedToPayment()" style="width:100px; height:50px;" />';
				}

				}
			};
		xhttp.open("GET", 'index.php?components=bill2&action=get_tmp_bill_items&bm_no='+$bm_no, true);
		xhttp.send();
	}

	function removeBillItem($bill_id){
		document.getElementById('itmdiv'+$bill_id).innerHTML=document.getElementById('loading').innerHTML;
		var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if(this.readyState == 4 && this.status == 200) {
					var returntext=this.responseText;
					if(returntext=='Done'){
						document.getElementById('search-code').value='';
						document.getElementById('search-desc').value='';
						document.getElementById('discount').value=0;
						hideAndResetRepair();
						document.getElementById('notifications').innerHTML='<span style="color:green; font-weight:bold; font-size:12pt;">Item was Removed from the Bill</span>';
					}else{
						document.getElementById('notifications').innerHTML='<span style="color:red; font-weight:bold; font-size:12pt;">'+returntext+'</span>';
					}
					getBillItems();
				}
			};
		xhttp.open("GET", 'index.php?components=bill2&action=remove_tmp_bill_item&bill_id='+$bill_id, true);
		xhttp.send();
	}

	function updateBillItem($bill_id){
		var $itemqty=document.getElementById('bi_qty_'+$bill_id).value;
		document.getElementById('itmdiv'+$bill_id).innerHTML=document.getElementById('loading').innerHTML;

		var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if(this.readyState == 4 && this.status == 200) {
					var returntext=this.responseText;
					if(returntext=='Done'){
						document.getElementById('notifications').innerHTML='<span style="color:green; font-weight:bold; font-size:12pt;">Quantity was Updated</span>';
					}else{
						document.getElementById('notifications').innerHTML='<span style="color:red; font-weight:bold; font-size:12pt;">'+returntext+'</span>';
					}
					getBillItems();
				}
			};
		xhttp.open("GET", 'index.php?components=bill2&action=update_tmp_bill_item&bill_id='+$bill_id+'&qty='+$itemqty, true);
		xhttp.send();
	}

	function proceedToPayment(){
		$bm_no=document.getElementById("bm_no").value;
		document.getElementById('div_bill_item0').innerHTML=document.getElementById('loading').innerHTML;
		$count=0;
		$out_err='Validation Failed';

		var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
					var returntext=this.responseText;
					$items=returntext.split('|');
					for($i=0;$i<$items.length;$i++){
						$item=$items[$i].split(',');
						$bill_id=$item[0];
						$out=$item[1];
						if($out=='ok'){
							document.getElementById('actiondiv_'+$bill_id).innerHTML='<a title="Okay to Proceed">'+document.getElementById('img_ok').innerHTML+'</a>';
						}else{
							document.getElementById('actiondiv_'+$bill_id).innerHTML='<a title="'+$out+'">'+document.getElementById('img_err').innerHTML+'</a>';
							$out_err=$out;
							$count++;
						}
					}
				if($count==0){
					document.getElementById('notifications').innerHTML='<span style="color:green; font-weight:bold; font-size:12pt;">Validated Successfully</span>';
					window.location = 'index.php?components=bill2&action=pay_bill&cust_odr=<?php print $cust_odr; ?>&bill_no='+$bm_no;
				}else{
					document.getElementById('notifications').innerHTML='<span style="color:red; font-weight:bold; font-size:12pt;">'+$out_err+'</span>';
				}
				document.getElementById('div_bill_item0').innerHTML='';
				}
			};
		xhttp.open("GET", 'index.php?components=bill2&action=validate_tmp_bill&bm_no='+$bm_no+'&case=detail', true);
		xhttp.send();
	}

	//--------------------------------------------------------//

	function changeSalesman($bm_no,$new_sm){
		document.getElementById('div_sm_update').innerHTML=document.getElementById('loading').innerHTML;
		var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
					var returntext=this.responseText;
					if(returntext=='Done') document.getElementById('div_sm_update').innerHTML='<span style="color:green">Done</span>';
					else document.getElementById('div_sm_update').innerHTML='<span style="color:red">Error</span>';
				}
			};
		xhttp.open("GET", 'index.php?components=bill2&action=change_salesman&bm_no='+$bm_no+'&new_sm='+$new_sm, true);
		xhttp.send();
	}

	function changeRecoveryAgent($bm_no,$new_rag){
		document.getElementById('div_recovery_agent').innerHTML=document.getElementById('loading').innerHTML;
		var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
					var returntext=this.responseText;
					if(returntext=='Done') document.getElementById('div_recovery_agent').innerHTML='<span style="color:green">Done</span>';
					else document.getElementById('div_recovery_agent').innerHTML='<span style="color:red">Error</span>';
				}
			};
		xhttp.open("GET", 'index.php?components=bill2&action=change_recovery_agent&bm_no='+$bm_no+'&new_rag='+$new_rag, true);
		xhttp.send();
	}

	//-----------------------------Discount Check-----------------------------//
	function loadDiscount($cust) {
		document.getElementById('div_dis_st').innerHTML=document.getElementById('loading').innerHTML;
		var $itemid=document.getElementById('itm_id').value;
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				// var returntext=this.responseText;
				// var n=returntext.indexOf("|");
				// var m = returntext.returntext;
				// var dis=returntext.substring(n+1, m);
				// $last_discount='<br />Last Dis: ' +dis+'%';
				// document.getElementById("div_discount").innerHTML = returntext.substring(0, n)+$last_discount;
				// document.getElementById("discount").value = dis;
				// document.getElementById('div_dis_st').innerHTML='';

				var returntext=this.responseText;
				var n=returntext.indexOf("|");
				var m = returntext;
				var dis=returntext.substring(n-1, m);
				$last_discount='<br/>Last Dis: ' +dis+'%';
				document.getElementById("div_discount").innerHTML = returntext.substring(0, n)+$last_discount;
				document.getElementById("discount").value = dis;
				document.getElementById('div_dis_st').innerHTML='';
			}
		};
		xhttp.open("GET", 'index.php?components=bill2&action=get_discount&itemid='+$itemid+'&cust='+$cust, true);
		xhttp.send();
	}

	function changeDiscount(){
		var old_discount_type=document.getElementById('discount_type').value;
		if(old_discount_type=='price'){
			document.getElementById('discount_type').value='percentage';
			document.getElementById('discount_div').innerHTML='%';
		}else{
			document.getElementById('discount_type').value='price';
			document.getElementById('discount_div').innerHTML='<?php print $currency; ?>';
		}
	}

	function deleteTMPBill(){
		$bm_no=document.getElementById('bm_no').value;
		$cust_odr='<?php print $cust_odr; ?>';
		var check= confirm("Do you want to Delete this Temp Bill?");
		if(check== true)
		window.location = 'index.php?components=bill2&action=delete_tmp_bill&bm_no='+$bm_no+'&cust_odr='+$cust_odr;
	}

	function cashbackToggle(){
	if((document.getElementById("search-code").value!='')||(document.getElementById("search-desc").value!='')){
		if(document.getElementById("cashback").checked){
				document.getElementById("cashback").checked=false;
		}else{
				document.getElementById("cashback").checked=true;
		}
	}
	}

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

	function showRepInputs(){
		document.getElementById('div_repair').style.display="block";
		document.getElementById('tr_comment').style.display="table-row";
		document.getElementById('tr_rp_model').style.display="table-row";
		document.getElementById('tr_rp_sn').style.display="table-row";
		document.getElementById('tr_rp_tc').style.display="table-row";
	}

	function hideAndResetRepair(){
		document.getElementById('div_repair').style.display="none";
		document.getElementById('tr_comment').style.display="none";
		document.getElementById('tr_rp_model').style.display="none";
		document.getElementById('tr_rp_sn').style.display="none";
		document.getElementById('tr_rp_tc').style.display="none";

		document.getElementById("comment").value = '';
		document.getElementById("repair_model").value = '';
		document.getElementById("repair_sn").value = '';
		document.getElementById("technicient").options[0].id = '';
	}
</script>

<?php
	if (isset($_REQUEST['message'])){
		if ($_REQUEST['re'] == 'success') $color = 'green';	else $color = '#DD3333';
		if(strpos($_REQUEST['message'],'|')==false){
			$message='<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>';
		}else{
			$messages=explode("|",$_REQUEST['message']);
			$message='<span style="color:green; font-weight:bold;font-size:12pt;">'.$messages[0].'</span> | <span style="color:#DD3333; font-weight:bold;font-size:12pt;">'.$messages[1].'</span>';
		}
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML=\''.$message.'\'</script>';
	}
?>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<div id="loading2" style="display:none"><img src="images/loading2.gif" style="width:15px" /></div>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<div id="img_ok" style="display:none"><img src="images/action_check.gif" /></div>
<div id="img_err" style="display:none"><img src="images/action_delete.gif" /></div>

<div class="w3-container" style="margin-top:75px">
	<table align="center">
		<tr>
			<td>
				<div id="notifications"></div>
			</td>
		</tr>
	</table>
	<hr>
	<div class="w3-row">
		<div class="w3-col">
			<input type="hidden" id="bm_no" value="<?php print $_GET['bill_no']; ?>" />
			<input type="hidden" id="cust_id" value="<?php print $cu_id; ?>" />
			<input type="hidden" id="itm_id" value="" />
			<input type="hidden" id="itm_unic" value="" />
			<input type="hidden" id="itm_pr_sr" value="" />
			<input type="hidden" id="last_price" value="NA" />
			<input type="hidden" name="discount_type" id="discount_type" value="<?php print $discount; ?>" />
			<input type="hidden" id="itu_list_tmp" value="" />
			<input type="hidden" id="gps_x" name="gps_x" value="0" />
			<input type="hidden" id="gps_y" name="gps_y" value="0" />

			<table align="center" border="0">
				<div id="div_leftpannel"></div>
				<tr>
					<td valign="top">
						<?php if($current_district!=''){ ?>
							<table align="center" bgcolor="<?php print $main_tale_color; ?>" border="0" class="bill-table">
								<tr>
									<td colspan="5">
										<br/>
									</td>
								</tr>
								<?php
									print '<tr><td width="20px;"></td><td>';
									if($cust_mtype=='primary') print '<span style="color:red">Primary<br/>Customer</span>';
									elseif($cust_mtype=='secondary') print '<span style="color:red">Secondary<br/>Customer</span>';
									else print 'Customer';
									print '</td><td colspan="2">';
									print '<span>'.$cu_name.'</span>';
									print '</td><td width="20px;"></td></tr>';
									print '<tr><td width="20px;"></td><td></td><td colspan="2" style="color:maroon">';
									print 'NIC &nbsp;: '.$cu_nic.'<br />';
									print 'Mob : '.$cu_mobile.'<br />';
									print '</td><td width="20px;"></td></tr>';
									print '<tr><td width="20px;"></td><td>Ref</td><td colspan="2" style="color:maroon">'.$cu_asso_sman.'</td><td width="20px;"></td></tr>';
								?>
								<!-- Salesman -->
								<tr>
									<td width="20px;"></td>
									<td>Salesman</td>
									<td colspan="2">
										<?php if(($systemid==4)||($systemid==13)||($systemid==15)||($systemid==20)||($systemid==24)){ ?>
											<?php if($bill_item_count==0){ ?>
												<select name="salesman" id="salesman" onchange="changeSalesman(<?php print $_GET['bill_no']; ?>,this.value)" >
												<?php for($i=0;$i<sizeof($sm_id);$i++){
													if($sm_id[$i]==$salesman_id) $select='selected="selected"'; else $select='';
													print '<option value="'.$sm_id[$i].'" '.$select.' >'.ucfirst($sm_name[$i]).'</option>';
												} ?>
												</select>
											<?php }else{
												print '<input type="text" value="'.$salesman_name.'" disabled="disabled" />';
											} ?>

										<?php }else{
											print '<input type="text" value="'.$salesman_name.'" disabled="disabled" />';
										} ?>
									</td>
									<td width="20px;" align="center">
										<dir id="div_sm_update"></dir>
									</td>
								</tr>
								<!-- Recover Agent -->
								<?php if($hire_purchase){ ?>
								<tr>
									<td width="20px;"></td>
									<td>Recovery Agent</td>
									<td colspan="2">
										<select onchange="changeRecoveryAgent(<?php print $_GET['bill_no']; ?>,this.value)" >
											<option value="">-SELECT-</option>
											<?php
												if(isset($rag_id)){
													for($i=0;$i<sizeof($rag_id);$i++){
													if($rag_id[$i]==$recovery_agent) $select='selected="selected"'; else $select='';
													print '<option value="'.$rag_id[$i].'" '.$select.' >'.ucfirst($rag_name[$i]).'</option>';
													}
												}
											?>
										</select>
									</td>
									<td width="20px;" align="center">
										<dir id="div_recovery_agent"></dir>
									</td>
								</tr>
								<?php } ?>
								<!-- Cash Back Invoice  -->
								<tr>
									<td width="20px;"></td>
									<td>Cash Back Invoice</td>
									<td colspan="2">
										<input id="cashback" type="checkbox" onchange="cashbackToggle()" <?php if(isset($_GET['cashback'])){ if($_GET['cashback']==1) print 'checked="checked"'; } ?> />
									</td>
									<td width="20px;"></td>
								</tr>
								<!--  Item Code -->
								<tr>
									<td width="20px;"></td>
									<td>Item Code</td>
									<td colspan="2">
										<div class="frmSearch">
											<input type="text" id="search-code" value="" onclick="this.value=''" placeholder="Item Code" autocomplete="off" />
											<div id="suggesstion-code"></div>
										</div>
									</td>
									<td width="20px;">
										<div id="div_icode"></div>
									</td>
								</tr>
								<!-- Item Description -->
								<tr>
									<td width="20px;"></td>
									<td>Item Description</td>
									<td colspan="2">
										<div class="frmSearch">
											<input type="text" id="search-desc" value="" onclick="this.value=''" placeholder="Item Description" autocomplete="off" />
											<div id="suggesstion-desc"></div>
										</div>
									</td>
									<td width="20px;">
										<div id="div_idesc"></div>
									</td>
								</tr>
								<!-- Quantity -->
								<tr>
									<td></td>
									<td>
										<div id="h_div">Quantity</div>
									</td>
									<td>
										<div id="sn_div" style="display:none">
											<input type="text" id="search-sn" value="" onclick="this.value=''" autocomplete="off" />
											<div id="suggesstion-sn"></div>
										</div>
										<div id="qty_div">
											<input type="number" name="qty" id="qty" style="width:65px" onclick="this.value=''" />
										</div>
									</td>
									<td>
										<div id="av_qty" align="right"></div>
										<div style="color:#CC0000" id="tt_qty" align="right"></div>
										<input type="hidden" id="av_qty_val" />
									</td>
									<td></td>
								</tr>
								<!-- Unit Price -->
								<tr>
									<td width="20px;"></td>
									<td>Unit Price</td>
									<td colspan="2">
										<div id="uprice0"></div>
									</td>
									<td width="20px;">
										<div id="div_iuprice"></div>
									</td>
								</tr>
								<!-- Unit Discount -->
								<tr>
									<td width="20px;"></td>
									<td>
										<table border="0">
											<tr>
												<td>Unit Discount</td>
												<td>
													<div id="discount_div" onclick="changeDiscount()" style="color:blue; cursor:pointer;"><?php if($discount=='price') print $currency; else print '%'; ?></div>
												</td>
											</tr>
										</table>
									</td>
									<td colspan="2">
										<table width="100%">
											<tr>
												<td>
													<input type="number" name="discount" id="discount" style="width:50px" onclick="this.value=''" />
													<input type="button" value="Cal" onclick="getSNList()"/>
													<input type="button" value="Check" onclick="loadDiscount('<?php print $cu_id; ?>')" />
												</td>
												<td>
													<div style="color:red" id="div_discount" align="right"></div>
												</td>
											</tr>
										</table>
									</td>
									<td width="20px;">
										<div id="div_dis_st"></div>
									</td>
								</tr>
								<!-- Comments -->
								<tr id="tr_comment" style="display:none">
									<td></td>
									<td>Comment</td>
									<td colspan="2">
										<div id="rp_comment_div">
											<textarea name="comment" id="comment"></textarea>
										</div>
									</td>
									<td width="20px;"></td>
								</tr>
								<!-- Repair Model -->
								<tr id="tr_rp_model" style="display:none;">
									<td></td>
									<td>Repair Model</td>
									<td colspan="2">
										<div id="rp_mod_div">
											<input type="text" id="repair_model" name="repair_model" placeholder="Repair Model"/>
										</div>
									</td>
									<td width="20px;"></td>
								</tr>
								<!-- Repair SN -->
								<tr id="tr_rp_sn" style="display:none;">
									<td></td>
									<td>Repair SN</td>
									<td colspan="2">
										<div id="rp_sn_div">
											<input type="text" id="repair_sn" name="repair_sn" placeholder="Repair SN"/>
										</div>
									</td>
									<td width="20px;"></td>
								</tr>
								<!-- Repair Technician -->
								<tr id="tr_rp_tc" style="display:none;">
									<td></td>
									<td>Technicient</td>
									<td colspan="2">
										<div id="tc_div">
										<select name="technicient" id="technicient">
											<option value="">-SELECT Technicient-</option>
											<?php
												if(isset($tech_id)){
													for($i=0;$i<sizeof($tech_id);$i++){
													print '<option value="'.$tech_id[$i].'">'.ucfirst($tech_name[$i]).'</option>';
													}
												}
											?>
											</select>
										</div>
									</td>
									<td width="20px;"></td>
								</tr>
								<!-- Submit -->
								<tr>
									<td colspan="5" align="center">
										<div id="div_add_to_bill" style="margin:20px; display: inline-block;">
											<input type="button" value="Add to Bill" onclick="addToBill()" style="width:100px; height:50px;" />
										</div>
										<div id="div_submit" style="display:none; margin:20px;">
											<input type="button" value="To Payment" onclick="proceedToPayment()" style="width:100px; height:50px;" />
										</div>
									</td>
								</tr>
							</table>
						<?php }?>
					</td>
					<td style="vertical-align:top;">
						<div id="landscape" style="vertical-align:top" ></div>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="w3-row">
		<div class="w3-col s3"></div>
		<div class="w3-col">
			<div id="portrait">
				<div class="div-temp-bill">
					<br />
					<table width="100%">
						<tr>
							<td>
								<div id="printorder_h">
									<span style="font-weight:bold; font-size:12pt;">Temp Bill No: &nbsp;&nbsp;&nbsp;T<?php print str_pad($_GET['bill_no'], 7, "0", STR_PAD_LEFT); ?></span>
								</div>
							</td>
							<td width="20px"></td>
							<td>
								<input type="button" value="Delete Bill" onclick="deleteTMPBill()" style="background-color:maroon; color:white" />
							</td>
							<td>
								<div id="div_bill_item0"></div>
							</td>
						</tr>
					</table>
					<br>
					<div id="div_bill_item">
					</div>
					<?php
					if($bill_item_count==0){
						print '<br/><hr /><br />';
						print '<table width="100%"><tr bgcolor="#E5E5FF"><th>Chque No</th><th width="100px">Amount</th></tr>';
						for($k=0;$k<sizeof($chq0_code);$k++){
							print '<tr bgcolor="#EFEFEF" style="color:#CC0000; font-weight:bold"><td style="padding-left:20px;; padding-right:20px;"><a href="index.php?components=bill2&action=chque_return" style="color:#CC0000; text-decoration:none;">'.$chq0_code[$k].'</a></td><td align="right" style="padding-left:20px;; padding-right:20px;">'.number_format($chq0_amount[$k], $decimal).'</td></tr>';
						}
						print '</table>';
						?><hr />
						<br>
					<?php } ?>
					<br />
				</div>
				<table width="100%"align="center" class="table-cheques">
						<tr bgcolor="#F5F5FF"><td>Returned Chques</td><td align="right" style="padding-left:20px;; padding-right:20px;; color:red"><?php print $chq2_retuned; ?></td></tr>
						<tr bgcolor="#F5F5FF"><td>Postponed Chques</td><td align="right" style="padding-left:20px;; padding-right:20px;; color:red"><?php print $chq2_postpone; ?></td></tr>
						<tr bgcolor="#F5F5FF"><td>Deposited Chques</td><td align="right" style="padding-left:20px;; padding-right:20px;; color:blue"><?php print $chq2_banked; ?></td></tr>
				</table>
				<table align="center" width="100%" class="table-balance">
					<tr style="height:35px">
						<th bgcolor="#C5C5C5" >Up to 30+</th>
						<th bgcolor="#C5C5C5" >Up to 14+</th
						><th bgcolor="#C5C5C5" >Up to 7+</th>
						<th bgcolor="#C5C5C5" title="Calculation of 'Up to Now'&#13;Invoice Total - Cash Payments - All Chque Payments" >Up to Now</th>
					</tr>
					<tr>
						<td bgcolor="#E5E5E5" align="right" ><?php print number_format($balance30, $decimal); ?></td>
						<td bgcolor="#E5E5E5" align="right" ><?php print number_format($balance14, $decimal); ?></td>
						<td bgcolor="#E5E5E5" align="right" ><?php print number_format($balance7, $decimal); ?></td>
						<td bgcolor="#E5E5E5" align="right" ><?php print number_format($balance0, $decimal); ?></td>
					</tr>
					<tr>
						<td colspan="4" height="5px"></td>
					</tr>
					<tr>
						<td colspan="3" bgcolor="#E5E5E5" align="right" style="padding-right:20px;" >Cheque to be Credited</td>
						<td bgcolor="#E5E5E5" align="right" ><?php print number_format($pending_chque, $decimal); ?></td>
					</tr>
					<tr>
						<td colspan="3" bgcolor="#E5E5E5" align="right" style="padding-right:20px;" title="Calculation of Remaining Credit Limit&#13;Customer Credit Limit - Invoice Total + Cash Payments + Deposited Cheque Payments">Remaining Credit Limit</td>
						<td bgcolor="#E5E5E5" align="right" ><?php print number_format($remaining_cr_limit, $decimal); ?></td>
					</tr>
				</table>
				<br>
			</div>
		</div>
	</div>
	<br><br>
	<div class="w3-row">
		<div class="w3-col s3"></div>
		<div class="w3-col">
		<div id="div_leftpannel0"></div>
			<div id="div_repair" style="display:none">
				<table width="100%" bgcolor="#EEEEEE" align="center"><tr><td style="font-size:10pt; color:navy;">Pre Check for Repairs</td></tr></table>
				<br />
				<table border="1" cellspacing="0"  style="font-size:9pt;" align="center">
				<tr>
					<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_1" id="pre_1_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_1" id="pre_1_2" value="Display Has an Issue" onchange="repairePreCheck()"></td><td width="20px;"></td><td rowspan="2">Display</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
					<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_2" id="pre_2_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_2" id="pre_2_2" value="Mic Has an Issue" onchange="repairePreCheck()"></td><td width="20px;"></td><td rowspan="2">Mic</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
					<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_3" id="pre_3_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_3" id="pre_3_2" value="Vol/Down Has an Issue" onchange="repairePreCheck()"></td><td width="20px;"></td><td rowspan="2">Vol/Down<br />Switch</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				</tr>
				<tr>
					<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_4" id="pre_4_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_4" id="pre_4_2" value="Speaker Has an Issue" onchange="repairePreCheck()"></td><td width="20px;"></td><td rowspan="2">Speaker</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
					<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_5" id="pre_5_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_5" id="pre_5_2" value="Ringer Has an Issue" onchange="repairePreCheck()"></td><td width="20px;"></td><td rowspan="2">Ringer</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
					<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_6" id="pre_6_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_6" id="pre_6_2" value="Camera Switch Has an Issue" onchange="repairePreCheck()"></td><td width="20px;"></td><td rowspan="2">Camera<br />Switch</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				</tr>
				<tr>
					<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_7" id="pre_7_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_7" id="pre_7_2" value="Signal Has an Issue" onchange="repairePreCheck()"></td><td width="20px;"></td><td rowspan="2">Signal</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
					<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_8" id="pre_8_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_8" id="pre_8_2" value="Touch Has an Issue" onchange="repairePreCheck()"></td><td width="20px;"></td><td rowspan="2">Touch</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
					<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_9" id="pre_9_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_9" id="pre_9_2" value="Keypad Lights Has an Issue" onchange="repairePreCheck()"></td><td width="20px;"></td><td rowspan="2">Light<br />(Keypad)</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				</tr>
				<tr>
					<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_10" id="pre_10_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_10" id="pre_10_2" value="Camera Has an Issue" onchange="repairePreCheck()"></td><td width="20px;"></td><td rowspan="2">Camera</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
					<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_11" id="pre_11_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_11" id="pre_11_2" value="Charging Has an Issue" onchange="repairePreCheck()"></td><td width="20px;"></td><td rowspan="2">Charging</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
					<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_12" id="pre_12_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_12" id="pre_12_2" value="Display Light Has an Issue" onchange="repairePreCheck()"></td><td width="20px;"></td><td rowspan="2">Light<br />(Display)</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				</tr>
				<tr>
					<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_13" id="pre_13_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_13" id="pre_13_2" value="Memory Card Has an Issue" onchange="repairePreCheck()"></td><td width="20px;"></td><td rowspan="2">Memory<br />Card</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
					<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_14" id="pre_14_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_14" id="pre_14_2" value="Call Transmitting Has an Issue" onchange="repairePreCheck()"></td><td width="20px;"></td><td rowspan="2">Call<br />Transmitting</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
					<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_15" id="pre_15_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_15" id="pre_15_2" value="Keypad Buttons Has an Issue" onchange="repairePreCheck()"></td><td width="20px;"></td><td rowspan="2">Keypad<br />Button</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				</tr>
				</table>
				<br /><br />
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	getBillItems();
</script>
<?php
	include_once  'template/m_footer.php';
?>