	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<style>
		#list_unic_items {float:left;list-style:none;margin-top:-3px;padding:0;width:153px;position: absolute; font-size: 14px;}
		#list_unic_items li{padding: 10px; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
		#list_unic_items li:hover{background:#ece3d2;cursor: pointer;}
	</style>
	<script>
		function getPrice2() {
			var code=document.getElementById('tags1').value;
			var desc=document.getElementById('tags2').value;
			if(code!=''){ $txt=code; $case='code' }
			if(desc!=''){ $txt=desc; $case='desc' }
			if($txt!=''){
			  var $txt_enc = encodeURIComponent($txt);
		 	  document.getElementById('div_item_code_status').innerHTML=document.getElementById('loading').innerHTML;
			  var xhttp = new XMLHttpRequest();
			  xhttp.onreadystatechange = function() {
			    if (this.readyState == 4 && this.status == 200) {
			    var returntext=this.responseText;
			    	$values=returntext.split('|');
			 	 	document.getElementById('div_item_code_status').innerHTML='';
			    	if($values[0]!=''){
			    		document.getElementById('item_id').value=$values[0];
			    		document.getElementById('tags1').value=$values[1];
			    		document.getElementById('tags2').value=$values[2];
			    		document.getElementById('c_price1').value=$values[3];
			    		document.getElementById('w_price1').value=$values[4];
			    		document.getElementById('r_price1').value=$values[5];
			    		document.getElementById('c_price2').value=$values[3];
			    		document.getElementById('w_price2').value=$values[4];
			    		document.getElementById('r_price2').value=$values[5];
			    		document.getElementById('stock').value=$values[6];
			    		document.getElementById('drawer').value=$values[7];
			    	}
			    }
			  };
			  xhttp.open("GET", 'index.php?components=inventory&action=get_item_data&item='+$txt_enc+'&case='+$case, true);
			  xhttp.send();
			}
		}	
	</script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#tags1").keyup(function(){
				if(document.getElementById('tags1').value.length>2){
					$.ajax({
					type: "POST",
					url: "index.php?components=inventory&action=list_unic_items",
					data:{keyword : $(this).val(), type : 'code' },
					beforeSend: function(){
						$("#div_item_code_status").html(document.getElementById('loading').innerHTML);
					},
					success: function(data){
						$("#div_item_code_status").html("");
						$("#suggest_items_code").show();
						$("#suggest_items_code").html(data);
						$("#suggest_items_code").css("background","#FFF");
					}
					});
				}
			});

			$("#tags2").keyup(function(){
				if(document.getElementById('tags2').value.length>2){
					$.ajax({
					type: "POST",
					url: "index.php?components=inventory&action=list_unic_items",
					data: {keyword : $(this).val(), type : 'desc' },
					beforeSend: function(){
						$("#div_item_desc_status").html(document.getElementById('loading').innerHTML);
					},
					success: function(data){
						$("#div_item_desc_status").html("");
						$("#suggest_items_desc").show();
						$("#suggest_items_desc").html(data);
						$("#suggest_items_desc").css("background","#FFF");
					}
					});
				}
			});
		});

		function selectItemCode(val) {
			$("#tags1").val(val);
			$("#suggest_items_code").hide();
			$("#div_item_code_status").hide();
			getPrice2();
		}

		function selectItemDesc(val){
			$("#tags2").val(val);
			$("#suggest_items_desc").hide();
			$("#div_item_desc_status").hide();
			getPrice2();
		}

		function editShipmentHeaderTmp(){
			var shipment_no = document.getElementById('shipment_no').value;
			var sub = '<?php echo $_GET['action']; ?>';
			window.location = 'index.php?components=inventory&action=edit_shipment_header_tmp&sub='+sub+'&shipment_no='+shipment_no;
		}

		function finalyzeOneShipmentTemp(){
			var shipment_no = document.getElementById('shipment_no').value;
			document.getElementById('div_finalyze').innerHTML=document.getElementById('loading').innerHTML;
			document.getElementById('div_submit').innerHTML=document.getElementById('loading').innerHTML;
			window.location = 'index.php?components=inventory&action=finalyze_one_shipment_tmp&shipment_no='+shipment_no+'&sub=show_add_unic_tmp';
		}
	</script>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:60px" /></div>	
<form action="index.php?components=inventory&action=add_qty_tmp" method="post" id="form1" onsubmit="return validateQTY2()"  >
<input type="hidden" id="item_id" name="item_id" value="" />
<input type="hidden" name="unic" value="yes" />
<input type="hidden" name="qty" id="qty" value="0" />
<table align="center" bgcolor="#E5E5E5">
<tr><td colspan="4"><?php 
if(isset($_REQUEST['message'])){
	if($_REQUEST['re']=='success') $color='green'; else $color='red';
print '<p style="color:'.$color.'; font-weight:bold;font-size:12pt;margin: 10px 0px;text-align: center;">'.$_REQUEST['message'].'</p>'; 
}
if(isset($_GET['shipment_no'])) $shipment_no=$_GET['shipment_no']; else $shipment_no=0;

?>
<input type="hidden" name="shipment_no" id="shipment_no" value="<?php print $shipment_no; ?>" />
<input type="button" value="Edit Shipment Header" onclick="editShipmentHeaderTmp()" style="float:right; margin-top: 10px;"/>
<br /></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Current Store</td><td style="font-size:12pt"><strong><?php print ucfirst($currentstore); ?></strong></td><td width="50px"><br /><br /><br /></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Item Code</td><td><input type="text" name="code" id="tags1" /><div id="suggest_items_code"></div></td><td rowspan="2"><div id="div_item_code_status"></div></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Item Description</td><td><input type="text" name="description" id="tags2" /><div id="suggest_items_desc"></td><td rowspan="2"><div id="div_item_desc_status"></div></td></tr>
<tr></tr>
<tr><td colspan="4"><hr />
	<table width="80%" align="center" style="font-size:9pt">
	<tr style="background-color:#C0C0C0"><th width="20%">Drawer</th><th width="20%">Cost</th><th width="20%">Wholesale Price</th><th width="20%">Retail Price</th><th width="20%">Available Stock</th></tr>
	<tr style="background-color:#CCCCCC;">
		<td style="padding-right:10px; padding-left:10px" align="center" height="25px"><input type="text" id="drawer" disabled="disabled" style="width:50px" /></td>
		<td style="padding-right:10px; padding-left:10px" align="center" height="25px"><input type="text" id="c_price1" name="c_price1" style="width:50px"/></td>
		<td style="padding-right:10px; padding-left:10px" align="center"><input type="text" id="w_price1" name="w_price1" style="width:50px"/></td>
		<td style="padding-right:10px; padding-left:10px" align="center"><input type="text" id="r_price1" name="r_price1" style="width:50px"/></td>
		<td style="padding-right:10px; padding-left:10px" align="center"><input type="text" id="stock" style="width:50px" disabled="disabled" /></td>
	</tr>
	</table>
	<input type="hidden" id="c_price2" name="c_price2" />
	<input type="hidden" id="w_price2" name="w_price2" />
	<input type="hidden" id="r_price2" name="r_price2" />
</td></tr>
<tr><td colspan="4"><hr></td></tr>
<tr><td></td><td colspan="2" align="center" style="font-size:10pt">SN1 : &nbsp;<input type="text" name="sn1" id="sn1" /></td><td><div id="div_id1"></div></td></tr>
<tr><td></td><td colspan="2" align="center" style="font-size:10pt">SN2 : &nbsp;<input type="text" name="sn2" id="sn2" /></td><td><div id="div_id2"></div></td></tr>
<tr><td></td><td colspan="2" align="center" style="font-size:10pt">SN3 : &nbsp;<input type="text" name="sn3" id="sn3" /></td><td><div id="div_id3"></div></td></tr>
<tr><td></td><td colspan="2" align="center" style="font-size:10pt">SN4 : &nbsp;<input type="text" name="sn4" id="sn4" /></td><td><div id="div_id4"></div></td></tr>
<tr><td></td><td colspan="2" align="center" style="font-size:10pt">SN5 : &nbsp;<input type="text" name="sn5" id="sn5" /></td><td><div id="div_id5"></div></td></tr>
<tr><td></td><td colspan="2" align="center" style="font-size:10pt">SN6 : &nbsp;<input type="text" name="sn6" id="sn6" /></td><td><div id="div_id6"></div></td></tr>
<tr><td></td><td colspan="2" align="center" style="font-size:10pt">SN7 : &nbsp;<input type="text" name="sn7" id="sn7" /></td><td><div id="div_id7"></div></td></tr>
<tr><td></td><td colspan="2" align="center" style="font-size:10pt">SN8 : &nbsp;<input type="text" name="sn8" id="sn8" /></td><td><div id="div_id8"></div></td></tr>
<tr><td></td><td colspan="2" align="center" style="font-size:10pt">SN9 : &nbsp;<input type="text" name="sn9" id="sn9" /></td><td><div id="div_id9"></div></td></tr>
<tr><td></td><td colspan="2" align="center" style="font-size:10pt">SN10 :      <input type="text" name="sn10" id="sn10" /></td><td><div id="div_id10"></div><br /><br /></td></tr>
<tr>
	<td colspan="4" align="right">
		<table width="100%" style="margin: 20px 0px;">
		<tr>
			<td align="center">
				<?php if($editable) { ?>
				<div id="div_submit"><input type="submit" value="Add Quantity" style="width:130px; height:50px" /></div>
				<?php } ?>
			</td>
			<td align="center">
				<?php
				if((sizeof($ship_itm_id)>0) && ($editable)){ ?>
				<div id="div_finalyze"><input type="Button" value="Finalyze" style="width:80px; height:50px" onclick="finalyzeOneShipmentTemp()" /></div>
				<?php } ?>
			</td>
		</tr>
		</table>
	</td>
<tr>
</table>
</form>