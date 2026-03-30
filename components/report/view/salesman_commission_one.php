<?php
    include_once  'template/header.php';
?>
<?php if($_REQUEST['components'] == 'report'){ ?>
<script type="text/javascript">
    function deleteCommissionReport($id){
        var check= confirm("Do you want Delete this Commission Report?");
        if (check== true)
            window.location = 'index.php?components=<?php print $components; ?>&action=salesman_commission_delete&id='+$id;
    }
</script>
<?php } ?>

<table align="center" style="font-family:Calibri">
    <tr>
        <td align="center" class="shipmentTB4" style="background-color:#568898; color:white; font-size:14pt">History Data | Commission Paid</td>
        <td width="100px"></td>
        <td align="center" class="shipmentTB4" style="background-color:#568898; color:white; font-size:14pt">Summary</td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%">
                <tr>
                    <td align="center" class="shipmentTB4" style="background-color:#568898; color:white; font-size:14pt" colspan="5">Salesman | Commission Paid</td>
                </tr>
                <tr style="background-color:#467898; color:white;">
                    <th class="shipmentTB3">Invoice No</th>
                    <th class="shipmentTB3">Customer</th>
                    <th class="shipmentTB3">Salesman</th>
                    <th class="shipmentTB3">Commission</th>
                </tr>
            <?php
                $st='Completed Invoice';
                for($i=0;$i<sizeof($invoice_no);$i++){
                    if(strlen($cust[$i])>25) $cust_name=substr($cust[$i],0,25).'...'; else $cust_name=$cust[$i];
                    $sm_commission_arr[$salesman[$i]]+=$commission[$i];
                    
                    if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
                    print '<tr bgcolor="'.$color.'">
                            <td class="shipmentTB3" align="center"><a target="_blank" href="index.php?components=billing&action=finish_bill&id='.$invoice_no[$i].'" style="text-decoration:none" >'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td>
                            <td class="shipmentTB3"><a title="'.$cust[$i].'">'.$cust_name.'</a></td>';
                    print '<td class="shipmentTB3">'.ucfirst($salesman[$i]).'</td>
                        <td class="shipmentTB3" align="right">'.number_format($commission[$i]).'</td>';
                    print '</tr>';
                }
                print '</table>';
                print '<br />';
            ?>
        </td>
        <td></td>
        <td valign="top">
            <table width="100%">
                <tr style="background-color:#467898; color:white;">
                    <th class="shipmentTB3">Salesman</th>
                    <th class="shipmentTB3">Total Commission</th>
                </tr>
                    <?php
                        $proceed_status1=false;
                        for($i=0;$i<sizeof($cm_salesman);$i++){
                            if((string)$sm_commission_arr[$cm_salesman[$i]] == (string)$sm_commission_arr1[$cm_salesman[$i]]) $proceed_status1=true;
                            if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
                            print '<tr bgcolor="'.$color.'">
                                    <td class="shipmentTB3">';
                                        if($_REQUEST['components'] == 'report'){
                                            print '<a href="index.php?components=report&action=salesman_commission_one_user&id='.$cm_id.'&user='.$cm_salesman_id[$i].'" style="text-decoration:none;">'.ucfirst($cm_salesman[$i]).'</a>';
                                        }else{
                                         print '<a href="#" style="text-decoration:none;">'.ucfirst($cm_salesman[$i]).'</a>';
                                        }
                                    print '</td>
                                    <td class="shipmentTB3" align="right">'.number_format($commission_amount[$i]).'</td></tr>';
                        }
                    ?>
            </table>
            <hr />
            <table width="100%">
                <tr style="background-color:#467898; color:white;">
                    <th class="shipmentTB3" align="left">Report No</th>
                    <th class="shipmentTB3"><?php print str_pad($cm_id, 7, "0", STR_PAD_LEFT); ?></th>
                </tr>
                <tr style="background-color:#467898; color:white;">
                    <th class="shipmentTB3" align="left">Total<br />Commission</th>
                    <th class="shipmentTB3"><input type="text" id="total_com" value="<?php print number_format($com_sm_amo); ?>" disabled="disabled" style="text-align:right" /></th>
                </tr>
                <tr style="background-color:#467898; color:white;">
                    <th class="shipmentTB3" align="left">Month</th>
                    <th class="shipmentTB3"><input type="month" id="month" name="month" value="<?php print $cm_month; ?>" disabled="disabled" /></th>
                </tr>
                <tr style="background-color:#467898; color:white;">
                    <th class="shipmentTB3" align="left">Status</th>
                    <td class="shipmentTB3"><div id="div_status" ><?php if($proceed_status1) print 'Calculation Okay'; else print '<span class="blink">Calculation Error</span>'; ?></div></td>
                </tr>
                <?php
                    if(($proceed_status1 && $delete_permission) && (!isset($_GET['user']) && ($_REQUEST['components'] == 'report'))){ ?>
                        <tr style="background-color:#EEEEEE;"><th class="shipmentTB3" colspan="2"><div id="div_gen_btn"><input type="button" value="Delete Commission Report" style="width:200px; height:50px; background-color:maroon; color:#FFFFFF" onclick="deleteCommissionReport(<?php print $cm_id; ?>)" /></div></th></tr>
                <?php } ?>
            </table>
            <?php if(($_REQUEST['components'] == 'report')){ ?>
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
        </td>
    </tr>
</table>

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?>.</h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Salesman | Commission Paid</h2>
	<h3 align="center" style="color:#3333FF;">Report Month : <?php print $cm_month; ?></h3>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr>
            <td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px">Print Date</td>
            <td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print date("Y-m-d",time()); ?>&nbsp;&nbsp;</td>
        </tr>
	</table><br />
	<p>Note: <?php print $inf_company; ?>, this report is prepared for salesman commission paid to <?php print $cm_month; ?></p><hr>
</div>

<div id="print" style="display:none">
    <table align="center" style="font-family:Calibri">
        <tr>
        <table width="100%">
                <tr style="background-color:#467898; color:white;">
                    <th class="shipmentTB3">Invoice No</th>
                    <th class="shipmentTB3">Customer</th>
                    <th class="shipmentTB3">Salesman</th>
                    <th class="shipmentTB3">Commission</th>
                </tr>
            <?php
                $st='Completed Invoice';
                for($i=0;$i<sizeof($invoice_no);$i++){
                    if(strlen($cust[$i])>25) $cust_name=substr($cust[$i],0,25).'...'; else $cust_name=$cust[$i];
                    $sm_commission_arr[$salesman[$i]]+=$commission[$i];
                    
                    if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
                    print '<tr bgcolor="'.$color.'">
                            <td class="shipmentTB3" align="center"><a target="_blank" href="index.php?components=billing&action=finish_bill&id='.$invoice_no[$i].'" style="text-decoration:none" >'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td>
                            <td class="shipmentTB3" align="left"><a title="'.$cust[$i].'">'.$cust_name.'</a></td>';
                    print '<td class="shipmentTB3" align="left">'.ucfirst($salesman[$i]).'</td>
                        <td class="shipmentTB3" align="right">'.number_format($commission[$i]).'</td>';
                    print '</tr>';
                    
                }
                print '<tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight:bold;" align="right">Total Commission: '.number_format($com_sm_amo).'</td>
                        </tr>';
                print '</table>';
                print '<br />';
            ?>
        </tr>
    </table>
</div>

<?php
    include_once  'template/footer.php';
?>