<?php
    include_once  'template/header.php';
?>
<script>
    function getTemporaryBills(){
        var salesman0=document.getElementById('salesman0').value;
        window.location='index.php?components=<?php print $components; ?>&action=temporary_bills&store=&salesman='+salesman0;
    }
</script>

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Temporary Bills [All Stores]</h2>
</div>

<table align="center" style="font-family:Calibri; border-radius: 15px; padding-left:20px; padding-right:20px" bgcolor="#EEEEEE" width="600px"	>
    <tr>
        <td colspan="2" style="color:navy; font-size:14pt; font-weight:bold" align="center">List Of Temporary Bills [All Stores]</td>
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

<br />
<table align="center"><tr><td>
	<div style="background-color:#EEEEEF; border-radius: 15px; padding-left:10px; padding-right:10px">
	<br />
		<div id="print">
			<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
				<tr>
                    <th width="60px">#</th>
                    <th width="100px">Invoice No</th>
                    <th width="100px">Date</th>
                    <th width="100px">Time</th>
                    <th>Salesman</th>
                    <th>Customer</th>
                    <th>Store</th>
                </tr>
                <?php for($i=0;$i<sizeof($invoice_no);$i++){
                        print '<tr>
                                <td align="center">'.sprintf('%02d',($i+1)).'</td>
                                <td align="center">
                                    <a target="_blank"  href="index.php?components=bill2&action=bill_item&cust_odr=no&bill_no='.$invoice_no[$i].'" >'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a>
                                </td>
                                <td align="center">'.$date[$i].'</td>
                                <td align="center">'.$time[$i].'</td>
                                <td style="padding: 0px 5px;">'.ucfirst($billed_by[$i]).'</td>
                                <td style="padding: 0px 5px;">'.$billed_cust[$i].'</td>
                                <td style="padding: 0px 5px;">'.$stores[$i].'</td>
                            </tr>';
                    }
                ?>
			</table>
		</div>
	<br />
	</div>
</td></tr></table>

<table align="center">
    <tr>
        <td align="center">
            <div style="background-color:#6699FF; border:medium; border-color:black; width:80px; border-radius: 15px;">
                <a class="shortcut-button" onclick="printdiv('print','printheader')" href="#">
                    <span style="text-decoration:none; font-family:Arial; color:navy;">
                        <img src="images/print.png" alt="icon" /><br />Print
                    </span>
                </a>
            </div>
        </td>
    </tr>
</table>
<br />

<?php
    include_once  'template/footer.php';
?>