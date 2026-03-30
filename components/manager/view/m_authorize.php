<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->

	<script type="text/javascript">
	function authorize(){
	  var $invoice_no=document.getElementById('invoice_no').value;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    var returntext=this.responseText;
	    	document.getElementById('auth_result').innerHTML=returntext;
	    }
	  };
	  xhttp.open("GET", 'index.php?components=manager&action=get_authorize&invoice_no='+$invoice_no, true);
	  xhttp.send();
	}
	</script>
<!-- ----------------------------------------------------------------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">

<table align="center" style="font-family:Calibri; font-weight:bold; "><tr><td>Authorize Codes for Wholesale Billing on Retail Shops</td></tr></table>
<br />
<table align="center" style="font-family:Calibri; font-weight:bold;" width="300px"><tr style="background-color:#EEEEEE"><td align="center"><input type="text" id="invoice_no" placeholder="Invoice No" style="width:80px; text-align:center" /></td><td width="50px" align="center"><div id="auth_result" style="color:red; font-weight:bold"></div></td><td><input type="button" value="Auth Code" onclick="authorize()" style="width:80px; height:40px" /></td></tr></table>
<br />
<table align="center" style="font-family:Calibri;">
<tr style="background-color:#467898;color :white;"><th width="150px">Temp Invoice No</th><th width="150px">Code</th></tr>
<?php for($i=0;$i<sizeof($tmp_bm_no_list);$i++){
		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
		print '<tr style="background-color:'.$color.'"><td align="center">T'.str_pad($tmp_bm_no_list[$i], 7, "0", STR_PAD_LEFT).'</td><td align="center">'.$tmp_code_list[$i].'</td></tr>';
} ?>
<tr><th colspan="2"><br /></th></tr>
<tr style="background-color:#467898;color :white;"><th width="150px">Invoice No</th><th width="150px">Code</th></tr>
<?php for($i=0;$i<sizeof($invoice_no_list);$i++){
		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
		print '<tr style="background-color:'.$color.'"><td align="center">'.str_pad($invoice_no_list[$i], 7, "0", STR_PAD_LEFT).'</td><td align="center">'.$code_list[$i].'</td></tr>';
} ?>
</table>

  </div>
</div>
</div>
<hr>
<br />
<?php
                include_once  'template/m_footer.php';
?>
