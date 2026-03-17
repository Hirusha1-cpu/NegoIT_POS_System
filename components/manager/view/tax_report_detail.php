<?php
	include_once  'template/header.php';
    $decimal = getDecimalPlaces(1);
?>
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
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

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Tax Report [Detail Report]</h2>
</div>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<form action="index.php" method="get" onsubmit="return validateForm()" >
	<input type="hidden" name="components" value="<?php print $components; ?>" />
	<input type="hidden" name="action" value="tax_report_detail" />
	<table align="center">
        <tr>
            <td>
                <div style="background-color:#DFDFDF; border-radius:10px; font-family:Calibri">
                    <table align="center" height="100%" cellspacing="0" style="font-size:10pt">
                        <tr style="height:30px;">
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
                            <td width="100px" align="right"><strong>Bill Status : </strong></td>
                            <td align="right">
                                <select id="lock" name="lock">
                                    <option value="1" <?php if($lock_req==1){ print 'selected="selected"'; $lockname='Lock'; } ?> >Lock</option>
                                    <option value="0" <?php if($lock_req==0){ print 'selected="selected"'; $lockname='Unlock'; } ?> >Unlock</option>
                                    <option value="all" <?php if($lock_req=='all'){ print 'selected="selected"'; $lockname='ALL'; } ?> >--ALL--</option>
                                </select>
                            </td>
                            <td width="50px"></td>
                            <td rowspan=2>
                                <div id="div_submit"><input type="submit" value="GET" style="width:50px; height:40px" /></div>
                            </td>
                            <td width="30px"></td>
                        </tr>
                        <tr style="height:30px;">
                            <td width="50px"></td>
                            <td width="80px" align="right"><strong>Group : </strong></td>
                            <td>
                                <select id="group" name="group">
                                    <option value="all">--ALL--</option>
                                    <?php
                                        $gpname='ALL Groups';
                                        for($i=0;$i<sizeof($gp_id);$i++){
                                            if(isset($_GET['group'])){
                                                if($gp_id[$i]==$_GET['group']){
                                                    $select='selected="selected"'; $gpname=ucfirst($gp_name[$i]);
                                                }else{
                                                    $select='';
                                                }
                                            }else{
                                                $select='';
                                            }
                                            print '<option value="'.$gp_id[$i].'" '.$select.'>'.ucfirst($gp_name[$i]).'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                            <td width="50px"></td>
                            <td width="100px" align="right"><strong>Salesman : </strong></td>
                            <td>
                                <select id="salesman" name="salesman">
                                    <option value="all">--ALL--</option>
                                    <?php
                                        $salesmanname='ALL';
                                        for($i=0;$i<sizeof($up_id);$i++){
                                            if(isset($_REQUEST['salesman'])){
                                                if($up_id[$i]==$_GET['salesman']){
                                                    $select='selected="selected"'; $salesmanname=ucfirst($up_name[$i]);
                                                }else{
                                                    $select='';
                                                }
                                            }else{
                                                $select='';
                                            }
                                            print '<option value="'.$up_id[$i].'" '.$select.'>'.ucfirst($up_name[$i]).'</option>';
                                        }
                                    ?>
                                </select>
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
		<tr bgcolor="#467898" style="color:white">
			<td colspan="11" style="border:0; background-color:black; color:white; font-weight:bold">Invoices</td>
		</tr>
		<tr bgcolor="#467898" style="color:white">
            <th width="60px">#</th>
			<th width="100px">Invoice No</th>
			<th width="170px">Time</th>
			<th>Salesman</th>
			<th width="300px">Customer</th>
			<th width="100px">Gross amount</th>
			<th width="100px">Tax Amount</th>
            <th width="100px">Invoice Total</th>
		</tr>
		<?php
		for($i=0;$i<sizeof($invoice_no);$i++){
            print '<tr height="30px;">
                <td align="center">
                    '.($i+1).'
                </td>
                <td align="center">
                    <a target="_blank"  href="index.php?components=bill2&action=finish_bill&id='.$invoice_no[$i].'" >'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a>
                </td>
                <td align="center">'.$billed_time[$i].'</td>
                <td align="left" style="padding:0px 10px;">'.ucfirst($billed_by[$i]).'</td>
                <td align="left" style="padding:0px 10px;">'.$billed_cust[$i].'</td>
                <td align="right" style="padding:0px 10px;">'.number_format($gross_amount[$i],$decimal).'</td>
                <td align="right" style="padding:0px 10px;">'.number_format($tax_amount[$i], $decimal).'</td>
                <td align="right" style="padding:0px 10px;">'.number_format($invoice_total[$i], $decimal).'</td>
             </tr>';
		}
		?>
        <tr style="font-weight:bold; background-color:gray; color:white">
            <th colspan="5" align="right" style="padding:0px 10px;">Total</th>
            <th align="right" style="padding:0px 10px;"><?php print number_format($gross_amount_total, $decimal); ?></th>
            <th align="right" style="padding:0px 10px;"><?php print number_format($tax_amount_total, $decimal); ?></th>
            <th align="right" style="padding:0px 10px;"><?php print number_format($bills_total, $decimal); ?></th>
        </tr>
	</table>
</div>

<table align="center">
    <tr>
        <td align="center">
            <div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
                <a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
                <img src="images/print.png" alt="icon" /><br />
                Print
                </span></a>
            </div>
        </td>
    </tr>
</table>
<?php
    include_once  'template/footer.php';
?>