<?php
    include_once  'template/m_header.php';
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
<style>
    #wrapper {
        display: flex;
        flex-direction: column;
    }

    #firstDiv {
    order: 1;
    }

    #secondDiv {
    order: 0;
    }
</style>

<div class="w3-container" style="margin-top:75px">
    <hr>
    <div class="w3-row">
        <div class="w3-col s3"></div>
        <div class="w3-col">
            <div id="wrapper">
                <div id="firstDiv">
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
                    ?>
                </div>
                <div id="secondDiv">
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
                                            print '<a href="index.php?components=report&action=salesman_commission_one_user&id='.$cm_id.'&user='.$cm_salesman_id[$i].'" style="text-decoration:none;">'.ucfirst($cm_salesman[$i]).'</a>';
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
                            if(($proceed_status1 && $delete_permission) && (!isset($_GET['user'])) && ($_REQUEST['components'] == 'report')){ ?>
                                <tr style="background-color:#EEEEEE;"><th class="shipmentTB3" colspan="2"><div id="div_gen_btn"><input type="button" value="Delete Commission Report" style="width:200px; height:50px; background-color:maroon; color:#FFFFFF" onclick="deleteCommissionReport(<?php print $cm_id; ?>)" /></div></th></tr>
                        <?php 
                            }
                        ?>
                    </table>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>

<hr>
<br />

<?php
    include_once  'template/m_footer.php';
?>