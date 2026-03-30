<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
<style>
#desc-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
#desc-list li{padding: 10px; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
#desc-list li:hover{background:#ece3d2;cursor: pointer;}
#search-desc{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
#sn-list-all{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
#sn-list-all li{padding: 10px; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
#sn-list-all li:hover{background:#ece3d2;cursor: pointer;}
#search-sn{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
</style>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
	$("#search-desc").keyup(function(){
		if(document.getElementById('search-desc').value.length>2){
			$.ajax({
			type: "POST",
			url: "index.php?components=<?php print $components; ?>&action=desc-list&item_type=1&item_filter=1",
			data:'keyword='+encodeURIComponent($(this).val()),
			beforeSend: function(){
				$("#search-desc").css("background","#FFF url(images/LoaderIcon.gif) no-repeat 165px");
			},
			success: function(data){
				$("#suggesstion-desc").show();
				$("#suggesstion-desc").html(data);
				$("#search-desc").css("background","#FFF");
			}
			});
		}
	});
	$("#search-sn").keyup(function(){
		if(document.getElementById('search-sn').value.length>2){
			$.ajax({
			type: "POST",
			url: "index.php?components=<?php print $components; ?>&action=sn-list-all",
			data:'keyword='+encodeURIComponent($(this).val()),
			beforeSend: function(){
				$("#search-sn").css("background","#FFF url(images/LoaderIcon.gif) no-repeat 165px");
			},
			success: function(data){
				$("#suggesstion-sn").show();
				$("#suggesstion-sn").html(data);
				$("#search-sn").css("background","#FFF");
			}
			});
		}
	});
});

function selectDesc(val) {
	$("#search-desc").val(val);
	$("#suggesstion-desc").hide();
	snLookup(val);
}

function selectSN(val) {
	$("#search-sn").val(val);
	$("#suggesstion-sn").hide();
	snLookup('');
}

function snLookup(val){
	$store=document.getElementById("store").value;
	$components=document.getElementById("components").value;
	$item_desc=encodeURIComponent(document.getElementById("search-desc").value);
	$item_sn=encodeURIComponent(document.getElementById("search-sn").value);
	var selected = document.getElementById('store').selectedOptions[0].value;
	if($store==''){
		alert('Please Select a Store');
	}else{
		document.getElementById('div_idesc').innerHTML=document.getElementById('loading').innerHTML;
		if($components=='topmanager') $cost_header='<td align="center" class="shipmentTB3">Cost</td>';else $cost_header='';
		if(selected == "all"){
				$datalist='<table style="font-size:12pt" cellspacing="0"><tr style="background-color:#467898; color:white;"><td align="center">Store</td><td align="center">S/N</td><td width="10px"></td>'+$cost_header+'<td width="10px"></td><td align="center" class="shipmentTB3">Wholesale</td><td width="10px"></td><td align="center" class="shipmentTB3">Retail</td></tr>';
		}else{
			$datalist='<table style="font-size:12pt" cellspacing="0"><tr style="background-color:#467898; color:white;"><td align="center">S/N</td><td width="10px"></td>'+$cost_header+'<td width="10px"></td><td align="center" class="shipmentTB3">Wholesale</td><td width="10px"></td><td align="center" class="shipmentTB3">Retail</td></tr>';
		}
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				    var returntext=this.responseText;
				    if(returntext!=''){
						$items=returntext.split('|');
						$price_change='';
						for($i=0;$i<$items.length;$i++){
							$item=$items[$i].split(',');
							$sn=$item[1];
							$store = $item[0];
							if(isNaN($item[2])) $c_price=$item[2]; else	$c_price=thousands_separators(parseFloat($item[2]));
							if(isNaN($item[3])) $w_price=$item[3]; else	$w_price=thousands_separators(parseFloat($item[3]));
							if(isNaN($item[4])) $r_price=$item[4]; else	$r_price=thousands_separators(parseFloat($item[4]));
							if(($i%2)==0) $color='#EEEEEE'; else $color='#FAFAFA';
							if(($i!=0)&&($price_change!=$item[2])) $color='#B1C5FF';
							if($components=='topmanager') $cost_body='<td align="right" class="shipmentTB3">'+$c_price+'</td>';else $cost_body='';
							if(selected == "all"){
								$datalist+='<tr style="background-color:'+$color+';"><td class="shipmentTB3" style="color:blue">'+$store+'</td><td class="shipmentTB3" style="color:blue">'+$sn+'</td><td width="10px"></td>'+$cost_body+'<td width="10px"></td><td align="right" class="shipmentTB3">'+$w_price+'</td><td width="10px"></td><td align="right" class="shipmentTB3">'+$r_price+'</td></tr>';
								$price_change=$item[2];
							}else{
								$datalist+='<tr style="background-color:'+$color+';"><td class="shipmentTB3" style="color:blue">'+$sn+'</><td width="10px"></td>'+$cost_body+'<td width="10px"></td><td align="right" class="shipmentTB3">'+$w_price+'</td><td width="10px"></td><td align="right" class="shipmentTB3">'+$r_price+'</td></tr>';
								$price_change=$item[2];
							}

							
						}
						document.getElementById("search-desc").value=$item[5];
					}
					$datalist+='</table>';
					document.getElementById('div_data_list').innerHTML=$datalist;
					document.getElementById('div_idesc').innerHTML='';
				}
			};
		xmlhttp.open("POST", "index.php?components=<?php print $components; ?>&action=sn_lookup_list", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send('store='+$store+'&item_desc='+$item_desc+'&item_sn='+$item_sn);
	}
}

function setStore($store){
	var desc=document.getElementById("search-desc").value;
	if(($store!='')&&(desc!='')){
		snLookup(desc);
	}
}
</script>

<!-- ------------------Item List----------------------- -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<div id="loading2" style="display:none"><img src="images/loading2.gif" style="width:15px" /></div>
<input type="hidden" id="components" value="<?php print $components; ?>" />

<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
	<table align="center" style="font-size:10pt; font-family:Calibri">
	<tr><td valign="top" align="center">
		<div style="background-color:#EEEEEF; border-radius: 15px; padding-left:10px; padding-right:10px">
			<table width="100%" cellspacing="0" border="0">
			<tr><td width="50px"></td><td style="font-size:12pt"><strong>Store</strong></td><td>
				<select id="store" style="height:35px;" onchange="setStore(this.value)">
					<option value="">-SELECT-</option>
					<?php
					print '<option value="all" selected="selected" >ALL</option>';
					for($i=0;$i<sizeof($st_id);$i++){
						print '<option value="'.$st_id[$i].'">'.$st_name[$i].'</option>';
					}
					?>
				</select>
			</td><td width="50px"></td></tr>
			<tr bgcolor="#DDDDFF"><td width="50px"></td><td style="font-size:12pt"><strong>Item Description</strong></td><td>
				<div class="frmSearch">
				<input type="text" id="search-desc" value="" onclick="this.value=''" autocomplete="off" />
				<div id="suggesstion-desc"></div>
				</div>
			</td><td width="50px"><div id="div_idesc"></div></td></tr>
			<tr><td align="center" colspan="4"><strong>OR</strong></td></tr>
			<tr bgcolor="#FFDDDD"><td width="50px"></td><td style="font-size:12pt"><strong>S/N</strong></td><td>
				<div class="frmSearch">
				<input type="text" id="search-sn" value="" onclick="this.value=''" autocomplete="off" />
				<div id="suggesstion-sn"></div>
				</div>
			</td><td width="50px"><div id="div_isn"></div></td></tr>
			</table>
		</div>
	</td></tr>
	</table>
  </div>
  
  <div class="w3-col">
	<br />
	<table align="center" style="font-family:Calibri">
	<tr><td align="center" style="color:gray; font-size:10pt">Following are the base prices of Wholesale and Retail. These Wholesale and Retail prices may vary from the billing console prices if a "District Rate" or "Category Rate" or "Special Rate" is applied.</td></tr>
	<tr><td align="center" style="color:gray"><br /></td></tr>
	<tr><td align="center">
	<div id="div_data_list" style="font-size:x-small;" ></div>
	</td></tr>
	</table>
  </div>	
  </div>
</div>
</div>
<hr>
<br />
<?php
//if($components=='supervisor') print '<script type="text/javascript"> document.getElementById("store").value='.$_COOKIE['store'].' </script>';

                include_once  'template/m_footer.php';
?>
