<?php
    include_once  'template/header.php';
?>
<?php if($_REQUEST['components'] == 'report'){ ?>
    <script type="text/javascript">
    function setOrder($type){
        $order='';
        if($type=='sm'){
            <?php if(isset($_GET["sm_order"])){ ?>
            $order='';
            <?php }else{  ?>
            if(document.getElementById("sm_order").checked==true){
                $order='&sm_order=1';
            }
            <?php } ?>
        }
        window.location = 'index.php?components=<?php print $components; ?>&action=salesman_commission_incomplete'+$order;
    }
    </script>
<?php } ?>

<style>
	table{
		font-family:Calibri;
	}
	.tbl-header{
		font-family:Calibri; 
		color:maroon; 
		font-weight:bold; 
		background:#EEEEEE;
		width: 920px;
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
</style>

<!-- Header -->
<table align="center" cellspacing="0" class="tbl-header" width="940px">
	<tr>
		<td align="center" style="padding: 10px; font-size: 13pt;">
            In-Completed Bills | Commission Not Paid
		</td>
	</tr>
</table>
<!--/ Header -->

<table align="center" class="styled-table" style="margin-top:10px"  width="920px">
    <thead>
        <tr style="background-color:#467898; color:white;">
            <th width="20px">#</th>
            <th class="shipmentTB3" align="center">Invoice No</th>
            <th class="shipmentTB3" align="center">Customer</th>
            <th class="shipmentTB3" align="center">Salesman <input type="checkbox" id="sm_order" onclick="setOrder('sm')"/> Order</span></th>
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
            print '<td class="shipmentTB3"><a target="_blank" href="index.php?components=report&action=salesman_commission_incomplete_one&user='.$salesman_id[$i].'">'.ucfirst($salesman[$i]).'</a></td>
                    <td class="shipmentTB3" align="left">'.$reason[$i].'</td>
                    <td class="shipmentTB3" align="right">'.number_format($paid_amount[$i]).'</td>';
            print '<td class="shipmentTB3" align="right">'.number_format($inv_total[$i]).'</td>';
            print '</tr>';
        }?>
    </tbody>
</table>

<br>
<?php if($_REQUEST['components'] == 'report'){ ?>
    <table align="center" width="100%">
        <tr>
            <td align="center">
                <div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
                    <a class="shortcut-button" onclick="printdiv('print','printheader')" href="#">
                        <span style="text-decoration:none; font-family:Arial; color:navy;">
                        <img src="images/print.png" alt="icon" /><br />
                        Print
                        </span>
                    </a>
                </div>
            </td>
        </tr>
    </table>
<?php } ?>

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?>.</h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">In-Completed Bills | Commission Not Paid</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr>
            <td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px">Print Date</td>
            <td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print date("Y-m-d",time()); ?>&nbsp;&nbsp;</td>
        </tr>
	</table><br />
</div>

<div id="print" style="display:none">
    <table align="center" style="font-family:Calibri">
        <tr>
            <table width="100%">
                <tr style="background-color:#467898; color:white;">
                    <th width="20px">#</th>
                    <th class="shipmentTB3" align="center">Invoice No</th>
                    <th class="shipmentTB3" align="center">Customer</th>
                    <th class="shipmentTB3" align="center">Salesman</th>
                    <th class="shipmentTB3" align="center">Status</th>
                    <th class="shipmentTB3" align="center">Paid Amount</th>
                    <th class="shipmentTB3" align="center">Invoice Total</th>
                </tr>
                    <?php
                    for($i=0;$i<sizeof($inv_no);$i++){
                        if(strlen($cust[$i])>25) $cust_name=substr($cust[$i],0,25).'...'; else $cust_name=$cust[$i];
                        if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
                        print '<tr bgcolor="'.$color.'">
                                <td>'.($i+1).'</td>
                               <td class="shipmentTB3" align="center">
                                    <a target="_blank" href="index.php?components=billing&action=finish_bill&id='.$inv_no[$i].'" style="text-decoration:none" >'.str_pad($inv_no[$i], 7, "0", STR_PAD_LEFT).'</a>
                               </td>
                               <td class="shipmentTB3"><a title="'.$cust[$i].'">'.$cust_name.'</a></td>';
                        print '<td class="shipmentTB3">'.ucfirst($salesman[$i]).'</td>
                               <td class="shipmentTB3" align="left">'.$reason[$i].'</td>
                               <td class="shipmentTB3" align="right">'.number_format($paid_amount[$i]).'</td>';
                        print '<td class="shipmentTB3" align="right">'.number_format($inv_total[$i]).'</td>';
                        print '</tr>';
                    }
            print '</table>';
            ?>
        </tr>
    </table>
</div>

<?php
    include_once  'template/footer.php';
?>