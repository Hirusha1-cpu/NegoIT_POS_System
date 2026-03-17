<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->

	<script src="js/zigo.js"></script>
<script type="text/javascript">
	function validateError($id) {
	document.getElementById('div_'+$id).innerHTML=document.getElementById('loading').innerHTML;
	  var $itq_id=document.getElementById('itq_id_'+$id).value;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    var returntext=this.responseText;
	     document.getElementById('div_'+$id).innerHTML = returntext;
	    }
	  };
	  xhttp.open("GET", 'index.php?components=backend&action=validate_error&itq_id='+$itq_id, true);
	  xhttp.send();
	}
</script>
<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
<!-- ------------------Item List----------------------- -->
<div id="loading" style="display:none"><img src="../images/loading.gif" style="width:30px" /></div>

	<table align="center" style="font-size:11pt"><tr><td>
	<?php 
		if(isset($_REQUEST['message'])){
			if($_REQUEST['re']=='success') $color='green'; else $color='red';
		print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br /><br />'; 
		}
	?></td></tr></table>
<table align="center" border="0" cellspacing="0" width="100%" bgcolor="F1F1F1">
<?php if($_GET['list']=='err'){ ?>
	<tr><td></td><td align="right"><input type="button" value="All List" onclick="window.location = 'index.php?components=backend&action=mismatch&list=all'" /></td></tr>
<?php } if($_GET['list']=='all'){ ?>
	<tr><td></td><td align="right"><input type="button" value="Error List" onclick="window.location = 'index.php?components=backend&action=mismatch&list=err'" /></td></tr>
<?php } ?>
</table>
<br>
<table align="center" border="1" cellspacing="0">
<tr bgcolor="#CCCCCC"><th>Item</th><th>Location</th><th>Unic Qty</th><th>ITQ Qty</th><th></th><th></th></tr>
<?php
for($i=0;$i<sizeof($litq_itm_id);$i++){
	if($_GET['list']=='err'){
		if($issue[$i]=='Error'){
			$button='<input type="button" value="Check" onclick="validateError('.$i.')"  />';
			print '<tr><td><input type="hidden" id="itq_id_'.$i.'" value="'.$litq_itq_id[$i].'" /><a href="index.php?components=backend&action=mismatch_one&itq_id='.$litq_itq_id[$i].'" title="'.$litq_itm_id[$i].'">'.$litq_itm_desc[$i].'</a></td><td><a title="'.$litq_st_id[$i].'">'.$litq_st_name[$i].'</a></td><td>'.$litq_itu_qty[$i].'</td><td><a title="ITQ ID = '.$litq_itq_id[$i].'">'.$litq_itq_qty[$i].'</a></td><td>'.$issue[$i].'</td><td><div id="div_'.$i.'" style="color:red">'.$button.'</div></td></tr>';
		}
	}else{
			if($issue[$i]=='Error') $button='<input type="button" value="Check" onclick="validateError('.$i.')"  />'; else $button=''; 
			print '<tr><td><input type="hidden" id="itq_id_'.$i.'" value="'.$litq_itq_id[$i].'" /><a href="index.php?components=backend&action=mismatch_one&itq_id='.$litq_itq_id[$i].'" title="'.$litq_itm_id[$i].'">'.$litq_itm_desc[$i].'</a></td><td><a title="'.$litq_st_id[$i].'">'.$litq_st_name[$i].'</a></td><td>'.$litq_itu_qty[$i].'</td><td><a title="ITQ ID = '.$litq_itq_id[$i].'">'.$litq_itq_qty[$i].'</a></td><td>'.$issue[$i].'</td><td><div id="div_'.$i.'" style="color:red">'.$button.'</div></td></tr>';
	}
}
?>
</table>
</div>	
  </div>
</div>
</div>
<hr>
Case 1: Check are there any <u>free IMEIs</u> listed in Bills<br />
Case 2: Check for any Bills without a IMEI for given item
<br />
<?php
                include_once  'template/m_footer.php';
?>
