<?php
                include_once  'template/header.php';
?>
	<script type="text/javascript">
	function authorize(){
	  var $module=document.getElementById('module').value;
	  var $invoice_no=document.getElementById('invoice_no').value;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
		var myObj = JSON.parse(this.responseText);
			if($module=='report'){
		    	document.getElementById('div_auth_code').innerHTML='Code = <span style="color:red">'+myObj.auth_code+'</span>';
		    	document.getElementById('div_inv_total').innerHTML='Total = <span style="color:blue">'+thousands_separators(myObj.inv_total)+'</span>';;
			}else{
		    	document.getElementById('auth_result').innerHTML=myObj.auth_code;
	    	}
	    }
	  };
	  xhttp.open("GET", 'index.php?components=manager&action=get_authorize&invoice_no='+$invoice_no, true);
	  xhttp.send();
	}
	</script>
<!-- ----------------------------------------------------------------------------- -->
<input type="hidden" id="module" value="<?php print $components; ?>" />
<table align="center" style="font-family:Calibri; font-weight:bold; border-radius: 15px; padding-left:20px; padding-right:20px" bgcolor="#EEEEEF"><tr><td>Authorize Codes for Wholesale Billing on Retail Shops</td></tr></table>
<br />
<table align="center"><tr><td>
<div style="background-color:#DDDDDD;  border-radius: 15px; padding-left:10px; padding-right:10px; padding-top:10px; padding-bottom:10px">
<div style="background-color:#FAFAFA;  border-radius: 15px; padding-left:10px; padding-right:10px; padding-top:10px; padding-bottom:10px">
	<table align="center" style="font-family:Calibri; font-weight:bold;" width="300px">
		<tr style="background-color:#EEEEEE"><td align="center"><input type="text" id="invoice_no" placeholder="Invoice No" style="width:80px; text-align:center" /></td><td width="50px" align="center"><div id="auth_result" style="color:red; font-weight:bold"></div></td><td><input type="button" value="Auth Code" onclick="authorize()" style="width:80px; height:40px" /></td></tr>
		<tr style="background-color:#EEEEEE"><td align="center"><div id="div_auth_code"></div></td><td width="50px" align="center"></td><td align="center"><div id="div_inv_total"></div></td></tr>
	</table>
	<br />
	<table align="center" style="font-family:Calibri;">
	<tr style="background-color:#467898;color :white;"><th width="150px">Temp Invoice No</th><th width="150px">Code</th><?php if($components=='report') print '<th class="shipmentTB3">Invoice Total</th>'; ?></tr>
	<?php for($i=0;$i<sizeof($tmp_bm_no_list);$i++){
			if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			print '<tr style="background-color:'.$color.'"><td align="center">T'.str_pad($tmp_bm_no_list[$i], 7, "0", STR_PAD_LEFT).'</td><td align="center">'.$tmp_code_list[$i].'</td>';
			if($components=='report') print '<td align="right" class="shipmentTB3">'.number_format($tmp_inv_total[$i]).'</td>';
			print '</tr>';
	} ?>
	<tr><th colspan="2"><br /></th></tr>
	<tr style="background-color:#467898;color :white;"><th width="150px">Invoice No</th><th width="150px">Code</th><?php if($components=='report') print '<th class="shipmentTB3">Invoice Total</th>'; ?></tr>
	<?php for($i=0;$i<sizeof($invoice_no_list);$i++){
			if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			print '<tr style="background-color:'.$color.'"><td align="center">'.str_pad($invoice_no_list[$i], 7, "0", STR_PAD_LEFT).'</td><td align="center">'.$code_list[$i].'</td>';
			if($components=='report') print '<td align="right" class="shipmentTB3">'.number_format($inv_total[$i]).'</td>';
			print '</tr>';
	} ?>
	</table>
</div>
</div>
</td></tr></table>
<?php
                include_once  'template/footer.php';
?>