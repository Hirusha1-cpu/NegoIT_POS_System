<?php
    include_once  'template/m_header.php';
    $apend_limit=30;
	$main_tale_color='#DDDDFF';
	$bill_module = bill_module(1);
	$decimal = getDecimalPlaces(1);
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
		});

		function selectCust(){
			var custid_arr = [<?php for ($x=0;$x<sizeof($cust_id);$x++){ print '"'.$cust_id[$x].'",'; } ?>	];
			var custname_arr = [<?php for ($x=0;$x<sizeof($cust_name);$x++){ print '"'.$cust_name[$x].'",'; } ?>	];
			var custname=document.getElementById('tags3').value;
			if(custname!=''){
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
			var invoice_no=document.getElementById('id').value;
			var invoice_cust=document.getElementById('cust').value;

			if(itemcode!=''){
				var a=code.indexOf(itemcode);
				var b=ttitm.indexOf(itemid[a]);
				var selected = '';
				document.getElementById('itemid').value=itemid[a];
				document.getElementById('tags2').value=description[a];
				<?php if(($systemid == 13) && ($components == 'to')){ ?>
					var selected = 'seleced';
				<?php } ?>
					document.getElementById('priceshow').innerHTML='<select name="price"><option value="'+wprice[a]+'" '+selected+'>Wholesale - '+wprice[a]+'</option><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option></select>';
				document.getElementById('av_qty').innerHTML=qty[a];
				document.getElementById('tt_qty').innerHTML='';
				if(b>-1){
					document.getElementById('av_qty').innerHTML='Old Stock - '+qty[a];
					document.getElementById('tt_qty').innerHTML='New Stock - '+ttqty[b];
				}
			}else if(itemdesc!=''){
				var a=description.indexOf(itemdesc);
				var selected = '';
				document.getElementById('itemid').value=itemid[a];
				document.getElementById('tags1').value=code[a];
				<?php if(($systemid == 13) && ($components == 'to')){ ?>
					var selected = 'seleced';
				<?php } ?>
				document.getElementById('priceshow').innerHTML='<select name="price"><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+' '+selected+'</option><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option></select>';
				document.getElementById('av_qty').innerHTML=qty[a];
				document.getElementById('tt_qty').innerHTML='';
				var b=ttitm.indexOf(itemid[a]);
				if(b>-1){
					document.getElementById('av_qty').innerHTML='Old Stock - '+qty[a];
					document.getElementById('tt_qty').innerHTML='New Stock - '+ttqty[b];
				}
			}
		}

	<?php } ?>

	function movetoTerms($id){
		document.getElementById('finalyze_div').innerHTML='';
		window.location = 'index.php?components=<?php print $components; ?>&action=qo_terms&id='+$id;
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

	function updatePoi($id){
		var itemid="poitemid"+$id;
		var qty=document.getElementById(itemid).value;
		var cust=document.getElementById('cust').value;
		var salesman=document.getElementById('salesman').value;
		window.location = 'index.php?components=<?php print $components; ?>&action=qo_item_gpdate&id='+$id+'&qty='+qty+'&s='+salesman+'&cust='+cust;
	}

	function removePoi($id){
		var cust=document.getElementById('cust').value;
		var salesman=document.getElementById('salesman').value;
		window.location = 'index.php?components=<?php print $components; ?>&action=qo_item_remove&id='+$id+'&s='+salesman+'&cust='+cust;
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
	  xhttp.open("GET", 'index.php?components=<?php print $bill_module; ?>&action=get_discount&itemid='+$itemid+'&cust='+$cust, true);
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
</script>

<?php
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:50px" /></div>

<?php if($systemid==13) $val='yes'; else $val='no';
print '<input type="hidden" id="system_tmp" value="'.$val.'" />';

if(!isset($_GET['s'])||$current_district=='')
	print '<input type="hidden" id="salesman" value="'.$_COOKIE['user_id'].'" />';
?>
<input type="hidden" id="cust_odr" value="" />

<div class="w3-container" style="margin-top:75px">
        <table align="center">
			<tr>
				<td>
					<div id="notifications"></div>
				</td>
			</tr>
		</table>
        <?php
			if(isset($_REQUEST['message'])){
				if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
				print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
			}
		?>
    <hr>
    <div class="w3-row">
        <div class="w3-col s3"></div>
        <div class="w3-col">
            <?php if(isset($_REQUEST['id'])) { ?>
                <div style="width:100%; text-align:center; padding-bottom:15px;">
                    <span style="font-weight:bold; font-size:12pt;" >Quotation No: &nbsp;&nbsp;&nbsp;<?php print str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT);?> </span>
                </div>
            <?php } ?>
            <?php if($current_district!=''){
                if(isset($_REQUEST['id']))
                    print '<form id="billingForm" action="index.php?components='.$components.'&action=apend_quot" onsubmit="return validateQuotation()" method="post" >';
                else
                    print '<form id="billingForm" action="index.php?components='.$components.'&action=new_quot" method="post" >';
                ?>
                    <input type="hidden" name="cust_id" id="cust_id" value="" />
                    <input type="hidden" name="id" id="id" value="<?php print $id; ?>" />
                    <?php if(($systemid == 13) && ($components == 'to')){ ?>
                        <input type="hidden" name="discount" id="discount" value="0" />
                    <?php } ?>
                    <?php if(isset($_GET['cust'])) print '<input type="hidden" id="cust" value="'.$_GET['cust'].'" />'; ?>
                    <input type="hidden" id="qty" name="qty" value="" />
                    <div id="price_div" style="display:none"></div>
                    <input type="hidden" name="unic_item" value="1" />
                    <table align="center" bgcolor="<?php print $main_tale_color; ?>" style="font-size:10pt;">
                        <tr>
                            <td colspan="5">
                                <?php
                                    if(isset($_REQUEST['message'])){
                                        if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
                                        print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
                                    }
                                ?>
                                <br />
                            </td>
                        </tr>
                        <?php
                            if(isset($_GET['cust'])){
                                $cid=array_search($_GET['cust'],$cust_id);
                                print '<tr><td width="50px"></td><td style="font-size:12pt">Customer</td><td colspan="2">';
                                print '<span style="font-size:12pt">'.$cust_name[$cid].'</span>';
                                print '<input type="hidden" name="cust" id="cust" value="'.$_GET['cust'].'" />';
                                print '</td><td width="50px"></td></tr>';
                            }else{
                                print '<tr><td width="50px"></td><td style="font-size:12pt">Customer</td><td colspan="2"><input type="text" name="cust" id="tags3" /></td><td width="50px"></td></tr>';
                            }
                        ?>
                        <tr>
                            <td width="50px"></td>
                            <td style="font-size:12pt"></td>
                            <td colspan="2" style="font-size:12pt; color:maroon">
                                <?php
                                    if(isset($_GET['cust'])){
                                        print 'NIC &nbsp;: '.$cust_nic[$cid].'<br />';
                                        print 'Mob : '.$cust_mobile[$cid].'<br />';
                                        if($systemid==1) print 'Ref &nbsp;: '.ucfirst($cust_asso_sman[$cid]);
                                    }
                                ?>
                            </td>
                            <td width="50px"></td>
                        </tr>
                        <?php if(!isset($_REQUEST['id'])) { ?>
                            <tr>
                                <td width="50px"></td>
                                <td style="font-size:12pt"></td>
                                <td colspan="2">
                                    <input type="submit" value="Submit" style="width:100px; height:50px;" onclick="selectCust()" />
                                    <br /><br />
                                </td>
                                <td width="50px"></td>
                            </tr>
                        <?php }else{ ?>
                            <!-- Item Code -->
                            <tr>
                                <td width="50px"></td>
                                <td style="font-size:12pt">Item Code</td>
                                <td colspan="2">
                                    <input type="text" name="code" id="tags1" value="<?php if($unic_item_code!='') print $unic_item_code; ?>" onclick="this.value=''" />
                                </td>
                                <td width="50px"></td>
                            </tr>
                            <!-- Item Description -->
                            <tr>
                                <td width="50px"></td>
                                <td style="font-size:12pt">Item Description</td>
                                <td colspan="2">
                                    <input type="text" name="description" id="tags2" onchange="document.getElementById('tags1').value=''" />
                                </td>
                                <td width="50px"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="font-size:12pt">Quantity</td>
                                <td>
                                    <?php
                                        if($is_unic_item==0){ ?>
                                            <input type="number" name="qty1" id="qty1" onfocus="getPrice()"  /><?php
                                        }else{
                                            print '<input type="button" value="Refresh" onclick="getPrice()" /><input type="hidden" name="qty1" id="qty1" value="1"  />';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <div style="font-size:10pt;" id="av_qty" align="right"></div>
                                    <div style="font-size:10pt; color:#CC0000" id="tt_qty" align="right"></div>
                                </td>
                                <td></td>
                            </tr>
                            <!-- Unit Price -->
                            <tr>
                                <td></td>
                                <?php if($components != 'to') {
                                    print '<td style="font-size:12pt">Unit Price</td>';
                                }?>
                                <td colspan="2">
                                    <div id="priceshow" <?php if(($systemid == 13) && ($components == 'to')){ echo 'style="display:none !important;"'; ?> <?php } ?>></div>
                                    <input type="hidden" name="itemid" id="itemid" />
                                    <input type="hidden" name="type" id="type" value="1" />
                                    <input type="hidden" name="discount_type" id="discount_type" value="<?php print $discount; ?>" />
                                    <input type="hidden" name="salesman" id="salesman" value="<?php print $_GET['s']; ?>" />
                                </td>
                                <td></td>
                            </tr>
                            <!-- Discount -->
                            <?php if(($systemid == 13) && ($components != 'to')){ ?>
                                <tr>
                                    <td width="50px"></td>
                                    <td style="font-size:12pt">
                                        <table border="0">
                                            <tr>
                                                <td>Unit Discount</td>
                                                <td>
                                                    <div id="discount_div" onclick="changeDiscount()" style="color:blue; cursor:pointer;"><?php if($discount=='price') print 'Rs'; else print '%'; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td colspan="2">
                                        <table width="100%">
                                            <tr>
                                                <td>
                                                    <input type="text" name="discount" id="discount" value="0" style="width:50px" onclick="this.value=''" />
                                                    <input type="button" value="Check" onclick="loadDiscount('<?php print $_GET['cust']; ?>')" />
                                                </td>
                                                <td>
                                                    <div style="font-size:10pt; color:red" id="div_discount" align="right"></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="50px"></td>
                                </tr>
                            <?php } ?>
                            <!-- Comment -->
                            <tr>
                                <td width="50px"></td>
                                <td style="font-size:12pt">Comment</td>
                                <td colspan="2">
                                    <textarea name="comment" ></textarea>
                                </td>
                                <td width="50px"></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="right"><br />
                                    <div id="addtobill">
                                        <?php if(sizeof($qo_itm_des)<$apend_limit) print '<input type="submit" value="Add to Quot" style="width:100px; height:30px" />'; ?>
                                    </div>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php if(sizeof($qo_itm_des)>0) { ?>
                                </td>
                                <td colspan="3" align="left">
                                        <div id="finalyze_div">
                                            <input type="Button" value="Finalyze" style="width:100px; height:70px" onclick="movetoTerms(<?php print $_GET['id']; ?>)" />
                                        </div>
                                    <?php } ?>
                                    <br /><br />
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </form>
            <?php  } ?>
        </div>
    </div>
    <br>
    <div class="w3-row">
        <div class="w3-col s3"></div>
        <div class="w3-col">
            <div id="portrait">
            <div style="background-color:#D1D8F1; padding-left:20px; padding-right:20px; height:100%;">
                <?php if(!isset($_REQUEST['id'])) { ?>
                    <br />
                        <table align="center" height="100%" style="font-size:10pt;">
                            <tr>
                                <td style="">
                                    <form id="searchquo" action="index.php?components=<?php print $components; ?>&action=search_quot" method="post">
                                        <input type="text" style="width:140px" name="search1" id="search1" placeholder="Search Quotation" />
                                        <input type="Submit" value="Search" />
                                    </form>
                                </td>
                            </tr>
                        </table>
                    <br />
                <?php }else{ ?>
                    <br />
                    <table align="center" style="width:100%; font-size:10pt;">
                        <?php
                            for($i=0;$i<sizeof($qo_itm_des);$i++){
                                $update_button='<input type="Button" value="Update"  onclick="updatePoi('.$qi_id[$i].')" />';
                                $remove_button='<input type="Button" value="Remove"  onclick="removePoi('.$qi_id[$i].')" style="background-color:maroon; color:white"/>';
                                print '<tr style="font-size:12pt">
                                        <td width="30px" style="color:blue">
                                            <strong>'.($i+1).'</strong>
                                        </td>
                                        <td>'.$qo_itm_des[$i].'</td>
                                        <td width="50px"></td>
                                        <td align="right">
                                            <div style="width:100%; display:inline-flex; justify-content: flex-end;">
                                            <input style="width:50px; type="text" id="poitemid'.$qi_id[$i].'" value="'.$qo_itm_qty[$i].'" /> '.$update_button.' '.$remove_button.'
                                            </div>
                                        </td>';
                                        if(($systemid != 13) && ($components != 'to')){
                                            print '<td width="80px" align="right">'.number_format(($qo_itm_uprice[$i]*$qo_itm_qty[$i]),$decimal).'</td>';
                                        }
                                print '</tr>';
                            }
                            if(($systemid != 13) && ($components != 'to')){
                                print '<tr style="font-size:12pt; font-weight:900;">
                                        <td colspan="2">Total Amount</td>
                                        <td width="50px"></td>
                                        <td align="right" colspan="2">'.number_format($total,$decimal).'</td>
                                    </tr>';
                            }
                        ?>
                    </table>
                    <br />
                <?php } ?>
            </div>
            </div>
        </div>
    </div>
</div>
<hr>
<br />

<?php
    if(isset($unic_item_code))
	if($unic_item_code!=''){
		print '<script type="text/javascript"> getPrice(); </script>';
	}

	if($current_district==''){
		if($systemid==2 && $static_district==0) print '<script type="text/javascript"> document.getElementById("district").value=10; setDistrict("'.$components.'"); </script>';
		if($systemid==4 && $static_district==0) print '<script type="text/javascript"> document.getElementById("district").value=10; setDistrict("'.$components.'"); </script>';
		if($static_district!=0) print '<script type="text/javascript"> document.getElementById("district").value='.$static_district.'; setDistrict("'.$components.'"); </script>';
	}
    include_once  'template/m_footer.php';
?>