<?php
    include_once  'template/header.php';
?>
<style>
#cust-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
#cust-list li{padding: 10px; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
#cust-list li:hover{background:#ece3d2;cursor: pointer;}
#desc-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
#desc-list li{padding: 10px; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
#desc-list li:hover{background:#ece3d2;cursor: pointer;}
</style>

<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>

<script>
$(document).ready(function(){
	$("#search-cust").keyup(function(){
		if(document.getElementById('search-cust').value.length>2){
			$.ajax({
			type: "POST",
			url: "index.php?components=marketing&action=cust-list",
			data:'keyword='+$(this).val(),
			beforeSend: function(){
				$("#search-cust").css("background","#FFF url(images/LoaderIcon.gif) no-repeat 165px");
			},
			success: function(data){
				$("#suggesstion-cust").show();
				$("#suggesstion-cust").html(data);
				$("#search-cust").css("background","#FFF");
			}
			});
		}
	});
	$("#search-item").keyup(function(){
		if(document.getElementById('search-item').value.length>2){
			$.ajax({
			type: "POST",
			url: "index.php?components=marketing&action=desc-list&item_type=all&item_filter=1",
			data:'keyword='+$(this).val(),
			beforeSend: function(){
				$("#search-cust").css("background","#FFF url(images/LoaderIcon.gif) no-repeat 165px");
			},
			success: function(data){
				$("#suggesstion-item").show();
				$("#suggesstion-item").html(data);
				$("#search-item").css("background","#FFF");
			}
			});
		}
	});
});

function selectCust(val) {
	$("#search-cust").val(val);
	$("#suggesstion-cust").hide();
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var myObj = JSON.parse(xmlhttp.responseText);
			document.getElementById('cust_id').value=myObj.cust_id;
			getPendingReturnItems(myObj.cust_id);
		}
	};
	xmlhttp.open("POST", "index.php?components=marketing&action=more_cust", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('case=name&val='+val);
}

function getPendingReturnItems(val) {
	$datalist='<table style="font-size:12pt"><tr style="background-color:#467898; color:white;"><td align="center">Return No</td><td align="center" width="100px">Date</td><td>Return Item</td><td>Replace Item</td></tr>';
	document.getElementById('div_leftpannel').innerHTML=document.getElementById('loading').innerHTML;
		
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var returntext=this.responseText;
			if(returntext!=''){
				$items=returntext.split('|');
				for($i=0;$i<$items.length;$i++){
					$item=$items[$i].split(',');
					if(($i%2)==0) $color='#EEEEEE'; else $color='#FAFAFA';
					$datalist+='<tr style="background-color:'+$color+';">'
								+'<td class="shipmentTB3" style="color:blue"><a target="_blank" href="index.php?components=bill2&action=finish_return&id='+$item[0]+'" >'+zeroPad($item[0], 7)+'</a></td>'
								+'<td class="shipmentTB3" align="center">'+$item[1]+'</td>'
								+'<td class="shipmentTB3">'+$item[3]+'</td>'
								+'<td class="shipmentTB3"><a onclick=\'setItem('+$item[4]+',"'+$item[5]+'")\' style="color:blue; cursor:pointer;">'+$item[5]+'</a></td>'
								+'</tr>';


				}				
			}
			$datalist+='</table>';
			document.getElementById('div_leftpannel').innerHTML=$datalist;
		}
	};
	xmlhttp.open("POST", "index.php?components=marketing&action=get_pending_return_items", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('cust_id='+val);
}

function setItem(item_id,item_desc){
	document.getElementById('item_id').value=item_id;
	document.getElementById('search-item').value=item_desc;
	getItemHistory();
}

function selectDesc(val) {
	$("#search-item").val(val);
	$("#suggesstion-item").hide();
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var myObj = JSON.parse(xmlhttp.responseText);
			document.getElementById('item_id').value=myObj.item_id;
			getItemHistory();
		}
	};
	xmlhttp.open("POST", "index.php?components=marketing&action=get_item_id", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('val='+val);
}

function getItemHistory(){
	cust_id = document.getElementById('cust_id').value;
	item_id = document.getElementById('item_id').value;
	if(cust_id!='' && item_id !=''){
		$datalist='<table style="font-size:12pt"><tr style="background-color:#467898; color:white;"><td align="center">Invoice No</td><td align="center" width="100px">Date</td><td align="center">Item Cost</td><td align="center">Item Sold P.</td></tr>';
		document.getElementById('div_rightpannel').innerHTML=document.getElementById('loading').innerHTML;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var returntext=this.responseText;
				if(returntext!=''){
					$items=returntext.split('|');
					for($i=0;$i<$items.length;$i++){
						$item=$items[$i].split(',');
						if(($i%2)==0) $color='#EEEEEE'; else $color='#FAFAFA';
						$datalist+='<tr style="background-color:'+$color+';">'
									+'<td class="shipmentTB3" style="color:blue"><a target="_blank" href="index.php?components=bill2&action=finish_bill&id='+$item[0]+'" >'+zeroPad($item[0], 7)+'</a></td>'
									+'<td class="shipmentTB3" align="center">'+$item[1]+'</td>'
									+'<td class="shipmentTB3" align="right"></td>'
									+'<td class="shipmentTB3" align="right">'+thousands_separators($item[3])+'</a></td>'
									+'</tr>';


					}				
				}
				$datalist+='</table>';
				document.getElementById('div_rightpannel').innerHTML=$datalist;
			}
		};
		xmlhttp.open("POST", "index.php?components=marketing&action=get_item_history", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send('cust_id='+cust_id+'&item_id='+item_id);
	}
}

function zeroPad(num, places) {
  var zero = places - num.toString().length + 1;
  return Array(+(zero > 0 && zero)).join("0") + num;
}


</script>
<!-- -------------------------------------------------------------------------------------------------------------------- -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<input type="hidden" id="cust_id" name="cust_id" value="" />
<input type="hidden" id="item_id" name="item_id" value="" />


<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px;" /></div>
<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	}
?></td></tr></table>

<table align="center" style="font-family:Calibri; font-size:12pt"><tr><td width="1000px">
	<div style="background-color:#EEEEEE; border-radius:10px">
		<table align="center" height="100%">
			<tr><td style="font-size:12pt;" height="50px">
				<div class="frmSearch">
					<input type="text" id="search-cust" placeholder="Customer Name" autocomplete="nope" onclick="this.value=''" />
					<div id="suggesstion-cust"></div>
				</div>
				<div id="div_cname"></div>
			</td><td width="100px"></td><td>
				<div class="frmSearch">
					<input type="text" id="search-item" placeholder="Item" autocomplete="nope" onclick="this.value=''" />
					<div id="suggesstion-item"></div>
				</div>
			</td></tr>
		</table>
	</div>
</table>

<div id="div_hid1" style="display:none"></div>
<table align="center" style="font-family:Calibri; font-size:12pt" border="0">
	<tr>
		<td valign="top" width="600px" align="center"><div id="div_leftpannel"></div></td>
		<td valign="top" width="100px" bgcolor="#EEEEEE"></td>
		<td valign="top" width="300px" align="center"><div id="div_rightpannel"></div></td>
</tr>
</table>


<?php
	include_once  'template/footer.php';
?>