<script type="text/javascript">
 	function updatePrice($id){
 		$c_price=document.getElementById('c_price_'+$id).value;
 		$w_price=document.getElementById('w_price_'+$id).value;
 		$r_price=document.getElementById('r_price_'+$id).value;
	 	document.getElementById('div_'+$id).innerHTML=document.getElementById('loading').innerHTML;

		  var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
		    var returntext=this.responseText;
		    	if(returntext=='Done'){
		 	  		document.getElementById('div_'+$id).innerHTML='<span style="color:green">Done</span>';
		 	  	}else{
		 	  		document.getElementById('div_'+$id).innerHTML='<span style="color:red">'+returntext+'</span>';
		 	  	}
		    }
		  };
		  xhttp.open("GET", 'index.php?components=inventory&action=update_one_unic_price&c_price='+$c_price+'&w_price='+$w_price+'&r_price='+$r_price+'&itu_id='+$id, true);
		  xhttp.send();
 	}
 	
 	function updateBulkPrice(){
 		$shipment_no=<?php print $_GET['shipment_no']; ?>;
 		$ins_id=<?php print $_GET['ins_id']; ?>;
 		$c_price=document.getElementById('c_price_bulk').value;
 		$w_price=document.getElementById('w_price_bulk').value;
 		$r_price=document.getElementById('r_price_bulk').value;

		if(($c_price!='')||($w_price!='')||($r_price!='')){
	 		document.getElementById('div_bulk').innerHTML=document.getElementById('loading').innerHTML;
			window.location = 'index.php?components=inventory&action=update_bulk_unic_price&c_price='+$c_price+'&w_price='+$w_price+'&r_price='+$r_price+'&shipment_no='+$shipment_no+'&ins_id='+$ins_id;
		}else{
			alert('Please enter the Bulk Cost or Wholesale or Retail Price');
		}
 	}
 	
</script>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px;" /></div>

<?php

if($unic_cal) $colspan=5; else $colspan=1;
	print '<table align="center" style="font-size:12pt; font-family:Calibri" height="100%">';
	print '<tr bgcolor="#CCCCCC"><th height="30px" style="padding-left:20px; padding-right:20px; color:navy" colspan="'.$colspan.'">List of Unique Items For the Selection</th></tr>';
	print '<tr bgcolor="#CCCCCC"><td style="padding-left:40px; padding-right:20px; color:navy" >Item Description : <strong>'.$itm_description.'</strong></td>';
	if($unic_cal){
		print '<td class="shipmentTB3" align="center"><strong>Cost</strong></td><td class="shipmentTB3" align="center"><strong>Wholesale Price</strong></td><td class="shipmentTB3" align="center"><strong>Retail Price</strong></td><td class="shipmentTB3"></td>';
		print '<tr bgcolor="#DDDDDD"><td style="padding-left:40px; padding-right:20px;">Bulk Price Update</td><td class="shipmentTB3" align="center"><input type="text" id="c_price_bulk" value="" style="text-align:right; width:80px" /></td><td class="shipmentTB3" align="center"><input type="text" id="w_price_bulk" value="" style="text-align:right; width:80px" /></td><td class="shipmentTB3" align="center"><input type="text" id="r_price_bulk" value="" style="text-align:right; width:80px" /></td><td><div id="div_bulk" ><input type="button" value="Update" onclick="updateBulkPrice('.$_GET['ins_id'].')" /></div></td></tr>';
	}
	print '</tr>';
	for($i=0;$i<sizeof($itu_id);$i++){
		switch ($itu_status[$i]){
		 case 0 :
			$link_start='<a href="index.php?components=inventory&action=show_edit_unic&shipment_no='.$_GET['shipment_no'].'&ins_id='.$_GET['ins_id'].'&sn='.$itu_sn[$i].'">'; 
			$link_end='</a>';
			$color='color:navy;';
		 break;
		 case 7 :
			$link_start='<a style="cursor:pointer" title="This Item was Replaced by Warranty">'; 
			$link_end='</a>';
			$color='color:red; text-decoration: line-through;';
		 break;
		 case 20 :
			$link_start='<a style="cursor:pointer" title="This Item was Added by Warranty">'; 
			$link_end='</a>';
			$color='color:green;';
		 break;
		 default :
			$link_start='';
			$link_end='';
			$color='color:navy;';
		 break;
		}
		print '<tr bgcolor="#EEEEEE"><td style="padding-left:40px; padding-right:20px; '.$color.'">'.$link_start.$itu_sn[$i].$link_end.'</td>';
		if($unic_cal){
			print '<td class="shipmentTB3" align="right">';
			if(($itu_status[$i]==0)||($itu_status[$i]==1)) 
				print '<input type="text" id="c_price_'.$itu_id[$i].'" value="'.$itu_c_price[$i].'" style="text-align:right; width:80px" />'; 
			else 
				print number_format($itu_c_price[$i]).'<input type="hidden" id="c_price_'.$itu_id[$i].'" value="0" />';
			print '</td><td class="shipmentTB3" align="right">';
			if($itu_status[$i]==0) 
				print '<input type="text" id="w_price_'.$itu_id[$i].'" value="'.$itu_w_price[$i].'" style="text-align:right; width:80px" />'; 
			else 
				print number_format($itu_w_price[$i]).'<input type="hidden" id="w_price_'.$itu_id[$i].'" value="0" />';
			print '</td><td class="shipmentTB3" align="right">';
			if($itu_status[$i]==0) 
				print '<input type="text" id="r_price_'.$itu_id[$i].'" value="'.$itu_r_price[$i].'" style="text-align:right; width:80px" />'; 
			else 
				print number_format($itu_r_price[$i]).'<input type="hidden" id="r_price_'.$itu_id[$i].'" value="0" />';
			print '</td>';
			print '<td align="center"><div id="div_'.$itu_id[$i].'" ><input type="button" value="Update" onclick="updatePrice('.$itu_id[$i].')" /></div></td>';
		}
		print '</tr>';
	}
	print '</table>';

?>