<?php
                include_once  'template/header.php';
?>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<script type="text/javascript">
	function addItemPO($itemid){
	  var $qty=document.getElementById('qty|'+$itemid).value;
	  var $suplier=document.getElementById('sup|'+$itemid).value;
	  if(($qty>0)&&($suplier!="")){
		  document.getElementById("loading|"+$itemid).innerHTML = document.getElementById("loading").innerHTML;
		  var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
		    var returntext=this.responseText;
		     document.getElementById("div|"+$itemid).innerHTML = returntext;
		    }
		  };
		  xhttp.open("GET", 'index.php?components=purchase_order&action=add_itempo&itemid='+$itemid+'&qty='+$qty+'&suplier='+$suplier, true);
		  xhttp.send();
	  }else{
	  	window.alert('Please Enter a Valid Quantity and a Supplier');
	  }
	}	
</script>
<!-- ------------------------------------------------------------------------------------------------------ -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:25px" /></div>

<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
	}
?></td></tr></table>
<form action="index.php?components=purchase_order&action=home4" method="post">
	<table align="center" border="0"  style="font-size:10pt; background-color:#EEEEEF; border-radius: 15px;">
	<tr><td width="20px"></td><td>From Date</td><td><input type="date" id="from_date" name="from_date" <?php if(isset($_POST['from_date'])) print 'value="'.$_POST['from_date'].'"'; ?> /></td><td></td>
	<td>To Date</td><td><input type="date" id="to_date" name="to_date" <?php if(isset($_POST['to_date'])) print 'value="'.$_POST['to_date'].'"'; ?> /></td><td rowspan="2"><input type="submit" value="Get" style="width:60px; height:50px; font-weight:bold; font-size:14pt" /></td>
	<td width="5px"></td></tr>
	<tr><td width="20px"></td><td>Category</td><td>
	<select name="category" id="category">
		<option value="">-SELECT-</option>
	<?php for($i=0;$i<sizeof($category_id);$i++){
		if(isset($_POST['category'])){
			if($_POST['category']==$category_id[$i]) $select='selected="selected"'; else $select='';
		 }else $select='';
		print '<option value="'.$category_id[$i].'" '.$select.'>'.$category_name[$i].'</option>';
	}?>
	</select>
	</td><td width="100px"></td><td>Group</td><td>
	<select name="group" id="group">
		<option value="">-SELECT-</option>
	<?php 
		if($systemid==1){
			$select='';
			if(isset($_POST['group'])) if($_POST['group']=='0') $select='selected="selected"';
			print '<option value="0" '.$select.'>Colombo Special</option>';
		}
		for($i=0;$i<sizeof($group_id);$i++){
		if(isset($_POST['group'])){
			if($_POST['group']==$group_id[$i]) $select='selected="selected"'; else $select='';
		 }else $select='';
		print '<option value="'.$group_id[$i].'" '.$select.'>'.$group_name[$i].'</option>';
	}?>
	</select>
	</td></tr>
	</table>
</form>
	<table align="center" border="0"  style="font-size:10pt; background-color:#DDDDDD; border-radius: 5px;">
	<?php
		print '<tr><td width="10px"></td><td colspan="'.sizeof($group_stores).'">Group Members: </td><td width="10px"></td></tr>';
		print '<tr><td width="10px"></td>';
		for($i=0;$i<sizeof($group_stores);$i++){
			print '<td style="background-color:#E5E5E5;">'.$group_stores[$i].'</td><td width="10px"></td>';
		}
		print '</tr>';
	?>
	</table>
	
	<br><br>
	<input type="hidden" name="category" value="<?php if(isset($_POST['category'])) print $_POST['category']; ?>" />
	<table align="center" style="font-size:10pt">
	<tr bgcolor="#DDDDDD"><th>Item</th>
	<?php for($i=0;$i<sizeof($month_list);$i++){
		print '<th width="100px">'.$month_list[$i].'</th>';
	} ?>
	<th>Selected Group<br>Inventory Qty</th><th>All Store<br>Inventory Qty</th><th>Required Qty<br>for New PO</th><th>PO Supplier</th><th width="100px">Action</th></tr>
	<tr>
	<?php for($i=0;$i<sizeof($item_id);$i++){
		print '<tr><td bgcolor="#DDDDEE" class="shipmentTB3"><strong>'.$item_name[$i].'</strong></td>';
			for($j=0;$j<sizeof($month_list);$j++){
				print '<td align="right" class="shipmentTB2" bgcolor="#DDDDEE">'.$quantity[$month_list[$j]][$item_id[$i]].'</td>';
			}
		print '<td bgcolor="#EEEEEE" align="right" class="shipmentTB2" >'.$inv_store_qty[$i].'</td><td bgcolor="#EEEEEE" align="right" class="shipmentTB2" title="';
		for($j=0;$j<sizeof($store_id2);$j++){
			print $store_name2[$j].' - '.$inv_store2_qty[$i][$store_id2[$j]].'&#xA;';
		}
		print '">'.$inv_all_qty[$i].'</td><td align="center" bgcolor="#EEEEEE"><input type="text" id="qty|'.$item_id[$i].'" style="width:60px" value="" /></td><td bgcolor="#EEEEEE">';
			print '<select id="sup|'.$item_id[$i].'" >';
			print '<option value="">-SELECT-</option>';
			for($j=0;$j<sizeof($su_id);$j++){
				if($su_status[$j]==1){
			 	if($su_id[$j]==$item_supplier[$i]) $select='selected="selected"'; else $select='';
					print '<option value="'.$su_id[$j].'" '.$select.'>'.$su_name[$j].'</option>';
				}
			}
		print '</td><td bgcolor="#EEEEEE" align="center"><div id="div|'.$item_id[$i].'" style="color:red"><table><tr><td><input type="button" value="Add" onclick="addItemPO('.$item_id[$i].')" /></td><td><div id="loading|'.$item_id[$i].'"></div></td></tr></table></div></td></tr>';
	} ?>
	</table>
	

<?php
                include_once  'template/footer.php';
?>