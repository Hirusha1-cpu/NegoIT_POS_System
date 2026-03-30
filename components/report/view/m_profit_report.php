<?php
    include_once  'template/m_header.php';
?>

<script>
    function validateForm(){
  		if(validateDateRange()){
  			document.getElementById('div_submit').innerHTML=document.getElementById('loading').innerHTML;
  			return true;
  		}else{
			return false;
  		}
  	}
</script>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<div class="w3-container" style="margin-top:75px; margin-bottom:75px;">
    <div class="w3-row">
        <div class="w3-col s3"></div>
        <div class="w3-col">

        <form id="rep_form1" action="index.php?components=report&action=profit_report" method="post" onsubmit="return validateForm()">
            <table align="center" border="0">
                <tr>
                    <td style="vertical-align:top">
                        <div style="border-radius:10px; background-color:#DDDDDD">
                            <table align="center" height="100%" border="0" cellspacing="0" style="font-family:Calibri; font-size:12pt">
                                <tr>
                                    <td width="30px"></td>
                                    <td align="right"><strong>From Date : </strong></td>
                                    <td>
                                        <input type="date" id="from_date" name="from_date" style="width:130px" value="<?php print $from_date; ?>" />
                                    </td>
                                    <td width="30px"></td>
                                    <td align="right"><strong>To Date : </strong></td>
                                    <td>
                                        <input type="date" id="to_date" name="to_date" style="width:130px" value="<?php print $to_date; ?>" />
                                    </td>
                                    <td width="30px"></td>
                                    <td align="right"><strong>HP/Cash</strong></td>
                                    <td>
                                        <select name="hp" >
                                            <option value="">--ALL--</option>
                                            <option value="yes" <?php if($hp=='yes') print 'selected="selected"'; ?>>HP</option>
                                            <option value="no" <?php if($hp=='no') print 'selected="selected"'; ?>>Cash</option>
                                        </select>
                                    </td>
                                    <td width="30px"></td>
                                    <td align="right"><strong>Group</strong></td>
                                    <td>
                                        <select name="group" >
                                        <option value="">--ALL--</option>
                                            <?php
                                                $group_print='ALL';
                                                for($i=0;$i<sizeof($gp_id);$i++){
                                                    if($gp_id[$i]==$group){
                                                        $select='selected="selected"'; $group_print=ucfirst($gp_name[$i]);
                                                    }else{
                                                        $select='';
                                                    }
                                                    print '<option value="'.$gp_id[$i].'" '.$select.'>'.ucfirst($gp_name[$i]).'</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td width="30px"></td>
                                    <td align="right"><strong>Sub System</strong></td>
                                    <td>
                                        <select name="sub_system0" >
                                        <option value="">--ALL--</option>
                                            <?php
                                                $sub_system_print='ALL';
                                                for($i=0;$i<sizeof($sub_system_list);$i++){
                                                    if($sub_system_list[$i]==$sub_system0){
                                                        $select='selected="selected"'; $sub_system_print=ucfirst($sub_system_names[$i]);
                                                    }else{
                                                        $select='';
                                                    }
                                                    print '<option value="'.$sub_system_list[$i].'" '.$select.'>'.ucfirst($sub_system_names[$i]).'</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td width="50px"></td>
                                    <td>
                                        <div id="div_submit">
                                            <input type="submit" value="GET" style="width:50px; height:40px" />
                                        </div>
                                    </div>
                                </tr>
                            </table>
                        </div>
                        <br /><br />
                    </td>
                </tr>
            </table>
        </form>

        <br>

        <div id="print">
            <table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-family:Calibri">
                <tr>
                    <td colspan="10" style="border:0; background-color:black; color:white; font-weight:bold">Invoices</td>
                </tr>
                <tr bgcolor="#BBBBBB">
                    <th width="50px">#</th>
                    <th width="100px">Invoice No</th>
                    <th width="100px">Date</th>
                    <th width="300px">Customer</th>
                    <th width="100px">Type</th>
                    <th width="100px">Invoice Price</th>
                    <th width="100px">Invoice Cost</th>
                    <th width="100px">Invoice<br />Profit</th>
                    <th width="100px">Advanced Paid Amount</th>
                    <th width="100px">Total Collection</th>
                </tr>
                <?php
                    for($i=0;$i<sizeof($invoice_no);$i++){
                        if($i%2 ==0) $color1='#EEEEEE'; else $color1='#DDDDDD';
                        if($invoice_type[$i] != '') $type = 'HP'; else $type = 'Cash';
                            print '<tr bgcolor="'.$color1.'">
                                    <td align="center">'.($i+1).'</th>
                                    <td align="center">
                                        <a href="index.php?components=billing&action=finish_bill&id='.$invoice_no[$i].'">'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a>
                                    </td>
                                    <td width="50px" align="center">'.$date[$i].'</td>
                                    <td align="left">&nbsp;&nbsp;'.ucfirst($cust[$i]).'</td>
                                    <td align="left">&nbsp;&nbsp;'.$type.'&nbsp;&nbsp;</td>
                                    <td align="right">'.number_format($invoice_total[$i], $decimal).'&nbsp;&nbsp;</td>
                                    <td align="right">'.number_format($invoice_cost[$i], $decimal).'&nbsp;&nbsp;</td>
                                    <td align="right">'.number_format($invoice_profit[$i], $decimal).'&nbsp;&nbsp;</td>
                                    <td align="right">'.number_format($advance_payment[$i], $decimal).'&nbsp;&nbsp;</td>
                                    <td align="right">'.number_format($payments[$i], $decimal).'&nbsp;&nbsp;</td>
                                </tr>';
                    }
                ?>
            </table>
        </div>
    </div>
</div>

<?php
    include_once  'template/m_footer.php';
?>