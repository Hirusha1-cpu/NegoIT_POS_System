<?php
    include_once  'template/m_header.php';
?>
<script>
    function getTemporaryBills(){
        var salesman0=document.getElementById('salesman0').value;
        window.location='index.php?components=<?php print $components; ?>&action=temporary_bills&store=&salesman='+salesman0;
    }
</script>

<div class="w3-container" style="margin-top:75px">
    <hr>
    <div class="w3-row">
        <div class="w3-col s3"></div>
        <div class="w3-col">
            <table style="font-family:Calibri;">
                <tr>
                    <td colspan="2" style="color:navy; font-size:14pt; font-weight:bold">List Of Temporary Bills [Current Store]</td>
                </tr>
                <tr>
                    <td width="50%" align="right" style="text-align:right;"><strong>Salesman : </strong></td>
                    <td width="50%" style="text-align:left;">
                        <select id="salesman0" onchange="getTemporaryBills()">
                        <option value="all">--ALL--</option>
                        <?php
                            $selectedsalesman='ALL';
                            $select='';
                            for($i=0;$i<sizeof($up_id);$i++){
                                if(isset($_GET['salesman'])){
                                    if($up_id[$i]==$_GET['salesman']){
                                        $select='selected="selected"';
                                        $selectedsalesman=ucfirst($up_name[$i]);
                                    }else $select='';
                                }
                                print '<option value="'.$up_id[$i].'" '.$select.'>'.ucfirst($up_name[$i]).'</option>';
                            }
                        ?>
                        </select>
                    </td>
                </tr>
            </table>
            <table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
                <tr>
                    <th width="60px">#</th>
                    <th width="100px">Invoice No</th>
                    <th width="100px">Date</th>
                    <th width="100px">Time</th>
                    <th>Salesman</th>
                    <th>Customer</th>
                </tr>
                <?php for($i=0;$i<sizeof($invoice_no);$i++){
                    print '<tr>
                            <td align="center">'.sprintf('%02d',($i+1)).'</td>
                            <td align="center">
                                <a target="_blank"  href="index.php?components=bill2&action=bill_item&cust_odr=no&bill_no='.$invoice_no[$i].'" >'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a>
                            </td>
                            <td align="center">'.$date[$i].'</td>
                            <td align="center">'.$time[$i].'</td>
                            <td style="padding: 0px 5px;>'.ucfirst($billed_by[$i]).'</td>
                            <td style="padding: 0px 5px;>'.$billed_cust[$i].'</td>
                        </tr>';
                }	?>
            </table>

        </div>
    </div>
</div>

<hr>
<br />
<?php
    include_once  'template/m_footer.php';
?>
