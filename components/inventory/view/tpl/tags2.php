<style>
#tag-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
#tag-list li{padding: 10px; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
#tag-list li:hover{background:#ece3d2;cursor: pointer;}
#search-tag{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
</style>
<script>
function createAddTag(){
	$tag_name=document.getElementById('search-tag').value;
	$item_id=document.getElementById('item_id_for_tag').value;
	document.getElementById('tmp').innerHTML=document.getElementById('div_tag_loading').innerHTML;
	document.getElementById('div_tag_loading').innerHTML=document.getElementById('loading').innerHTML;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var myObj=JSON.parse(this.responseText);
			if(myObj.status=='Done'){
				document.getElementById('notifications').innerHTML='<span style="color:green; font-weight:bold; font-size:12pt;">Tag: '+$tag_name+' was Added Successfully</span>';
				showItemTags();
			}else{
				document.getElementById('notifications').innerHTML='<span style="color:red; font-weight:bold; font-size:12pt;">'+myObj.status+'</span>';
			}
			document.getElementById('div_tag_loading').innerHTML=document.getElementById('tmp').innerHTML;
			document.getElementById('search-tag').value='';
		}
	};
	xmlhttp.open("POST", "index.php?components=<?php print $components; ?>&action=create_add_tag", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('item='+$item_id+'&tag='+$tag_name);
}

function removeTag($tag_id){
	$item_id=document.getElementById('item_id_for_tag').value;
	document.getElementById('tmp').innerHTML=document.getElementById('div_tag_loading').innerHTML;
	document.getElementById('div_tag_loading').innerHTML=document.getElementById('loading').innerHTML;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var myObj=JSON.parse(this.responseText);
			if(myObj.status=='Done'){
				document.getElementById('notifications').innerHTML='<span style="color:green; font-weight:bold; font-size:12pt;">Tag: '+myObj.tag_name+' was Removed Successfully</span>';
				showItemTags();
			}else{
				document.getElementById('notifications').innerHTML='<span style="color:red; font-weight:bold; font-size:12pt;">'+myObj.status+'</span>';
			}
			document.getElementById('div_tag_loading').innerHTML=document.getElementById('tmp').innerHTML;
		}
	};
	xmlhttp.open("POST", "index.php?components=<?php print $components; ?>&action=remove_tag", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('item='+$item_id+'&tag_id='+$tag_id);
}

function showItemTags(){
	$item_id=document.getElementById('item_id_for_tag').value;
	$tag_list='';
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var outObjA=this.responseText;
			var outObjA = JSON.parse(outObjA);
			for (var i = 0; i < outObjA.length; i++) {
			  	var jsonData = outObjA[i];
			  	$tag_list+='<span class="badge tag-badge" id="tag-">'+jsonData.tag_name+'<a style="cursor:pointer" onclick=\'removeTag("'+jsonData.tag_id+'")\' class="close"  data-dismiss="alert" aria-label="Remove"><span aria-hidden="true"> &times; </span></a></span> ';
				if((i+1)%3 ==0) $tag_list+='<br />';
			}
			document.getElementById('div_show_tags').innerHTML=$tag_list;
		}
	};
	xmlhttp.open("POST", "index.php?components=<?php print $components; ?>&action=show_item_tags", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('item='+$item_id);
}
</script>
<input type="hidden" id="item_id_for_tag" value="<?php if(isset($id)) print $id; ?>" />
<div id="tmp" style="display:none"></div>
<table>
	<tr><td>
		<div class="frmSearch">
		  <input type="text" list="tag-list" id="search-tag" name="search-tag" placeholder="Tag" autocomplete="nope" />
		  <datalist id="tag-list">
		  <?php
		  for($i=0;$i<sizeof($tag_name);$i++){
		  	print '<option value="'.$tag_name[$i].'">';
		  }
		  ?>
		  </datalist>
		</div>
	</td><td width="50px"><div id="div_tag_loading"><input type="button" value="Add Tag" class="button2" style="height:38px;" onclick="createAddTag()" /></div></td></tr>
</table>
<br />
<div id="div_show_tags"></div>

<script type="text/javascript">
showItemTags();
</script>
