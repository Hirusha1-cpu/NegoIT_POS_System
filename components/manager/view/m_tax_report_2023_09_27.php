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

            <form action="index.php" method="get" onsubmit="return validateForm()" style="overflow-x: auto;">
                <input type="hidden" name="components" value="<?php print $components; ?>" />
                <input type="hidden" name="action" value="tax_report" />
                <table align="left" height="100%" cellspacing="0" style="font-size:10pt">
                    <tr style="height:30px;">
                        <td width="30px"></td>
                        <td align="left"><strong>From Date : </strong></td>
                        <td>
                            <input type="date" id="from_date" name="from_date" style="width:130px" value="<?php print $from_date; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td width="50px"></td>
                        <td align="left"><strong>To Date : </strong></td>
                        <td>
                            <input type="date" id="to_date" name="to_date" style="width:130px" value="<?php print $to_date; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td width="50px"></td>
                        <td width="100px" align="left"><strong>Bill Status : </strong></td>
                        <td align="left">
                            <select id="lock" name="lock">
                                <option value="1" <?php if($lock_req==1){ print 'selected="selected"'; $lockname='Lock'; } ?> >Lock</option>
                                <option value="0" <?php if($lock_req==0){ print 'selected="selected"'; $lockname='Unlock'; } ?> >Unlock</option>
                                <option value="all" <?php if($lock_req=='all'){ print 'selected="selected"'; $lockname='ALL'; } ?> >--ALL--</option>
                            </select>
                        </td>
                    </tr>
                    <tr style="height:30px;">
                        <td width="50px"></td>
                        <td width="80px" align="left"><strong>Group : </strong></td>
                        <td>
                            <select id="group" name="group">
                                <option value="all">--ALL--</option>
                                <?php
                                    $gpname='ALL Groups';
                                    for($i=0;$i<sizeof($gp_id);$i++){
                                        if($gp_id[$i]==$_GET['group']){
                                            $select='selected="selected"'; $gpname=ucfirst($gp_name[$i]);
                                        }else{
                                            $select='';
                                        }
                                        print '<option value="'.$gp_id[$i].'" '.$select.'>'.ucfirst($gp_name[$i]).'</option>';
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="50px"></td>
                        <td width="100px" align="left"><strong>Salesman : </strong></td>
                        <td>
                            <select id="salesman" name="salesman">
                            <option value="all">--ALL--</option>
                            <?php
                                $salesmanname='ALL';
                                for($i=0;$i<sizeof($up_id);$i++){
                                    if($up_id[$i]==$_GET['salesman']){
                                        $select='selected="selected"'; $salesmanname=ucfirst($up_name[$i]);
                                    }else{
                                        $select='';
                                    }
                                    print '<option value="'.$up_id[$i].'" '.$select.'>'.ucfirst($up_name[$i]).'</option>';
                                }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="50px"></td>
                        <td>
                            <div id="div_submit"><input type="submit" value="GET" style="width:50px; height:30px" /></div>
                        </td>
                        <td width="50px"></td>
                        <td>
                            <div id="div_submit">
                                <input style="height:30px" type="button" value="Detail Report" onclick="window.location = 'index.php?components=manager&action=tax_report_detail'" />
                            </div>
                        </td>
                        <td width="30px"></td>
                    </tr>
                </table>
            </form>

            <div id="print">
                <p><center>Total Tax : <?php print number_format($total_tax, $decimal); ?></center></p>
            </div>
        </div>
</div>
<?php
    include_once  'template/m_footer.php';
?>