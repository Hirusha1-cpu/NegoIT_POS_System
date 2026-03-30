<?php
                include_once  'template/header.php';
?>
<style type="text/css">
.error-span{
	color: red;
    margin-left: 10px;
    margin-right: 10px;
}
</style>
<script type="text/javascript">
function createTag(){
	$tag_name=document.getElementById('tag_name').value;
	$tag_profit=document.getElementById('tag_profit').value;
	document.getElementById('tmp').innerHTML=document.getElementById('div_tag_loading').innerHTML;
	document.getElementById('div_tag_loading').innerHTML=document.getElementById('loading').innerHTML;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var myObj=JSON.parse(this.responseText);
			if(myObj.status=='Done'){
				document.getElementById('notifications').innerHTML='<span style="color:green; font-weight:bold; font-size:12pt;">Tag: '+$tag_name+' was Created Successfully</span>';
				window.location = 'index.php?components=inventory&action=tag_mgmt&message=Tag: '+$tag_name+' was Created Successfully&re=success';
			}else{
				document.getElementById('notifications').innerHTML='<span style="color:red; font-weight:bold; font-size:12pt;">'+returntext+'</span>';
				document.getElementById('div_tag_loading').innerHTML=document.getElementById('tmp').innerHTML;
				document.getElementById('tag_name').value='';
				document.getElementById('tag_profit').value='';
			}
		}
	};
	xmlhttp.open("POST", "index.php?components=inventory&action=create_tag", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('tag_name='+$tag_name+'&tag_profit='+$tag_profit);
}
function checkUpdatingTag(){
	$tag_id=document.getElementById('tag_id').value;
	$tag_name=document.getElementById('tag_name').value;
	$tag_profit=document.getElementById('tag_profit').value;
	document.getElementById('tmp').innerHTML=document.getElementById('div_tag_loading').innerHTML;
	document.getElementById('div_tag_loading').innerHTML=document.getElementById('loading').innerHTML;

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var returntext=this.responseText;
			if(returntext=='Ok'){
				updateTag($tag_id,$tag_name,$tag_profit);
			}else{
				document.getElementById('notifications').innerHTML='<span style="color:red; font-weight:bold; font-size:12pt;">'+returntext+'</span>';
				document.getElementById('div_tag_loading').innerHTML=document.getElementById('tmp').innerHTML;
				document.getElementById('tag_name').value='';
				document.getElementById('tag_profit').value='';
			}
		}
	};
	xmlhttp.open("POST", "index.php?components=inventory&action=check_updating_tag", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('tag_id='+$tag_id+'&tag_profit='+$tag_profit);
}

function updateTag($tag_id,$tag_name,$tag_profit){
	document.getElementById('tmp').innerHTML=document.getElementById('div_tag_loading').innerHTML;
	document.getElementById('div_tag_loading').innerHTML=document.getElementById('loading').innerHTML;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var returntext=this.responseText;
			if(returntext=='Done'){
				document.getElementById('notifications').innerHTML='<span style="color:green; font-weight:bold; font-size:12pt;">Tag: '+$tag_name+' was Updated Successfully</span>';
				window.location = 'index.php?components=inventory&action=tag_mgmt';
			}else{
				document.getElementById('notifications').innerHTML='<span style="color:red; font-weight:bold; font-size:12pt;">'+returntext+'</span>';
				document.getElementById('div_tag_loading').innerHTML=document.getElementById('tmp').innerHTML;
				document.getElementById('tag_name').value='';
				document.getElementById('tag_profit').value='';
			}
		}
	};
	xmlhttp.open("POST", "index.php?components=inventory&action=update_tag", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('tag_id='+$tag_id+'&tag_name='+$tag_name+'&tag_profit='+$tag_profit);
}

function searchItem(){
	$category=document.getElementById('category').value;
	$key_word=document.getElementById('key_word').value;
	document.getElementById('tmp').innerHTML=document.getElementById('div_search').innerHTML;
	document.getElementById('div_search').innerHTML=document.getElementById('loading').innerHTML;
	$div_result='<table style="font-family:Calibri; font-size:11pt">';
	$tmp_item_id_all='';
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var outObjA=this.responseText;
			var outObjA = JSON.parse(outObjA);
			for(var i=0;i<outObjA.length;i++){
				if((i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			  	var jsonData = outObjA[i];
			  	$div_result+='<tr style="background-color:'+$color+'"><td class="shipmentTB3"><input type="checkbox" id="tick_'+jsonData.id+'" onclick="SetUnsetItem(\'one\')" /></td><td class="shipmentTB3">'+jsonData.desc+'</td><td id="item_'+jsonData.id+'"></td></tr>';
				$tmp_item_id_all+=jsonData.id+',';
			}
			$div_result+='</table>';
			$tmp_item_id_all=$tmp_item_id_all.slice(0, -1);
			document.getElementById('div_search_result').innerHTML=$div_result;
			document.getElementById('tmp_item_id_all').value=$tmp_item_id_all;
		}
		document.getElementById('div_search').innerHTML=document.getElementById('tmp').innerHTML;
	};
	xmlhttp.open("POST", "index.php?components=inventory&action=search_items", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('category='+$category+'&key_word='+$key_word);
	
}

function SetUnsetItem($item_id){
	$tmp_item_id_selected='';
	$items=document.getElementById('tmp_item_id_all').value.split(',');
	for(var i=0;i<$items.length;i++){
		if($item_id=='all'){
			if(document.getElementById('tick_all').checked==true){
				document.getElementById('tick_'+$items[i]).checked=true;
			}else{
				document.getElementById('tick_'+$items[i]).checked=false;
			}
		}
		
		if(document.getElementById('tick_'+$items[i]).checked==true){
			$tmp_item_id_selected+=$items[i]+',';
		}
	}
	$tmp_item_id_selected=$tmp_item_id_selected.slice(0, -1);
	document.getElementById('tmp_item_id_selected').value=$tmp_item_id_selected;
}

function checkTagsInserting(){
	$item_id_selected=document.getElementById('tmp_item_id_selected').value;
	$selected_tag=document.getElementById('selected_tag').value;
	$out = true;
	if(($item_id_selected=='')||($selected_tag=='')){
		alert('Please Select Items and a TAG to apply');
		$out = false;
	}
	if($out){
		document.getElementById('tmp').innerHTML=document.getElementById('div_apply_tag').innerHTML;
		document.getElementById('div_apply_tag').innerHTML=document.getElementById('loading').innerHTML;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var $obj_arr=this.responseText;
				var $items_arr = JSON.parse($obj_arr);
				for (let $i = 0; $i < $items_arr.length; $i++) {
					var $item = document.getElementById('item_'+$items_arr[$i].item_id);
					if($items_arr[$i].tag_count != 0){
						$out = false;
						$item.innerHTML='<span class="error-span">Price Tag Found</span>';
					}
					else{
						$item.innerHTML='<img src="images/action_check.gif" />';
					} 
				}
				if($out){
					applyTag($item_id_selected,$selected_tag);
				}
			}
			document.getElementById('div_apply_tag').innerHTML=document.getElementById('tmp').innerHTML;
		};
		xmlhttp.open("POST", "index.php?components=inventory&action=check_tags_inserting", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send('selected_tag='+$selected_tag+'&item_id_selected='+$item_id_selected);
	}
	
}

function applyTag($item_id_selected, $selected_tag){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var out=this.responseText;
			if(out=='Done'){
				document.getElementById('notifications').innerHTML='<span style="color:green; font-weight:bold; font-size:12pt;">Tag was Applied to Bulk Items</span>';
				window.location = 'index.php?components=inventory&action=tag_mgmt';
			}else{
				document.getElementById('notifications').innerHTML='<span style="color:red; font-weight:bold; font-size:12pt;">'+out+'</span>';
			}
		}
		document.getElementById('div_apply_tag').innerHTML=document.getElementById('tmp').innerHTML;
	};
	xmlhttp.open("POST", "index.php?components=inventory&action=apply_bulk_tag", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('selected_tag='+$selected_tag+'&item_id_selected='+$item_id_selected);
}

function SetUnsetItemAjax($item_id){
	$tag_id=document.getElementById('tag_id').value;
	$tag_name=document.getElementById('tag_name').value;
	if(document.getElementById('tick_'+$item_id).checked==true){
		$action='create_add_tag';
		$txt1='Applied';
		$txt2='to';
	}else{
		$action='remove_tag';
		$txt1='Removed';
		$txt2='from';
	}
	
	document.getElementById('div_tick_'+$item_id).innerHTML=document.getElementById('loading').innerHTML;

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var myObj=JSON.parse(this.responseText);
			if(myObj.status=='Done'){
				document.getElementById('notifications').innerHTML='<span style="color:green; font-weight:bold; font-size:12pt;">Tag was '+$txt1+' '+$txt2+' the Items</span>';
			}else{
				document.getElementById('notifications').innerHTML='<span style="color:red; font-weight:bold; font-size:12pt;">'+out+'</span>';
			}
		}
		document.getElementById('div_tick_'+$item_id).innerHTML='';
	};
	xmlhttp.open("POST", "index.php?components=inventory&action="+$action, true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('item='+$item_id+'&tag_id='+$tag_id+'&tag='+$tag_name);
}

function editTag($tag_id){
	document.getElementById('div_tag_'+$tag_id).innerHTML=document.getElementById('loading').innerHTML;
	window.location = 'index.php?components=inventory&action=tag_mgmt&tag_id='+$tag_id;
}

function deleteTag($tag_id,$item_count){
	if($item_count>0){ 
		alert('Please remove all associated items from this Tag before deletion'); 
	}else{
		document.getElementById('div_tag_'+$tag_id).innerHTML=document.getElementById('loading').innerHTML;
		window.location = 'index.php?components=inventory&action=delete_tag&tag_id='+$tag_id;
	}
}

</script>

<!-- ------------------Item List----------------------- -->
<?php
	if (isset($_REQUEST['message'])){
		if ($_REQUEST['re'] == 'success') $color = 'green';	else $color = '#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML=\'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>\'</script>';
	}
?>

<div id="tmp" style="display:none"></div>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<input type="hidden" id="tmp_item_id_all" value="" />
<input type="hidden" id="tmp_item_id_selected" value="" />

<table align="center">
	<tr><td valign="top">
		<div style="background-color:maroon; color:white; font-family:Calibri; text-align:center; padding:10px;">Tag Create/Update</div><br />
		<table align="center" border="0" style="font-family:Calibri; font-size:11pt">
			<tr bgcolor="#EEEEEE"><td class="shipmentTB3">Tag Name</td><td><input type="text" id="tag_name" value="<?php print $tag_name_one; ?>" style="width:100px" /></td><td width="50px" rowspan="2">
				<div id="div_tag_loading">
					<?php
						if(isset($_GET['tag_id'])){
							print '<input type="button" value="Update Tag" class="button1" style="height:38px;" onclick="checkUpdatingTag()" />';
							print '<input type="hidden" id="tag_id" value="'.$_GET['tag_id'].'" />';
						}else{
							print '<input type="button" value="Create Tag" class="button1" style="height:38px;" onclick="createTag()" />';
						}
					?>
				</div>
			</td></tr>
			<tr bgcolor="#EEEEEE"><td class="shipmentTB3">Minumum Profit (Rs)</td><td><input type="number" id="tag_profit" value="<?php print $tag_profit_one; ?>" style="width:100px; text-align:right" /></td></tr>
		</table>
		
		<br />
		<table align="center" style="font-family:Calibri">
		<tr style="background-color:#467898; color:white;"><th class="shipmentTB3" width="150px">TAG</th><th class="shipmentTB3" width="80px">Minimum<br />Profit</th><th class="shipmentTB3" width="80px">Linked Item Count</th><th class="shipmentTB3" width="100px"></th></tr>
		<?php
			for($i=0;$i<sizeof($tag_id);$i++){
				if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
				print '<tr style="background-color:'.$color.'"><td class="shipmentTB3"><span class="badge tag-badge" id="tag-">'.$tag_name[$i].'</span></td><td class="shipmentTB3" align="right">'.number_format($tag_profit[$i]).'</td><td class="shipmentTB3" align="right"><a href="index.php?components=inventory&action=tag_mgmt&tag_id='.$tag_id[$i].'" title="Show TAG Applied Items" style="text-decoration:none">'.$tag_licount[$i].'</a></td><td align="center"><div id="div_tag_'.$tag_id[$i].'"><a style="cursor:pointer" onclick="editTag('.$tag_id[$i].')" title="Edit TAG"><img src="images/edit.gif" /></a> <a style="cursor:pointer" onclick="deleteTag('.$tag_id[$i].','.$tag_licount[$i].')" title="Delete TAG"><img src="images/action_delete.gif" /></a></div></td></tr>';
			}
		?>
		</table>
	</td><td width="100px"></td><td valign="top">
	<?php if(isset($_GET['tag_id'])){ ?>
	<!-- -------------------------------------------------Associated Tags-------------------------------------------------------------------- -->
		<div style="background-color:maroon; color:white; font-family:Calibri; text-align:center; padding:10px;">Item  with the Tag</div><br />

			<table style="font-family:Calibri; font-size:11pt">
			<?php
			for($i=0;$i<sizeof($tag_item_id);$i++){
				if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
				print '<tr style="background-color:'.$color.'"><td class="shipmentTB3"><div id="div_tick_'.$tag_item_id[$i].'"></div><input type="checkbox" id="tick_'.$tag_item_id[$i].'" onchange="SetUnsetItemAjax('.$tag_item_id[$i].')" checked="checked" /></td><td class="shipmentTB3">'.$tag_item_desc[$i].'</td></tr>';
			}
			?>
			
			</table>
	<?php }else{ ?>
	<!-- -------------------------------------------------Tag Bulk Apply-------------------------------------------------------------------- -->
		<div style="background-color:#467898; color:white; font-family:Calibri; text-align:center; padding:10px;">Tag Bulk Apply</div><br />
		<table style="font-family:Calibri; font-size:11pt" bgcolor="#EEEEEE">
			<tr><td class="shipmentTB3">Category</td><td>
			<select id="category" >
			<option value="">-SELECT-</option>
			<?php
			 for($i=0;$i<sizeof($category_id);$i++){
			 	print '<option value="'.$category_id[$i].'" >'.$category_name[$i].'</option>';
			 }
			?>
			</select>
			</td><td width="30px"></td><td><input type="search" id="key_word" placeholder="Search Key Word" style="width:150px" /></td>
			<td><div id="div_search"><a style="cursor:pointer" onclick="searchItem()"><img src="images/search.png" style="width:30px" /></a></div></td></tr>
		</table>
		<br />
		
		<table style="font-family:Calibri; font-size:11pt" bgcolor="#EEEEEE">
		<tr ><td class="shipmentTB3"><input type="checkbox" id="tick_all" onclick="SetUnsetItem('all')" /></td><td class="shipmentTB3">Select All Items</td>
		<td width="30px"></td><td>
			<div>
			  <input type="search" list="tag-list" id="selected_tag" placeholder="Tag" autocomplete="nope" />
			  <datalist id="tag-list">
			  <?php
			  for($i=0;$i<sizeof($tag_name);$i++){
			  	print '<option value="'.$tag_name[$i].'">';
			  }
			  ?>
			  </datalist>
		</div>
		</td>
		<td width="12px"></td><td><div id="div_apply_tag"><input type="button" value="Apply Tag" class="button2" style="height:25px; font-size:11pt" onclick="checkTagsInserting()" /></div></td>
		</tr>
		</table>
		<div id="div_search_result"></div>

	<?php } ?>
	</td></tr>
</table>


<?php           
    include_once  'template/footer.php';
?>