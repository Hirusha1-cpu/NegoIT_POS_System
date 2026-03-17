<?php
    include_once  'template/m_header.php';
    $decimal = getDecimalPlaces(1);
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

        <form action="index.php" method="get" onsubmit="return validateForm()" >
            <input type="hidden" name="components" value="<?php print $components; ?>" />
            <input type="hidden" name="action" value="tax_report_detail" />
            <table align="center">
                <tr>
                    <td>
                        <div style="background-color:#DFDFDF; border-radius:10px; font-family:Calibri">
                            <table align="center" height="100%" cellspacing="0" style="font-size:10pt">
                                <tr>
                                    <td width="30px"></td>
                                    <td align="right"><strong>From Date : </strong></td>
                                    <td>
                                        <input type="date" id="from_date" name="from_date" style="width:130px" value="<?php print $from_date; ?>" />
                                    </td>
                                    <td width="50px"></td>
                                    <td align="right"><strong>To Date : </strong></td>
                                    <td>
                                        <input type="date" id="to_date" name="to_date" style="width:130px" value="<?php print $to_date; ?>" />
                                    </td>
                                    <td width="50px"></td>
                                    <td>
                                        <div id="div_submit"><input type="submit" value="GET" style="width:50px; height:40px" /></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
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
                <tr>
                    <th width="100px">Invoice No</th>
                    <th width="170px">Time</th>
                    <th>Salesman</th>
                    <th width="300px">Customer</th>
                    <th width="100px">Gross Amount</th>
                    <th width="100px">Tax Amount</th>
                    <th width="100px">Invoice Total</th>
                </tr>
                <?php
                    for($i=0;$i<sizeof($invoice_no);$i++){
                        print '<tr>
                            <td align="center">
                                <a target="_blank"  href="index.php?components=bill2&action=finish_bill&id='.$invoice_no[$i].'" >'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a>
                            </td>
                            <td align="center">'.$billed_time[$i].'</td>
                            <td align="left">&nbsp;&nbsp;'.ucfirst($billed_by[$i]).'&nbsp;&nbsp;</td>
                            <td align="left">&nbsp;&nbsp;'.$billed_cust[$i].'&nbsp;&nbsp;</td>
                            <td align="right">&nbsp;&nbsp;'.number_format($gross_amount[$i],$decimal).'&nbsp;&nbsp;</td>
                            <td align="right">&nbsp;&nbsp;'.number_format($tax_amount[$i], $decimal).'&nbsp;&nbsp;</td>
                            <td align="right">&nbsp;&nbsp;'.number_format($invoice_total[$i], $decimal).'&nbsp;&nbsp;</td>
                            </tr>';
                    }
                    ?>
                <tr>
                    <th colspan="4" align="right">&nbsp;&nbsp;Total&nbsp;&nbsp;</th>
                    <th align="right">&nbsp;&nbsp;<?php print number_format($gross_amount_total, $decimal); ?>&nbsp;&nbsp;</th>
                    <th align="right">&nbsp;&nbsp;<?php print number_format($tax_amount_total, $decimal); ?>&nbsp;&nbsp;</th>
                    <th align="right">&nbsp;&nbsp;<?php print number_format($bills_total, $decimal); ?>&nbsp;&nbsp;</th>
                </tr>
            </table>
        </div>
    </div>
</div>

<?php
    include_once  'template/m_footer.php';
?>