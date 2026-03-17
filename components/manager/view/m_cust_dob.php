<?php
    include_once  'template/m_header.php';
?>

<style>
	table{
		font-family:Calibri;
	}
	.tbl-header{
		font-family:Calibri; 
		color:maroon; 
		font-weight:bold; 
		background:#EEEEEE;
		width: 100%;
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

    .table-responsive{
		overflow-x: auto;
    	white-space: nowrap;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
	}
</style>

<script type="text/javascript">
	function filterDateRange(){
		$from_date=document.getElementById("from_date").value;
		$to_date=document.getElementById("to_date").value;
		window.location = 'index.php?components=<?php print $components; ?>&action=cust_dob&from_date='+$from_date+'&to_date='+$to_date;	
	}
</script>

<div class="w3-container" style="margin:75px 0;">
    <hr>
    <div class="w3-row">
        <div class="w3-col s3"></div>
        <div class="w3-col">
            <!-- Header -->
            <table align="center" cellspacing="0" class="tbl-header" width="100%">
                <tr>
					<td colspan="8" style="color: black; background: #dddddd;" class="td-style">
                        <strong style="padding-left: 10px">Customer Details</strong>
                    </td>
                </tr>
                <tr>
                    <td align="center"  style="text-align:center;padding: 5px;">
                        <span><strong>From Date : </strong></span>
                        <input type="date" id="from_date" name="from_date" value="<?php if(isset($_REQUEST['from_date']))  print $_REQUEST['from_date']; ?>"/>
                    </td>
                    <td  align="center" style="text-align:center;padding: 5px;">
                        <span><strong>To Date : </strong></span>
                        <input type="date" id="to_date" name="to_date" value="<?php if(isset($_REQUEST['to_date']))  print $_REQUEST['to_date']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center; padding-top:10px;">
                        <input style="width:80px; height:40px; margin: 0 10px 8px 0;" type="button" value="GET" onclick="filterDateRange()"/>		
                    </td>
                </tr>
            </table>
            <!--/ Header -->
            <div class="table-responsive">
                <table align="center" class="styled-table" style="margin-top:10px"  width="100%">
                    <thead>
                        <tr style="background-color:#467898; color:white;">
                            <th align="left">#</th>
                            <th  align="left">Customer Name</th>
                            <th  align="center">Customer Mobile</th>
                            <th  align="left">Customer Home Address</th>
                            <th  align="left">Customer Shop Address</th>
                            <th  align="center">Customer DOB</th>
                            <th  align="center">Customer Age</th>
                            <th align="right">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            for($i=0;$i<sizeof($cust_id);$i++){
                                print '<tr>
                                        <td>'.($i+1).'</td>
                                        <td align="left"><a target="_blank" href="index.php?components=manager&action=editcust&id='.$cust_id[$i].'&show_map=no=">'.$cust_name[$i].'</a></td>
                                        <td align="center">'.$cust_mob[$i].'</td>
                                        <td align="left">'.$cust_home_address[$i].'</td>
                                        <td align="left">'.$cust_shop_address[$i].'</td>
                                        <td align="center">'.$cust_dob[$i].'</td>
                                        <td align="center">'.$cust_age[$i].'</td>
                                        <td align="right">'.($i+1).'</td>
                                    </tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
    include_once  'template/m_footer.php';
?>