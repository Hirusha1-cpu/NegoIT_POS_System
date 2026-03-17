<?php
    include_once  'template/header.php';
?>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<style>
	table{
		font-family:Calibri;
	}
	.tbl-header{
		font-family:Calibri; 
		color:maroon; 
		font-weight:bold; 
		background:#EEEEEE;
		width: 800px;
	}
	.td-style{
		background-color:silver; 
		color:navy; 
		font-family:Calibri; 
		font-size:14pt;
	}
	.styled-table {
		border-collapse: collapse;
		margin-top: 30px;
		font-family:Calibri;
		min-width: 400px;
		box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
        font-size:11pt;
	}
	.styled-table thead tr {
		/* background-color: #009879; */
		background-color: #3f83d7;
		color: #ffffff;
		text-align: left;
	}
	.styled-table th,
	.styled-table td {
		padding: 5px 15px;
	}

	.styled-table tbody tr {
    	border-bottom: thin solid #dddddd;
	}

	.styled-table tbody tr:nth-of-type(even) {
		/* background-color: #f3f3f3; */
	}

	.styled-table tbody tr:last-of-type {
		border-bottom: 2px solid #205081;
	}

	.styled-table tbody tr:hover {background-color: #f3f3f3;}

	.table-wrap {
		margin-top:10px;
		display:flex;
		flex-direction: column;
	}
</style>
<script type="text/javascript">
	function filterDateRange(){
		$from_date=document.getElementById("from_date").value;
		$to_date=document.getElementById("to_date").value;
		window.location = 'index.php?components=<?php print $components; ?>&action=cust_dob&from_date='+$from_date+'&to_date='+$to_date;	
	}

	function generateTag(){
		var cust_arr = [<?php for ($x=0;$x<sizeof($cust_id);$x++){ print '"'.$cust_id[$x].'",'; } ?>	];
		var i;
		var tag_list='';
		for (i = 0; i < cust_arr.length; i++) { 
			if(document.getElementById('tag_'+cust_arr[i]).checked){
			tag_list=tag_list+','+cust_arr[i];
			}
		}
		if(tag_list.length>0){
			tag_list=tag_list.slice(1);
			window.open('index.php?components=<?php print $components; ?>&action=tag_list&id='+tag_list, '_blank');
		}
	}
</script>

<table align="center" style="font-size:11pt">
	<tr>
		<td>
			<?php 
				if(isset($_REQUEST['message'])){
					if($_REQUEST['re']=='success') $color='green'; else $color='red';
				print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br /><br />'; 
				}
			?>
		</td>
	</tr>
</table>

<!-- Header -->
<table align="center" cellspacing="0" class="tbl-header" width="720px" style="font-size:11pt">
	<tr>
        <td align="center"  style="text-align:right;">
			<span><strong>From Date : </strong></span>
			<input type="date" id="from_date" name="from_date" value="<?php if(isset($_REQUEST['from_date']))  print $_REQUEST['from_date']; ?>"/>
		</td>
		<td  align="center" style="text-align:center;">
			<span><strong>To Date : </strong></span>
			<input type="date" id="to_date" name="to_date" value="<?php if(isset($_REQUEST['to_date']))  print $_REQUEST['to_date']; ?>"/>
		</td>
        <td style="text-align:right; padding-top:10px;">
			<input style="width:80px; height:40px; margin: 0 10px 8px 0;" type="button" value="GET" onclick="filterDateRange()"/>		
		</td>
	</tr>
</table>
<!--/ Header -->

<div class="table-wrap">
	<div>
		<table align="center" border="0"  class="styled-table" width="70%">
			<thead>
				<tr>
					<td colspan="8" style="color: black; background: #dddddd;" class="td-style">
                        <strong style="padding-left: 10px">Customer Details</strong>
                    </td>
				</tr>
				<tr>
					<th><input type="button" value="TAG" onclick="generateTag()" style="height:40px" /></th></th>
					<th width="10px" align="left">#</th>
					<th width="120px" align="left">Customer Name</th>
					<th width="120px" align="center">Customer Mobile</th>
					<th width="120px" align="left">Customer Home Address</th>
					<th width="120px" align="left">Customer Shop Address</th>
					<th width="120px" align="center">Customer DOB</th>
					<th width="100px" align="center">Customer Age</th>
				</tr>
			</thead>
			<tbody>
				<?php
				    for($i=0;$i<sizeof($cust_id);$i++){
                        print '<tr>
								<th><input type="checkbox" id="tag_'.$cust_id[$i].'" /></th>
                                <td>'.($i+1).'</td>
                                <td align="left"><a target="_blank" href="index.php?components=manager&action=editcust&id='.$cust_id[$i].'&show_map=no=">'.$cust_name[$i].'</a></td>
                                <td align="center">'.$cust_mob[$i].'</td>
                                <td align="left">'.$cust_home_address[$i].'</td>
                                <td align="left">'.$cust_shop_address[$i].'</td>
                                <td align="center">'.$cust_dob[$i].'</td>
                                <td align="center">'.$cust_age[$i].'</td>
                            </tr>';
				    }
				?>
			</tbody>
		</table>
	</div> <!--/ DOB List  -->
</div> <!---/wrap -->
<?php
	// if ($_GET['action'] == 'cust_dob') 
    //     include_once  'components/manager/view/tpl/cust_dob.php';
	// else 
    //     include_once  'components/manager/view/tpl/add_cust.php';
?>

<?php
    include_once  'template/footer.php';
?>