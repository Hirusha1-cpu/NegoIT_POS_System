	<script type="text/javascript">
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($customer_name);$x++){ print '"'.$customer_name[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
		var availableTags2 = [<?php for ($x=0;$x<sizeof($itm_desc);$x++){ print '"'.$itm_desc[$x].'",'; } ?>	];
		$( "#tags2" ).autocomplete({
			source: availableTags2
		});
	});
	
 	function activateOrder(){
 		if(document.getElementById("order_act").checked){
	 		document.getElementById('qty_order').value='DESC'; 
 		}else{
	 		document.getElementById('qty_order').value=''; 
 		}
 		filterData();
  	}
  	
 	function setOrder(){
 		var qty_order=document.getElementById('qty_order').value; 
 		if(qty_order=='ASC') document.getElementById('qty_order').value='DESC';
 		if(qty_order=='DESC') document.getElementById('qty_order').value='ASC';
 		filterData();
  	}
	
	function validateCatalog(){
	}
	
	function filterData(){
	  var $item_id='';
	  var $from_date=document.getElementById('from_date').value;
	  var $to_date=document.getElementById('to_date').value;
	  var $cust=document.getElementById('tags1').value;
	  var item=document.getElementById('tags2').value;
	  var $district=document.getElementById('district').value;
	  var $filter_cat=document.getElementById('filter_cat').value;
	  if(document.getElementById('show_all').checked) 	$show_all='&show_all'; else $show_all='';
	  var $qty_order=document.getElementById('qty_order').value;
	  if($qty_order!='') $filter_order='&order='+$qty_order; else $filter_order='';

		var $count=0;
	    if($district=='') $count++;
	    if($cust=='') $count++;
	    if ($count!=0) {
	        alert('District and Customer must be filled');
	        return false;
	    }else{
			var cust_id_arr = [<?php for ($x=0;$x<sizeof($customer_id);$x++){ print '"'.$customer_id[$x].'",'; } ?>	];
			var cust_name_arr = [<?php for ($x=0;$x<sizeof($customer_name);$x++){ print '"'.$customer_name[$x].'",'; } ?>	];
			var itm_id_arr = [<?php for ($x=0;$x<sizeof($itm_id);$x++){ print '"'.$itm_id[$x].'",'; } ?>	];
			var itm_name_arr = [<?php for ($x=0;$x<sizeof($itm_desc);$x++){ print '"'.$itm_desc[$x].'",'; } ?>	];
			if(item!=''){
				var a=itm_name_arr.indexOf(item);
				$item_id=itm_id_arr[a];
			}
			if($cust!=''){
				var b=cust_name_arr.indexOf($cust);
				$cust_id=cust_id_arr[b];
				window.location = 'index.php?components=<?php print $components; ?>&action=catalog&from_date='+$from_date+'&to_date='+$to_date+'&cust_id='+$cust_id+'&item_id='+$item_id+'&cust='+$cust+'&district='+$district+'&filter_cat='+$filter_cat+$show_all+$filter_order;
			}
		}
	}
	
	function loadDiscount($itemid,$pricenow) {
	  var $cust_id=document.getElementById('cust_id').value;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
		    var returntext=this.responseText;
		    var n=returntext.indexOf("|");
		    var m = returntext.length;
		    var dis=returntext.substring(0, n);
		    var lastsold0=returntext.substring(n+1, m);
		    var lastsold=parseFloat(lastsold0);
		    if(lastsold!=$pricenow){
		     	document.getElementById("div_discount"+$itemid).innerHTML ='<table width="100%" bgcolor="red"><tr><td>'+dis+'%  - Last Unit Price @ '+lastsold+'</td></tr></table>';
		    }else{
		     	document.getElementById("div_discount"+$itemid).innerHTML = dis+'%  ';
		    }
	    }
	  };
	  xhttp.open("GET", 'index.php?components=<?php print $components; ?>&action=get_discount&itemid='+$itemid+'&cust_id='+$cust_id, true);
	  xhttp.send();
	}	
	</script>
<!-- ------------------------------------------------------------------------------ -->
<input type="hidden" id="qty_order" value="<?php print $qty_order; ?>" />
<?php if(sizeof($cat_desc)==0) print '<input type="hidden" id="filter_cat" value="all" />'; ?>

<?php if($direct_mkt==0){ 
	$title_price='W/Price';
	$title_discount='Discount Range';
	$title_lastdiscount='<th class="shipmentTB3">Last Discount</th>';
	$title_filter='<th></th>';
	print '<table align="center"><tr><td bgcolor="green" width="10px"></td><td>Sold Previously</td><td width="100px"></td><td bgcolor="orange" width="10px"></td><td>Sold &amp; Discounted Previously</td></tr></table>';
}else{
	$title_price='R/Price';
	$title_discount='Max Discount';
	$title_lastdiscount='';
	$title_filter='';
} ?>
<?php if(isset($_REQUEST['cust_id'])||($direct_mkt==1)){ ?>
	<table align="center" style="font-family:Calibri; font-size:11pt" >
	<tr style="background-color:#467898;color :white;"><th class="shipmentTB3">Category</th><th class="shipmentTB3">Item</th><th class="shipmentTB3">
		<table style="background-color:#467898;color :white;">
			<tr><td colspan="2">Qty</td><td rowspan="2">
			<?php
				if($qty_order=='ASC') print '<a onclick="setOrder()"><img src="images/arrow_up_red.png" style="height:30px;" /></a>';
				if($qty_order=='DESC') print '<a onclick="setOrder()"><img src="images/arrow_down_red.png" style="height:30px;" /></a>';
			?>
			</td></tr><tr><td><input type="checkbox" id="order_act" <?php if($qty_order!='') print 'checked="checked"'; ?> onchange="activateOrder()"  /></td><td style="font-size:6pt;" valign="middle">Order</td></tr>
		</table>
	</th><th class="shipmentTB3"><?php print $title_price; ?></th><th class="shipmentTB3"><?php print $title_discount; ?></th><?php print $title_lastdiscount; ?><?php if($item_id!='') print '<th>Store</th>'; ?></tr>
	<tr style="background-color:#467898;color :white;"><th>
		<select id="filter_cat" name="filter_cat" onchange="filterData()" >
		<option value="all">-ALL-</option>
		<?php 
		$selected_catname='ALL';
		$filter_category_id=array_values(array_unique($filter_category_id));
		$filter_category_name=array_values(array_unique($filter_category_name));
		for($i=0;$i<sizeof($filter_category_id);$i++){
			if($selected_cat==$filter_category_id[$i]){ $select='selected="selected"'; $selected_catname=$filter_category_name[$i]; }else{ $select='';  }
			print '<option value="'.$filter_category_id[$i].'" '.$select.'>'.$filter_category_name[$i].'</option>';
		} ?>
		</select>
	</th><th></th><th></th><th></th><th></th><?php print $title_filter; if($item_id!='') print '<th></th>'; ?></tr>
	<?php
	for($i=0;$i<sizeof($cat_desc);$i++){
		if($direct_mkt==1) $price=$r_price[$i]; else $price=$w_price[$i];
		if($direct_mkt==1) $discount=$cat_max_r_rate[$i].'%'; else $discount=$cat_min_w_rate[$i].'% ~ '.$cat_max_w_rate[$i].'%';
		if($direct_mkt==1) $lastdiscount=''; else $lastdiscount='<td class="shipmentTB3"><div id="div_discount'.$cat_itemid[$i].'"><input type="button" value="Last Discount" onclick="loadDiscount('.$cat_itemid[$i].','.$price.');" style="font-size:9pt;" /></div></td>';
		if($sold_color[$i]==3){	if(($i%2)==0) $color='style="background-color:#FAFAFA"'; else $color='style="background-color:"#EEEEEE"';
		}else if($sold_color[$i]==1){ $color='style="background-color:orange; color:white;"';
		}else if($sold_color[$i]==2){ $color='style="background-color:green; color:white;"'; }
		print '<tr '.$color.' ><td class="shipmentTB3">'.$cat_category[$i].'</td><td class="shipmentTB3">'.$cat_desc[$i].'</td>
		<td class="shipmentTB3" align="right">'.$cat_qty[$i].'</td><td class="shipmentTB3" align="right">'.number_format($price,$decimal).'</td><td class="shipmentTB3">'.$discount.'</td>'.$lastdiscount;
		if($item_id!='') print '<td class="shipmentTB3">'.$cat_store[$i].'</td>';
		print '</tr>';
	}
	?>
	</table>
	
	<!-- -------------------------------Print Start--------------------------------------------- -->
	<div id="printheader" style="display:none" >
		<h1 style="color:navy"><?php print $inf_company; ?></h1>
		<h2 align="center" style="color:#3333FF; text-decoration:underline">CATALOG</h2>
		<table style="font-size:12pt" border="1" cellspacing="0">
			<tr><td width="150px" style="background-color:#C0C0C0; padding-left:10px">Category</td><td width="200px" style="background-color:#EEEEEE; padding-left:10px"><?php print $selected_catname; ?></td></tr>
			<tr><td width="150px" style="background-color:#C0C0C0; padding-left:10px">Generated Date&nbsp;&nbsp;&nbsp;</td><td width="200px" style="background-color:#EEEEEE; padding-left:10px"><?php print $today; ?></td></tr>
		</table><br />
	</div>
	
	<div id="print" style="display:none">
	<table align="center" style="font-family:Calibri; font-size:11pt" border="1" cellspacing="0">
	<tr style="background-color:#467898;color :white;"><th>Category</th><th>Item</th><th>QTY</th><th>W/Price</th><th>Discount Range</th><?php if($item_id!='') print '<th>Store</th>'; ?></tr>
	<?php
	for($i=0;$i<sizeof($cat_desc);$i++){
		print '<tr><td>&nbsp;&nbsp;&nbsp;'.$cat_category[$i].'&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;'.$cat_desc[$i].'&nbsp;&nbsp;&nbsp;</td>
		<td align="right">&nbsp;&nbsp;&nbsp;'.$cat_qty[$i].'&nbsp;&nbsp;&nbsp;</td><td align="right">&nbsp;&nbsp;&nbsp;'.number_format($w_price[$i],$decimal).'&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;'.$cat_min_w_rate[$i].'% - '.$cat_max_w_rate[$i].'% &nbsp;&nbsp;&nbsp;</td>';
		if($item_id!='') print '<td>&nbsp;&nbsp;&nbsp;'.$cat_store[$i].'&nbsp;&nbsp;&nbsp;</td>';
		print '</tr>';
	}
	?>
	</table>
	</div>
	<!-- -------------------------------Print End----------------------------------------------- -->
	
<?php } ?>