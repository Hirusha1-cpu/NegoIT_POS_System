<style type="text/css">
	.st-div{
		border-radius: 3px;
    	padding: 2px 10px 2px 10px;
    	color: white;
    	font-size: 11pt;
	}
	.sn{
		display: inline-block;
	    font-size: 14px;
    	padding: 0px;
    	margin: 5px 10px 5px 10px;
	}
	.sn-remove{
		background: maroon;
    	color: white;
    	padding: 2px 10px 2px 10px;
    	text-decoration: none;
    	font-size: 9pt;
	}
</style>
<script type="text/javascript">
	function getSnList($item_id){
		document.getElementById('unic_sn_list_'+$item_id).innerHTML=document.getElementById('loading').innerHTML;
		var $remove_btn='';
		var $sn_table='<table>';

		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var myObj = JSON.parse(xmlhttp.responseText);
				for(var j=0; j < myObj['tmp_sn_id'].length; j++){
					if(myObj.unic_tmp_editable[j]){
						$remove_btn = "<input type='button' id="+myObj['tmp_sn_id'][j]+" value='Remove' class='sn-remove' onclick='removeSnTemp("+myObj['tmp_sn_id'][j]+")'/>";
					}
					$sn_table+='<tr><td style="font-size:10pt; color:gray;">SN '+(j+1)+':</td><td class="sn">'+myObj['tmp_sn'][j]+'</td> <td class="sn" style="color:gray;">('+myObj['tmp_sn_status'][j]+')</td><td>'+$remove_btn+'</td></tr>';
				}
				$sn_table+='</table>';
		        document.getElementById('unic_sn_list_'+$item_id).innerHTML = $sn_table;
			}
		};
		xmlhttp.open("POST", "index.php?components=inventory&action=list_sn_tmp", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send('shipment_tmp_itm_id='+$item_id);
	}
	function removeSnTemp($tmp_sn_id){
		var rm = document.getElementById($tmp_sn_id);
		rm.disabled = true;
		rm.style.color = 'white';
		rm.style.backgroundColor = 'gray';
		window.location = 'index.php?components=inventory&action=remove_sn_tmp&sn_id='+$tmp_sn_id;
	}

	function removeUnicShipmentItem($itm_id){
		document.getElementById('div-update'+$itm_id).innerHTML = document.getElementById('loading').innerHTML
		window.location = 'index.php?components=inventory&action=shipment_unic_item_remove_tmp&itm_id='+$itm_id;
	}
</script>
<?php 
if(isset($_GET['shipment_no'])){
	print '<table align="center" bgcolor="#E5E5E5" height="100%" id="table_unic_list" border="0">';
	for($i=0;$i<sizeof($ship_itm_id);$i++){
		print '<tr class="sn-row" style="font-size:12pt" >
					<td width="30px" style="color:blue">
						<strong>'.($i+1).'</strong></td><td><span id="'.$ship_itm_st_color[$i].'" onclick=getSnList('.$ship_itm_id[$i].') class="unic-item">'.$ship_itm_desc[$i].'</span></td><td width="5px"></td><td align="center"><div class="st-div" style="background-color:'.$ship_itm_st_color[$i].'">'.$ship_itm_st_name[$i].'<div></td><td width="5px">
					</td>
				<td align="right">';

		if($ship_itm_st_name[$i]!='Saved'){
			print  '<input style="width:50px; type="text" value="'.$ship_itm_qty[$i].'" disabled/>';
			if($editable) print '<div id="div-update'.$ship_itm_id[$i].'" style="display:inline-block;"><input type="Button" id="btn-remove" value="Remove"  onclick="removeUnicShipmentItem('.$ship_itm_id[$i].')" style="background-color:maroon; color:white"/></div>';
		}
		print '</td></tr>';
		print '<tr><td></td><td colspan="5"><div id="unic_sn_list_'.$ship_itm_id[$i].'"></div></td></tr>';

	}
	print '</table>';
}

?>