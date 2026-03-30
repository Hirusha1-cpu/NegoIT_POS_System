<?php
    include_once  'template/header.php';
    $print_button='';
	if(($_GET['action']=='warranty')||($_GET['action']=='warranty_search')){
		$war_status_name='New Warranty Claim';
		$war_status_color='white';
		$title_button_name='';
    	$title_button_url='';
    }elseif($_GET['action']=='warranty_show'){
		$title_button_name='New';
    	$title_button_url='index.php?components=bill2&action=warranty';
    	$print_button='<a href="index.php?components=bill2&action=warranty_print&id='.$_GET['id'].'" ><img src="images/print.png" width="20px" /></a>';
	}else{
		$war_status_name='Claim ID: '.str_pad($_GET['id'], 7, "0", STR_PAD_LEFT);
		$war_status_color='white';
		$title_button_name='Full View';
    	$title_button_url='index.php?components=bill2&action=warranty_show&id='.$_GET['id'];
    }
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete2.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
	<script type="text/javascript">
	
	function validateWarranty() {
	    var sn=document.getElementById('sn').value;
	    document.getElementById('validate_div').innerHTML=document.getElementById('loading').innerHTML;
		
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
		        var myObj = JSON.parse(this.responseText);
		        document.getElementById("sn_send").value = sn;
		        document.getElementById("validity").value = myObj.validity;
		        document.getElementById("div_cust").innerHTML = myObj.cust_name;
		        document.getElementById("div_sup").innerHTML = myObj.su_name;
		        document.getElementById("div_inv").innerHTML = '<a href="index.php?components=bill2&action=finish_bill&id='+myObj.inv_no+'" style="color:maroon; text-decoration:none">'+myObj.inv_no_full+'</a>';
		        document.getElementById("div_price").innerHTML = myObj.bi_price;
		        document.getElementById("div_item").innerHTML = myObj.itm_dec;
		        document.getElementById("div_bmdate").innerHTML = myObj.bm_date;
		        document.getElementById("div_uptonow").innerHTML = myObj.uptonow;
	    		document.getElementById('validate_div').innerHTML='<input type="button" value="Validate" style="width:100px; height:30px" onclick="validateWarranty()" />';
		    	document.getElementById("div_claim").innerHTML ='';
		    	if(myObj.validity==1){
		    		document.getElementById('submit_div').innerHTML='<input type="submit" value="Submit for Warranty" style="height:30px" />';
		    		document.getElementById('div_price').innerHTML='Sold Price';
		    	}
		    	if(myObj.validity==2){
		    		document.getElementById('submit_div').innerHTML='<input type="submit" value="Submit for Warranty" style="height:30px" />';
		    		document.getElementById('div_price').innerHTML='Purchased Price';
		        	document.getElementById("div_inv").innerHTML = '';
		    	}
		    	if(myObj.claim_history!=''){
		    		var history='';
		    		var res = myObj.claim_history.split(",");
		    		for($i=0;$i<res.length;$i++){
		    			history+='<a href="index.php?components=bill2&action=warranty_show&id='+res[$i]+'">'+res[$i]+'</a>';
		    			if($i<(res.length-1)) history+=',';
		    		}
		       		document.getElementById("div_claim").innerHTML = history;
		    	}
		    }
		};
		xmlhttp.open("GET", 'index.php?components=bill2&action=warranty_validate&sn='+sn, true);
		xmlhttp.send();
	}
		
	function setWarrantyStatus($claim,$repair,$replace,$inv,$st,$case){
	    var id=document.getElementById('id').value;
		window.location = 'index.php?components=bill2&action=set_warranty_status&id='+id+'&claim='+$claim+'&repair='+$repair+'&replace='+$replace+'&inv='+$inv+'&st='+$st+'&case='+$case;
	}
	
	function returnWarrantyInventory($st){
	    var id=document.getElementById('id').value;
		window.location = 'index.php?components=bill2&action=return_warranty_inv&id='+id+'&st='+$st;
	}
	
	function returnWarrantyInventory2($st){
	    var id=document.getElementById('id').value;
	    document.getElementById('div_805').innerHTML='<br />';
		window.location = 'index.php?components=bill2&action=return_warranty_inv2&id='+id+'&st='+$st;
	}
	
	function collectWarrantypay(){
	    var id=document.getElementById('id').value;
		window.location = 'index.php?components=bill2&action=warranty_pay&id='+id;
	}
	
	function setWarrantyRepair($claim,$repair,$case){
		var id=document.getElementById('id').value;
		var out=true;
		if($case==2){
			$amo=document.getElementById('amo').value;
			if($amo=='' || $amo==0){ alert("Please Fill the Amount"); out=false; }
		}else $amo=0;
		if(out) window.location = 'index.php?components=bill2&action=set_warranty_repair&id='+id+'&claim='+$claim+'&repair='+$repair+'&case='+$case+'&amo='+$amo;
	}
	
	function deleteWarranty($id){
		window.location = 'index.php?components=bill2&action=delete_warranty&id='+$id;
	}
	
	function warrantyRepair(){
		var id=document.getElementById('id').value;
		window.location = 'index.php?components=bill2&action=warranty_repair&id='+id;
	}
	
	function showWarrantyReplace(){
		var id=document.getElementById('id').value;
		window.location = 'index.php?components=bill2&action=warranty_replace&id='+id;
	}
	
	function showWarrantyInventory($claim,$repair,$replace,$inv,$st){
		var id=document.getElementById('id').value;
		window.location = 'index.php?components=bill2&action=warranty_inventory&id='+id+'&claim='+$claim+'&repair='+$repair+'&replace='+$replace+'&inv='+$inv+'&st='+$st;
	}
	
	function setWarrantyHandover($claim,$repair,$replace,$inv,$st){
		var id=document.getElementById('id').value;
		var check= confirm("Do you want to Mark this Items as Handed overe ?");
	 if (check== true)
		window.location = 'index.php?components=bill2&action=set_warranty_handover&id='+id+'&claim='+$claim+'&repair='+$repair+'&replace='+$replace+'&inv='+$inv+'&st='+$st;
	}
	
	function showWACustPay(){
		var id=document.getElementById('id').value;
		window.location = 'index.php?components=bill2&action=warranty_cust_pay&id='+id;
	}
	</script>
	<style type="text/css">
		.rotate90{
	    -webkit-transform: rotate(270deg);
	    -moz-transform: rotate(270deg);
	    -o-transform: rotate(270deg);
	    -ms-transform: rotate(270deg);
	    transform: rotate(270deg);
		}
	</style>
	<table align="center" bgcolor="#E5E5E5"><tr><td>
	<?php if(isset($_REQUEST['message'])){
			if($_REQUEST['re']=='success') $color='green'; else $color='red';
				print '<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'; 
		} ?>
	</td></tr></table>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>	
<input type="hidden" id="validity" value="" />
<table align="center" cellspacing="0"><tr><td width="603px" align="center" bgcolor="#4678bb" style="font-weight:bold; font-size:11pt; color:<?php print $war_status_color; ?>;"><table width="100%"><tr><td width="100px"><input type="button" value="<?php print $title_button_name; ?>" onclick="window.location = '<?php print $title_button_url; ?>'" /></td><td align="center"><?php print $war_status_name; ?></td><td width="100px" align="right"><?php print $print_button; ?>&nbsp;</td></tr></table></td><td width="300px" valign="top" align="center"></td></tr></table>
<table align="center" border="1" cellspacing="0">
<tr><td width="600px" valign="top">
<?php
	if($_GET['action']=='warranty')  include_once  'components/bill2/view/tpl/warranty_new.php'; else
	if($_GET['action']=='warranty_show')  include_once  'components/bill2/view/tpl/warranty_show.php'; else
	if($_GET['action']=='warranty_search')  include_once  'components/bill2/view/tpl/warranty_new.php'; else
	if($_GET['action']=='warranty_repair')  include_once  'components/bill2/view/tpl/warranty_repair.php';
	if($_GET['action']=='warranty_replace')  include_once  'components/bill2/view/tpl/warranty_replace.php';
	if($_GET['action']=='warranty_inventory')  include_once  'components/bill2/view/tpl/warranty_inventory.php';
	if($_GET['action']=='warranty_pay')  include_once  'components/bill2/view/tpl/warranty_pay.php';
	if($_GET['action']=='warranty_cust_pay')  include_once  'components/bill2/view/tpl/warranty_cust_pay.php';
	if($_GET['action']=='warranty_print'){
		if($tm_template==1)  print '<iframe id="invoice_iframe" width="100%" height="400px" src="components/bill2/view/tpl/warranty_print1.php?id='.$_GET['id'].'"></iframe>';
		if($tm_template==2)  print '<iframe id="invoice_iframe" width="100%" height="400px" src="components/bill2/view/tpl/warranty_print1.php?id='.$_GET['id'].'"></iframe>';
		if($tm_template==3)  print '<iframe id="invoice_iframe" width="100%" height="400px" src="components/bill2/view/tpl/warranty_print1.php?id='.$_GET['id'].'"></iframe>';
		if($tm_template==4)  print '<iframe id="invoice_iframe" width="100%" height="400px" src="components/bill2/view/tpl/warranty_print1.php?id='.$_GET['id'].'"></iframe>';
		if($tm_template==5)  print '<iframe id="invoice_iframe" width="100%" height="400px" src="components/bill2/view/tpl/warranty_print5.php?id='.$_GET['id'].'"></iframe>';
		if($tm_template==6)  print '<iframe id="invoice_iframe" width="100%" height="400px" src="components/bill2/view/tpl/warranty_print1.php?id='.$_GET['id'].'"></iframe>';
		if($tm_template==15)  print '<iframe id="invoice_iframe" width="100%" height="400px" src="components/bill2/view/tpl/warranty_print15.php?id='.$_GET['id'].'"></iframe>';
	}
?>
</td><td width="300px" valign="top" align="center">
	<div style="background-color:#EEEEEF; border-radius: 5px; margin-top: 15px; padding-left:10px; padding-right:10px; padding-bottom:10px; width:250px;">
	<br />
	<table align="center" height="100%">
		<?php if($_GET['action']!='warranty_print'){ ?>
		<tr>
			<td style="font-size:12pt;">
				<form action="index.php">
					<input type="hidden" name="components" value="bill2" />
					<input type="hidden" name="action" value="warranty_search" />
					<input type="text" style="width:120px" name="search" id="search" placeholder="Warranty Number" />
					<input type="submit" value="Search" />
				</form>
			</td>
		</tr>
		<tr>
			<td style="font-size:12pt;">
				<form id="searchinv" action="index.php">
					<input type="hidden" name="components" value="bill2" />
					<input type="hidden" name="action" value="warranty" />
					<input type="text" style="width:120px" name="searchinv" id="searchinv" placeholder="Invoice Number" />
					<input type="Submit" value="Search" />
				</form>
			</td>
		</tr>
		<tr>
			<td style="font-size:12pt;">
				<form action="index.php">
					<input type="hidden" name="components" value="bill2" />
					<input type="hidden" name="action" value="warranty" />
					<input type="text" style="width:120px" name="searchname" placeholder="Customer Name" />
					<input type="Submit" value="Search" />
				</form>
			</td>
		</tr>
		<tr>
			<td style="font-size:12pt;">
				<form action="index.php">
					<input type="hidden" name="components" value="bill2" />
					<input type="hidden" name="action" value="warranty" />
					<input type="text" style="width:120px" name="searchmob" placeholder="Mobile Number" />
					<input type="Submit" value="Search" />
				</form>
			</td>
		</tr>
		<tr>
			<td style="font-size:12pt;">
				<form action="index.php">
					<input type="hidden" name="components" value="bill2" />
					<input type="hidden" name="action" value="warranty" />
					<input type="text" style="width:120px" name="searchemei" placeholder="IMEI Number" />
					<input type="Submit" value="Search" />
				</form>
			</td>
		</tr>
		</table>
		</div>
		<?php 
			if(isset($_GET['searchinv'])){
				print '<iframe id="search_frm" width="260px" height="350px" src="components/bill2/view/tpl/warranty_search.php?searchinv='.$_GET['searchinv'].'"></iframe>';
			}else if(isset($_GET['searchname'])){
				print '<iframe id="search_frm" width="260px" height="350px" src="components/bill2/view/tpl/warranty_search.php?searchname='.$_GET['searchname'].'"></iframe>';
			}else if(isset($_GET['searchmob'])){
				print '<iframe id="search_frm" width="260px" height="350px" src="components/bill2/view/tpl/warranty_search.php?searchmob='.$_GET['searchmob'].'"></iframe>';
			}else if(isset($_GET['searchemei'])){
				print '<iframe id="search_frm" width="260px" height="350px" src="components/bill2/view/tpl/warranty_search.php?searchemei='.$_GET['searchemei'].'"></iframe>';
			}else if($_REQUEST['action'] == 'warranty'){
				print '<p style="color:silver; font-family:Calibri; font-size:10pt;"">Ongoing Warranties</p>';
				print '<table style="width:90%; margin: 10px; font-family:Calibri; font-size:10pt">';
			    for($i=0;$i<sizeof($wa_list_id);$i++){
			        if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
			    	print '<tr bgcolor="'.$color.'"><td><img src="../../../../images/bullet_blue.gif" /></td><td><a style="text-decoration:none" href="index.php?components=bill2&action=warranty_show&id='.$wa_list_id[$i].'" style="text-decoration:none">'.str_pad($wa_list_id[$i], 7, "0", STR_PAD_LEFT).'</a></td><td>'.$wa_cust[$i].'</td></tr>';
			    } 
			    print '</table>';
			} 
		?>
		</td></tr>
	</table>
	<?php }else{ ?><br />
		<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
			<a class="shortcut-button" onclick="print_bill()" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
				<img src="images/print.png" alt="icon" /><br />
				Print
			</span></a>
			</div>
			<br />
			<?php if($war_status==1){ ?>
			<div style="background-color:#FF9191; border:medium; border-color:black; width:80px;">
			<a class="shortcut-button" onclick="deleteWarranty(<?php print $_GET['id'] ?>)" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
				<img src="images/cancel.png" alt="icon" /><br />
				Delete
			</span></a>
			</div>

		<?php } ?>
		</div>
		</td></tr>
	</table>
	<?php } ?>

<?php
    include_once  'template/footer.php';
?>