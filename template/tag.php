<?php
	getTags();
?>
<input type="hidden" id="tags" name="tags" value="<?php if(isset($_REQUEST['tags'])) print $_REQUEST['tags']; ?>" />
<input type="hidden" id="tag_selection" name="tag_selection" value="1" />

<script type="text/javascript">
function addTag(){
	$val=document.getElementById('search-tag').value;
	$tags=document.getElementById('tags').value;
	$count=0;
	if($val!=''){
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
			nextTagAction();
		}
	}
	if(($val=='')&&($tags!='')) nextTagAction();
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
	nextTagAction();
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

function setSelection($selection){
	document.getElementById('tag_selection').value=$selection;
	if($selection==1){
		document.getElementById("tag_selection1").className='tag_selected';
		document.getElementById("tag_selection2").className='';
	}else{
		document.getElementById("tag_selection1").className='';
		document.getElementById("tag_selection2").className='tag_selected';
	}
}
</script>

	<table class="shipmentTB3"><tr><td>
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
	</td><td>
		<div id="div_tag_loading"><input type="button" value="Apply Tag" class="button3" style="height:25px;" onclick="addTag()" /></div>
	</td><td>
		<a onclick="setSelection(1)" style="cursor:pointer" title="eg: Either Tag1 OR Tag2"><img src="images/selction1.gif" id="tag_selection1" style="width:30px" class="tag_selected" /></a>
		<a onclick="setSelection(2)" style="cursor:pointer" title="eg: Both Tag1 G1 AND Tag2"><img src="images/selction2.gif" id="tag_selection2" style="width:30px" /></a>
	</td><td>
		<div id="div_show_tags"></div>
	</td></tr></table>
<script type="text/javascript">
	showTags();
	<?php
	if(isset($_REQUEST['tag_selection'])){
		print 'setSelection('.$_REQUEST['tag_selection'].')';
	}
	?>
</script>
