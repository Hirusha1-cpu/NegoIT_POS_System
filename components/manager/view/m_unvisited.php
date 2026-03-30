<?php
                include_once  'template/m_header.php';
                
                $backdate14=date("Y-m-d",time()-(60*60*24*14));
                $backdate30=date("Y-m-d",time()-(60*60*24*30));
                if($from_date==$backdate14) $selected_fromdate=$backdate14;
                if($from_date==$backdate30) $selected_fromdate=$backdate30;
?>
<!-- ------------------------------------------------------------------------------------ -->

	<script type="text/javascript">
		function setDuration($type){
		  var from_date1='<?php print $backdate14; ?>';
		  var from_date2='<?php print $backdate30; ?>';
		  if($type==1){
		  	document.getElementById('from_date').value=from_date1;
		  }
		  if($type==2){
		  	document.getElementById('from_date').value=from_date2;
		  }
		  document.getElementById("search_form").submit();
		}
	</script>
	<style type="text/css">
	a{ 
    color: blue;
    text-decoration:none;
	}
	a:visited { 
    color:maroon;
    text-decoration:none;
	}
	</style>
<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
	<form id="search_form" action="index.php?components=<?php print $_GET['components']; ?>&action=unvisited&type=<?php print $_GET['type']; ?>&asso_salesman=<?php print $_GET['asso_salesman']; ?>" method="post" >
	<table height="100%" cellspacing="0" style="font-family:Calibri" bgcolor="#F0F0F0" >
		<tr><td width="50px"></td>
	<?php if($_GET['components']=='billing'){
		print '<td><strong>Duration </strong>:</td><td>'; ?>
		<input type="hidden" name="from_date" id="from_date" value="<?php print $selected_fromdate; ?>" />
		<input type="hidden" name="to_date" id="to_date" value="<?php print dateNow(); ?>" />
		<select onchange="setDuration(this.value)">
		<option value="1" <?php if($from_date==$backdate14) print 'selected="selected"'; ?> >-Two Weeks-</option>
		<option value="2" <?php if($from_date==$backdate30) print 'selected="selected"'; ?> >-One Month-</option>
		</select>
		
		<?php print '</td>';
	}else{ ?>
		<td><strong>From </strong>:</td><td><input type="date" name="from_date" style="width:130px" value="<?php print $from_date; ?>" />&nbsp;&nbsp;&nbsp;<strong>To </strong>: &nbsp;<input type="date" name="to_date" style="width:130px" value="<?php print $to_date; ?>" /></td>
	<?php } ?>
			<td><a onclick="document.getElementById('search_form').submit();" style="cursor:pointer"><img src="images/search.png" style="width:30px; vertical-align:middle" /></a></td>
		</tr><tr>
	<td></td><td><strong>Associated<br />Salesman</strong></td><td colspan="2">
	<?php if($_GET['components']=='billing'){
		$key=array_search($_GET['asso_salesman'],$sm_id);
		if($key>-1) $asso_sm_name=ucfirst($sm_name[$key]); else $asso_sm_name='ALL';
		print '<input type="text" value="'.$asso_sm_name.'" />';
		print '<input type="hidden" name="asso_salesman" id="asso_salesman" value="'.$_GET['asso_salesman'].'" />';
	}else{ ?>
	<select name="asso_salesman" id="asso_salesman" onchange="window.location = 'index.php?components=<?php print $_GET['components']; ?>&action=unvisited&type='+document.getElementById('type').value+'&asso_salesman='+document.getElementById('asso_salesman').value"   >
		<option value="all" >-ALL-</option>
		<?php for($i=0;$i<sizeof($sm_id);$i++){
			if($sm_id[$i]==$_GET['asso_salesman']){ $select='selected="selected"'; $smname=ucfirst($sm_name[$i]); }else{ $select=''; }
		 	print '<option value="'.$sm_id[$i].'" '.$select.'>'.ucfirst($sm_name[$i]).'</option>';
		} ?>
	</select>
	<?php } ?>
	&nbsp;&nbsp;&nbsp;&nbsp;<strong>Filter</strong>	<select id="type" onchange="window.location = 'index.php?components=<?php print $_GET['components']; ?>&action=unvisited&type='+document.getElementById('type').value+'&asso_salesman='+document.getElementById('asso_salesman').value">
		<option value="unvisited" <?php if($_GET['type']=='unvisited') print 'selected="selected"'; ?> >Unvisited</option>
		<option value="visited" <?php if($_GET['type']=='visited') print 'selected="selected"'; ?> >Visited</option>
	</select>
	</td></tr></table>
	</form>
	<hr />

	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-family:Calibri">
	<?php if($_GET['type']=='unvisited'){ ?>
		<tr style="border:0; background-color:black; color:white; font-weight:bold"><td>Unvisited Customers</td><td>Associated SM</td></tr>
		<?php
		for($i=0;$i<sizeof($unvisited_cust_id);$i++){
			if(($_GET['asso_salesman']=='all')||($_GET['asso_salesman']==$unvisited_cust_sm_id[$i])){
				print '<tr><td><a href="index.php?components=billing&action=cust_details&id='.$unvisited_cust_id[$i].'&action2=unvisited" >'.$unvisited_cust_name[$i].'</a></td><td>'.ucfirst($unvisited_cust_sm_name[$i]).'</td></tr>';
			}
		}
	}else{ ?>
		<tr style="border:0; background-color:black; color:white; font-weight:bold"><td >Visited Customers</td><td>Associated SM</td></tr>
		<?php
		for($i=0;$i<sizeof($visited_cust_id);$i++){
			if(($_GET['asso_salesman']=='all')||($_GET['asso_salesman']==$visited_cust_sm_id[$i])){
				print '<tr><td><a href="index.php?components=billing&action=cust_details&id='.$visited_cust_id[$i].'&action2=unvisited" >'.$visited_cust_name[$i].'</a></td><td>'.ucfirst($visited_cust_sm_name[$i]).'</td></tr>';
			}
		}
	 }	?>	
	</table>

  </div>
</div>
</div>
<hr>
<br />
<?php
                include_once  'template/m_footer.php';
?>
