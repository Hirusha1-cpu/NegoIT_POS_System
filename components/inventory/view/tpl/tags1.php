<tr><td></td><td>TAG</td><td>
<style>
#tag-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
#tag-list li{padding: 10px; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
#tag-list li:hover{background:#ece3d2;cursor: pointer;}
#search-tag{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
</style>
<script>

function addTag($val){
	$tags=document.getElementById('tags').value;
	$count=0;
	if($tags!=''){
		$tag_arr=$tags.split("|");
		for(var i=0;i<$tag_arr.length;i++){
			if($tag_arr[i]==$val) $count++;
		}
	}
	if($count==0){
		if($tags=='') $tags=$val; else $tags+='|'+$val;
		document.getElementById('tags').value=$tags;
		document.getElementById('search-tag').value='';
		showTags();
	}
}

function removeTag($val){
	$tags=document.getElementById('tags').value;
	$tags_new='';
	$tag_arr=$tags.split("|");
	for(var i=0;i<$tag_arr.length;i++){
		if($tag_arr[i]!=$val){
			if($tags_new=='') $tags_new=$tag_arr[i]; else $tags_new+='|'+$tag_arr[i];
		}
	}
	document.getElementById('tags').value=$tags_new;
	showTags();
}

function createAddTag(){
	$count=0;
	$tag_create=true;
	$tag_name=document.getElementById('search-tag').value;
	var tag_arr = [<?php for ($x=0;$x<sizeof($tag_name);$x++){ print '"'.$tag_name[$x].'",'; } ?>	];
	document.getElementById('tmp').innerHTML=document.getElementById('div_tag_loading').innerHTML;
	document.getElementById('div_tag_loading').innerHTML=document.getElementById('loading').innerHTML;
	
	for(var i=0;i<tag_arr.length;i++){
		if(tag_arr[i]==$tag_name){
			$count++;
		}
	}
	if($count>0){
		addTag($tag_name);
		document.getElementById('div_tag_loading').innerHTML=document.getElementById('tmp').innerHTML;
		$tag_create=false;
	}
	
	if($tag_create){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var returntext=this.responseText;
				var myObj=JSON.parse(this.responseText);
				if(myObj.status=='Done'){
					document.getElementById('notifications').innerHTML='<span style="color:green; font-weight:bold; font-size:12pt;">Tag: '+$tag_name+' was Created Successfully</span>';
					addTag($tag_name);
				}else{
					document.getElementById('notifications').innerHTML='<span style="color:red; font-weight:bold; font-size:12pt;">'+returntext+'</span>';
				}
				document.getElementById('div_tag_loading').innerHTML=document.getElementById('tmp').innerHTML;
			}
		};
		xmlhttp.open("POST", "index.php?components=inventory&action=create_tag", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send('tag_name='+$tag_name+'&tag_profit=0');
	}
}

function showTags(){
	$tags=document.getElementById('tags').value;
	$tag_list='';
	if($tags!=''){
		$tag_arr=$tags.split("|");
		for(var i=0;i<$tag_arr.length;i++){
			$tag_list+='<span class="badge tag-badge" id="tag-">'+$tag_arr[i]+'<a style="cursor:pointer" onclick=\'removeTag("'+$tag_arr[i]+'")\' class="close"  data-dismiss="alert" aria-label="Remove"><span aria-hidden="true"> &times; </span></a></span> ';
			if((i+1)%3 ==0) $tag_list+='<br />';
		}
	}
	document.getElementById('div_show_tags').innerHTML=$tag_list;
}
</script>

<input type="hidden" id="tags" name="tags" value="" />
<div id="tmp" style="display:none"></div>

<table>
	<tr><td>
		<div class="frmSearch">
		  <input type="text" list="tag-list" id="search-tag" name="search-tag" placeholder="Tag" autocomplete="nope" style="width:90px;" />
		  <datalist id="tag-list">
		  <?php
		  for($i=0;$i<sizeof($tag_name);$i++){
		  	print '<option value="'.$tag_name[$i].'">';
		  }
		  ?>
		  </datalist>
		</div>
	</td><td width="50px"><div id="div_tag_loading"><input type="button" value="Add" class="button2" style="height:38px;" onclick="createAddTag()"  /></div></td></tr>
</table>
</td><td></td></tr>
<tr><td></td><td colspan="2">
	<div id="div_show_tags"></div>
</td><td></td></tr>

