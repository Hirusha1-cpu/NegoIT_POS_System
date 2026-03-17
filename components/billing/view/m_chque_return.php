<?php
                include_once  'template/m_header.php';
                if(isset($_COOKIE['store_name'])) $st_name=$_COOKIE['store_name']; else $st_name='';
                $components=$_GET['components'];
                if(isset($_GET['filter1'])) $filter_sm=$_GET['filter1']; else $filter_sm='';
?>
<!-- ------------------------------------------------------------------------------------ -->
<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
	<style type="text/css">
	.style2 {
		color: navy;
		font-weight: bold;
		background-color:#EEEEEE;
	}
	</style>
</head>

<div class="w3-container" style="margin-top:75px">
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>'; 
	}
?>	

<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
		<h2 align="center" style="color:#0158C2">List Of Returned Chques<?php if($components=='supervisor') print 'for '.$st_name; ?></h2>
		<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0"  style="font-size:xx-small" >
		<tr style="font-weight:bold; background-color:#0066AA; color:white"><th>Chque No</th><th>Bank</th><th>Branch</th><th>Chque Date</th><th>Returned Date</th><th>Amount</th><th>Customer</th><th>Related Invoice</th><th><?php if($components=='supervisor') print 'Salesman'; ?></th></tr>
		<?php 
			if($components=='supervisor'){
				print '<tr style="font-weight:bold; background-color:#0066AA; color:white"><td colspan="8"></td><td>';
				print '<select id="filter1" onchange="window.location = '."'index.php?components=supervisor&action=chque_return&filter1='".'+this.value">';
					print '<option value="">-ALL-</option>';
				for($i=0;$i<sizeof($salesman_filter);$i++){
					if($filter_sm==$salesman_filter[$i]) $select='selected="selected"'; else $select='';
					print '<option value="'.$salesman_filter[$i].'" '.$select.'>'.ucfirst($salesman_filter[$i]).'</option>';
				}
				print '</select>';
				print '</td></tr>';
			}

		$total_amount=0;	
		for($i=0;$i<sizeof($chq0_id);$i++){
			if(($filter_sm==$chq0_salesman[$i])||($filter_sm=='')){
				$total_amount+=$chq0_amount[$i];
				print '<tr><td style="padding-left:10px; padding-right:10px">'.$chq0_no[$i].'</td><td style="padding-left:10px; padding-right:10px">'.$chq0_bank[$i].'</td><td style="padding-left:10px; padding-right:10px">'.$chq0_branch[$i].'</td><td style="padding-left:10px; padding-right:10px">'.$chq0_date[$i].'</td><td style="padding-left:10px; padding-right:10px">'.$chq0_returndate[$i].'</td><td style="padding-left:10px; padding-right:10px" align="right">'.number_format($chq0_amount[$i]).'</td><td style="padding-left:10px; padding-right:10px">'.ucfirst($chq0_cuname[$i]).'</td><td align="center">'.$chq0_invno[$i].'</td>';
				if($components=='supervisor') print '<td style="padding-left:10px; padding-right:10px">'.ucfirst($chq0_salesman[$i]).'</td>';
				else print '<td><input type="button" value="clear" onclick="clearReturnChq('.$chq0_id[$i].',\'billing\')" /></td>';
				print '</tr>';
			}
		} 
		print '<tr><td colspan="5"></td><td style="padding-left:10px; padding-right:10px" align="right">'.number_format($total_amount).'</td><td colspan="3"></td></tr>';
		?>
		</table>
  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>