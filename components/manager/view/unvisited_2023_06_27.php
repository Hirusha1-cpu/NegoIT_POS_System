<?php
                include_once  'template/header.php';
                $components=$_GET['components'];
?>
<!-- ------------------Item List----------------------- -->
	<table align="center" height="100%" cellspacing="0" style="font-family:Calibri" bgcolor="#F0F0F0" >
	<tr><td width="50px"></td><td colspan="8">
		<form id="search_form" action="index.php?components=<?php print $_GET['components']; ?>&action=unvisited&type=<?php print $_GET['type']; ?>&asso_salesman=<?php print $_GET['asso_salesman']; ?>" method="post" >
			<table>
			<tr><td><strong>From </strong>: &nbsp;<input type="date" name="from_date" style="width:130px" value="<?php print $from_date; ?>" />&nbsp;&nbsp;&nbsp;<strong>To </strong>: &nbsp;<input type="date" name="to_date" style="width:130px" value="<?php print $to_date; ?>" />
			</td><td><a onclick="document.getElementById('search_form').submit();" style="cursor:pointer"><img src="images/search.png" style="width:30px; vertical-align:middle" /></a>
			</td></tr></table>
		</form>
	</td><td width="50px"></td><td><strong>Associated<br />Salesman</strong></td><td>
	<select name="asso_salesman" id="asso_salesman" onchange="window.location = 'index.php?components=<?php print $_GET['components']; ?>&action=unvisited&type='+document.getElementById('type').value+'&asso_salesman='+document.getElementById('asso_salesman').value" <?php if($_GET['components']=='billing') print 'disabled'; ?> >
		<option value="all" >-ALL-</option>
		<?php for($i=0;$i<sizeof($sm_id);$i++){
			if($sm_id[$i]==$_GET['asso_salesman']){ $select='selected="selected"'; $smname=ucfirst($sm_name[$i]); }else{ $select=''; }
		 	print '<option value="'.$sm_id[$i].'" '.$select.'>'.ucfirst($sm_name[$i]).'</option>';
		} ?>
	</select>
	</td><td width="50px"></td><td>
	<select name="type" id="type" onchange="window.location = 'index.php?components=<?php print $_GET['components']; ?>&action=unvisited&type='+document.getElementById('type').value+'&asso_salesman='+document.getElementById('asso_salesman').value">
		<option value="unvisited" <?php if($_GET['type']=='unvisited') print 'selected="selected"'; ?> >Unvisited</option>
		<option value="visited" <?php if($_GET['type']=='visited') print 'selected="selected"'; ?> >Visited</option>
	</select>
	</td><td width="50"></td></tr></table>
	<hr />

<br /><br />
<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<table><tr><td>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px">Date</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print 'From :'.$from_date.' To :'.$to_date; ?></td></tr>
	</table>
	</td></tr></table>
	<hr />
</div>

<div id="print">
	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-family:Calibri">
	<?php if($_GET['type']=='unvisited'){ ?>
		<tr style="border:0; background-color:black; color:white; font-weight:bold"><td>Unvisited Customers</td><td>Associated SM</td></tr>
		<?php
		for($i=0;$i<sizeof($unvisited_cust_id);$i++){
			if(($_GET['asso_salesman']=='all')||($_GET['asso_salesman']==$unvisited_cust_sm_id[$i])){
				print '<tr><td><a style="text-decoration:none;" href="index.php?components='.$components.'&action=cust_details&id='.$unvisited_cust_id[$i].'&action2=unvisited" >'.$unvisited_cust_name[$i].'</a></td><td>'.ucfirst($unvisited_cust_sm_name[$i]).'</td></tr>';
			}
		}
	}else{ ?>
		<tr style="border:0; background-color:black; color:white; font-weight:bold"><td >Visited Customers</td><td>Associated SM</td></tr>
		<?php
		for($i=0;$i<sizeof($visited_cust_id);$i++){
			if(($_GET['asso_salesman']=='all')||($_GET['asso_salesman']==$visited_cust_sm_id[$i])){
				print '<tr><td><a style="text-decoration:none;" href="index.php?components='.$components.'&action=cust_details&id='.$visited_cust_id[$i].'&action2=unvisited" >'.$visited_cust_name[$i].'</a></td><td>'.ucfirst($visited_cust_sm_name[$i]).'</td></tr>';
			}
		}
	 }	?>	
	</table>
</div>
<br />
<table align="center"><tr><td align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br />
	Print
	</span></a>
</div>
</td></tr></table>

<?php
                include_once  'template/footer.php';
?>