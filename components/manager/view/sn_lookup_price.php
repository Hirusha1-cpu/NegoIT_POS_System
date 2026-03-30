<?php
                include_once  'template/header.php';
?>
<style>
#desc-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
#desc-list li{padding: 10px; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
#desc-list li:hover{background:#ece3d2;cursor: pointer;}
#search-desc{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
#sn-list-all{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
#sn-list-all li{padding: 10px; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
#sn-list-all li:hover{background:#ece3d2;cursor: pointer;}
#search-sn{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
.legend{
  	width: 10px;
    height: 10px;
    border: 1px solid rgba(0, 0, 0, .2);
    display: inline-block;
    margin-right: 5px;
}
.red{
 background:#ff0000;
}
.blue{
 background: #B1C5FF;
}
.td{
	padding-top: 5px;
    padding-bottom: 5px;
}

</style>
<script>

function snLookupPrice(val){
	$store=document.getElementById("store").value;
	$max_price=parseFloat(document.getElementById("max_price").value);
	$min_price=parseFloat(document.getElementById("min_price").value);
	$key_word=encodeURIComponent(document.getElementById("key_word").value);
	$out=true;
	var selected = document.getElementById('store').selectedOptions[0].value;
	
	if(isNaN($max_price)){ $out=false; $msg='Error: Price enter a Number for price'; }
	if(isNaN($min_price)){ $out=false; $msg='Error: Price enter a Number for price'; }
	if($out){
		if($min_price > $max_price){ $out=false; $msg='Error: Max price must be more than Min price'; }
	}
	
	if(!$out){
		alert($msg);
	}
	
	if($out){
		document.getElementById('div_idesc').innerHTML=document.getElementById('loading').innerHTML;
		if(selected == "all"){
				$datalist='<table style="font-size:12pt" cellspacing="0"><tr style="background-color:#467898; color:white;"><td align="center" width="150px">Store</td><td align="center">Model</td><td width="20px"><td align="center">S/N</td><td width="20px"></td><td class="shipmentTB3">Min. Selling Price</td></tr>';
		}else{
			$datalist='<table style="font-size:12pt" cellspacing="0"><tr style="background-color:#467898; color:white;"><td align="center">Model</td><td width="20px"><td align="center">S/N</td><td width="20px"></td><td class="shipmentTB3">Min. Selling Price</td></tr>';
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
							$desc = $item[4];
							$min_price = $item[3];
							if($item[3] == "No Price Tag") $tag_color = "#ff0000"; 
							else{ 
								$tag_color= '#000000';
								$min_price = thousands_separators(parseFloat($min_price));
							}
							if(($i%2)==0) $color='#EEEEEE'; else $color='#FAFAFA';
							if(($i!=0)&&($price_change!=$item[2])) $color='#B1C5FF';
							
							if(selected == "all"){
								$datalist+='<tr style="background-color:'+$color+';"><td class="td shipmentTB3" style="color:blue">'+$store+'</td><td class="shipmentTB3" style="color:blue">'+$desc+'</td><td width="20px"><td class="shipmentTB3" style="color:blue">'+$sn+'</td><td width="10px"></td><td align="right" style="color:'+$tag_color+' !important;" class="shipmentTB3">'+$min_price+'</td></tr>';
								$price_change=$item[2];
							}else{
								$datalist+='<tr style="background-color:'+$color+';"><td class="td shipmentTB3" style="color:blue">'+$desc+'</td><td width="20px"><td class="shipmentTB3" style="color:blue">'+$sn+'</><td width="20px"></td><td align="right" class="shipmentTB3" style="color:'+$tag_color+' !important;">'+$min_price+'</td></tr>';
								$price_change=$item[2];
							}
						}
					}
					$datalist+='</table>';
					document.getElementById('div_data_list').innerHTML=$datalist;
					document.getElementById('div_idesc').innerHTML='';
				}
			};
		xmlhttp.open("POST", "index.php?components=<?php print $components; ?>&action=sn_lookup_price_list", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send('store='+$store+'&max_price='+$max_price+'&min_price='+$min_price+'&key_word='+$key_word);
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

<table align="center" style="font-size:10pt; font-family:Calibri">
<tr><td valign="top" align="center">
	<div style="background-color:#EEEEEF; border-radius: 15px; padding-left:10px; padding-right:10px">
		<table width="100%" cellspacing="0" border="0">
			<tr>
			<td width="50px"></td><td style="font-size:12pt"><strong>Store</strong></td><td>
				<select id="store" style="height:35px;" onchange="setStore(this.value)">
					<?php
					print '<option value="all" selected="selected" >ALL</option>';
					for($i=0;$i<sizeof($st_id);$i++){
						print '<option value="'.$st_id[$i].'">'.$st_name[$i].'</option>';
					}
					?>
				</select>
			</td>
			<td width="20px"></td><td width="50px" bgcolor="#FFDDDD"></td><td style="font-size:12pt; padding-left:10px;" bgcolor="#FFDDDD"><strong>Price</strong></td><td style="padding-right:10px;" bgcolor="#FFDDDD">
				<div class="frmSearch">
				<input type="text" id="min_price" value="" onclick="this.value=''" autocomplete="off" placeholder="Min" style="width:50px" />
				<input type="text" id="max_price" value="" onclick="this.value=''" autocomplete="off" placeholder="Max" style="width:50px" />
				<div id="suggesstion-sn"></div>
				</div>
			</td>
			<td width="50px"></td><td style="padding-left:30px; padding-right:10px;" bgcolor="#DDDDFF">
				<div class="frmSearch">
				<input type="text" id="key_word" value="" onclick="this.value=''" autocomplete="off" placeholder="Key word" />
				<div id="suggesstion-desc"></div>
				</div>
			</td><td width="50px" bgcolor="#DDDDFF"><div id="div_idesc"></div></td>
			<td><input type="button" value="Search" style="height:40px;" onclick="snLookupPrice()" /></td>
			<td width="50px"><div id="div_isn"></div></td>
			
			</tr>
		</table>
	</div>
</td></tr>
</table>

<br />
<table align="center" style="font-family:Calibri">
<tr><td align="center" style="color:gray; font-size:10pt">Following are the base prices of Wholesale and Retail. These Wholesale and Retail prices may vary from the billing console prices if a "District Rate" or "Category Rate" or "Special Rate" is applied.</td></tr>
<tr><td align="center" style="color:gray"><br /></td></tr>
<tr>
	<td align="center">
		<div style="margin-bottom: 10px;">
			<div class="legend red"></div><span style="display: inline-block; margin-right: 10px;">No Price Tag</span>
			<div class="legend blue"></div><span style="display: inline-block;">Price Changed</span>
		</div>
	</td>
</tr>
<tr><td align="center">
<div id="div_data_list" ></div>
</td></tr>
</table>
<?php
//if($components=='supervisor') print '<script type="text/javascript"> document.getElementById("store").value='.$_COOKIE['store'].' </script>';

                include_once  'template/footer.php';
?>