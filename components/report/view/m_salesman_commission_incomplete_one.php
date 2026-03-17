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

<div class="w3-container" style="margin:75px 0;">
    <hr>
    <div class="w3-row">
        <div class="w3-col s3"></div>
        <div class="w3-col">
            <!-- Header -->
            <table align="center" cellspacing="0" class="tbl-header" width="100%">
                <tr>
                    <td align="center" style="padding: 10px; font-size: 13pt;">
                    <?php if(!empty($salesman)) echo ucfirst($salesman[0])."'s"; ?> In-Completed Bills | Commission Not Paid
                    </td>
                </tr>
            </table>
            <!--/ Header -->
            <div class="table-responsive">
                <table align="center" class="styled-table" style="margin-top:10px"  width="100%">
                    <thead>
                        <tr style="background-color:#467898; color:white;">
                            <th width="20px">#</th>
                            <th class="shipmentTB3" align="center">Invoice No</th>
                            <th class="shipmentTB3" align="center">Customer</th>
                            <th class="shipmentTB3" align="center">Salesman</th>
                            <th class="shipmentTB3" align="center">Status</th>
                            <th class="shipmentTB3" align="center">Paid Amount</th>
                            <th class="shipmentTB3" align="center">Invoice Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for($i=0;$i<sizeof($inv_no);$i++){
                            if(strlen($cust[$i])>25) $cust_name=substr($cust[$i],0,25).'...'; else $cust_name=$cust[$i];
                            if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
                            print '<tr>
                                    <td>'.($i+1).'</td>
                                    <td class="shipmentTB3" align="center">
                                        <a target="_blank" href="index.php?components=billing&action=finish_bill&id='.$inv_no[$i].'" style="text-decoration:none" >'.str_pad($inv_no[$i], 7, "0", STR_PAD_LEFT).'</a>
                                    </td>
                                    <td class="shipmentTB3"><a title="'.$cust[$i].'">'.$cust_name.'</a></td>';
                            print '<td class="shipmentTB3" align="center">'.ucfirst($salesman[$i]).'</td>
                                    <td class="shipmentTB3" align="left">'.$reason[$i].'</td>
                                    <td class="shipmentTB3" align="right">'.number_format($paid_amount[$i]).'</td>';
                            print '<td class="shipmentTB3" align="right">'.number_format($inv_total[$i]).'</td>';
                            print '</tr>';
                        }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php
    include_once  'template/m_footer.php';
?>