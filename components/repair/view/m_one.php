<?php
                include_once  'template/m_header.php';
                $action=$_GET['action'];
                $job_inv=$_GET['id'];
                if(isset($_COOKIE['manager']) || isset($_COOKIE['top manager']) || isset($_COOKIE['report'])) $topuser=true; else $topuser=false;
                $user_id=$_COOKIE['user_id'];
?>
<style>
#rep_item_list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
#rep_item_list li{padding: 10px; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
#rep_item_list li:hover{background:#ece3d2;cursor: pointer;}
#search-rep-item{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
</style>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
	$("#search-rep-item").keyup(function(){
		if(document.getElementById('search-rep-item').value.length>2){
			$.ajax({
			type: "POST",
			url: "index.php?components=repair&action=rep_item_list",
			data:'keyword='+$(this).val(),
			beforeSend: function(){
				$("#search-rep-item").css("background","#FFF url(images/LoaderIcon.gif) no-repeat 165px");
			},
			success: function(data){
				$("#suggesstion-rep-item").show();
				$("#suggesstion-rep-item").html(data);
				$("#search-rep-item").css("background","#FFF");
			}
			});
		}
	});
});



function selectRepPart(val) {
	$("#search-rep-item").val(val);
	$("#suggesstion-rep-item").hide();
	getRepItemData(val);
}

function getRepItemData($val){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var myObj = JSON.parse(xmlhttp.responseText);
			document.getElementById('rpitm_id').value=myObj.rpitm_id;
			document.getElementById('rpitm_qty').value=myObj.rpitm_qty;
			document.getElementById('div_dr').innerHTML=myObj.rpitm_drawer;

		}
	};
	xmlhttp.open("POST", "index.php?components=repair&action=more_rep_item", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('val='+$val);
}

function apendPart() {
	var $count = 0;
	var $msg = "Please the Part and the Qty";
	if (document.getElementById('rpitm_id').value == '') $count++;
	if (document.getElementById('qty').value == '') $count++;
	if ($count == 0) {
		if(document.getElementById('qty').value > document.getElementById('rpitm_qty').value){
			$count++;
			$msg = "Insufficient Quantity";
		}
	}
	if ($count != 0) {
		alert($msg);
		return false;
	}
}
</script>
	<script type="text/javascript">
		function orderPick(){
			document.getElementById('orderprocess1').innerHTML=''; 
			window.location = 'index.php?components=repair&action=pick&id=<?php print $_GET['id']; ?>';
		}
		function orderProcess($type){
			if($type==2){
				var check= confirm("You're about to Reject the repair. Please confirm");
				if(check==true){
				document.getElementById('orderprocess1').innerHTML=''; 
				document.getElementById('orderprocess2').innerHTML='';
				window.location = 'index.php?components=repair&action=reject&id=<?php print $_REQUEST['id']; ?>';
				}	
			}else{
				document.getElementById('orderprocess1').innerHTML=''; 
				document.getElementById('orderprocess2').innerHTML='';
				document.getElementById('form1').submit();
			}
		}
		
		function orderUnassign(){
			var check= confirm("Do want to Unassign this Job from Current Technicien ?");
			if(check==true){
				document.getElementById('orderprocess3').innerHTML='';
				window.location = 'index.php?components=repair&action=unassign&id=<?php print $_GET['id']; ?>';
			}	
		}
		
		function removePart($rin_id){
			var check= confirm("Do you want to remove this part from Job ?");
			if(check==true){
				window.location = 'index.php?components=repair&action=remove_part&id=<?php print $_REQUEST['id']; ?>&rin_id='+$rin_id;
			}	
		}
		
		function displayCust(){
			document.getElementById("cust_details").style.display= "";
		}
		
		function showComment($id){
			if($id=='close')
			document.getElementById("bi_comment").innerHTML='';
			else
			document.getElementById("bi_comment").innerHTML='<table width="100%"><tr><td height="40px">'+document.getElementById("com"+$id).value+'</td><td width="5px" style="color:blue" valign="top"><a style="cursor:pointer; color:blue;" onclick="showComment('+"'close'"+')">x</a></td></tr></table>';
		}
		
<?php  if($action=='list_one'){ ?>
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($part_name);$x++){ print '"'.ucfirst($part_name[$x]).'",'; } ?>	];
		$( "#part" ).autocomplete({
			source: availableTags1
		});
	});
<?php } ?>

	function updatePrice($bi_id){
	  var $bi_total=document.getElementById('bi_total'+$bi_id).value;
	  document.getElementById('bi_div'+$bi_id).innerHTML=document.getElementById('loading').innerHTML;
	  window.location = 'index.php?components=repair&action=update_price&id='+$bi_id+'&new_price='+$bi_total;
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
		//window.alert(chkcount);
		document.getElementById('bo_comment').value=rep_com;
		if(chkcount==15)document.getElementById('precheck').value=1;
	}
	
	function validateBOComm($co){
		var $msg="Please Fill "+$co+"-Check Form";
	    if(document.getElementById('precheck').value==0){
	        alert($msg);
	        return false;
	    }else{
	    	document.getElementById('div_bo_comm').innerHTML=document.getElementById('loading').innerHTML; 
	    	return true;
	    }
	}
	
	function clearPreCheck($id,$co){
		var auth_code=document.getElementById('auth_code').value;
		if(auth_code!=''){
		var check= confirm("Do you want to Clear "+$co+"-Check ?");
		if(check==true){
			window.location = 'index.php?components=repair&action=remove_bo_comment&co='+$co+'&id='+$id+'&auth_code='+auth_code;
		}
		}else{
			window.alert("Please Get the Auth Code from Your Manager");
		}
	}
	
	function repairForceAccept($id){
		var check= confirm("Do you want to Force Accept this Repair Job Price ?");
		if(check==true){
			window.location = 'index.php?components=repair&action=repair_force_accept&id='+$id;
		}
	}
	
	
	</script>

<!-- ------------------------------------------------------------------------------------ -->
<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
</head>

<div class="w3-container" style="margin-top:75px">
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>'; 
	}
?>	
<?php
if($action=='list_pending'){ 
	$menu_status='<th style="padding-left:20px; padding-right:20px">Status</th>'; 
}else{
	$menu_status=$td_status='';
}
?>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
		<table align="center"><tr><td valign="top">
		<form action="index.php?components=repair&action=finish&id=<?php print $_REQUEST['id']; ?>" method="post" id="form1" >
			<table align="center" style="font-size:xx-small" width="350px" >
			<tr><td style="background-color:#467898;color :white;"><strong>Invoice No</strong></td><td bgcolor="#EEEEEE"><?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?></td><td rowspan="4">
			<?php if($button=='Pick'){ ?>
				<div id="orderprocess1"><input type="button" value="Pick" style="height:30px; width:70px; background-color:green; font-weight:bold; color:white" onclick="orderPick()" /></div>
			<?php }else	if($button=='Repair'){ 
				if(($job_owner==$user_id)&&((!$gpm_exceed) ||(($total==$bm_force_accept)&&(!is_null($bm_force_accept))))){
			?>
				<div id="orderprocess1"><input type="button" value="Finish" style="height:30px; width:70px; background-color:green; font-weight:bold; color:white" onclick="orderProcess(1)" /></div>
				<div id="orderprocess2"><input type="button" value="Reject" style="height:30px; width:70px; background-color:#CC0000; font-weight:bold; color:white" onclick="orderProcess(2)" /></div>
			<?php }	if($topuser){
					print '<div id="orderprocess3"><input type="button" value="Unassign" style="height:30px; width:70px; background-color:orange; font-weight:bold; color:white" onclick="orderUnassign()" /></div>'; 
					}
				}else if($button=='Print'){ ?>
				<input type="button" value="Print" style="height:50px; width:100px; background-color:#0099FF; font-weight:bold; color:white" onclick="window.location = 'index.php?components=billing&action=finish_bill&id=<?php print $_REQUEST['id']; ?>'" />
			<?php } ?>
			</td></tr>
			<tr><td style="background-color:#467898;color :white;"><strong>Salesman</strong></td><td bgcolor="#EEEEEE"><?php print  ucfirst($bi_salesman); ?></td></tr>
			<tr><td style="background-color:#467898;color :white;"><strong>Customer</strong></td><td bgcolor="#EEEEEE"><a style="cursor:pointer; color:blue; text-decoration:underline" onclick="displayCust()"><?php print  ucfirst($bi_cust); ?></a></td></tr>
			<tr><td style="background-color:#467898;color :white;"><strong>Invoice Date</strong></td><td bgcolor="#EEEEEE"><?php print  $bi_date; ?></td></tr>
			<tr><td colspan="5" height="50px">
				<?php
					if($gpm_exceed){
						if(($total==$bm_force_accept)&&(!is_null($bm_force_accept)))
							print '<table width="100%" cellspacing="0"><tr bgcolor="maroon" style="color:white; style="font-weight:bold;"><td align="center" height="45px" >Job Cost Exceeded - Force Accepted</td></tr></table>';
						else{
							if($topuser) $button='<input type="button" value="Accept" onclick="repairForceAccept('.$_GET["id"].')" />'; else $button='';
							print '<table width="100%" cellspacing="0"><tr bgcolor="maroon" style="color:white; style="font-weight:bold;"><td align="center" height="45px" >Job Cost Exceeded</td><td width="60px">'.$button.'</td></tr></table>';
						}
					}
				?>
			</td><td></td></tr>
			<tr><td colspan="5">
				<table>
				<tr style="background-color:#C0C0C0"><th>Item Description</th><th>Item Qty</th><th>Total Price</th><th></th></tr>
				<tr><td style="padding-left:20px; padding-right:20px"></td><td style="padding-right:20px" align="right"></td><td style="padding-right:20px" align="right"></td></tr>
				<?php
				for($i=0;$i<sizeof($bill_id);$i++){
					if($bi_comment[$i]!=''){ $comment_show1='<a style="cursor:pointer; color:blue; text-decoration:underline" onclick="showComment('.$i.')">'; $comment_show2='</a>';} else { $comment_show1=$comment_show2=''; }
					print '<tr style="background-color:#F0F0F0"><td style="padding-left:20px; padding-right:20px"><input type="hidden" id="com'.$i.'" value="'.$bi_comment[$i].'<br>'.$bi_repair_sn[$i].'" />'.$comment_show1.''.$bi_desc[$i].''.$comment_show2.'</td><td style="padding-right:20px" align="right">'.$bi_qty[$i].'</td><td align="center"><input type="number" id="bi_total'.$bill_id[$i].'" value="'.$bi_qty[$i] * $bi_price[$i].'" style="width:50px; text-align:right" /></td><td><div id="bi_div'.$bill_id[$i].'">';
					if($topuser) print '<input type="button" value="Update" onclick="updatePrice('.$bill_id[$i].')" />';
					print '</div></td></tr>';
				}
					print '<tr bgcolor="DDDDDD"><td colspan="2" style="padding-left:20px"><strong>Invoice Total</strong></td><td align="right" style="padding-right:20px"><div id="div_total"><strong>'.number_format($total).'</strong></div></td><td></td></tr>';
				?>
				</table>
			</td><td></td></tr>
			<tr><td colspan="4" height="50px"><div id="bi_comment" style="font-size:xx-small; color:maroon; background-color:#FEEFE0"></div></td><td></td><td></td></tr>
			<tr><td colspan="5">
				<table width="100%">
				<tr style="background-color:#C0C0C0"><?php if(isset($_COOKIE['report'])){ ?><td style="padding-left:5px;">Seen By</td><?php } ?><td style="padding-left:5px;">Picked By</td><td style="padding-left:5px;">Repaired By</td><td style="padding-left:5px;">Deliverd By</td></tr>
				<tr style="background-color:#F0F0F0"><?php if(isset($_COOKIE['report'])){ ?><td style="padding-left:5px;"><?php print  ucfirst($bi_seen_by); ?></td><?php } ?><td style="padding-left:5px;"><?php print  ucfirst($bi_picked_by); ?></td><td style="padding-left:5px;"><?php print  ucfirst($bi_repaired_by); ?></td><td style="padding-left:5px;"><?php print  ucfirst($bi_deliverd_by); ?></td></tr>
				<tr style="background-color:#F0F0F0"><?php if(isset($_COOKIE['report'])){ ?><td style="padding-left:5px;"><?php print  $bi_seen_date.'<br/>'.$bi_seen_time; ?></td><?php } ?><td style="padding-left:5px;"><?php print  $bi_picked_date.'<br/>'.$bi_picked_time; ?></td><td style="padding-left:5px;"><?php print  $bi_repaired_date.'<br/>'.$bi_repaired_time; ?></td><td style="padding-left:5px;"><?php print  $bi_deliverd_date.'<br/>'.$bi_deliverd_time; ?></td></tr>
				</table>
			</td><td></td></tr>
			</table>
		</form>
<!-- ------------------------------------------------------------------------------------------------ -->		
			<hr />
			<form action="index.php?components=repair&action=apend_part&id=<?php print $_REQUEST['id']; ?>" method="post" onsubmit="return apendPart()">
			<table style="font-size:xx-small" width="300px">
				<tr><th colspan="4" style="background-color:#467898;color :white;">Spare Parts Allocation</th></tr>
				<?php if($job_owner==$user_id){ ?>
				<tr>
					<th>
						<input type="hidden" id="rpitm_id" name="rpitm_id" value="0"/>
						<input type="hidden" id="rpitm_qty" name="rpitm_qty" value="0"/>
						<div class="frmSearch" style="text-align:left; font-weight: normal;">
						<input type="text" id="search-rep-item" placeholder="Spare Parts" autocomplete="nope" onclick="this.value=''" />
						<div id="suggesstion-rep-item"></div>
						</div>
						</th>
					<th><div id="div_dr"></div></th>
					<th><input type="number" name="qty" id="qty" style="width:40px" placeholder="Qty" /></th>
					<th><input type="submit" value="Add" /></th>

				</tr>
				<tr><th colspan="4"></th></tr>
				<?php } ?>
				<tr style="background-color:#467898;color :white;"><th>Part</th><th>Qty</th><?php if($topuser) print '<th>Total</th>'; ?><th></th></tr>
				<?php 
				for($i=0;$i<sizeof($order_part_name);$i++){
					if($job_owner==$_COOKIE['user_id']) $removepart='<a style="cursor:pointer; color:red;" onclick="removePart('.$order_rin_id[$i].')">x</a>'; else $removepart='';
					if($topuser) $show_total='<td align="right" style="padding-right:10px">'.number_format($order_part_qty[$i] * $order_part_uprice[$i]).'</td>'; else $show_total='';
					if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
					print '<tr bgcolor="'.$color.'"><td style="padding-left:10px">'.$order_part_name[$i].'</td><td align="right" style="padding-right:10px">'.$order_part_qty[$i].'</td>'.$show_total.'<td align="center">'.$removepart.'</td></tr>';
				} 
					if($topuser) $show_total='<td align="right" style="padding-right:10px"><strong>'.number_format($cogs).'</strong></td>'; else $show_total='';
					print '<tr bgcolor="DDDDDD"><td colspan="2" style="padding-left:10px"><strong>Job Parts Cost</strong></td>'.$show_total.'<td></td></tr>';
				?>
			</table>
			</form>
			<div id="cust_details" style="display:none">
			<br />
			<table bgcolor="#E5E5E5" style="font-size:xx-small;">
			<tr><td colspan="4" height="15px"></td></tr>
			<tr><td width="20px"></td><td>Name</td><td><input type="text" value="<?php print $cu_name; ?>" /></td><td width="20px"></td></tr>
			<tr><td width="20px"></td><td>Mobile</td><td><input type="text" value="<?php print $cu_mobile; ?>" /></td><td width="20px"></td></tr>
			<tr><td width="20px"></td><td>Nic</td><td><input type="text" value="<?php print $cu_nic; ?>" /></td><td width="20px"></td></tr>
			<tr><td width="20px"></td><td>Home Address</td><td><textarea style="width:97%"><?php print $cu_address; ?></textarea></td><td width="20px"></td></tr>
			<tr><td colspan="4" height="15px"></td></tr>
			</table>
			</div>
			<hr />
			<?php 
			if($systemid==4){
				print '<table width="100%" bgcolor="#EEEEEE"><tr><td style="font-size:xx-small; color:navy" align="center">Pre Check for Repairs</td></tr></table>';
			if($bm_bocom_type==0){ ?>
			<form method="post" action="index.php?components=repair&action=add_bo_comment&co=Pre&id=<?php print $_GET['id']; ?>" onsubmit="return validateBOComm('Pre')">
			<input type="hidden" id="bo_comment" name="bo_comment" />
			<input type="hidden" id="precheck" value="0" />
			<br />
			<table border="1" cellspacing="0" style="font-size:8pt;">
			<tr>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_1" id="pre_1_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_1" id="pre_1_2" value="Display Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Display</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_2" id="pre_2_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_2" id="pre_2_2" value="Mic Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Mic</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_3" id="pre_3_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_3" id="pre_3_2" value="Vol/Down Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Vol/Down<br />Switch</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
			</tr>
			<tr>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_4" id="pre_4_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_4" id="pre_4_2" value="Speaker Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Speaker</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_5" id="pre_5_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_5" id="pre_5_2" value="Ringer Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Ringer</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_6" id="pre_6_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_6" id="pre_6_2" value="Camera Switch Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Camera<br />Switch</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
			</tr>
			<tr>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_7" id="pre_7_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_7" id="pre_7_2" value="Signal Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Signal</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_8" id="pre_8_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_8" id="pre_8_2" value="Touch Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Touch</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_9" id="pre_9_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_9" id="pre_9_2" value="Keypad Lights Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Light<br />(Keypad)</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
			</tr>
			<tr>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_10" id="pre_10_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_10" id="pre_10_2" value="Camera Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Camera</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_11" id="pre_11_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_11" id="pre_11_2" value="Charging Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Charging</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_12" id="pre_12_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_12" id="pre_12_2" value="Display Light Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Light<br />(Display)</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
			</tr>
			<tr>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_13" id="pre_13_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_13" id="pre_13_2" value="Memory Card Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Memory<br />Card</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_14" id="pre_14_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_14" id="pre_14_2" value="Call Transmitting Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Call<br />Transmitting</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_15" id="pre_15_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_15" id="pre_15_2" value="Keypad Buttons Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Keypad<br />Button</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
			</tr>
			<tr><td bgcolor="#E5E5E5" style="padding-left:5px">More Issues</td><td colspan="2"><input type="text" name="pre_more" style="width:220px" /></td></tr>
			</table>
			<table width="100%" ><tr><td><div id="div_bo_comm"><input type="submit" value="Submit" style="height:30px; width:100px" /></div></td></tr></table>
			</form>
			<?php }else{
				print '<table width="100%" style="font-size:xx-small; color:navy">';
				$bm_bocom=rtrim($bm_bocom,'; ');
				$comm=explode(";",$bm_bocom);
				for($i=0;$i<sizeof($comm);$i++){
					print '<tr bgcolor="#F5F5F5"><td align="center">'.($i + 1).'</td><td class="shipmentTB3">'.$comm[$i].'</td></tr>';
				}
				if($bm_bocom2=='') print '<tr bgcolor="#F5F5F5"><td align="center" colspan="2"><input type="text" id="auth_code" style="width:50px" placeholder="Code" /><input type="button" value="Clear Pre-Check" style="background-color:maroon; color:white" onclick="clearPreCheck('.$_GET["id"].','."'Pre'".')" /></td></tr>';
				print '</table>';
				
				print '<table width="100%" bgcolor="#EEEEEE"><tr><td style="font-size:xx-small; color:navy" align="center">Post Check for Repairs</td></tr></table>';
			if($bm_bocom2!=''){
				print '<table width="100%" style="font-size:xx-small; color:navy">';
				$bm_bocom2=rtrim($bm_bocom2,'; ');
				$comm=explode(";",$bm_bocom2);
				for($i=0;$i<sizeof($comm);$i++){
					print '<tr bgcolor="#F5F5F5"><td align="center">'.($i + 1).'</td><td class="shipmentTB3">'.$comm[$i].'</td></tr>';
				}
				print '<tr bgcolor="#F5F5F5"><td align="center" colspan="2"><input type="text" id="auth_code" style="width:50px" placeholder="Code" /><input type="button" value="Clear Post-Check" style="background-color:maroon; color:white" onclick="clearPreCheck('.$_GET["id"].','."'Post'".')" /></td></tr>';
				print '</table>';
			}else{
				?>
				<br />	
			<form method="post" action="index.php?components=repair&action=add_bo_comment&co=Post&id=<?php print $_GET['id']; ?>" onsubmit="return validateBOComm('Post')">
			<input type="hidden" id="bo_comment" name="bo_comment" />
			<input type="hidden" id="precheck" value="0" />
			<table border="1" cellspacing="0">
			<tr>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_1" id="pre_1_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_1" id="pre_1_2" value="Display Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Display</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_2" id="pre_2_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_2" id="pre_2_2" value="Mic Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Mic</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_3" id="pre_3_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_3" id="pre_3_2" value="Vol/Down Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Vol/Down<br />Switch</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
			</tr>
			<tr>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_4" id="pre_4_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_4" id="pre_4_2" value="Speaker Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Speaker</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_5" id="pre_5_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_5" id="pre_5_2" value="Ringer Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Ringer</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_6" id="pre_6_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_6" id="pre_6_2" value="Camera Switch Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Camera<br />Switch</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
			</tr>
			<tr>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_7" id="pre_7_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_7" id="pre_7_2" value="Signal Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Signal</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_8" id="pre_8_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_8" id="pre_8_2" value="Touch Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Touch</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_9" id="pre_9_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_9" id="pre_9_2" value="Keypad Lights Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Light<br />(Keypad)</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
			</tr>
			<tr>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_10" id="pre_10_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_10" id="pre_10_2" value="Camera Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Camera</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_11" id="pre_11_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_11" id="pre_11_2" value="Charging Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Charging</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_12" id="pre_12_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_12" id="pre_12_2" value="Display Light Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Light<br />(Display)</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
			</tr>
			<tr>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_13" id="pre_13_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_13" id="pre_13_2" value="Memory Card Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Memory<br />Card</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_14" id="pre_14_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_14" id="pre_14_2" value="Call Transmitting Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Call<br />Transmitting</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
				<td><table cellspacing="0"><tr><td bgcolor="#E5E5E5"><input type="radio" name="pre_15" id="pre_15_1" value="yes" onchange="repairePreCheck()"></td><td bgcolor="#E5E5E5"><input type="radio" name="pre_15" id="pre_15_2" value="Keypad Buttons Has an Issue" onchange="repairePreCheck()"></td><td width="10px"></td><td rowspan="2">Keypad<br />Button</td></tr><tr class="yesno"><td bgcolor="#E5E5E5" align="center">Yes</td><td bgcolor="#E5E5E5" align="center">No</td><td></td></tr></table></td>
			</tr>
			<tr><td bgcolor="#E5E5E5" style="padding-left:5px">More Issues</td><td colspan="2"><input type="text" name="pre_more" style="width:220px" /></td></tr>
			</table>
			<table width="100%" ><tr><td align="center"><div id="div_bo_comm"><input type="submit" value="Submit" style="height:30px; width:100px" /></div></td></tr></table>
			</form>
			<?php }
			} 
		} ?>
		<table width="90%" style="font-size:xx-small">
		<tr style="background-color:#DDDDDD; color:maroon"><td colspan="2" align="center" ><strong>HISTORY RECORDS</strong></td></tr>
		<tr style="background-color:#DDDDDD"><td width="100px" class="shipmentTB4" valign="top"><strong>Repair Jobs</strong></td><td align="center">
			<?php for($i=0;$i<sizeof($history_inv);$i++){
				print '<a href="index.php?components=repair&action=inv_preview&id='.$history_inv[$i].'" style="text-decoration:none">'.str_pad($history_inv[$i], 7, "0", STR_PAD_LEFT).'</a><br />';
			} ?>
		</td></tr>
		<tr style="background-color:#DDDDDD"><td class="shipmentTB4"><strong>Invoice Records</strong></td><td align="center">
			<?php for($i=0;$i<sizeof($history_repair);$i++){
				if($history_repair[$i]!=$job_inv)
				print '<a href="index.php?components=repair&action=inv_preview&id='.$history_repair[$i].'" style="text-decoration:none">'.str_pad($history_repair[$i], 7, "0", STR_PAD_LEFT).'</a><br />';
			} ?>
		</td></tr>
		</table>
		
	</td></tr></table>
  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>